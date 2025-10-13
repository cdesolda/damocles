<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Mail\UserAccepted;
use App\Mail\UserDeclined;
use App\Mail\UserIsActiveUpdated;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    /**
     * Get all the profile.
     */
    public function index($role = null)
    {
        $query = User::where('type', '=', 'Real');

        if ($role) {
            $query->where('role', '=', $role);
        } else {
            $query->where('role', '=', 'User');
        }

        $users = $query->orderBy('created_at', 'desc')->get();

        return view('user.users', [
            'users' => $users,
            'role' => $role
        ]);
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();

        // Converti la data nel formato corretto (YYYY-MM-DD)
        $validatedData['dob'] = Carbon::createFromFormat('d/m/Y', $validatedData['dob'])->format('Y-m-d');

        $request->user()->fill($validatedData);

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Update the user's acception (is_accept).
     */
    public function updateAccept(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'id' => ['required', 'integer'],
                'value' => ['required', 'boolean'],
            ]);
            $user = User::findOrFail($validatedData['id']);

            if ($validatedData['value'] != $user->is_accept) {
                $user->is_accept = $validatedData['value'];
                $user->save();

                Mail::to($user->email)->send($validatedData['value'] ? new UserAccepted($user) : new UserDeclined($user));
            }

            return redirect()->route('users', ['role' => strtolower($user->role)])->with('success', 'User updated successfully!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Update the user's state (is_active).
     */
    public function updateActive($id)
    {
        try {
            $user = User::findOrFail($id);

            $user->is_active = !$user->is_active;
            $user->save();

            Mail::to($user->email)->send(new UserIsActiveUpdated($user));

            return redirect()->route('users', ['role' => strtolower($user->role)])->with('success', 'User updated successfully!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Update the user's account from an Admin.
     */
    public function updateFromAdmin(Request $request)
    {
        $validatedData = $request->validate([
            'id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'string', 'max:255'],
            'dob' => ['required', 'date'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'role' => ['required', 'string', 'in:Admin,Evaluator,User'],
            'company_role' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'in:Real,Fake,DigitalTwin'],
        ]);

        $user = User::findOrFail($request->id);

        if ($request->email !== $user->email) {
            if (User::where('email', $request->email)->exists()) {
                return redirect()->back()->withErrors(['email' => 'This email is already in use.'])->withInput();
            }
        }

        $user->update($validatedData);

        return redirect()->route('users')->with('success', 'User updated successfully!');
    }

    /**
     * Delete the user's account from an Admin.
     */
    public function destroyFromAdmin(Request $request, $id): RedirectResponse
    {
        // Verifica se l'utente autenticato è un amministratore
        if (!$request->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $userToDelete = User::findOrFail($id);

        $userToDelete->delete();

        return Redirect::route('users')->with('success', 'User deleted successfully!');
    }

    /**
     * Create fake user.
     */
    public function createFakeUser(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'string', 'max:255'],
            'dob' => ['required', 'date_format:d/m/Y'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'company_role' => ['required', 'string', 'max:255'],
        ]);

        if (User::where('email', $validatedData['email'])->exists()) {
            return redirect()->back()->withErrors(['email' => 'This email is already in use.'])->withInput();
        }

        $dateOfBirth = Carbon::createFromFormat('d/m/Y', $validatedData['dob'])->format('Y-m-d');

        $fakeUser = User::create([
            'name' => $validatedData['name'],
            'surname' => $validatedData['surname'],
            'gender' => $validatedData['gender'],
            'dob' => $dateOfBirth,
            'email' => $validatedData['email'],
            'password' => Hash::make(Str::random(12)),
            'role' => 'User',
            'company_role' => $validatedData['company_role'],
            'type' => 'Fake',
        ]);

        return redirect()->route('training-campaign.option')->with('success', 'Added successfully!');
    }

    /**
     * Delete the fake user.
     */
    public function destroyFakeUser(Request $request, $id): RedirectResponse
    {
        $userToDelete = User::findOrFail($id);
        if ($userToDelete->type === 'Fake') {
            $userToDelete->delete();
        }

        return Redirect::route('training-campaign.option')->with('success', 'Deleted successfully!');
    }
}
