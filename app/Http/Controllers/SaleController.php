<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
class SaleController extends Controller {
 public function index(){ $sales=DB::table('sales')->leftJoin('branches','branches.id','=','sales.branch_id')->leftJoin('customers','customers.id','=','sales.customer_id')->select('sales.*','branches.name as branch_name','customers.full_name as customer_name')->orderByDesc('sales.id')->limit(200)->get();return view('sales.index',compact('sales')); }
 public function create(){ $branchId=session('branch_id');$branches=DB::table('branches')->where('active',1)->get();$rolls=DB::table('rolls')->join('products','products.id','=','rolls.product_id')->where('rolls.status','disponible')->where('rolls.meters_available','>',0)->when($branchId,fn($q)=>$q->where('rolls.branch_id',$branchId))->select('rolls.*','products.name as product_name','products.sale_price')->get();$customers=DB::table('customers')->where('active',1)->get();return view('sales.create',compact('branches','rolls','customers')); }
 public function store(Request $r){
  $data=$r->validate(['branch_id'=>'required|exists:branches,id','customer_id'=>'nullable|exists:customers,id','roll_id'=>'required|exists:rolls,id','meters'=>'required|numeric|gt:0','discount_percent'=>'nullable|numeric|min:0|max:100']);
  if(session('branch_id') && session('user_role')!=='admin' && (int)$data['branch_id']!==(int)session('branch_id'))throw ValidationException::withMessages(['branch_id'=>'Solo puedes vender desde tu sucursal asignada.']);
  DB::transaction(function()use($data){
   $roll=DB::table('rolls')->where('id',$data['roll_id'])->lockForUpdate()->first();
   if(!$roll || $roll->branch_id!=$data['branch_id'])throw ValidationException::withMessages(['roll_id'=>'El rollo no pertenece a la sucursal seleccionada.']);
   if($roll->status!=='disponible'||(float)$roll->meters_available+0.00001<(float)$data['meters'])throw ValidationException::withMessages(['meters'=>'Metraje insuficiente. Disponible: '.($roll->meters_available??0).' m.']);
   $product=DB::table('products')->find($roll->product_id);$subtotal=round($data['meters']*$product->sale_price,2);$discount=round($subtotal*(($data['discount_percent']??0)/100),2);$total=round($subtotal-$discount,2);
   $saleId=DB::table('sales')->insertGetId(['branch_id'=>$data['branch_id'],'user_id'=>session('user_id'),'customer_id'=>$data['customer_id']??null,'subtotal'=>$subtotal,'discount_amount'=>$discount,'total'=>$total,'created_at'=>now(),'updated_at'=>now()]);
   DB::table('sale_details')->insert(['sale_id'=>$saleId,'product_id'=>$product->id,'roll_id'=>$roll->id,'meters'=>$data['meters'],'price_per_meter'=>$product->sale_price,'subtotal'=>$subtotal,'created_at'=>now(),'updated_at'=>now()]);
   $available=round($roll->meters_available-$data['meters'],3);DB::table('rolls')->where('id',$roll->id)->update(['meters_available'=>$available,'meters_sold'=>DB::raw('meters_sold + '.(float)$data['meters']),'status'=>$available<=0?'agotado':'disponible','updated_at'=>now()]);
   DB::table('inventory_movements')->insert(['roll_id'=>$roll->id,'branch_id'=>$data['branch_id'],'user_id'=>session('user_id'),'type'=>'salida_venta','meters'=>-$data['meters'],'reason'=>'Venta #'.$saleId,'reference_id'=>$saleId,'created_at'=>now(),'updated_at'=>now()]);
   DB::table('audit_logs')->insert(['user_id'=>session('user_id'),'action'=>'venta_confirmada','entity_type'=>'sale','entity_id'=>$saleId,'old_values'=>json_encode(['roll_id'=>$roll->id,'meters_available'=>(float)$roll->meters_available]),'new_values'=>json_encode(['meters_sold'=>(float)$data['meters'],'meters_available'=>$available,'subtotal'=>$subtotal,'discount_amount'=>$discount,'total'=>$total]),'ip_address'=>request()->ip(),'created_at'=>now(),'updated_at'=>now()]);
  });
  return redirect()->route('sales.index')->with('success','Venta registrada y metraje descontado correctamente.');
 }
}
