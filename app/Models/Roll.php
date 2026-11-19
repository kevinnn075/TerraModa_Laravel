<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Roll extends Model { protected $fillable=['code','product_id','branch_id','location_id','meters_initial','meters_available','meters_sold','status']; protected $casts=['meters_initial'=>'decimal:3','meters_available'=>'decimal:3','meters_sold'=>'decimal:3']; public function product(){return $this->belongsTo(Product::class);} public function branch(){return $this->belongsTo(Branch::class);} public function movements(){return $this->hasMany(InventoryMovement::class);} }
