<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\JwtAuth;
use App\Car;
use App\User;

class CarController extends Controller
{
    public function index(Request $req){
        /*$jwtAuth = new JwtAuth();
        $hash = $req->header('Authorization', null);
        $checkToken = $jwtAuth->checkToken($hash);
        if($checkToken) {
            echo 'Index de Carcontroller Autenticado'; die();
        } else {
            echo 'Index de Carcontroller No Autenticado'; die();
        }*/

        $cars = Car::all()->load('user');
    	return response()->json(array(
    			'cars' => $cars,
    			'status' => 'success'
    		), 200);
        
    }

    public function store(Request $req) {
        $jwtAuth = new JwtAuth();
        $hash = $req->header('Authorization', null);
        $checkToken = $jwtAuth->checkToken($hash);
        if($checkToken) {
            $json = $req->input('json', null);
            $params = json_decode($json);
            $params_array = json_decode($json, true);
            
               //validacion
            
            $validacionData = \Validator::make($params_array, [
                'title'=>'required|min:5',
                'description'=>'required',
                'price'=>'required',
                'status'=>'required'
            ]);

            if( $validacionData->fails()) {
                return response()->json($validacionData->errors(), 400);
            }
            
            //guardar el coche
            $user = $jwtAuth->checkToken($hash, true);

            $car = new Car();
            $car->user_id = $user->sub;
            $car->title = $params->title;
            $car->description = $params->description;
            $car->status = $params->status;
            $car->price = $params->price;
            $car->save();

            $data = array(
                'car'=>$car,
                'status'=>'success',
                'code'=>200
            );

        } else {
            $data = array(
                'message'=>'login incorrecto',
                'status'=>'error',
                'code'=>400
            );
        }

        return response()->json($data, 200);
    }

}
