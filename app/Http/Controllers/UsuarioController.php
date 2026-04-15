<?php

namespace App\Http\Controllers;

use App\Models\Colaborador;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UsuarioController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (! $request->user() || $request->user()->isTecnico()) {
                abort(403, 'Acesso restrito.');
            }

            return $next($request);
        });
    }

    public function index(Request $request)
    {
        $q = User::query()->with('colaboradorConta')->orderBy('name');

        if ($request->filled('search')) {
            $s = '%' . $request->search . '%';
            $q->where(function ($qq) use ($s) {
                $qq->where('name', 'like', $s)->orWhere('email', 'like', $s);
            });
        }

        $usuarios = $q->paginate(15)->withQueryString();

        return view('crm.usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        $colaboradores = $this->colaboradoresParaSelect();

        return view('crm.usuarios.form', [
            'usuario' => new User(),
            'colaboradores' => $colaboradores,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(8)],
            'role' => 'required|in:admin,tecnico,comercial',
            'colaborador_id' => 'nullable|exists:colaboradores,id',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
        ]);

        $this->sincronizarColaborador($user, $data['colaborador_id'] ?? null);

        return redirect()->route('crm.usuarios.index')->with('success', 'Usuário criado e vínculo atualizado.');
    }

    public function edit(User $user)
    {
        $colaboradores = $this->colaboradoresParaSelect($user);
        $user->load('colaboradorConta');

        return view('crm.usuarios.form', [
            'usuario' => $user,
            'colaboradores' => $colaboradores,
        ]);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'role' => 'required|in:admin,tecnico,comercial',
            'colaborador_id' => 'nullable|exists:colaboradores,id',
        ]);

        if ($user->isAdmin() && $data['role'] !== 'admin') {
            $outrosAdmins = User::where('role', 'admin')->where('id', '!=', $user->id)->exists();
            if (! $outrosAdmins) {
                return back()->withInput()->withErrors(['role' => 'Deve existir pelo menos um administrador.']);
            }
        }

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->role = $data['role'];
        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        $user->save();

        $this->sincronizarColaborador($user, $data['colaborador_id'] ?? null);

        return redirect()->route('crm.usuarios.index')->with('success', 'Usuário atualizado.');
    }

    public function destroy(Request $request, User $user)
    {
        if ($request->user()->id === $user->id) {
            return redirect()->route('crm.usuarios.index')->withErrors(['delete' => 'Você não pode excluir o próprio usuário.']);
        }

        if ($user->isAdmin()) {
            $outros = User::where('role', 'admin')->where('id', '!=', $user->id)->count();
            if ($outros < 1) {
                return redirect()->route('crm.usuarios.index')->withErrors(['delete' => 'Não é possível remover o único administrador.']);
            }
        }

        DB::transaction(function () use ($user) {
            Colaborador::where('user_id', $user->id)->update(['user_id' => null]);
            $user->delete();
        });

        return redirect()->route('crm.usuarios.index')->with('success', 'Usuário removido.');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, Colaborador>
     */
    private function colaboradoresParaSelect(?User $editando = null)
    {
        return Colaborador::query()
            ->where('ativo', true)
            ->orderBy('nome_profissional')
            ->get(['id', 'nome_profissional', 'user_id', 'email']);
    }

    private function sincronizarColaborador(User $user, ?int $colaboradorId): void
    {
        DB::transaction(function () use ($user, $colaboradorId) {
            Colaborador::where('user_id', $user->id)->update(['user_id' => null]);
            if ($colaboradorId) {
                Colaborador::where('id', $colaboradorId)->update(['user_id' => $user->id]);
            }
        });
    }
}
