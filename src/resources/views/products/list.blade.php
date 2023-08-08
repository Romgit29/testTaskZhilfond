@extends('layouts.app')

@section('content')
<div class="container">
    @foreach ($products as $key => $product)
        <div class="row justify-content-center mt-3">
            <div class="col-md-8">
                <div class="card">
                    <div style='display:flex; margin:5px;'>
                        <div style='display:flex; flex-direction:column'>
                            <div>{{$product->name}}</div>
                            <div>{{$product->cost}}₽</div>
                        </div>
                        <div style='display:flex; padding:5px; padding-left:15px; align-items:center;'>
                            <form method="POST" action="{{route('basket.add')}}">
                                @csrf
                                <input type="number" name='count' value="1">
                                <input type="hidden" name='product_id' value='{{$product->id}}'>
                                <button type="submit" style='margin-left:10px;' class="btn btn-primary">В корзину</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <div class="row justify-content-center mt-3">
        <div class="col-md-8">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif
        </div>
    </div>
    <div class="row justify-content-center mt-3">
        <div class="col-md-8">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection