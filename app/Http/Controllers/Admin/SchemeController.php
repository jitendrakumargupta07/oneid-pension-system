<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PensionScheme;
use Illuminate\Http\Request;

class SchemeController extends Controller
{
    public function index()
    {
        $schemes = PensionScheme::withCount('applications')->latest()->paginate(10);
        return view('admin.schemes.index', compact('schemes'));
    }

    public function create()
    {
        return view('admin.schemes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'            => 'required|string|max:255',
            'description'     => 'required|string',
            'monthly_amount'  => 'required|numeric|min:1',
            'eligibility_age' => 'required|integer|min:50|max:100',
            'scheme_code'     => 'required|string|unique:pension_schemes,scheme_code|max:20',
            'status'          => 'required|in:active,inactive',
        ]);

        PensionScheme::create($request->all());

        return redirect()->route('admin.schemes.index')
            ->with('success', 'Pension scheme created successfully.');
    }

    public function edit(PensionScheme $scheme)
    {
        return view('admin.schemes.edit', compact('scheme'));
    }

    public function update(Request $request, PensionScheme $scheme)
    {
        $request->validate([
            'name'            => 'required|string|max:255',
            'description'     => 'required|string',
            'monthly_amount'  => 'required|numeric|min:1',
            'eligibility_age' => 'required|integer|min:50|max:100',
            'scheme_code'     => 'required|string|max:20|unique:pension_schemes,scheme_code,' . $scheme->id,
            'status'          => 'required|in:active,inactive',
        ]);

        $scheme->update($request->all());

        return redirect()->route('admin.schemes.index')
            ->with('success', 'Pension scheme updated successfully.');
    }

    public function destroy(PensionScheme $scheme)
    {
        $scheme->delete();
        return redirect()->route('admin.schemes.index')
            ->with('success', 'Scheme deleted successfully.');
    }
}
