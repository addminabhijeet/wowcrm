<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'admin')
            ->where('is_deleted', 0)
            ->get();
        return view('user.admin', compact('users'));
    }

    public function admincreate()
    {
        return view('user.admincreate');
    }

    public function adminstore(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'phone'       => 'nullable|string|max:20',
            'designation' => 'required|string',
            'role'        => 'required|string',
            'password'    => 'required|string|min:6',
            'status'      => 'required|boolean',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle Image Upload directly to public/user_images
        if ($request->hasFile('image')) {
            $file = $request->file('image');

            // Generate unique, clean filename
            $timestamp = now()->format('Ymd_His');
            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $newName = Str::slug($filename) . "_{$timestamp}.{$extension}";

            try {
                // Move file directly to public/user_images
                $file->move(public_path('user_images'), $newName);
                $validated['image'] = 'user_images/' . $newName; // Store relative path for asset()
            } catch (\Exception $e) {
                return back()->with('error', 'Image upload failed: ' . $e->getMessage());
            }
        }

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route("users.admin")
            ->with('success', ' added successfully!');
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
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $user->id,
            'phone'       => 'nullable|string|max:20',
            'designation' => 'nullable|string',
            'role'        => 'required|string|in:junior,admin,senior,customer,accountant',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'password'    => 'nullable|string|min:6|confirmed',
        ]);

        $validated['status'] = $request->has('status') ? 1 : 0;

        // Handle Image Upload directly to public/user_images
        if ($request->hasFile('image')) {
            $file = $request->file('image');

            // Generate unique filename
            $timestamp = now()->format('Ymd_His');
            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $newName = Str::slug($filename) . "_{$timestamp}.{$extension}";

            try {
                // Move file directly to public/user_images
                $file->move(public_path('user_images'), $newName);

                // Delete old image if exists
                if ($user->image && file_exists(public_path($user->image))) {
                    unlink(public_path($user->image));
                }

                // Store relative path for asset()
                $validated['image'] = 'user_images/' . $newName;
            } catch (\Exception $e) {
                return back()->with('error', 'Image upload failed: ' . $e->getMessage());
            }
        }

        if (!empty($request->password)) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()
            ->route("users.admin.edit", $user->id)
            ->with('success', ' updated successfully!');
    }

    // ======================
    // DELETE
    // ======================
    public function admindestroy($id)
    {
        $user = User::findOrFail($id);
        $user->is_deleted = 1; // Mark as deleted
        $user->save();

        return redirect()->route("users.admin")
            ->with('success', 'User deleted successfully!');
    }

    public function junior()
    {
        $users = User::where('role', 'junior')
            ->where('is_deleted', 0)
            ->get();

        return view('user.junior', compact('users'));
    }


    public function juniorcreate()
    {
        return view('user.juniorcreate');
    }
    public function juniorstore(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'phone'       => 'nullable|string|max:20',
            'designation' => 'required|string',
            'role'        => 'required|string',
            'password'    => 'required|string|min:6',
            'status'      => 'required|boolean',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle Image Upload directly to public/user_images
        if ($request->hasFile('image')) {
            $file = $request->file('image');

            // Generate unique, clean filename
            $timestamp = now()->format('Ymd_His');
            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $newName = Str::slug($filename) . "_{$timestamp}.{$extension}";

            try {
                // Move file directly to public/user_images
                $file->move(public_path('user_images'), $newName);
                $validated['image'] = 'user_images/' . $newName; // Store relative path for asset()
            } catch (\Exception $e) {
                return back()->with('error', 'Image upload failed: ' . $e->getMessage());
            }
        }

        // Hash the password before saving
        $validated['password'] = Hash::make($validated['password']);

        // Create user record
        User::create($validated);

        return redirect()
            ->route('users.junior')
            ->with('success', 'Junior user added successfully!');
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

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $user->id,
            'phone'       => 'nullable|string|max:20',
            'designation' => 'nullable|string',
            'role'        => 'required|string|in:junior,admin,senior,customer,accountant',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'password'    => 'nullable|string|min:6|confirmed',
        ]);

        $validated['status'] = $request->has('status') ? 1 : 0;

        // Handle Image Upload directly to public/user_images
        if ($request->hasFile('image')) {
            $file = $request->file('image');

            // Generate unique filename
            $timestamp = now()->format('Ymd_His');
            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $newName = Str::slug($filename) . "_{$timestamp}.{$extension}";

            try {
                // Move file directly to public/user_images
                $file->move(public_path('user_images'), $newName);

                // Delete old image if exists
                if ($user->image && file_exists(public_path($user->image))) {
                    unlink(public_path($user->image));
                }

                // Store relative path for asset()
                $validated['image'] = 'user_images/' . $newName;
            } catch (\Exception $e) {
                return back()->with('error', 'Image upload failed: ' . $e->getMessage());
            }
        }

        // Handle password
        if (!empty($request->password)) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        // Update user
        $user->update($validated);

        return redirect()
            ->route('users.junior.edit', $user->id)
            ->with('success', 'User updated successfully!');
    }


    // ======================
    // DELETE
    // ======================
    public function juniordestroy($id)
    {
        $user = User::findOrFail($id);
        $user->is_deleted = 1; // Mark as deleted
        $user->save();

        return redirect()->route("users.junior")
            ->with('success',  ' deleted successfully!');
    }

    public function senior()
    {
        $users = User::where('role', 'senior')
            ->where('is_deleted', 0)
            ->get();
        return view('user.senior', compact('users'));
    }

    public function seniorcreate()
    {
        return view('user.seniorcreate');
    }

    public function seniorstore(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'phone'       => 'nullable|string|max:20',
            'designation' => 'required|string',
            'role'        => 'required|string',
            'password'    => 'required|string|min:6',
            'status'      => 'required|boolean',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle Image Upload directly to public/user_images
        if ($request->hasFile('image')) {
            $file = $request->file('image');

            // Generate unique, clean filename
            $timestamp = now()->format('Ymd_His');
            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $newName = Str::slug($filename) . "_{$timestamp}.{$extension}";

            try {
                // Move file directly to public/user_images
                $file->move(public_path('user_images'), $newName);
                $validated['image'] = 'user_images/' . $newName; // Store relative path for asset()
            } catch (\Exception $e) {
                return back()->with('error', 'Image upload failed: ' . $e->getMessage());
            }
        }

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);
        return redirect()->route("users.senior")
            ->with('success', ' added successfully!');
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
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $user->id,
            'phone'       => 'nullable|string|max:20',
            'designation' => 'nullable|string',
            'role'        => 'required|string|in:junior,admin,senior,customer,accountant',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'password'    => 'nullable|string|min:6|confirmed',
        ]);

        $validated['status'] = $request->has('status') ? 1 : 0;

        // Handle Image Upload directly to public/user_images
        if ($request->hasFile('image')) {
            $file = $request->file('image');

            // Generate unique filename
            $timestamp = now()->format('Ymd_His');
            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $newName = Str::slug($filename) . "_{$timestamp}.{$extension}";

            try {
                // Move file directly to public/user_images
                $file->move(public_path('user_images'), $newName);

                // Delete old image if exists
                if ($user->image && file_exists(public_path($user->image))) {
                    unlink(public_path($user->image));
                }

                // Store relative path for asset()
                $validated['image'] = 'user_images/' . $newName;
            } catch (\Exception $e) {
                return back()->with('error', 'Image upload failed: ' . $e->getMessage());
            }
        }

        if (!empty($request->password)) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route("users.senior.edit", $user->id)
            ->with('success', ' updated successfully!');
    }

    // ======================
    // DELETE
    // ======================
    public function seniordestroy($id)
    {
        $user = User::findOrFail($id);
        $user->is_deleted = 1; // Mark as deleted
        $user->save();

        return redirect()->route("users.senior")
            ->with('success',  ' deleted successfully!');
    }

    public function trainer()
    {
        $users = User::where('role', 'trainer')
            ->where('is_deleted', 0)
            ->get();
        return view('user.trainer', compact('users'));
    }

    public function trainercreate()
    {
        return view('user.trainercreate');
    }

    public function trainerstore(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'phone'       => 'nullable|string|max:20',
            'designation' => 'required|string',
            'role'        => 'required|string',
            'password'    => 'required|string|min:6',
            'status'      => 'required|boolean',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle Image Upload directly to public/user_images
        if ($request->hasFile('image')) {
            $file = $request->file('image');

            // Generate unique, clean filename
            $timestamp = now()->format('Ymd_His');
            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $newName = Str::slug($filename) . "_{$timestamp}.{$extension}";

            try {
                // Move file directly to public/user_images
                $file->move(public_path('user_images'), $newName);
                $validated['image'] = 'user_images/' . $newName; // Store relative path for asset()
            } catch (\Exception $e) {
                return back()->with('error', 'Image upload failed: ' . $e->getMessage());
            }
        }

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route("users.trainer")
            ->with('success', ' added successfully!');
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
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $user->id,
            'phone'       => 'nullable|string|max:20',
            'designation' => 'nullable|string',
            'role'        => 'required|string|in:junior,admin,senior,customer,accountant',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'password'    => 'nullable|string|min:6|confirmed',
        ]);

        $validated['status'] = $request->has('status') ? 1 : 0;

        // Handle Image Upload directly to public/user_images
        if ($request->hasFile('image')) {
            $file = $request->file('image');

            // Generate unique, clean filename
            $timestamp = now()->format('Ymd_His');
            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $newName = Str::slug($filename) . "_{$timestamp}.{$extension}";

            try {
                // Move file directly to public/user_images
                $file->move(public_path('user_images'), $newName);
                $validated['image'] = 'user_images/' . $newName; // Store relative path for asset()
            } catch (\Exception $e) {
                return back()->with('error', 'Image upload failed: ' . $e->getMessage());
            }
        }

        if (!empty($request->password)) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route("users.trainer.edit", $user->id)
            ->with('success', ' updated successfully!');
    }

    // ======================
    // DELETE
    // ======================
    public function trainerdestroy($id)
    {
        $user = User::findOrFail($id);
        $user->is_deleted = 1; // Mark as deleted
        $user->save();

        return redirect()->route("users.trainer")
            ->with('success',  ' deleted successfully!');
    }

    public function accountant()
    {
        $users = User::where('role', 'accountant')
            ->where('is_deleted', 0)
            ->get();
        return view('user.accountant', compact('users'));
    }

    public function accountantcreate()
    {
        return view('user.accountantcreate');
    }

    public function accountantstore(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'phone'       => 'nullable|string|max:20',
            'designation' => 'required|string',
            'role'        => 'required|string',
            'password'    => 'required|string|min:6',
            'status'      => 'required|boolean',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle Image Upload directly to public/user_images
        if ($request->hasFile('image')) {
            $file = $request->file('image');

            // Generate unique, clean filename
            $timestamp = now()->format('Ymd_His');
            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $newName = Str::slug($filename) . "_{$timestamp}.{$extension}";

            try {
                // Move file directly to public/user_images
                $file->move(public_path('user_images'), $newName);
                $validated['image'] = 'user_images/' . $newName; // Store relative path for asset()
            } catch (\Exception $e) {
                return back()->with('error', 'Image upload failed: ' . $e->getMessage());
            }
        }

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route("users.account")
            ->with('success', ' added successfully!');
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
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $user->id,
            'phone'       => 'nullable|string|max:20',
            'designation' => 'nullable|string',
            'role'        => 'required|string|in:junior,admin,senior,customer,accountant',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'password'    => 'nullable|string|min:6|confirmed',
        ]);

        $validated['status'] = $request->has('status') ? 1 : 0;

        // Handle Image Upload directly to public/user_images
        if ($request->hasFile('image')) {
            $file = $request->file('image');

            // Generate unique, clean filename
            $timestamp = now()->format('Ymd_His');
            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $newName = Str::slug($filename) . "_{$timestamp}.{$extension}";

            try {
                // Move file directly to public/user_images
                $file->move(public_path('user_images'), $newName);
                $validated['image'] = 'user_images/' . $newName; // Store relative path for asset()
            } catch (\Exception $e) {
                return back()->with('error', 'Image upload failed: ' . $e->getMessage());
            }
        }

        if (!empty($request->password)) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route("users.accountant.edit", $user->id)
            ->with('success', ' updated successfully!');
    }

    // ======================
    // DELETE
    // ======================
    public function accountantdestroy($id)
    {
        $user = User::findOrFail($id);
        $user->is_deleted = 1; // Mark as deleted
        $user->save();

        return redirect()->route("users.account")
            ->with('success',  ' deleted successfully!');
    }

    public function customer()
    {
        $users = User::where('role', 'customer')
            ->where('is_deleted', 0)
            ->get();
        return view('user.customer', compact('users'));
    }

    public function customercreate()
    {
        return view('user.customercreate');
    }

    public function customerstore(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'phone'       => 'nullable|string|max:20',
            'designation' => 'required|string',
            'role'        => 'required|string',
            'password'    => 'required|string|min:6',
            'status'      => 'required|boolean',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Handle Image Upload directly to public/user_images
        if ($request->hasFile('image')) {
            $file = $request->file('image');

            // Generate unique, clean filename
            $timestamp = now()->format('Ymd_His');
            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $newName = Str::slug($filename) . "_{$timestamp}.{$extension}";

            try {
                // Move file directly to public/user_images
                $file->move(public_path('user_images'), $newName);
                $validated['image'] = 'user_images/' . $newName; // Store relative path for asset()
            } catch (\Exception $e) {
                return back()->with('error', 'Image upload failed: ' . $e->getMessage());
            }
        }

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route("users.customer")
            ->with('success', ' added successfully!');
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

        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $user->id,
            'phone'       => 'nullable|string|max:20',
            'designation' => 'nullable|string',
            'role'        => 'required|string|in:junior,admin,senior,customer,accountant',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'password'    => 'nullable|string|min:6|confirmed',
        ]);

        $validated['status'] = $request->has('status') ? 1 : 0;

        // Handle Image Upload directly to public/user_images
        if ($request->hasFile('image')) {
            $file = $request->file('image');

            // Generate unique, clean filename
            $timestamp = now()->format('Ymd_His');
            $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $newName = Str::slug($filename) . "_{$timestamp}.{$extension}";

            try {
                // Move file directly to public/user_images
                $file->move(public_path('user_images'), $newName);
                $validated['image'] = 'user_images/' . $newName; // Store relative path for asset()
            } catch (\Exception $e) {
                return back()->with('error', 'Image upload failed: ' . $e->getMessage());
            }
        }

        if (!empty($request->password)) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);
        return redirect()->route("users.customer.edit", $user->id)
            ->with('success', ' updated successfully!');
    }

    // ======================
    // DELETE
    // ======================
    public function customerdestroy($id)
    {
        $user = User::findOrFail($id);
        $user->is_deleted = 1; // Mark as deleted
        $user->save();

        return redirect()->route("users.customer")
            ->with('success',  ' deleted successfully!');
    }
}
