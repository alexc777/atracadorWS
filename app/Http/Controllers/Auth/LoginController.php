<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $rules = [
            'email'    => 'required|email',
            'password' => 'required|alphaNum|min:5'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['error' => true, 'message' => $validator->messages()->first(), 'code' => 400], 400);

        }
        $user = User::where('email',$request->email)->where('status',1)->first();

        if (!empty($user))
        {

            $credentials = $request->only('email', 'password');
            if(!Auth::attempt($credentials)) {
                return response()->json(['error'=>true,'message'=>'Invalido campo de email o password', 'code' =>400], 400);
            }
            else{
                $user =Auth::user();
                $data = [
                    "first_name"    => $user->first_name,
                    "last_name"     => $user->last_name,
                    "id_user"       => $user->id,
                    "id_rol"        => $user->id_rol
                ];
                return response()->json(['error' => false, 'code' => 200, 'data'=>$data],200);
            }
        }
        else{
            return response()->json(['error'=>true,'message'=>'Usuario desactivado para mas informacion comunicate a soporte', 'code' =>400], 400);
        }
    }
}
