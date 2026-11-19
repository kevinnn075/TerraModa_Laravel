<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Customer extends Model { protected $fillable=['full_name','document_number','phone','email','address','active']; public function sales(){return $this->hasMany(Sale::class);} }
