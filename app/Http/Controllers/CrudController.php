<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class CrudController extends Controller
{
    private array $config = [
        'sucursales' => [
            'table' => 'branches',
            'title' => 'Sucursales',
            'fields' => [
                'name' => 'Nombre',
                'address' => 'Dirección',
                'phone' => 'Teléfono',
            ],
        ],
        'categorias' => [
            'table' => 'categories',
            'title' => 'Categorías',
            'fields' => [
                'name' => 'Nombre',
                'description' => 'Descripción',
            ],
        ],
        'productos' => [
            'table' => 'products',
            'title' => 'Telas y productos',
            'fields' => [
                'code' => 'Código',
                'name' => 'Nombre',
                'category_id' => 'Categoría (ID)',
                'fabric_type' => 'Tipo de tela',
                'design' => 'Diseño',
                'color' => 'Color',
                'purchase_price' => 'Precio de compra (Bs/m)',
                'sale_price' => 'Precio de venta (Bs/m)',
                'low_stock_meters' => 'Alerta de stock (m)',
            ],
        ],
        'ubicaciones' => [
            'table' => 'locations',
            'title' => 'Ubicaciones',
            'fields' => [
                'branch_id' => 'Sucursal (ID)',
                'warehouse' => 'Depósito',
                'room' => 'Ambiente',
                'aisle' => 'Pasillo',
                'shelf' => 'Estante',
                'position' => 'Posición',
                'level' => 'Nivel',
                'section' => 'Sección',
            ],
        ],
        'rollos' => [
            'table' => 'rolls',
            'title' => 'Rollos',
            'fields' => [
                'code' => 'Código',
                'product_id' => 'Producto (ID)',
                'branch_id' => 'Sucursal (ID)',
                'location_id' => 'Ubicación (ID)',
                'meters_initial' => 'Metraje inicial',
                'meters_available' => 'Metraje disponible',
            ],
        ],
        'clientes' => [
            'table' => 'customers',
            'title' => 'Clientes',
            'fields' => [
                'full_name' => 'Nombre completo',
                'document_number' => 'Documento',
                'phone' => 'Teléfono',
                'email' => 'Correo electrónico',
                'address' => 'Dirección',
            ],
        ],
        'movimientos' => [
            'table' => 'inventory_movements',
            'title' => 'Movimientos de inventario',
            'fields' => [],
        ],
        'usuarios' => [
            'table' => 'users',
            'title' => 'Usuarios',
            'fields' => [
                'name' => 'Nombre',
                'email' => 'Correo',
                'password' => 'Contraseña',
                'role' => 'Rol (admin/encargado/vendedor/almacen/consulta)',
                'branch_id' => 'Sucursal (ID)',
            ],
        ],
    ];

    public function index(Request $r)
    {
        $slug = $r->route('module');
        $m = $this->module($r);

        if ($slug === 'movimientos') {
            $q = DB::table('inventory_movements')
                ->leftJoin('rolls', 'rolls.id', '=', 'inventory_movements.roll_id')
                ->leftJoin('products', 'products.id', '=', 'rolls.product_id')
                ->leftJoin('users', 'users.id', '=', 'inventory_movements.user_id')
                ->select(
                    'inventory_movements.*',
                    'rolls.code as roll_code',
                    'products.name as product_name',
                    'users.name as user_name'
                );

            if ($r->filled('from')) {
                $q->whereDate('inventory_movements.created_at', '>=', $r->input('from'));
            }

            if ($r->filled('to')) {
                $q->whereDate('inventory_movements.created_at', '<=', $r->input('to'));
            }

            $rows = $q->orderByDesc('inventory_movements.id')
                ->limit(500)
                ->get();

        } elseif ($slug === 'rollos') {
            $q = DB::table('rolls')
                ->leftJoin('products', 'products.id', '=', 'rolls.product_id')
                ->leftJoin('locations', 'locations.id', '=', 'rolls.location_id')
                ->select(
                    'rolls.*',
                    'products.name as product_name',
                    DB::raw("
                        CASE
                            WHEN locations.shelf IS NOT NULL
                            AND locations.position IS NOT NULL
                            THEN CONCAT('E', locations.shelf, '-P', locations.position)
                            ELSE 'Sin ubicación'
                        END as location_code
                    ")
                );

            // Obtener el ID de sucursal desde la sesión correctamente.
            $branchId = $r->session()->get('branch_id');

            if ($branchId) {
                $q->where('rolls.branch_id', $branchId);
            }

            $rows = $q->orderByDesc('rolls.id')
                ->limit(200)
                ->get();

        } elseif ($slug === 'ubicaciones') {
            $rows = DB::table('locations')
                ->select(
                    '*',
                    DB::raw("
                        CASE
                            WHEN shelf IS NOT NULL AND position IS NOT NULL
                            THEN CONCAT('E', shelf, '-P', position)
                            ELSE 'Código pendiente'
                        END as location_code
                    ")
                )
                ->orderByDesc('id')
                ->limit(200)
                ->get();

        } else {
            $rows = DB::table($m['table'])
                ->orderByDesc('id')
                ->limit(200)
                ->get();
        }

        $locations = DB::table('locations')
            ->orderBy('shelf')
            ->orderBy('position')
            ->get();

        return view('crud.index', [
            'module' => $slug,
            'config' => $m,
            'rows' => $rows,
            'locations' => $locations,
        ]);
    }

    public function create(Request $r)
    {
        $m = $this->module($r);

        return view('crud.create', [
            'module' => $r->route('module'),
            'config' => $m,
        ]);
    }

    public function store(Request $r)
    {
        $slug = $r->route('module');
        $m = $this->module($r);
        $input = [];

        foreach ($m['fields'] as $field => $label) {
            if ($field === 'password') {
                $input[$field] = Hash::make($r->input($field));
            } else {
                $input[$field] = $r->input($field);
            }
        }

        if (in_array($slug, ['sucursales', 'categorias', 'productos', 'clientes'])) {
            $input['active'] = true;
        }

        if ($slug === 'rollos') {
            $input['meters_sold'] = 0;
            $input['status'] = 'disponible';

            if (isset($input['meters_initial'])) {
                $input['meters_available'] = $input['meters_initial'];
            }
        }

        if ($slug === 'usuarios') {
            $input['active'] = true;
        }

        if (in_array($slug, [
            'sucursales',
            'productos',
            'categorias',
            'clientes',
            'ubicaciones',
            'rollos',
            'usuarios',
        ])) {
            $input['created_at'] = now();
            $input['updated_at'] = now();
        }

        if ($slug === 'movimientos') {
            return redirect()->route('movimientos.index');
        }

        $rules = [];

        foreach ($m['fields'] as $f => $label) {
            if ($f === 'password') {
                $rules[$f] = 'required|min:8';

            } elseif ($slug === 'productos' && $f === 'code') {
                $rules[$f] = 'required|string|max:255|unique:products,code';

            } elseif ($slug === 'rollos' && $f === 'code') {
                $rules[$f] = 'required|string|max:255|unique:rolls,code';

            } elseif ($slug === 'usuarios' && $f === 'email') {
                $rules[$f] = 'required|email|unique:users,email';

            } elseif (
                $slug === 'ubicaciones'
                && in_array($f, ['shelf', 'position'])
            ) {
                $rules[$f] = 'required|string|max:20';

            } elseif (
                str_contains($f, 'price')
                || str_contains($f, 'meters')
            ) {
                $rules[$f] = 'required|numeric|min:0';

            } elseif (str_ends_with($f, '_id')) {
                $rules[$f] = 'required|integer';

            } else {
                $rules[$f] = 'nullable|string|max:255';
            }
        }

        $r->validate($rules, [
            'code.unique' => 'Ese código ya existe. Introduce uno diferente.',
            'email.unique' => 'Ese correo ya está registrado.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        ]);

        $newId = DB::table($m['table'])->insertGetId($input);

        if (in_array($slug, ['rollos', 'ubicaciones', 'productos'])) {
            DB::table('audit_logs')->insert([
                'user_id' => $r->session()->get('user_id'),
                'action' => $slug . '_creado',
                'entity_type' => rtrim($slug, 's'),
                'entity_id' => $newId,
                'old_values' => null,
                'new_values' => json_encode($input),
                'ip_address' => $r->ip(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()
            ->route($slug . '.index')
            ->with('success', 'Registro creado correctamente.');
    }

    public function historialUbicacion(int $roll)
    {
        $item = DB::table('rolls')->where('id', $roll)->first();

        if (!$item) {
            abort(404);
        }

        $history = DB::table('roll_location_movements as h')
            ->leftJoin('users', 'users.id', '=', 'h.user_id')
            ->leftJoin('locations as old', 'old.id', '=', 'h.from_location_id')
            ->leftJoin('locations as new', 'new.id', '=', 'h.to_location_id')
            ->where('h.roll_id', $roll)
            ->select(
                'h.*',
                'users.name as user_name',
                DB::raw("
                    CASE
                        WHEN old.shelf IS NOT NULL AND old.position IS NOT NULL
                        THEN CONCAT('E', old.shelf, '-P', old.position)
                        ELSE 'Sin ubicación'
                    END as from_code
                "),
                DB::raw("
                    CASE
                        WHEN new.shelf IS NOT NULL AND new.position IS NOT NULL
                        THEN CONCAT('E', new.shelf, '-P', new.position)
                        ELSE 'Sin ubicación'
                    END as to_code
                ")
            )
            ->orderByDesc('h.id')
            ->get();

        return view('crud.location-history', [
            'roll' => $item,
            'history' => $history,
        ]);
    }

    public function auditoria(Request $r)
    {
        $q = DB::table('audit_logs')
            ->leftJoin('users', 'users.id', '=', 'audit_logs.user_id')
            ->select('audit_logs.*', 'users.name as user_name');

        if ($r->filled('from')) {
            $q->whereDate('audit_logs.created_at', '>=', $r->input('from'));
        }

        if ($r->filled('to')) {
            $q->whereDate('audit_logs.created_at', '<=', $r->input('to'));
        }

        $logs = $q->orderByDesc('audit_logs.id')
            ->limit(500)
            ->get();

        return view('crud.audit', [
            'logs' => $logs,
            'from' => $r->input('from'),
            'to' => $r->input('to'),
        ]);
    }

    public function reubicar(Request $r, int $roll)
    {
        $data = $r->validate([
            'location_id' => 'required|exists:locations,id',
            'reason' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($r, $roll, $data) {
            $item = DB::table('rolls')
                ->where('id', $roll)
                ->lockForUpdate()
                ->first();

            if (!$item) {
                abort(404);
            }

            $branchId = $r->session()->get('branch_id');
            $userRole = $r->session()->get('user_role');

            if (
                $branchId
                && $userRole !== 'admin'
                && (int) $item->branch_id !== (int) $branchId
            ) {
                abort(403);
            }

            $location = DB::table('locations')
                ->where('id', $data['location_id'])
                ->first();

            if (
                !$location
                || (int) $location->branch_id !== (int) $item->branch_id
            ) {
                throw ValidationException::withMessages([
                    'location_id' => 'La ubicación debe pertenecer a la misma sucursal del rollo.',
                ]);
            }

            $old = $item->location_id;

            DB::table('rolls')
                ->where('id', $roll)
                ->update([
                    'location_id' => $location->id,
                    'updated_at' => now(),
                ]);

            DB::table('roll_location_movements')->insert([
                'roll_id' => $roll,
                'user_id' => $r->session()->get('user_id'),
                'from_location_id' => $old,
                'to_location_id' => $location->id,
                'reason' => $data['reason'] ?? 'Reubicación',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('audit_logs')->insert([
                'user_id' => $r->session()->get('user_id'),
                'action' => 'rollo_reubicado',
                'entity_type' => 'roll',
                'entity_id' => $roll,
                'old_values' => json_encode([
                    'location_id' => $old,
                ]),
                'new_values' => json_encode([
                    'location_id' => $location->id,
                    'location_code' => 'E' . $location->shelf . '-P' . $location->position,
                ]),
                'ip_address' => $r->ip(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        return redirect()
            ->route('rollos.index')
            ->with('success', 'Ubicación del rollo actualizada y movimiento registrado.');
    }

    private function module(Request $r)
    {
        $slug = $r->route('module');

        if (!isset($this->config[$slug])) {
            abort(404);
        }

        return $this->config[$slug];
    }
}
