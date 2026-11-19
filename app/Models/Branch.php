<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Branch extends Model { protected $fillable=['name','address','phone','active']; public function rolls(){return $this->hasMany(Roll::class);} }
