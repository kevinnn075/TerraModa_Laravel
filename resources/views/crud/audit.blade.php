@extends('layouts.app')
@section('title','Auditoría')
@section('heading','Auditoría')
@section('content')
<div class="welcome-row"><div><h2>Bitácora de auditoría</h2><p>Operaciones críticas con usuario, fecha, valores anteriores y nuevos.</p></div></div>
<form class="panel p-3 mb-3" method="GET"><div class="row g-2 align-items-end"><div class="col-sm-4"><label class="form-label">Desde</label><input class="form-control" type="date" name="from" value="{{ $from }}"></div><div class="col-sm-4"><label class="form-label">Hasta</label><input class="form-control" type="date" name="to" value="{{ $to }}"></div><div class="col-sm-4"><button class="btn btn-dark">Filtrar</button> <a class="btn btn-outline-secondary" href="{{ route('auditoria.index') }}">Limpiar</a></div></div></form>
<section class="panel"><div class="table-responsive"><table class="table align-middle"><thead><tr><th>Fecha y hora</th><th>Usuario</th><th>Operación</th><th>Entidad</th><th>ID</th><th>Valores anteriores</th><th>Valores nuevos</th></tr></thead><tbody>
@forelse($logs as $log)<tr><td>{{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i:s') }}</td><td>{{ $log->user_name ?? 'Usuario eliminado' }}</td><td>{{ $log->action }}</td><td>{{ $log->entity_type }}</td><td>{{ $log->entity_id ?? '—' }}</td><td><code>{{ $log->old_values ?? '—' }}</code></td><td><code>{{ $log->new_values ?? '—' }}</code></td></tr>@empty<tr><td colspan="7" class="empty">No hay registros de auditoría en este rango.</td></tr>@endforelse
</tbody></table></div></section>
@endsection
