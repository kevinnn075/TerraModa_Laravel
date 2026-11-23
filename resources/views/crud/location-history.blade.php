@extends('layouts.app')
@section('title','Historial de ubicación')
@section('heading','Historial de ubicación')
@section('content')
<div class="welcome-row"><div><h2>Rollo {{ $roll->code }}</h2><p>Historial de reubicaciones registradas.</p></div><a class="btn btn-outline-secondary" href="{{ route('rollos.index') }}">← Volver a rollos</a></div>
<section class="panel"><div class="table-responsive"><table class="table align-middle"><thead><tr><th>Fecha y hora</th><th>Usuario</th><th>Ubicación anterior</th><th>Ubicación nueva</th><th>Motivo</th></tr></thead><tbody>
@forelse($history as $h)<tr><td>{{ \Carbon\Carbon::parse($h->created_at)->format('d/m/Y H:i:s') }}</td><td>{{ $h->user_name ?? 'Usuario eliminado' }}</td><td>{{ $h->from_code }}</td><td>{{ $h->to_code }}</td><td>{{ $h->reason }}</td></tr>@empty<tr><td colspan="5" class="empty">No hay reubicaciones registradas para este rollo.</td></tr>@endforelse
</tbody></table></div></section>
@endsection
