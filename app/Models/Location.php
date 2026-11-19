<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Location extends Model { protected $fillable=['branch_id','warehouse','room','aisle','shelf','level','section']; public function branch(){return $this->belongsTo(Branch::class);} }
