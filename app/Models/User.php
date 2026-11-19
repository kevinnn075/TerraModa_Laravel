<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class User extends Model { protected $fillable=['name','email','password','role','branch_id','active']; protected $hidden=['password']; public function branch(){return $this->belongsTo(Branch::class);} }
