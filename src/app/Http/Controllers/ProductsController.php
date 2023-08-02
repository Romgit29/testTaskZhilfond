<?php

namespace App\Http\Controllers;

use App\Models\Basket;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use League\CommonMark\Extension\CommonMark\Node\Inline\Link;

class ProductsController extends Controller
{
    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cost' => 'required|integer|min:1',
            'name' => 'required|string'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors())->withInput();
        }

        $result = Product::create( $request->all() );
        internalErrorResponse($result);
        
        return back()->withSuccess('Product added successfully!');
    }

    public function addProductsBasket(Request $request)
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

    public function list()
    {
        $products = Product::latest()
        ->select('id', 'name', 'cost')
        ->paginate(10);

        return view('products.list', compact('products'));
    }
}
