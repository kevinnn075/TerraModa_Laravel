@extends('layouts.app')
@section('title','Transferencias')
@section('heading','Transferencias')
@section('content')
<div class="welcome-row"><div><h2>Transferencias entre sucursales</h2><p>Trazabilidad de los movimientos de metraje.</p></div><a class="btn btn-dark" href="{{ route('transfers.create') }}">＋ Nueva transferencia</a></div>
<section class="panel"><div class="table-responsive"><table class="table align-middle"><thead><tr><th>N.º</th><th>Fecha</th><th>Origen</th><th>Destino</th><th>Estado</th></tr></thead><tbody>@forelse($transfers as $t)<tr><td>#{{ $t->id }}</td><td>{{ \Carbon\Carbon::parse($t->created_at)->format('d/m/Y H:i') }}</td><td>{{ $t->origin }}</td><td>{{ $t->destination }}</td><td><span class="pill">{{ ucfirst($t->status) }}</span></td></tr>@empty<tr><td colspan="5" class="empty">No hay transferencias registradas.</td></tr>@endforelse</tbody></table></div></section>
@endsection
