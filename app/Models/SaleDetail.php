<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class SaleDetail extends Model { protected $fillable=['sale_id','product_id','roll_id','meters','price_per_meter','subtotal']; public function sale(){return $this->belongsTo(Sale::class);} }
