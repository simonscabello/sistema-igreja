<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request): Response
    {
        $this->authorize('gerenciar_usuarios');

        $query = User::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%')
                ->orWhere('email', 'like', '%'.$request->search.'%');
        }

        $users = $query->with('roles')->orderBy('name')->paginate(10);

        return Inertia::render('Users/Index', compact('users'));
    }

    public function create(): Response
    {
        $this->authorize('gerenciar_usuarios');

        $roles = Role::orderBy('name')->get();

        return Inertia::render('Users/Create', compact('roles'));
    }

    public function store(StoreUserRequest $request): Response
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
        return Inertia::render('Users/Created', compact('user', 'temporaryPassword'));
    }

    public function show(User $user): Response
    {
        $this->authorize('gerenciar_usuarios');

        $user->load('roles');

        return Inertia::render('Users/Show', compact('user'));
    }

    public function edit(User $user): Response
    {
        $this->authorize('gerenciar_usuarios');

        $roles = Role::orderBy('name')->get();
        $user->load('roles');

        return Inertia::render('Users/Edit', compact('user', 'roles'));
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
