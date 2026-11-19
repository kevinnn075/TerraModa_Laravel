<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class CrudController extends Controller {
 private array $config = [
 'sucursales'=>['table'=>'branches','title'=>'Sucursales','fields'=>['name'=>'Nombre','address'=>'Dirección','phone'=>'Teléfono']],
 'categorias'=>['table'=>'categories','title'=>'Categorías','fields'=>['name'=>'Nombre','description'=>'Descripción']],
 'productos'=>['table'=>'products','title'=>'Telas y productos','fields'=>['code'=>'Código','name'=>'Nombre','category_id'=>'Categoría (ID)','fabric_type'=>'Tipo de tela','design'=>'Diseño','color'=>'Color','purchase_price'=>'Precio compra (Bs/m)','sale_price'=>'Precio venta (Bs/m)','low_stock_meters'=>'Alerta de stock (m)']],
 'ubicaciones'=>['table'=>'locations','title'=>'Ubicaciones','fields'=>['branch_id'=>'Sucursal (ID)','warehouse'=>'Depósito','room'=>'Ambiente','aisle'=>'Pasillo','shelf'=>'Estante','level'=>'Nivel','section'=>'Sección']],
 'rollos'=>['table'=>'rolls','title'=>'Rollos','fields'=>['code'=>'Código','product_id'=>'Producto (ID)','branch_id'=>'Sucursal (ID)','location_id'=>'Ubicación (ID)','meters_initial'=>'Metraje inicial','meters_available'=>'Metraje disponible']],
 'clientes'=>['table'=>'customers','title'=>'Clientes','fields'=>['full_name'=>'Nombre completo','document_number'=>'Documento','phone'=>'Teléfono','email'=>'Correo','address'=>'Dirección']],
 'movimientos'=>['table'=>'inventory-movements','title'=>'Movimientos de inventario','fields'=>[]],
 'usuarios'=>['table'=>'users','title'=>'Usuarios','fields'=>['name'=>'Nombre','email'=>'Correo','password'=>'Contraseña','role'=>'Rol (admin/encargado/vendedor/almacen/consulta)','branch_id'=>'Sucursal (ID)']]
 ];
 public function index(Request $r){$m=$this->module($r);$q=DB::table($m['table']); if($r->session('branch_id') && in_array($r->route('module'),['rollos']))$q->where('branch_id',$r->session('branch_id')); $rows=$q->orderByDesc('id')->limit(200)->get(); return view('crud.index',['module'=>$r->route('module'),'config'=>$m,'rows'=>$rows]);}
 public function create(Request $r){$m=$this->module($r);return view('crud.create',['module'=>$r->route('module'),'config'=>$m]);}
 public function store(Request $r){
  $slug=$r->route('module');$m=$this->module($r);$input=[];
  foreach($m['fields'] as $field=>$label){ if($field==='password'){$r->validate([$field=>'required|min:8']);$input[$field]=\Illuminate\Support\Facades\Hash::make($r->input($field));} else {$input[$field]=$r->input($field);}}
  if(in_array($slug,['sucursales','categorias','productos','clientes'])) $input['active']=true;
  if($slug==='rollos'){ $input['meters_sold']=0; $input['status']='disponible'; if(isset($input['meters_initial']))$input['meters_available']=$input['meters_initial']; }
  if($slug==='usuarios')$input['active']=true;
  if($slug==='sucursales')$input['created_at']=$input['updated_at']=now();
  if(in_array($slug,['productos','categorias','clientes','ubicaciones','rollos','usuarios']))$input['created_at']=$input['updated_at']=now();
  if($slug==='movimientos')return redirect()->route('movimientos.index');
  $rules=[]; foreach($m['fields'] as $f=>$l){$rules[$f]=($f==='password'?'required|min:8':(str_contains($f,'price')||str_contains($f,'meters')?'required|numeric|min:0':(str_ends_with($f,'_id')?'required|integer':'nullable|string|max:255')));}
  $r->validate($rules);
  DB::table($m['table'])->insert($input);
  return redirect()->route($slug.'.index')->with('success','Registro creado correctamente.');
 }
 private function module(Request $r){$slug=$r->route('module');if(!isset($this->config[$slug]))abort(404);$m=$this->config[$slug];if($slug==='movimientos')$m['table']='inventory_movements';return $m;}
}
