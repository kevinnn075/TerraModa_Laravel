<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
class DatabaseSeeder extends Seeder {
 public function run(): void {
  foreach([['Terra Moda - Central','La Paz'],['Terra Moda - Sucursal 2','La Paz'],['Terra Moda - Sucursal 3','La Paz']] as [$name,$address]) DB::table('branches')->insertOrIgnore(['name'=>$name,'address'=>$address,'active'=>1,'created_at'=>now(),'updated_at'=>now()]);
  $branch=DB::table('branches')->orderBy('id')->first();
  DB::table('users')->updateOrInsert(['email'=>'admin@terramoda.test'],['name'=>'Administrador Terra Moda','password'=>Hash::make('password'),'role'=>'admin','branch_id'=>$branch->id,'active'=>1,'created_at'=>now(),'updated_at'=>now()]);
  foreach(['Algodón','Jean','Lino','Seda','Poliéster','Telas decorativas'] as $name) DB::table('categories')->insertOrIgnore(['name'=>$name,'active'=>1,'created_at'=>now(),'updated_at'=>now()]);
  $cat=DB::table('categories')->where('name','Algodón')->first();
  DB::table('products')->updateOrInsert(['code'=>'TEL-001'],['name'=>'Algodón estampado floral','category_id'=>$cat->id,'fabric_type'=>'Algodón','design'=>'Floral','color'=>'Multicolor','purchase_price'=>15,'sale_price'=>25,'low_stock_meters'=>10,'active'=>1,'created_at'=>now(),'updated_at'=>now()]);
  $product=DB::table('products')->where('code','TEL-001')->first();
  $loc=DB::table('locations')->first();
  if(!$loc){$locId=DB::table('locations')->insertGetId(['branch_id'=>$branch->id,'warehouse'=>'Depósito principal','aisle'=>'B','shelf'=>'03','level'=>'2','created_at'=>now(),'updated_at'=>now()]);}else $locId=$loc->id;
  DB::table('rolls')->updateOrInsert(['code'=>'TM-0001'],['product_id'=>$product->id,'branch_id'=>$branch->id,'location_id'=>$locId,'meters_initial'=>50,'meters_available'=>50,'meters_sold'=>0,'status'=>'disponible','created_at'=>now(),'updated_at'=>now()]);
  DB::table('customers')->insertOrIgnore(['full_name'=>'Cliente demostración','phone'=>'70000000','email'=>'cliente@terramoda.test','active'=>1,'created_at'=>now(),'updated_at'=>now()]);
 }
}
