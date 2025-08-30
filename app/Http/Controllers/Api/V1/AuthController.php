<?php


namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Exception;

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

        try {
            $response=$this->auth->register($data);

            return $this->success($response, 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        try {
            $response=$this->auth->login($data);

            return $this->success($response);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    public function logout()
    {
        try {
            $this->auth->logout();
            return $this->success('Sesión cerrada');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    public function me()
    {
        try {
            $response = $this->auth->me();
            return $this->success($response);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
}
