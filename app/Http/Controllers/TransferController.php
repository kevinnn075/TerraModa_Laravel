<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
class TransferController extends Controller {
 public function index(){ $transfers=DB::table('transfers')->join('branches as a','a.id','=','transfers.from_branch_id')->join('branches as b','b.id','=','transfers.to_branch_id')->select('transfers.*','a.name as origin','b.name as destination')->orderByDesc('transfers.id')->limit(100)->get();return view('transfers.index',compact('transfers')); }
 public function create(){ $branches=DB::table('branches')->where('active',1)->get();$rolls=DB::table('rolls')->join('products','products.id','=','rolls.product_id')->where('rolls.status','disponible')->where('rolls.meters_available','>',0)->select('rolls.*','products.name as product_name')->get();return view('transfers.create',compact('branches','rolls')); }
 public function store(Request $r){$d=$r->validate(['from_branch_id'=>'required|exists:branches,id','to_branch_id'=>'required|different:from_branch_id|exists:branches,id','roll_id'=>'required|exists:rolls,id','meters'=>'required|numeric|gt:0']);
 DB::transaction(function()use($d){$roll=DB::table('rolls')->where('id',$d['roll_id'])->lockForUpdate()->first();if(!$roll||$roll->branch_id!=$d['from_branch_id']||$roll->meters_available<$d['meters'])throw ValidationException::withMessages(['meters'=>'El rollo no pertenece al origen o no tiene metraje suficiente.']);
 $transfer=DB::table('transfers')->insertGetId(['from_branch_id'=>$d['from_branch_id'],'to_branch_id'=>$d['to_branch_id'],'user_id'=>session('user_id'),'status'=>'completada','created_at'=>now(),'updated_at'=>now()]);
 DB::table('rolls')->where('id',$roll->id)->update(['meters_available'=>DB::raw('meters_available - '.(float)$d['meters']),'updated_at'=>now()]);
 $newId=DB::table('rolls')->insertGetId(['code'=>$roll->code.'-T'.$transfer,'product_id'=>$roll->product_id,'branch_id'=>$d['to_branch_id'],'location_id'=>null,'meters_initial'=>$d['meters'],'meters_available'=>$d['meters'],'meters_sold'=>0,'status'=>'disponible','created_at'=>now(),'updated_at'=>now()]);
 DB::table('transfer_details')->insert(['transfer_id'=>$transfer,'source_roll_id'=>$roll->id,'destination_roll_id'=>$newId,'meters'=>$d['meters'],'created_at'=>now(),'updated_at'=>now()]);
 foreach([[$roll->id,$d['from_branch_id'],-$d['meters'],'transferencia_salida'],[$newId,$d['to_branch_id'],$d['meters'],'transferencia_entrada']] as [$rid,$bid,$meters,$type])DB::table('inventory_movements')->insert(['roll_id'=>$rid,'branch_id'=>$bid,'user_id'=>session('user_id'),'type'=>$type,'meters'=>$meters,'reason'=>'Transferencia #'.$transfer,'reference_id'=>$transfer,'created_at'=>now(),'updated_at'=>now()]);
 });return redirect()->route('transfers.index')->with('success','Transferencia registrada.');
 }
}
