<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;


class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'admin')
            ->where('is_deleted', 0)
            ->paginate(50);
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
            'gender' => 'required|string',
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
    public function adminedit(int $id)
    {
        $user = User::findOrFail($id);
        return view('user.adminedit', compact('user'));
    }

    public function adminupdate(Request $request, int $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $user->id,
            'phone'       => 'nullable|string|max:20',
            'gender' => 'nullable|string',
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
    public function admindestroy(int $id)
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
            ->paginate(50);

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
            'gender' => 'required|string',
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
    public function junioredit(int $id)
    {
        $user = User::findOrFail($id);
        return view('user.junioredit', compact('user'));
    }

    public function juniorupdate(Request $request, int $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $user->id,
            'phone'       => 'nullable|string|max:20',
            'role'        => 'required|string|in:junior,admin,senior,customer,accountant',
            'password'    => 'nullable|string|min:6|confirmed',
        ]);

        $validated['status'] = $request->has('status') ? 1 : 0;

        /* ================= IMAGE UPLOAD FIX ================= */

        if ($request->hasFile('image')) {
            $file = $request->file('image');

            $uploadPath = 'user_images';

            // ✅ Ensure directory exists
            if (!File::exists(public_path($uploadPath))) {
                File::makeDirectory(public_path($uploadPath), 0755, true);
            }

            $timestamp = now()->format('Ymd_His');
            $filename  = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $newName   = Str::slug($filename) . "_{$timestamp}.{$extension}";

            try {
                // ✅ Laravel-safe upload (FIX)
                $file->storeAs($uploadPath, $newName, 'public');

                // ✅ Delete old image safely
                if ($user->image && File::exists(public_path($user->image))) {
                    File::delete(public_path($user->image));
                }

                $validated['image'] = $uploadPath . '/' . $newName;
            } catch (\Exception $e) {
                return back()->with('error', 'Image upload failed: ' . $e->getMessage());
            }
        }


        /* ================= PASSWORD HANDLING ================= */

        if (!empty($request->password)) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()
            ->route('users.junior.edit', $user->id)
            ->with('success', 'User updated successfully!');
    }


    // ======================
    // DELETE
    // ======================
    public function juniordestroy(int $id)
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
            ->paginate(50);
        return view('user.senior', compact('users'));
    }

    public function seniorgroup()
    {
        $users = User::where('role', 'senior')
            ->where('is_deleted', 0)
            ->paginate(50);
        return view('user.seniorgroup', compact('users'));
    }

    public function seniorgroupmail()
    {
        $users = User::where('role', 'senior')
            ->where('is_deleted', 0)
            ->paginate(50);
        return view('user.seniorgroupmail', compact('users'));
    }

    public function seniorgroupmailchart()
    {
        $users = User::whereIn('role', ['senior', 'junior'])
            ->where('is_deleted', 0)
            ->paginate(50);

        // Add this only
        $seniors = User::where('role', 'senior')
            ->where('is_deleted', 0)
            ->get();

        foreach ($seniors as $senior) {
            $juniorIds = is_array($senior->mail) ? $senior->mail : [];

            $senior->juniors = User::whereIn('id', $juniorIds)
                ->where('role', 'junior')
                ->where('is_deleted', 0)
                ->get();
        }

        return view('user.seniorgroupmailchart', compact('users', 'seniors'));
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
            'gender' => 'required|string',
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
    public function senioredit(int $id)
    {
        $user = User::findOrFail($id);
        return view('user.senioredit', compact('user'));
    }

    public function seniorupdate(Request $request, int $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $user->id,
            'phone'       => 'nullable|string|max:20',
            'role'        => 'required|string|in:junior,admin,senior,customer,accountant',
            'password'    => 'nullable|string|min:6|confirmed',
        ]);

        $validated['status'] = $request->has('status') ? 1 : 0;

        /* ================= IMAGE UPLOAD FIX ================= */

        if ($request->hasFile('image')) {
            $file = $request->file('image');

            $uploadPath = 'user_images';

            // Ensure directory exists
            if (!File::exists(public_path($uploadPath))) {
                File::makeDirectory(public_path($uploadPath), 0755, true);
            }

            $timestamp = now()->format('Ymd_His');
            $filename  = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $newName   = Str::slug($filename) . "_{$timestamp}.{$extension}";

            try {
                // Same upload method as juniorupdate()
                $file->storeAs($uploadPath, $newName, 'public');

                // Delete old image
                if ($user->image && File::exists(public_path($user->image))) {
                    File::delete(public_path($user->image));
                }

                // Save relative path
                $validated['image'] = $uploadPath . '/' . $newName;
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

    public function senioreditgroup(int $id)
    {
        $user = User::findOrFail($id);

        $groupIds = User::where('id', '!=', $id)
            ->whereNotNull('group')
            ->pluck('group')
            ->toArray();

        $assignedJuniorIds = [];

        foreach ($groupIds as $group) {
            if (is_array($group)) {
                $assignedJuniorIds = array_merge($assignedJuniorIds, $group);
            }
        }

        $assignedJuniorIds = array_unique($assignedJuniorIds);

        $juniors = User::where('role', 'junior')
            ->where('is_deleted', 0)
            ->whereNotIn('id', $assignedJuniorIds)
            ->get();

        return view('user.senioreditgroup', compact('user', 'juniors'));
    }

    public function senioreditgroupmail(int $id)
    {
        $user = User::findOrFail($id);

        $mailIds = User::where('id', '!=', $id)
            ->whereNotNull('mail')
            ->pluck('mail')
            ->toArray();

        $assignedJuniorIds = [];

        foreach ($mailIds as $mail) {
            if (is_array($mail)) {
                $assignedJuniorIds = array_merge($assignedJuniorIds, $mail);
            }
        }

        $assignedJuniorIds = array_unique($assignedJuniorIds);

        $juniors = User::where('role', 'junior')
            ->where('is_deleted', 0)
            ->whereNotIn('id', $assignedJuniorIds)
            ->get();

        return view('user.senioreditgroupmail', compact('user', 'juniors'));
    }

    public function seniorgroupmailupdate(Request $request, int $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'mail' => 'nullable|array',
            'mail.*' => 'exists:users,id'
        ]);

        $existingMail = $user->mail ?? [];
        $newMail = $request->mail ?? [];

        $mergedMail = array_unique(array_merge($existingMail, $newMail));

        $validated['mail'] = $mergedMail;

        $user->update($validated);

        return redirect()->route("users.senior.editgroupmail", $user->id)
            ->with('success', 'Updated successfully!');
    }

    public function seniorgroupremove(int $seniorId, int $juniorId)
    {
        $user = User::findOrFail($seniorId);

        $groups = $user->group ?? [];

        // Remove junior id
        $groups = array_values(array_diff($groups, [$juniorId]));

        $user->group = $groups;
        $user->save();

        return back()->with('success', 'Junior removed successfully');
    }

    public function seniorgroupmailremove(int $seniorId, int $juniorId)
    {
        $user = User::findOrFail($seniorId);

        $mail = $user->mail ?? [];

        // Remove junior id 
        $mail = array_values(array_diff($mail, [$juniorId]));

        $user->mail = $mail;
        $user->save();

        return back()->with('success', 'Junior removed successfully');
    }
    // ======================
    // DELETE
    // ======================
    public function seniordestroy(int $id)
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
            ->paginate(50);
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
            'gender' => 'required|string',
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
    public function traineredit(int $id)
    {
        $user = User::findOrFail($id);
        return view('user.traineredit', compact('user'));
    }

    public function trainerupdate(Request $request, int $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $user->id,
            'phone'       => 'nullable|string|max:20',
            'gender' => 'nullable|string',
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
    public function trainerdestroy(int $id)
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
            ->paginate(50);
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
            'gender' => 'required|string',
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
    public function accountantedit(int $id)
    {
        $user = User::findOrFail($id);
        return view('user.accountantedit', compact('user'));
    }

    public function accountantupdate(Request $request, int $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $user->id,
            'phone'       => 'nullable|string|max:20',
            'gender' => 'nullable|string',
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
    public function accountantdestroy(int $id)
    {
        $user = User::findOrFail($id);
        $user->is_deleted = 1; // Mark as deleted
        $user->save();

        return redirect()->route("users.account")
            ->with('success',  ' deleted successfully!');
    }

    public function operation()
    {
        $users = User::where('role', 'operation')
            ->where('is_deleted', 0)
            ->paginate(50);
        return view('user.operation', compact('users'));
    }

    public function operationcreate()
    {
        return view('user.operationcreate');
    }

    public function operationstore(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'phone'       => 'nullable|string|max:20',
            'gender' => 'required|string',
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
    public function operationedit(int $id)
    {
        $user = User::findOrFail($id);
        return view('user.operationedit', compact('user'));
    }

    public function operationupdate(Request $request, int $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $user->id,
            'phone'       => 'nullable|string|max:20',
            'gender' => 'nullable|string',
            'role'        => 'required|string|in:junior,admin,senior,customer,operation',
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

        return redirect()->route("users.operation.edit", $user->id)
            ->with('success', ' updated successfully!');
    }

    // ======================
    // DELETE
    // ======================
    public function operationdestroy(int $id)
    {
        $user = User::findOrFail($id);
        $user->is_deleted = 1; // Mark as deleted
        $user->save();

        return redirect()->route("users.operation")
            ->with('success',  ' deleted successfully!');
    }


    public function customer()
    {
        $users = User::where('role', 'customer')
            ->where('is_deleted', 0)
            ->paginate(50);
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
            'gender' => 'required|string',
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
    public function customeredit(int $id)
    {
        $user = User::findOrFail($id);
        return view('user.customeredit', compact('user'));
    }

    public function customerupdate(Request $request, int $id)
    {
        $user = User::findOrFail($id);

        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $user->id,
            'phone'       => 'nullable|string|max:20',
            'gender' => 'nullable|string',
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
    public function customerdestroy(int $id)
    {
        $user = User::findOrFail($id);
        $user->is_deleted = 1; // Mark as deleted
        $user->save();

        return redirect()->route("users.customer")
            ->with('success',  ' deleted successfully!');
    }


    public function associate()
    {
        $users = User::where('role', 'associate')
            ->where('is_deleted', 0)
            ->paginate(50);
        return view('user.associate', compact('users'));
    }

    public function associatecreate()
    {
        return view('user.associatecreate');
    }

    public function associatestore(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'phone'       => 'nullable|string|max:20',
            'gender' => 'required|string',
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
        return redirect()->route("users.associate")
            ->with('success', ' added successfully!');
    }

    // ======================
    // EDIT / UPDATE
    // ======================
    public function associateedit(int $id)
    {
        $user = User::findOrFail($id);
        return view('user.associateedit', compact('user'));
    }

    public function associateupdate(Request $request, int $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $user->id,
            'phone'       => 'nullable|string|max:20',
            'gender' => 'nullable|string',
            'role'        => 'required|string|in:junior,admin,associate,customer,accountant',
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

        return redirect()->route("users.associate.edit", $user->id)
            ->with('success', ' updated successfully!');
    }

    // ======================
    // DELETE
    // ======================
    public function associatedestroy(int $id)
    {
        $user = User::findOrFail($id);
        $user->is_deleted = 1; // Mark as deleted
        $user->save();

        return redirect()->route("users.associate")
            ->with('success',  ' deleted successfully!');
    }


    public function support()
    {
        $users = User::where('role', 'support')
            ->where('is_deleted', 0)
            ->paginate(50);
        return view('user.support', compact('users'));
    }

    public function supportcreate()
    {
        return view('user.supportcreate');
    }

    public function supportstore(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'phone'       => 'nullable|string|max:20',
            'gender' => 'required|string',
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
        return redirect()->route("users.support")
            ->with('success', ' added successfully!');
    }

    // ======================
    // EDIT / UPDATE
    // ======================
    public function supportedit(int $id)
    {
        $user = User::findOrFail($id);
        return view('user.supportedit', compact('user'));
    }

    public function supportupdate(Request $request, int $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $user->id,
            'phone'       => 'nullable|string|max:20',
            'gender' => 'nullable|string',
            'role'        => 'required|string|in:junior,admin,support,customer,accountant',
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

        return redirect()->route("users.support.edit", $user->id)
            ->with('success', ' updated successfully!');
    }

    // ======================
    // DELETE
    // ======================
    public function supportdestroy(int $id)
    {
        $user = User::findOrFail($id);
        $user->is_deleted = 1; // Mark as deleted
        $user->save();

        return redirect()->route("users.support")
            ->with('success',  ' deleted successfully!');
    }


    public function writer()
    {
        $users = User::where('role', 'writer')
            ->where('is_deleted', 0)
            ->paginate(50);
        return view('user.writer', compact('users'));
    }

    public function writtercreate()
    {
        return view('user.writtercreate');
    }

    public function writterstore(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'phone'       => 'nullable|string|max:20',
            'gender' => 'required|string',
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
        return redirect()->route("users.writer")
            ->with('success', ' added successfully!');
    }

    // ======================
    // EDIT / UPDATE
    // ======================
    public function writteredit(int $id)
    {
        $user = User::findOrFail($id);
        return view('user.writteredit', compact('user'));
    }

    public function writterupdate(Request $request, int $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email,' . $user->id,
            'phone'       => 'nullable|string|max:20',
            'gender' => 'nullable|string',
            'role'        => 'required|string|in:junior,admin,writer,customer,accountant',
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

        return redirect()->route("users.writer.edit", $user->id)
            ->with('success', ' updated successfully!');
    }

    // ======================
    // DELETE
    // ======================
    public function writterdestroy(int $id)
    {
        $user = User::findOrFail($id);
        $user->is_deleted = 1; // Mark as deleted
        $user->save();

        return redirect()->route("users.writer")
            ->with('success',  ' deleted successfully!');
    }

    // ======================
    // BULK OPERATIONS
    // ======================
    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:status,role,delete',
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'integer|exists:users,id',
            'status' => 'nullable|boolean',
            'role' => 'nullable|string|in:junior,admin,senior,customer,accountant,trainer,associate,operation,resource,support,writer',
        ]);

        $userIds = $validated['user_ids'];
        $operation = $validated['action'];
        $data = [];

        if ($operation === 'status') {
            $data['status'] = $validated['status'] ?? 0;
        } elseif ($operation === 'role') {
            $data['role'] = $validated['role'] ?? 'user';
        }

        // Dispatch to queue for background processing
        \App\Services\QueueService::bulkUpdateUsers($operation, $userIds, $data);

        return response()->json([
            'success' => true,
            'message' => 'Bulk ' . $operation . ' operation queued for ' . count($userIds) . ' users'
        ]);
    }

    // ======================
    // CACHE WARMER
    // ======================
    public function warmCache()
    {
        \App\Services\QueueService::fillCache();

        return response()->json([
            'success' => true,
            'message' => 'Cache warming job dispatched'
        ]);
    }
}
