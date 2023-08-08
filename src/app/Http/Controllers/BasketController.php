<?php

namespace App\Http\Controllers;

use App\Models\Basket;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BasketController extends Controller
{
    public function show()
    {
        $basketElements = Basket::where('basket.user_id', Auth::id())
        ->join('products', 'products.id', 'basket.product_id')
        ->select('basket.id', 'basket.user_id', 'basket.count', 'products.name')
        ->paginate(10);

        $totalCost = Basket::where('basket.user_id', Auth::id())
        ->join('products', 'products.id', 'basket.product_id')
        ->select(DB::raw('SUM(basket.count * products.cost)'))
        ->limit(1)
        ->get();

        $totalCost[0]->sum !== null ? $totalCost = $totalCost[0]->sum : $totalCost = 0;
        
        return view('basket.show', compact('basketElements', 'totalCost'));
    }

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'count' => 'required|integer|min:1',
            'product_id' => 'required|integer|min:1|exists:products,id'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors())->withInput();
        }

        $productsInBasket = Basket::where('user_id', Auth::id())
        ->where('product_id', $request['product_id'])
        ->select('count')
        ->first();
        
        if($productsInBasket !== null) {
            $count = $productsInBasket->count + $request['count'];
            $result = Basket::where('user_id', Auth::id())
            ->where('product_id', $request['product_id'])
            ->update(['count' => $count]);
        } else {
            $result = Basket::create( [
                'user_id' => Auth::id(),
                'product_id' => $request['product_id'],
                'count' => $request['count']
            ]);
        }

        internalErrorResponse($result);
        
        return back()->withSuccess('Products added successfully!');
    }

    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|min:1|exists:basket',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors())->withInput();
        }

        $basketElementUser = Basket::where('id',$request['id'])
        ->select('user_id')
        ->first();

        if($basketElementUser->user_id !== Auth::id()) return new Response('Access error', 403);

        $result = Basket::where('id',$request['id'])
        ->delete();
        internalErrorResponse($result);
        
        return back()->withSuccess('Success!');
    }
}
