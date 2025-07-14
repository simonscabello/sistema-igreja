<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{

    public function index(Request $request): View
    {
        $this->authorize('gerenciar_usuarios');

        $query = User::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        $users = $query->with('roles')->orderBy('name')->paginate(10);

        return view('users.index', compact('users'));
    }

    public function create(): View
    {
        $this->authorize('gerenciar_usuarios');

        $roles = Role::orderBy('name')->get();

        return view('users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request): View
    {
        $this->authorize('gerenciar_usuarios');

        // Generate temporary password based on user name
        $temporaryPassword = User::generateTemporaryPassword($request->name);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($temporaryPassword),
            'must_change_password' => true,
        ]);

        if ($request->filled('roles')) {
            $user->syncRoles($request->roles);
        }

        // Return view showing the generated password
        return view('users.created', compact('user', 'temporaryPassword'));
    }

    public function show(User $user): View
    {
        $this->authorize('gerenciar_usuarios');

        $user->load('roles');

        return view('users.show', compact('user'));
    }

    public function edit(User $user): View
    {
        $this->authorize('gerenciar_usuarios');

        $roles = Role::orderBy('name')->get();
        $user->load('roles');

        return view('users.edit', compact('user', 'roles'));
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $this->authorize('gerenciar_usuarios');

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        if ($request->filled('roles')) {
            $user->syncRoles($request->roles);
        } else {
            $user->syncRoles([]);
        }

        return redirect()->route('users.index')->with('success', 'Usuário atualizado com sucesso.');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->authorize('gerenciar_usuarios');

        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')->with('error', 'Não é possível excluir seu próprio usuário.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Usuário excluído com sucesso.');
    }
}
