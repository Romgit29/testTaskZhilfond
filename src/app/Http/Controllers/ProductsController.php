<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ProductsController extends Controller
{
    public function create()
    {
        $auth = Auth::user();
        if(!$auth->hasRole('admin')) return back();
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

    public function list()
    {
        $products = Product::latest()
        ->select('id', 'name', 'cost')
        ->paginate(10);

        return view('products.list', compact('products'));
    }
}
