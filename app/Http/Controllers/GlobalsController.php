<?php

namespace App\Http\Controllers;

use App\Models\Roles;
use Illuminate\Http\Request;

class GlobalsController extends Controller
{
    public function getRoles(Request $request)
    {
        try {
            $roles = Roles::where('status',1)
                ->select(
                    'name',
                    'id_rol'
                )
                ->get();
            if (!empty($roles))
            {
                return response()->json(['error' => false, 'code' => 200, 'data'=>$roles],200);
            }
            else{

                return response()->json(['error'=>true,'message'=>'No existen datos', 'code' =>400], 400);
            }
        } catch (\Exception $e) {
            $decision = $e->getMessage();
            return response()->json(['error' => 'true', 'code' => 400, 'message' =>$decision],400);
        }
    }
}
