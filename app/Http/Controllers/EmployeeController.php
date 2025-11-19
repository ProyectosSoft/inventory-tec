<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    // public function __construct() { $this->middleware('auth'); }

    public function index(Request $request)
    {
        $q = Employee::query()
            ->with('company')
            ->when($request->filled('company_id'), fn($qq) => $qq->where('company_id', $request->company_id))
            ->when($request->filled('status'), fn($qq) => $qq->where('status', $request->status))
            ->when($request->filled('search'), function ($qq) use ($request) {
                $s = '%' . $request->search . '%';
                $qq->where(function ($sub) use ($s) {
                    $sub->where('first_name', 'like', $s)
                        ->orWhere('last_name', 'like', $s)
                        ->orWhere('email', 'like', $s)
                        ->orWhere('code', 'like', $s)
                        ->orWhere('document_id', 'like', $s);
                });
            })
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        $companies = Company::orderBy('name')->get(['id', 'name']);

        return view('employees.index', compact('q', 'companies'));
    }

    public function create()
    {
        $companies = Company::orderBy('name')->get(['id', 'name']);
        return view('employees.create', compact('companies'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'company_id' => ['required', 'exists:companies,id'],
            'code'       => ['nullable', 'string', 'max:50'],
            'first_name' => ['required', 'string', 'max:120'],
            'last_name'  => ['nullable', 'string', 'max:120'],
            'document_id' => ['nullable', 'string', 'max:120'],
            'email'      => ['nullable', 'email', 'max:255'],
            'phone'      => ['nullable', 'string', 'max:50'],
            'position'   => ['nullable', 'string', 'max:120'],
            'site'       => ['nullable', 'string', 'max:120'],
            'status'     => ['required', Rule::in(['active', 'inactive', 'suspended'])],
        ]);

        $employee = Employee::create($data);

        return redirect()
            ->route('employees.show', $employee)
            ->with('success', 'Empleado creado correctamente');
    }

    // public function show(Employee $employee)
    // {
    //     $employee->load(['company', 'currentDevices.type', 'currentDevices.company']);
    //     return view('employees.show', compact('employee'));
    // }

    public function show(Employee $employee)
    {
        $employee->load([
            'company',
            'currentDevices.type',
            'currentDevices.company'
        ]);

        return view('employees.show', compact('employee'));
    }



    public function edit(Employee $employee)
    {
        $companies = Company::orderBy('name')->get(['id', 'name']);
        return view('employees.edit', compact('employee', 'companies'));
    }

    public function update(Request $request, Employee $employee)
    {
        $data = $request->validate([
            'company_id' => ['required', 'exists:companies,id'],
            'code'       => ['nullable', 'string', 'max:50'],
            'first_name' => ['required', 'string', 'max:120'],
            'last_name'  => ['nullable', 'string', 'max:120'],
            'document_id' => ['nullable', 'string', 'max:120'],
            'email'      => ['nullable', 'email', 'max:255'],
            'phone'      => ['nullable', 'string', 'max:50'],
            'position'   => ['nullable', 'string', 'max:120'],
            'site'       => ['nullable', 'string', 'max:120'],
            'status'     => ['required', Rule::in(['active', 'inactive', 'suspended'])],
        ]);

        $employee->update($data);

        return redirect()
            ->route('employees.show', $employee)
            ->with('success', 'Empleado actualizado');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();

        return redirect()
            ->route('employees.index')
            ->with('success', 'Empleado eliminado');
    }

    public function search(Request $request)
    {
        $term = trim($request->get('q', ''));

        if (strlen($term) < 2) {
            return response()->json([]);
        }

        $employees = \App\Models\Employee::query()
            ->where('first_name', 'like', "%{$term}%")
            ->orWhere('last_name', 'like', "%{$term}%")
            ->orWhere('document_id', 'like', "%{$term}%")
            ->orWhere('code', 'like', "%{$term}%")
            ->limit(10)
            ->get(['id', 'code', 'first_name', 'last_name', 'document_id']);

        return response()->json($employees);
    }
}
