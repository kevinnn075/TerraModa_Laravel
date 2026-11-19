@extends('layouts.app')
@section('title','Ventas')
@section('heading','Ventas')
@section('content')
<div class="welcome-row"><div><h2>Historial de ventas</h2><p>Operaciones registradas y sus totales.</p></div><a class="btn btn-dark" href="{{ route('sales.create') }}">＋ Registrar venta</a></div>
<section class="panel"><div class="panel-head"><div><h3>Ventas recientes</h3><p>{{ count($sales) }} operaciones</p></div></div><div class="table-responsive"><table class="table align-middle"><thead><tr><th>N.º</th><th>Fecha</th><th>Sucursal</th><th>Cliente</th><th>Subtotal</th><th>Descuento</th><th>Total</th></tr></thead><tbody>@forelse($sales as $s)<tr><td><strong>#{{ $s->id }}</strong></td><td>{{ \Carbon\Carbon::parse($s->created_at)->format('d/m/Y H:i') }}</td><td>{{ $s->branch_name }}</td><td>{{ $s->customer_name??'Venta general' }}</td><td>Bs {{ number_format($s->subtotal,2) }}</td><td>Bs {{ number_format($s->discount_amount,2) }}</td><td><strong>Bs {{ number_format($s->total,2) }}</strong></td></tr>@empty<tr><td colspan="7" class="empty">Todavía no se registraron ventas.</td></tr>@endforelse</tbody></table></div></section>
@endsection
