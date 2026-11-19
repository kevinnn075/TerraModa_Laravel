<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Sale extends Model { protected $fillable=['branch_id','user_id','customer_id','subtotal','discount_amount','total']; public function details(){return $this->hasMany(SaleDetail::class);} }
