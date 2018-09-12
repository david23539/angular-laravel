<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \App\User;

class UserController extends Controller
{
    public function register(Request $req){
        $json = $req->input('json', null);
        $params = json_decode($json);

        $email = (!is_null($json) && isset($params->email))?$params->email:null;
        
        $name = (!is_null($json) && isset($params->name))?$params->name:null;
        $surname = (!is_null($json) && isset($params->surname))?$params->surname:null;
        $role = (!is_null($json) && isset($params->role))?$params->role:null;
        $password = (!is_null($json) && isset($params->password))?$params->password:null;

        if(!is_null($email) && !is_null($password) && !is_null($name)){
            $user = new User();
            $user->email = $email;
            $user->name= $name;
            $user->surname= $surname;
            $user->role= $role;
            $pwd = hash('sha256', $password);
            $user->password= $pwd;

            $isset_user =User::where('email', '=', $email)->first();
            if(count($isset_user) == 0){
                $user->save();
                $data = array(
                    'status' => 'success',
                    'code'=>200,
                    'message' => 'Usuario creado'
                );
            }else{
                $data = array(
                    'status' => 'error',
                    'code'=>400,
                    'message' => 'Usuario duplicado, no puede registrase'
                );
            }

        }else{
            $data = array(
                'status' => 'error',
                'code'=>400,
                'message' => 'Usuario no creado'
            );
        }

        return response()->json($data, 200);
    }

    public function login(Request $req){
        echo 'Accion login'; die();
    }
}
