<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{
    public function createMenu(Request $request)
    {
        try {
            $rules = [
                'name'    => 'required',
                'description'    => 'required',
                'price'    => 'required',
                'user_action'    => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json(['error' => true, 'message' => $validator->messages()->first(), 'code' => 400], 400);

            }

            $user = Menu::create([
                'name'    => $request->name,
                'description'     => $request->description,
                'price'         => $request->price,
                'user_action'   => $request->user_action,
                'status'        => 1
            ]);

            return response()->json(['error'=>false,'message'=>'Menu Creado', 'code' =>200], 200);

        } catch (\Exception $e) {
            $decision = $e->getMessage();
            return response()->json(['error' => 'true', 'code' => 400, 'message' =>$decision],400);
        }
    }

    public function editMenu(Request $request)
    {
        try {
            $menu = Menu::where('id',$request->id_menu)->first();
            if (!empty($menu))
            {
                $menu->fill([
                    "name"          =>isset($request['name']) ? $request['name'] : $menu->name,
                    "description"   =>isset($request['description']) ? $request['description'] : $menu->description,
                    "status"        =>isset($request['status']) ?$request['status'] : $menu->status,
                    "price"         =>isset($request['price']) ? $request['price'] : $menu->price,
                    "user_action"   =>isset($request['user_action']) ? $request['user_action'] : $menu->user_action,
                ]);
                $menu->save();
                return response()->json(['error'=>false,'message'=>'Menu Actualizado', 'code' =>200], 200);
            }
            else{
                return response()->json(['error'=>true,'message'=>'Menu no encontrado', 'code' =>400], 400);
            }
        } catch (\Exception $e) {
            $decision = $e->getMessage();
            return response()->json(['error' => 'true', 'code' => 400, 'message' =>$decision],400);
        }
    }

    public function deleteMenu(Request $request)
    {
        try {
            $menu = Menu::where('id',$request->id_menu)->first();
            if (!empty($menu))
            {
                $menu->fill([
                    "status"        => 0,
                    "user_action"   =>isset($request['user_action']) ? $request['user_action'] : $menu->user_action,
                ]);
                $menu->save();
                return response()->json(['error'=>false,'message'=>'Menu eliminado', 'code' =>200], 200);
            }
            else{
                return response()->json(['error'=>true,'message'=>'Menu no encontrado', 'code' =>400], 400);
            }
        } catch (\Exception $e) {
            $decision = $e->getMessage();
            return response()->json(['error' => 'true', 'code' => 400, 'message' =>$decision],400);
        }
    }

    public function getMenu(Request $request)
    {
        try {
            $menu = Menu::where('status','!=',0)
                ->select(
                    'id as id_menu',
                    'name',
                    'description',
                    'price',
                    'status'
                )
                ->where('status','!=',0)
                ->get();
            return response()->json(['error'=>false,'data'=>$menu, 'code' =>200], 200);

        } catch (\Exception $e) {
            $decision = $e->getMessage();
            return response()->json(['error' => 'true', 'code' => 400, 'message' =>$decision],400);
        }
    }
}
