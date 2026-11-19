@extends('layouts.app')
@section('title',$config['title'])
@section('heading',$config['title'])
@section('content')
<div class="welcome-row"><div><h2>Administración de {{ strtolower($config['title']) }}</h2><p>Consulta y registra información del sistema.</p></div>@if($module!=='movimientos')<a class="btn btn-dark" href="{{ route($module.'.create') }}">＋ Nuevo registro</a>@endif</div>
<section class="panel"><div class="panel-head"><div><h3>Listado general</h3><p>{{ count($rows) }} registros recientes</p></div><span class="pill">Terra Moda</span></div><div class="table-responsive"><table class="table align-middle"><thead><tr>@if(count($rows))@foreach(array_keys((array)$rows[0]) as $key)<th>{{ ucfirst(str_replace('_',' ',$key)) }}</th>@endforeach@else<th>Información</th>@endif</tr></thead><tbody>@forelse($rows as $row)<tr>@foreach((array)$row as $key=>$value)<td>@if(in_array($key,['created_at','updated_at']) && $value){{ \Carbon\Carbon::parse($value)->format('d/m/Y H:i') }}@elseif($key==='password')••••••••@else{{ is_null($value)?'—':(is_bool($value)?($value?'Sí':'No'):$value) }}@endif</td>@endforeach</tr>@empty<tr><td class="empty">No hay registros todavía. Usa “Nuevo registro” para agregar el primero.</td></tr>@endforelse</tbody></table></div></section>
@endsection
