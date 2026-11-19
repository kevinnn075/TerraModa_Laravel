<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
class DashboardController extends Controller {
 public function __invoke(){
  $rolls=DB::table('rolls')->where('status','disponible');
  $totalRolls=(clone $rolls)->count(); $meters=(clone $rolls)->sum('meters_available');
  $products=DB::table('products')->where('active',1)->count();
  $today=DB::table('sales')->whereDate('created_at',today())->sum('total');
  $month=DB::table('sales')->whereMonth('created_at',now()->month)->whereYear('created_at',now()->year)->sum('total');
  $branches=DB::table('branches')->where('active',1)->get()->map(function($b){
    $b->meters=DB::table('rolls')->where('branch_id',$b->id)->where('status','disponible')->sum('meters_available'); return $b;
  });
  $recent=DB::table('inventory_movements')->leftJoin('rolls','rolls.id','=','inventory_movements.roll_id')->leftJoin('products','products.id','=','rolls.product_id')->select('inventory_movements.*','products.name as product_name')->orderByDesc('inventory_movements.id')->limit(7)->get();
  return view('dashboard',compact('products','totalRolls','meters','today','month','branches','recent'));
 }
}
