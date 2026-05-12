<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AdminManagerController extends Controller
{
    /**
     * List all administrators.
     */
    public function index()
    {
        $admins = User::where('role', 'admin')->latest()->paginate(15);
        return view('admin.admins.index', compact('admins'));
    }

    /**
     * Show form to create a new administrator.
     */
    public function create()
    {
        return view('admin.admins.create');
    }

    /**
     * Store a newly created administrator.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $newAdmin = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
        ]);

        // Log the creation
        ActivityLogger::log('admin.created', "New administrator account created: {$newAdmin->name} ({$newAdmin->email})", $newAdmin);

        return redirect()->route('admin.admins.index')
            ->with('success', "✅ Administrator account for {$newAdmin->name} created successfully.");
    }
}
