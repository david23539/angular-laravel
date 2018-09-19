<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\JwtAuth;
use App\Car;
use App\User;

class CarController extends Controller
{
    public function index(){
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

    public function show($id) {
        $car = Car::find($id);
        return response()->json(array(
            'car' => $car,
            'status' => 'success'
        ),200);
    }

    public function update($id, Request $req) {
        $jwtAuth = new JwtAuth();
        $hash = $req->header('Authorization', null);
        $checkToken = $jwtAuth->checkToken($hash);
        if($checkToken) {
            //recojer parametros POST
            $json = $req->input('json',null);
            $params_array = json_decode($json, true);

            //validar Datos
            $validacionData = \Validator::make($params_array, [
                'title'=>'required|min:5',
                'description'=>'required',
                'price'=>'required',
                'status'=>'required'
            ]);
            if( $validacionData->fails()) {
                return response()->json($validacionData->errors(), 400);
            }
            //actualizar el registro

            $car = Car::where('id','=', $id) -> update($params_array);
            if( $car == 0){
                $data = array(
                    'message' => 'coche no modificado'. $car,
                    'status' => 'Error',
                    'code' => '500'
                );
            } else {
                $data = array(
                    'car' => $car,
                    'status' => 'success',
                    'code' => '200'
                );
            }

        } else {
            $data = array(
                'message'=>'login incorrecto',
                'status'=>'error',
                'code'=>400
            );
        }
        return response()->json($data, 200);

    }

    public function destroy ($id, Request $req) {
        $jwtAuth = new JwtAuth();
        $hash = $req->header('Authorization', null);
        $checkToken = $jwtAuth->checkToken($hash);
        if($checkToken) {
            $car = Car::find($id);

            if(isset($car)) {
                $registro_eliminado = $car->delete();
                if( $registro_eliminado ){
                    $data = array(
                        'Eliminado'=> $registro_eliminado,
                        'status'=>'success',
                        'code'=>200
                    );
                } else {
                    $data = array(
                        'message'=> 'Ha ocurrido un error',
                        'status'=>'error',
                        'code'=>501
                    );
                }
            } else {
                $data = array(
                    'message'=> 'Ha ocurrido un error',
                    'status'=>'error',
                    'code'=>500
                );
            }



        } else {
            $data = array(
                'message'=>'login incorrecto',
                'status'=>'error',
                'code'=>400
            );
        }
        return response()->json($data, 200);
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
