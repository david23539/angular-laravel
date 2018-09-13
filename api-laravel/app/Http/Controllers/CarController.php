<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\JwtAuth;

class CarController extends Controller
{
    public function index(Request $req){
        $jwtAuth = new JwtAuth();
        $hash = $req->header('Authorization', null);
        $checkToken = $jwtAuth->checkToken($hash);
        if($checkToken) {
            echo 'Index de Carcontroller Autenticado'; die();
        } else {
            echo 'Index de Carcontroller No Autenticado'; die();
        }
        
    }
}
