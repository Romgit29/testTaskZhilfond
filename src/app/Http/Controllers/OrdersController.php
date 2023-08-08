<?php

namespace App\Http\Controllers;

use App\Models\Basket;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrdersController extends Controller
{
    public function add()
    {
        $basketData = Basket::where('user_id', Auth::id())
        ->select('user_id', 'product_id', 'count')
        ->get();
        
        if( count($basketData) == 0) return back()->withErrors('Basket is empty');
        DB::beginTransaction();
        try{
            $result = Order::create([
                'user_id' => Auth::id(),
                'order_info' => json_encode($basketData),
            ]);
            if(!$result) internalErrorResponse($result, true);
    
            $result = Basket::where('user_id', Auth::id())
            ->delete();
            if(!$result) internalErrorResponse($result, true);
        } catch (\Throwable $th){
            DB::rollback();
            throw $th;
        }
        DB::commit();

        return back()->withSuccess('Order made successfully!');
    }

    public function list()
    {
        $subQueryOrdersData = Order::whereRaw('user_id='.Auth::id())
        ->select(
            DB::raw("( json_array_elements(order_info)->'product_id' #>> '{}' )::integer as product_id"),
            DB::raw("( json_array_elements(order_info)->'count' #>> '{}' )::integer as product_count"),
            'orders.id as order_id',
            'orders.created_at as date',
        )
        ->toSql();

        $subQuerySum = DB::table( DB::raw("( $subQueryOrdersData ) as orders_data") )
        ->join('products', 'products.id', 'orders_data.product_id')
        ->select('order_id', DB::raw('SUM(products.cost * orders_data.product_count) OVER (PARTITION BY order_id) as cost'))
        ->groupBy('order_id', 'cost', 'product_count')
        ->distinct('order_id')
        ->toSql();

        $ordersData = DB::table( DB::raw("( $subQueryOrdersData ) as orders_data") )
        ->join('products', 'products.id', 'orders_data.product_id')
        ->join(DB::raw("($subQuerySum) as order_sum"), function ($join) {
            $join->on('order_sum.order_id', 'orders_data.order_id');
        })
        ->select(
            'orders_data.order_id', 
            DB::raw("json_agg(json_build_object('count', orders_data.product_count, 'name', products.name)) as products"), 
            'date',
            'order_sum.cost'
            )
        ->groupBy('orders_data.order_id', 'date', 'order_sum.cost')
        ->paginate(10);
        
        foreach($ordersData as $key=>$value){
            $ordersData[$key]->date = date('Y-m-d', strtotime($value->date));
        }
        
        return view('orders.list', compact('ordersData'));
    }

    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|integer|min:1|exists:orders,id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors())->withInput();
        }

        $result = Order::where('id', $request['order_id'])
        ->delete();
        internalErrorResponse($result);

        return back();
    }
}
