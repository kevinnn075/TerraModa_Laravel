<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class InventoryMovement extends Model { protected $fillable=['roll_id','branch_id','user_id','type','meters','reason','reference_id']; public function roll(){return $this->belongsTo(Roll::class);} }
