<?php
// corrigir_login_controller.php
// Script para corrigir o LoginController

echo "=== Corrigindo LoginController ===\n\n";

$loginControllerPath = __DIR__ . '/app/Http/Controllers/Auth/LoginController.php';

$newContent = <<<'PHP'
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $credentials = $validator->validated();

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        throw ValidationException::withMessages([
            'email' => __('As credenciais fornecidas estão incorretas.'),
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
PHP;

// Garantir que o diretório existe
$dir = dirname($loginControllerPath);
if (!is_dir($dir)) {
    mkdir($dir, 0755, true);
    echo "✓ Diretório criado: $dir\n";
}

file_put_contents($loginControllerPath, $newContent);
chmod($loginControllerPath, 0644);

echo "✓ LoginController corrigido com sucesso!\n\n";

echo "=== CONCLUÍDO! ===\n";
echo "Teste o login agora:\n";
echo "  https://yellow-spoonbill-121332.hostingersite.com/login\n";
