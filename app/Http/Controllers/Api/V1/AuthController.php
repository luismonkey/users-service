<?php


namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(private AuthService $auth) {}

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        $response=$this->auth->register($data);

        return $this->success($response, 201);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $response=$this->auth->login($data);

        return $this->success($response);
    }

    public function logout()
    {
        $this->auth->logout();
        return $this->success('Sesión cerrada');
    }

    public function me()
    {
        $response = $this->auth->me();
        return $this->success($response);
    }
}
