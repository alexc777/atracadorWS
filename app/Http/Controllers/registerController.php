<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class registerController extends Controller
{
    public function createUsers(Request $request)
    {
        try {
            $rules = [
                'first_name'    => 'required',
                'last_name'    => 'required',
                'id_rol'    => 'required',
                'user_action'    => 'required',
                'email'    => 'required|email',
                'password' => 'required|alphaNum|min:5'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json(['error' => true, 'message' => $validator->messages()->first(), 'code' => 400], 400);

            }

            $user = User::where('email',$request->email)->where('status',1)->first();
            if (!$user)
            {

                $user = User::create([
                    'first_name'    => $request->first_name,
                    'last_name'     => $request->last_name,
                    'email'         => $request->email,
                    'password'      => bcrypt($request['password']),
                    'id_rol'        => $request->id_rol,
                    'user_action'   => $request->user_action,
                    'status'        => 1
                ]);

                return response()->json(['error'=>false,'message'=>'Usuario Creado', 'code' =>200], 200);
            }
            else{
                return response()->json(['error'=>true,'message'=>'El correo ya ha sido registrado', 'code' =>400], 400);
            }
        } catch (\Exception $e) {
            $decision = $e->getMessage();
            return response()->json(['error' => 'true', 'code' => 400, 'message' =>$decision],400);
        }
    }

    public function editUsers(Request $request)
    {
        try {
            $user = User::where('id',$request->id_user)->first();
            if (!empty($user))
            {
                $user->fill([
                    "first_name"    =>isset($request['first_name']) ? $request['first_name'] : $user->first_name,
                    "last_name"     =>isset($request['last_name']) ? $request['last_name'] : $user->last_name,
                    "email"         =>isset($request['email']) ? $request['email'] : $user->email,
                    "password"      =>isset($request['password']) ? bcrypt($request['password']) : $user->password,
                    "id_rol"        =>isset($request['id_rol']) ? $request['id_rol'] : $user->id_rol,
                    "user_action"   =>isset($request['user_action']) ? $request['user_action'] : $user->user_action,
                ]);
                $user->save();
                return response()->json(['error'=>false,'message'=>'Usuario Actualizado', 'code' =>200], 200);
            }
            else{
                return response()->json(['error'=>true,'message'=>'Usuario no encontrado', 'code' =>400], 400);
            }
        } catch (\Exception $e) {
            $decision = $e->getMessage();
            return response()->json(['error' => 'true', 'code' => 400, 'message' =>$decision],400);
        }
    }

    public function deleteUsers(Request $request)
    {
        try {
            $user = User::where('id',$request->id_user)->first();
            if (!empty($user))
            {

                $user->fill([
                    'status'        =>0,
                    'user_action'   => $request->user_action
                ]);
                $user->save();
                return response()->json(['error'=>false,'message'=>'Usuario Eliminado', 'code' =>200], 200);

            } else{
                return response()->json(['error'=>true,'message'=>'Usuario no encontrado', 'code' =>400], 400);
            }
        } catch (\Exception $e) {
            $decision = $e->getMessage();
            return response()->json(['error' => 'true', 'code' => 400, 'message' =>$decision],400);
        }
    }

    public function getUsers(Request $request)
    {
        try {
            $user = User::where('status',1)
                ->select(
                    'id as id_user',
                    'first_name',
                    'last_name',
                    'email',
                    'id_rol'
                )
                ->where('status',1)
                ->get();
            return response()->json(['error'=>false,'data'=>$user, 'code' =>200], 200);


        } catch (\Exception $e) {
            $decision = $e->getMessage();
            return response()->json(['error' => 'true', 'code' => 400, 'message' =>$decision],400);
        }
    }
}
