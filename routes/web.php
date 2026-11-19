<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CrudController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\TransferController;

Route::get('/login', [AuthController::class, 'show'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth.session')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::get('/ventas/crear', [SaleController::class, 'create'])->name('sales.create');
    Route::post('/ventas', [SaleController::class, 'store'])->name('sales.store');
    Route::get('/ventas', [SaleController::class, 'index'])->name('sales.index');
    Route::get('/transferencias/crear', [TransferController::class, 'create'])->name('transfers.create');
    Route::post('/transferencias', [TransferController::class, 'store'])->name('transfers.store');
    Route::get('/transferencias', [TransferController::class, 'index'])->name('transfers.index');

    $modules = [
      'sucursales' => ['branches','Sucursales'], 'categorias' => ['categories','Categorías'],
      'productos' => ['products','Telas y productos'], 'ubicaciones' => ['locations','Ubicaciones'],
      'rollos' => ['rolls','Rollos'], 'clientes' => ['customers','Clientes'],
      'movimientos' => ['inventory-movements','Movimientos'], 'usuarios' => ['users','Usuarios'],
    ];
    foreach ($modules as $slug => [$table, $label]) {
        Route::get("/{$slug}", [CrudController::class, 'index'])->defaults('module', $slug)->name($slug.'.index');
        Route::get("/{$slug}/crear", [CrudController::class, 'create'])->defaults('module', $slug)->name($slug.'.create');
        Route::post("/{$slug}", [CrudController::class, 'store'])->defaults('module', $slug)->name($slug.'.store');
    }
});
