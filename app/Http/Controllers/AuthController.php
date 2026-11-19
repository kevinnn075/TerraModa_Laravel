<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
class AuthController extends Controller {
 public function show(){ if(session('user_id')) return redirect()->route('dashboard'); return view('auth.login'); }
 public function login(Request $r){
   $data=$r->validate(['email'=>'required|email','password'=>'required|string']);
   $user=DB::table('users')->where('email',$data['email'])->where('active',true)->first();
   if(!$user || !Hash::check($data['password'],$user->password)) return back()->withErrors(['email'=>'Correo o contraseña incorrectos.'])->onlyInput('email');
   $r->session()->regenerate(); $r->session()->put(['user_id'=>$user->id,'user_name'=>$user->name,'user_role'=>$user->role,'branch_id'=>$user->branch_id]);
   return redirect()->intended(route('dashboard'));
 }
 public function logout(Request $r){ $r->session()->invalidate(); $r->session()->regenerateToken(); return redirect()->route('login'); }
}
