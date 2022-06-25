<?php

namespace App\Http\Controllers;

use App\Models\Table;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TableController extends Controller
{
    public function createTable(Request $request)
    {
        try {

            $rules = [
                'name'          => 'required',
                'number_table'  => 'required',
                'capacity'      => 'required',
                'user_action'   => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json(['error' => true, 'message' => $validator->messages()->first(), 'code' => 400], 400);

            }

            $table = Table::create([
                'name'          => $request->name,
                'number_table'  => $request->number_table,
                'capacity'      => $request->capacity,
                'user_action'   => $request->user_action,
                'status'        => 1
            ]);

            return response()->json(['error'=>false,'message'=>'Mesa Creada', 'code' =>200], 200);
        } catch (\Exception $e) {
            $decision = $e->getMessage();
            return response()->json(['error' => 'true', 'code' => 400, 'message' =>$decision],400);
        }
    }

    public function editTable(Request $request)
    {
        try {

            $table = Table::where('id',$request->id_table)->first();
            if (!empty($table))
            {
                $table->fill([
                    "name"    =>isset($request['name']) ? $request['name'] : $table->name,
                    "number_table"    =>isset($request['number_table']) ? $request['number_table'] : $table->number_table,
                    "capacity"    =>isset($request['capacity']) ? $request['capacity'] : $table->capacity,
                    "user_action"    =>isset($request['user_action']) ? $request['user_action'] : $table->user_action,
                ]);
                $table->save();
                return response()->json(['error'=>false,'message'=>'Mesa actualizada', 'code' =>200], 200);
            }else{
                return response()->json(['error'=>true,'message'=>'Mesa no encontrado', 'code' =>400], 400);
            }
        } catch (\Exception $e) {
            $decision = $e->getMessage();
            return response()->json(['error' => 'true', 'code' => 400, 'message' =>$decision],400);
        }
    }

    public function deleteTable(Request $request)
    {
        try {

            $table = Table::where('id',$request->id_table)->first();
            if (!empty($table))
            {
                $table->fill([
                    "status"        =>0,
                    "user_action"    =>$request['user_action'],
                ]);
                $table->save();
                return response()->json(['error'=>false,'message'=>'Mesa eliminada', 'code' =>200], 200);

            }else{
                return response()->json(['error'=>true,'message'=>'Mesa no encontrado', 'code' =>400], 400);
            }

        } catch (\Exception $e) {
            $decision = $e->getMessage();
            return response()->json(['error' => 'true', 'code' => 400, 'message' =>$decision],400);
        }
    }

    public function getTable(Request $request)
    {
        try {
            $table = Table::where('status','!=',0)
                ->select(
                    'id as id_table',
                    'name',
                    'number_table',
                    'capacity',
                    'status',
                )
                ->where('status','!=',0)
                ->get();
            return response()->json(['error'=>false,'data'=>$table, 'code' =>200], 200);

        } catch (\Exception $e) {
            $decision = $e->getMessage();
            return response()->json(['error' => 'true', 'code' => 400, 'message' =>$decision],400);
        }
    }
}
