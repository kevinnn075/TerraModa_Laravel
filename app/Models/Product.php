<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Product extends Model { protected $fillable=['code','name','category_id','fabric_type','design','color','purchase_price','sale_price','low_stock_meters','active']; public function category(){return $this->belongsTo(Category::class);} public function rolls(){return $this->hasMany(Roll::class);} }
