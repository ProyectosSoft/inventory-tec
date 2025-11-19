<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CompanyController extends Controller
{
    // Si quieres proteger con auth:
    // public function __construct() { $this->middleware('auth'); }

    public function index()
    {
        $companies = Company::query()
            ->latest('id')
            ->paginate(15);

        return view('companies.index', compact('companies'));
    }

    public function create()
    {
        return view('companies.create');
    }

    public function store(Request $request)
    {
        try {

            $data = $request->validate([
                'name'       => ['required', 'string', 'max:255'],
                'legal_name' => ['nullable', 'string', 'max:255'],
                'tax_id'     => ['nullable', 'string', 'max:100'],
                'email'      => ['nullable', 'email', 'max:255'],
                'phone'      => ['nullable', 'string', 'max:50'],
                'website'    => ['nullable', 'string', 'max:255'],
                'address'    => ['nullable', 'string', 'max:255'],
                'city'       => ['nullable', 'string', 'max:120'],
                'country'    => ['nullable', 'string', 'size:2'],
                'status'     => ['required', Rule::in(['active', 'inactive'])],
            ]);

            $company = Company::create($data);

            session()->flash('swal', [
                'icon' => 'success',
                'title' => '¡Guardado con éxito!',
                'text' => 'La Empresa fue registrada correctamente',
                'showConfirmButton' => true,
            ]);
            return redirect()->route('companies.index');
            // return redirect()
            //     ->route('companies.show', $company)
            //     ->with('success', 'Empresa creada correctamente');

        } catch (\Exception $e) {
            session()->flash('swal', [
                'icon' => 'error',
                'title' => '¡Error!',
                'text' => 'No se pudo guardar la Empresa.',
                'footer' => $e->getMessage(),
                'showConfirmButton' => true,
            ]);
            return redirect()->back()->withInput();
        }
    }

    public function show(Company $company)
    {
        // Puedes cargar empleados si quieres: $company->load('employees');
        return view('companies.show', compact('company'));
    }

    public function edit(Company $company)
    {
        return view('companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        try {

            $data = $request->validate([
                'name'       => ['required', 'string', 'max:255'],
                'legal_name' => ['nullable', 'string', 'max:255'],
                'tax_id'     => ['nullable', 'string', 'max:100'],
                'email'      => ['nullable', 'email', 'max:255'],
                'phone'      => ['nullable', 'string', 'max:50'],
                'website'    => ['nullable', 'string', 'max:255'],
                'address'    => ['nullable', 'string', 'max:255'],
                'city'       => ['nullable', 'string', 'max:120'],
                'country'    => ['nullable', 'string', 'size:2'],
                'status'     => ['required', Rule::in(['active', 'inactive'])],
            ]);

            $company->update($data);

            // return redirect()
            //     ->route('companies.show', $company)
            //     ->with('success', 'Empresa actualizada');
            session()->flash('swal', [
                'icon' => 'success',
                'title' => '¡Actualizado con éxito!',
                'text' => 'La Empresa fue actualizada correctamente',
                'showConfirmButton' => true,
            ]);
            return redirect()->route('companies.index');
        } catch (\Exception $e) {
            session()->flash('swal', [
                'icon' => 'error',
                'title' => '¡Error!',
                'text' => 'No se pudo actualizar la Empresa.',
                'footer' => $e->getMessage(),
                'showConfirmButton' => true,
            ]);
            return redirect()->back()->withInput();
        }
    }

    public function destroy(Company $company)
    {
        try {

            $company->delete();

            session()->flash('swal', [
                'icon' => 'success',
                'title' => '¡Borrado con éxito!',
                'text' => 'La Empresa fue eliminada correctamente',
                'showConfirmButton' => true,
            ]);
            return redirect()->route('companies.index');
            // return redirect()
            //     ->route('companies.index')
            //     ->with('success', 'Empresa eliminada');
        } catch (\Exception $e) {
            session()->flash('swal', [
                'icon' => 'error',
                'title' => '¡Error!',
                'text' => 'No se pudo eliminar la Empresa.',
                'footer' => $e->getMessage(),
                'showConfirmButton' => true,
            ]);
            return redirect()->back();
        }
    }
}
