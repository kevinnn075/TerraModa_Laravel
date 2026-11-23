@extends('layouts.app')
@section('title',$config['title'])
@section('heading',$config['title'])
@section('content')
<div class="welcome-row"><div><h2>Administración de {{ strtolower($config['title']) }}</h2><p>Consulta y registra información del sistema.</p></div>@if($module!=='movimientos')<a class="btn btn-dark" href="{{ route($module.'.create') }}">＋ Nuevo registro</a>@endif</div>
@if($module==='movimientos')
<form class="panel mb-3 p-3" method="GET"><div class="row g-2 align-items-end"><div class="col-sm-4"><label class="form-label">Desde</label><input class="form-control" type="date" name="from" value="{{ request('from') }}"></div><div class="col-sm-4"><label class="form-label">Hasta</label><input class="form-control" type="date" name="to" value="{{ request('to') }}"></div><div class="col-sm-4"><button class="btn btn-dark">Filtrar movimientos</button> <a class="btn btn-outline-secondary" href="{{ route('movimientos.index') }}">Limpiar</a></div></div></form>
@endif
<section class="panel"><div class="panel-head"><div><h3>Listado general</h3><p>{{ count($rows) }} registros recientes</p></div><span class="pill">Terra Moda</span></div><div class="table-responsive"><table class="table align-middle"><thead><tr>@if(count($rows))@foreach(array_keys((array)$rows[0]) as $key)<th>{{ ucfirst(str_replace('_',' ',$key)) }}</th>@endforeach@else<th>Información</th>@endif @if($module==='rollos')<th>Reubicar</th>@endif</tr></thead><tbody>@forelse($rows as $row)<tr>@foreach((array)$row as $key=>$value)<td>@if(in_array($key,['created_at','updated_at']) && $value){{ \Carbon\Carbon::parse($value)->format('d/m/Y H:i') }}@elseif($key==='password')••••••••@else{{ is_null($value)?'—':(is_bool($value)?($value?'Sí':'No'):$value) }}@endif</td>@endforeach
@if($module==='rollos')
<td><form method="POST" action="{{ route('rollos.reubicar',$row->id) }}" class="d-flex gap-1">@csrf<select class="form-select form-select-sm" name="location_id" required><option value="">Ubicación...</option>@foreach($locations->where('branch_id',$row->branch_id) as $loc)<option value="{{ $loc->id }}" @selected($row->location_id==$loc->id)>E{{ $loc->shelf }}-P{{ $loc->position }}</option>@endforeach</select><button class="btn btn-sm btn-outline-dark">Guardar</button></form><a class="small" href="{{ route("rollos.historial",$row->id) }}">Historial</a></td>
@endif
</tr>@empty<tr><td class="empty">No hay registros todavía. Usa “Nuevo registro” para agregar el primero.</td></tr>@endforelse</tbody></table></div></section>
@endsection
