<?php

namespace App\Http\Controllers;

use App\Models\DetailOrdenes;
use App\Models\Ordenes;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ordenesController extends Controller
{
    public function getOrdenes(Request $request)
    {
        try {
            $orders = Ordenes::with('detail')
                ->select(
                    'id as id_order',
                    'comments',
                    'total',
                    'status',
                    'id_table'
                )
                ->orderBy('id_order', 'DESC')
                ->get();
            return response()->json(['error'=>false,'data'=>$orders, 'code' =>200], 200);

        } catch (\Exception $e) {
            $decision = $e->getMessage();
            return response()->json(['error' => 'true', 'code' => 400, 'message' =>$decision],400);
        }
    }

    public function createOrdenes(Request $request)
    {
        $rules = [
            'comments'      => 'required',
            'total'         => 'required',
            'id_table'      => 'required',
            'id_user'       => 'required',
            'user_action'   => 'required',
            'menus'         => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['error' => true, 'message' => $validator->messages()->first(), 'code' => 400], 400);

        }

        try {
            $orders = Ordenes::create([
                'comments'      => $request->comments,
                'total'         => $request->total,
                'status'        => 1,
                'id_table'      => $request->id_table,
                'id_user'       => $request->id_user,
                'user_action'   => $request->user_action

            ]);
            $table = Table::where('id',$request->id_table)->first();
            $table->fill([
                'status'    =>2
            ]);
            $table->save();
            if (!empty($request->menus))
            {
                foreach ($request->menus as $value)
                {
                    $detail = DetailOrdenes::create([
                        'price'         => $value['price'],
                        'quantity'      => $value['quantity'],
                        'sub_total'     => $value['sub_total'],
                        'status'        => 1,
                        'id_order'      => $orders->id,
                        'id_menu'       => $value['id_menu'],
                        'user_action'   => $request->user_action
                    ]);
                }
            }
            return response()->json(['error'=>false,"mensaje"=> "Orden creada", 'code' =>200], 200);

        } catch (\Exception $e) {
            $decision = $e->getMessage();
            return response()->json(['error' => 'true', 'code' => 400, 'message' =>$decision],400);
        }
    }

    public function updateStatusOrder(Request $request)
    {
        $rules = [
            'id_order'      => 'required',
            'status'         => 'required',
            'user_action'      => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['error' => true, 'message' => $validator->messages()->first(), 'code' => 400], 400);

        }

        try {
            $order =Ordenes::where('id',$request->id_order)->first();
            if (!empty($order))
            {
                $order->fill([
                    'status'    => $request->status,
                    'user_action'   => $request->user_action
                ]);
                $order->save();

                if ($request->status != 2)
                {
                    $table = Table::where('id',$order->id_table)->first();
                    $table->fill([
                        'status' =>1
                    ]);
                    $table->save();
                }
                return response()->json(['error'=>false,"mensaje"=> "Orden actualizada", 'code' =>200], 200);
            }
            else{
                return response()->json(['error' => 'true', 'code' => 400, 'message' =>"no se encuentra la orden"],400);
            }

        } catch (\Exception $e) {
            $decision = $e->getMessage();
            return response()->json(['error' => 'true', 'code' => 400, 'message' =>$decision],400);
        }
    }
}
