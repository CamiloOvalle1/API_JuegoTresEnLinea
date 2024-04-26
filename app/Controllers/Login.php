<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;
use \Firebase\JWT\JWT;


class Login extends BaseController
{
    use ResponseTrait;

    public function index()
    {
        $userModel = new UserModel();
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');
        $user = $userModel->where('email', $email)->first();
        if(is_null($user)) {
            return $this->respond(['error'=> 'Invalid username or password.'], 401);
        }
        $pwd_verify = password_verify($password, $user['password']);
        if(!$pwd_verify) {
            return $this->respond(['error'=> 'Invalid username or password.'], 401);
        }
        $key = getenv('1234');
        $iat = time();
        $exp = $iat + 3600;
        $payload =array(
            "iss" => "issuer of the JWT",
            "aud" => "Audience that the JWT",
            "sub" => "Subject of the JWT",
            "iat" => $iat, 
            "exp" => $exp,
            "email" => $user['email'],
        );
        $token = JWT::encode($payload,$key, 'HS256');
        $response = [
            'message' => 'Login Succesful',     "Succesful": Unknown word.
            'token' => $token
        ];
        return $this->respond($response, 200);
    }
}