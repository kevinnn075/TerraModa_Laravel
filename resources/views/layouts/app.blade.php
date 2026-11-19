<!doctype html>
<html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>@yield('title','Terra Moda') · Terra Moda</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="{{ asset('css/app.css') }}" rel="stylesheet"></head>
<body><div class="app-shell"><aside class="sidebar"><div class="brand"><span class="brand-mark">TM</span><div><strong>TERRA MODA</strong><small>Gestión inteligente</small></div></div><div class="side-caption">MENÚ PRINCIPAL</div>
<a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard')?'active':'' }}">▦ <span>Panel principal</span></a>
<div class="side-caption">OPERACIONES</div>
@foreach([['sucursales.index','Sucursales'],['categorias.index','Categorías'],['productos.index','Telas y productos'],['rollos.index','Rollos'],['ubicaciones.index','Ubicaciones'],['clientes.index','Clientes'],['sales.index','Ventas'],['transfers.index','Transferencias'],['movimientos.index','Movimientos'],['usuarios.index','Usuarios']] as [$route,$label])
<a href="{{ route($route) }}" class="{{ request()->routeIs($route)?'active':'' }}">◈ <span>{{ $label }}</span></a>
@endforeach
<div class="sidebar-bottom"><div class="user-dot">{{ strtoupper(substr(session('user_name','A'),0,1)) }}</div><div class="user-info"><strong>{{ session('user_name') }}</strong><small>{{ ucfirst(session('user_role')) }}</small></div><form method="POST" action="{{ route('logout') }}">@csrf<button class="logout" title="Cerrar sesión">↗</button></form></div>
</aside><main class="main"><header class="topbar"><div><span class="eyebrow">TERRA MODA / SISTEMA</span><h1>@yield('heading','Panel principal')</h1></div><div class="top-right"><span class="live-dot"></span><span>Entorno local</span><span class="avatar">{{ strtoupper(substr(session('user_name','A'),0,1)) }}</span></div></header><div class="content">
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if($errors->any())<div class="alert alert-danger"><strong>Revisa los datos:</strong><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
@yield('content')</div><footer>Terra Moda · Plataforma de inventario <span>Informática Industrial · La Paz, Bolivia</span></footer></main></div></body></html>
