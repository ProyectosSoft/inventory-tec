<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Models\DeviceType;
use App\Http\Controllers\{
    CompanyController,
    DeviceController,
    DeviceAssignmentController,
    DeviceTypeController,
    EmployeeController,
    AssignmentController,
    DashboardController
};

// =========================
// 🏠 Página de inicio
// =========================
Route::get('/', function () {
    return view('welcome');
})->name('home');

// =========================
// 📊 Dashboard (solo autenticados)
// =========================
// Route::view('dashboard', 'dashboard')
//     ->middleware(['auth', 'verified'])
//     ->name('dashboard');
// Route::get('/dashboard', [DashboardController::class, 'index'])
//     ->middleware(['auth'])
//     ->name('dashboard');


Route::middleware(['auth'])->group(function () {

    // Dashboard principal
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // AJAX: equipos por tipo
    Route::get('/dashboard/company/{company}/devices-by-type', [DashboardController::class, 'devicesByType'])
        ->name('dashboard.company.devicesByType');

    // AJAX: equipos asignados por tipo
    Route::get('/dashboard/company/{company}/employees-by-type', [DashboardController::class, 'employeesByType'])
        ->name('dashboard.company.employeesByType');

    // =========================
    // ⚙️ Configuración de usuario (Livewire Volt)
    // =========================
    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');

    // =========================
    // 👥 Empleados
    // =========================
    Route::get('/employees/search', [EmployeeController::class, 'search'])
        ->name('employees.search');
    Route::resource('employees', EmployeeController::class);

    // =========================
    // 🏢 Empresas
    // =========================
    Route::resource('companies', CompanyController::class);

    // =========================
    // 🖥️ Tipos de dispositivos
    // =========================
    Route::resource('device-types', DeviceTypeController::class)->except(['show']);

    // =========================
    // 💻 Dispositivos
    // =========================
    Route::get('/blade/devices/partials/fields/{type}', function (string $type) {
        $deviceType = DeviceType::where('key', $type)->first();
        if (!$deviceType) return response('', 204);
        return view('devices.partials.fields._dynamic', [
            'schema' => $deviceType->schema ?? ['groups' => []],
        ])->render();
    })->name('blade.devices.partials.fields');

    Route::get('/devices/{device}/history', [DeviceController::class, 'history'])
        ->name('devices.history');

    // Mantenemos rutas antiguas de DeviceAssignmentController (para compatibilidad)
    Route::post('/devices/{device}/assignments', [DeviceAssignmentController::class, 'store'])
        ->name('devices.assignments.store');
    Route::patch('/devices/{device}/assignments/{assignment}', [DeviceAssignmentController::class, 'update'])
        ->name('devices.assignments.update');

    Route::resource('devices', DeviceController::class);

    // =========================
    // 🔄 Módulo independiente: ASIGNACIONES
    // =========================
    // routes/web.php

    Route::prefix('assignments')->name('assignments.')->group(function () {
        // 1. Rutas Estáticas y AJAX (Deben ir primero)
        Route::get('/', [AssignmentController::class, 'index'])->name('index');
        Route::get('/create', [AssignmentController::class, 'create'])->name('create');
        Route::post('/', [AssignmentController::class, 'store'])->name('store');

        // Rutas AJAX
        Route::get('/filter-devices', [AssignmentController::class, 'filterDevices'])->name('filterDevices');
        Route::get('/employees-by-company', [AssignmentController::class, 'getEmployeesByCompany'])->name('getEmployeesByCompany'); // <-- ¡MOVER ARRIBA!

        // Rutas Dinámicas con parámetro (pueden ir después, aunque la de consecutivo está bien)
        Route::get('/consecutive/{company}', [AssignmentController::class, 'getConsecutive'])->name('consecutive');

        // 2. Ruta Dinámica Genérica (Debe ir al final para evitar capturar las AJAX)
        Route::get('/{assignment}', [AssignmentController::class, 'show'])->name('show'); // <-- ¡MOVER ABAJO!
        Route::patch('/{assignment}/return', [AssignmentController::class, 'return'])->name('return');

        // 3. Ruta Generar PDF
        Route::get('{assignment}/pdf', [AssignmentController::class, 'pdf'])
            ->name('pdf');
    });
});

require __DIR__ . '/auth.php';
