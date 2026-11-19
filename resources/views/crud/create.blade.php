@extends('layouts.app')
@section('title','Nuevo registro')
@section('heading','Nuevo registro')
@section('content')
<div class="welcome-row"><div><h2>{{ $config['title'] }}</h2><p>Completa los datos para registrar la información.</p></div><a class="btn btn-outline-secondary" href="{{ route($module.'.index') }}">← Volver</a></div>
<section class="panel form-panel"><form method="POST" action="{{ route($module.'.store') }}">@csrf<div class="form-grid">@foreach($config['fields'] as $field=>$label)<div class="mb-3"><label class="form-label">{{ $label }}</label>@if(str_contains($field,'description')||str_contains($field,'address'))<textarea class="form-control" name="{{ $field }}" rows="2">{{ old($field) }}</textarea>@else<input class="form-control" name="{{ $field }}" type="{{ $field==='password'?'password':(str_contains($field,'price')||str_contains($field,'meters')?'number':'text') }}" @if(str_contains($field,'price')||str_contains($field,'meters')) step="0.001" min="0" @endif value="{{ old($field) }}" @if(in_array($field,['name','code','product_id','branch_id','meters_initial','meters_available','sale_price'])) required @endif placeholder="Ingrese {{ strtolower($label) }}">@endif</div>@endforeach</div><div class="form-actions"><a class="btn btn-outline-secondary" href="{{ route($module.'.index') }}">Cancelar</a><button class="btn btn-dark px-4">Guardar registro →</button></div></form></section>
<div class="notice">Consejo: los campos identificados como (ID) utilizan el identificador numérico del registro relacionado.</div>
@endsection
