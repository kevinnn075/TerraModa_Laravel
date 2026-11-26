
@extends('layouts.app')

@section('title', $config['title'])
@section('heading', $config['title'])

@section('content')

@php
    $nombres = [
        'id' => 'N.º',
        'code' => 'Código',
        'name' => 'Nombre',
        'product_name' => 'Producto',
        'product_id' => 'ID del producto',
        'category_id' => 'ID de categoría',
        'branch_id' => 'ID de sucursal',
        'location_id' => 'ID de ubicación',
        'customer_id' => 'ID de cliente',
        'roll_id' => 'ID de rollo',
        'roll_code' => 'Código del rollo',
        'category_name' => 'Categoría',
        'branch_name' => 'Sucursal',
        'location_code' => 'Código de ubicación',
        'customer_name' => 'Cliente',
        'user_id' => 'ID del usuario',
        'user_name' => 'Usuario responsable',
        'full_name' => 'Nombre completo',
        'document_number' => 'Documento de identidad',
        'email' => 'Correo electrónico',
        'phone' => 'Teléfono',
        'address' => 'Dirección',
        'fabric_type' => 'Tipo de tela',
        'design' => 'Diseño',
        'color' => 'Color',
        'purchase_price' => 'Precio de compra (Bs/m)',
        'sale_price' => 'Precio de venta (Bs/m)',
        'low_stock_meters' => 'Alerta de stock (m)',
        'meters_initial' => 'Metraje inicial (m)',
        'meters_available' => 'Metraje disponible (m)',
        'meters_sold' => 'Metraje vendido (m)',
        'meters' => 'Metraje (m)',
        'status' => 'Estado',
        'active' => 'Activo',
        'role' => 'Rol',
        'warehouse' => 'Depósito',
        'room' => 'Ambiente',
        'aisle' => 'Pasillo',
        'shelf' => 'Estante',
        'position' => 'Posición',
        'level' => 'Nivel',
        'section' => 'Sección',
        'type' => 'Tipo',
        'reason' => 'Motivo',
        'action' => 'Operación realizada',
        'entity_type' => 'Tipo de registro',
        'entity_id' => 'ID del registro',
        'old_values' => 'Valores anteriores',
        'new_values' => 'Valores nuevos',
        'ip_address' => 'Dirección IP',
        'created_at' => 'Fecha de creación',
        'updated_at' => 'Última actualización',
        'from_location_id' => 'Ubicación anterior (ID)',
        'to_location_id' => 'Ubicación nueva (ID)',
        'from_code' => 'Ubicación anterior',
        'to_code' => 'Ubicación nueva',
        'quantity' => 'Cantidad',
        'total' => 'Total',
        'total_amount' => 'Importe total',
        'sale_id' => 'ID de venta',
        'description' => 'Descripción',
        'reference_id' => 'ID de referencia',
    ];
@endphp

<div class="welcome-row">
    <div>
        <h2>Administración de {{ strtolower($config['title']) }}</h2>
        <p>Consulta y registra información del sistema.</p>
    </div>

    @if ($module !== 'movimientos')
        <a class="btn btn-dark" href="{{ route($module . '.create') }}">
            + Nuevo registro
        </a>
    @endif
</div>

@if ($module === 'movimientos')
    <form class="panel mb-3 p-3" method="GET">
        <div class="row g-2 align-items-end">
            <div class="col-sm-4">
                <label class="form-label">Fecha desde</label>
                <input
                    class="form-control"
                    type="date"
                    name="from"
                    value="{{ request('from') }}"
                >
            </div>

            <div class="col-sm-4">
                <label class="form-label">Fecha hasta</label>
                <input
                    class="form-control"
                    type="date"
                    name="to"
                    value="{{ request('to') }}"
                >
            </div>

            <div class="col-sm-4">
                <button class="btn btn-dark">Filtrar movimientos</button>

                <a class="btn btn-outline-secondary"
                   href="{{ route('movimientos.index') }}">
                    Limpiar
                </a>
            </div>
        </div>
    </form>
@endif

<section class="panel">
    <div class="panel-head">
        <div>
            <h3>Listado de registros</h3>
            <p>{{ count($rows) }} registros recientes</p>
        </div>

        <span class="pill">Terra Moda</span>
    </div>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    @if (count($rows))
                        @foreach (array_keys((array) $rows[0]) as $key)
                            <th>
                                {{ $nombres[$key] ?? ucfirst(str_replace('_', ' ', $key)) }}
                            </th>
                        @endforeach
                    @else
                        <th>Información</th>
                    @endif

                    @if ($module === 'rollos')
                        <th>Reubicar rollo</th>
                    @endif
                </tr>
            </thead>

            <tbody>
                @forelse ($rows as $row)
                    <tr>
                        @foreach ((array) $row as $key => $value)
                            <td>
                                @if (in_array($key, ['created_at', 'updated_at']) && $value)
                                    {{ \Carbon\Carbon::parse($value)->format('d/m/Y H:i') }}
                                @elseif ($key === 'password')
                                    ••••••••
                                @elseif ($key === 'active')
                                    {{ $value ? 'Sí' : 'No' }}
                                @elseif ($key === 'status')
                                    {{ ucfirst(str_replace('_', ' ', $value ?? '')) }}
                                @elseif (in_array($key, ['old_values', 'new_values']) && $value)
                                    <details>
                                        <summary>Ver detalles</summary>
                                        <pre class="small text-wrap">{{ $value }}</pre>
                                    </details>
                                @else
                                    {{ is_null($value) ? '—' : (is_bool($value) ? ($value ? 'Sí' : 'No') : $value) }}
                                @endif
                            </td>
                        @endforeach

                        @if ($module === 'rollos')
                            <td>
                                <form
                                    method="POST"
                                    action="{{ route('rollos.reubicar', $row->id) }}"
                                    class="d-flex gap-1"
                                >
                                    @csrf

                                    <select
                                        class="form-select form-select-sm"
                                        name="location_id"
                                        required
                                    >
                                        <option value="">Elegir ubicación...</option>

                                        @foreach ($locations->where('branch_id', $row->branch_id) as $loc)
                                            <option
                                                value="{{ $loc->id }}"
                                                @selected($row->location_id == $loc->id)
                                            >
                                                E{{ $loc->shelf }}-P{{ $loc->position }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <button class="btn btn-sm btn-outline-dark">
                                        Guardar
                                    </button>
                                </form>

                                <a class="small"
                                   href="{{ route('rollos.historial', $row->id) }}">
                                    Ver historial
                                </a>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="30" class="empty">
                            No hay registros todavía. Usa “Nuevo registro”
                            para agregar el primero.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

@endsection
