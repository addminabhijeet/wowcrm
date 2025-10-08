<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;


class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'admin')->get();
        return view('user.admin', compact('users'));
    }

    public function admincreate($role)
    {
        return view('user.admincreate', compact('role'));
    }

    public function adminstore(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role'     => 'required|string',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        User::create($validated);

        return redirect()->route("users." . strtolower($validated['role']))
            ->with('success', ucfirst($validated['role']) . ' added successfully!');
    }

    // ======================
    // EDIT / UPDATE
    // ======================
    public function adminedit($id)
    {
        $user = User::findOrFail($id);
        return view('user.adminedit', compact('user'));
    }

    public function adminupdate(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role'  => 'required|string',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        return redirect()->route("users." . strtolower($validated['role']))
            ->with('success', ucfirst($validated['role']) . ' updated successfully!');
    }

    // ======================
    // DELETE
    // ======================
    public function admindestroy($id)
    {
        $user = User::findOrFail($id);
        $role = $user->role;
        $user->delete();

        return redirect()->route("users." . strtolower($role))
            ->with('success', ucfirst($role) . ' deleted successfully!');
    }

    public function junior()
    {
        $users = User::where('role', 'junior')->get();
        return view('user.junior', compact('users'));
    }

    public function juniorcreate($role)
    {
        return view('user.juniorcreate', compact('role'));
    }

    public function juniorstore(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role'     => 'required|string',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        User::create($validated);

        return redirect()->route("users." . strtolower($validated['role']))
            ->with('success', ucfirst($validated['role']) . ' added successfully!');
    }

    // ======================
    // EDIT / UPDATE
    // ======================
    public function junioredit($id)
    {
        $user = User::findOrFail($id);
        return view('user.junioredit', compact('user'));
    }

    public function juniorupdate(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // ✅ Validate all relevant fields
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $user->id,
            'phone'       => 'nullable|string|max:20',
            'designation' => 'nullable|string',
            'role'        => 'required|string|in:junior,admin,senior,customer,accountant',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'password'    => 'nullable|string|min:6|confirmed',
        ]);

        // ✅ Handle status separately (checkbox)
        $validated['status'] = $request->has('status') ? 1 : 0;

        // ✅ Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($user->image && Storage::exists('public/user_images/' . $user->image)) {
                Storage::delete('public/user_images/' . $user->image);
            }

            // Save new image
            $filename = time() . '.' . $request->image->extension();
            $request->image->storeAs('public/user_images', $filename);
            $validated['image'] = $filename;
        }

        // ✅ Handle password update only if provided
        if (!empty($request->password)) {
            $validated['password'] = Hash::make($request->password);
        }

        // 🔹 Dump validated data before updating
        dd($validated);

        // ✅ Update user
        $user->update($validated);

        return redirect()->route("users." . strtolower($validated['role']))
            ->with('success', ucfirst($validated['role']) . ' updated successfully!');
    }

    // ======================
    // DELETE
    // ======================
    public function juniordestroy($id)
    {
        $user = User::findOrFail($id);
        $role = $user->role;
        $user->delete();

        return redirect()->route("users." . strtolower($role))
            ->with('success', ucfirst($role) . ' deleted successfully!');
    }

    public function senior()
    {
        $users = User::where('role', 'senior')->get();
        return view('user.senior', compact('users'));
    }

    public function seniorcreate($role)
    {
        return view('user.seniorcreate', compact('role'));
    }

    public function seniorstore(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role'     => 'required|string',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        User::create($validated);

        return redirect()->route("users." . strtolower($validated['role']))
            ->with('success', ucfirst($validated['role']) . ' added successfully!');
    }

    // ======================
    // EDIT / UPDATE
    // ======================
    public function senioredit($id)
    {
        $user = User::findOrFail($id);
        return view('user.senioredit', compact('user'));
    }

    public function seniorupdate(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role'  => 'required|string',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        return redirect()->route("users." . strtolower($validated['role']))
            ->with('success', ucfirst($validated['role']) . ' updated successfully!');
    }

    // ======================
    // DELETE
    // ======================
    public function seniordestroy($id)
    {
        $user = User::findOrFail($id);
        $role = $user->role;
        $user->delete();

        return redirect()->route("users." . strtolower($role))
            ->with('success', ucfirst($role) . ' deleted successfully!');
    }

    public function trainer()
    {
        $users = User::where('role', 'trainer')->get();
        return view('user.trainer', compact('users'));
    }

    public function trainercreate($role)
    {
        return view('user.trainercreate', compact('role'));
    }

    public function trainerstore(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role'     => 'required|string',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        User::create($validated);

        return redirect()->route("users." . strtolower($validated['role']))
            ->with('success', ucfirst($validated['role']) . ' added successfully!');
    }

    // ======================
    // EDIT / UPDATE
    // ======================
    public function traineredit($id)
    {
        $user = User::findOrFail($id);
        return view('user.traineredit', compact('user'));
    }

    public function trainerupdate(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role'  => 'required|string',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        return redirect()->route("users." . strtolower($validated['role']))
            ->with('success', ucfirst($validated['role']) . ' updated successfully!');
    }

    // ======================
    // DELETE
    // ======================
    public function trainerdestroy($id)
    {
        $user = User::findOrFail($id);
        $role = $user->role;
        $user->delete();

        return redirect()->route("users." . strtolower($role))
            ->with('success', ucfirst($role) . ' deleted successfully!');
    }

    public function accountant()
    {
        $users = User::where('role', 'accountant')->get();
        return view('user.accountant', compact('users'));
    }

    public function accountantcreate($role)
    {
        return view('user.accountantcreate', compact('role'));
    }

    public function accountantstore(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role'     => 'required|string',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        User::create($validated);

        return redirect()->route("users." . strtolower($validated['role']))
            ->with('success', ucfirst($validated['role']) . ' added successfully!');
    }

    // ======================
    // EDIT / UPDATE
    // ======================
    public function accountantedit($id)
    {
        $user = User::findOrFail($id);
        return view('user.accountantedit', compact('user'));
    }

    public function accountantupdate(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role'  => 'required|string',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        return redirect()->route("users." . strtolower($validated['role']))
            ->with('success', ucfirst($validated['role']) . ' updated successfully!');
    }

    // ======================
    // DELETE
    // ======================
    public function accountantdestroy($id)
    {
        $user = User::findOrFail($id);
        $role = $user->role;
        $user->delete();

        return redirect()->route("users." . strtolower($role))
            ->with('success', ucfirst($role) . ' deleted successfully!');
    }

    public function customer()
    {
        $users = User::where('role', 'customer')->get();
        return view('user.customer', compact('users'));
    }

    public function customercreate($role)
    {
        return view('user.customercreate', compact('role'));
    }

    public function customerstore(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role'     => 'required|string',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        User::create($validated);

        return redirect()->route("users." . strtolower($validated['role']))
            ->with('success', ucfirst($validated['role']) . ' added successfully!');
    }

    // ======================
    // EDIT / UPDATE
    // ======================
    public function customeredit($id)
    {
        $user = User::findOrFail($id);
        return view('user.customeredit', compact('user'));
    }

    public function customerupdate(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role'  => 'required|string',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        $user->update($validated);

        return redirect()->route("users." . strtolower($validated['role']))
            ->with('success', ucfirst($validated['role']) . ' updated successfully!');
    }

    // ======================
    // DELETE
    // ======================
    public function customerdestroy($id)
    {
        $user = User::findOrFail($id);
        $role = $user->role;
        $user->delete();

        return redirect()->route("users." . strtolower($role))
            ->with('success', ucfirst($role) . ' deleted successfully!');
    }
}
