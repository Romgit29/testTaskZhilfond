@extends('layouts.app')

@section('content')
<div class="container">
    @foreach ($basketElements as $key => $basketElement)
        <div class="row justify-content-center mt-3">
            <div class="col-md-8">
                <div class="card">
                    <div style='display:flex; margin:5px;'>
                        <div style='display:flex; flex-direction:inline; align-items: center; margin-left:5px;'>
                            <div>{{$basketElement->name}} - {{$basketElement->count}} шт.</div>
                        </div>
                        <div style='display:flex; padding:5px; padding-left:15px; align-items:center;'>
                            <form method="POST" action="{{route('basket.delete')}}">
                                @csrf
                                <input type="hidden" name='id' value='{{$basketElement->id}}'>
                                <button type="submit" style='margin-left:10px;' class="btn btn-danger">Удалить</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <div class="row justify-content-center mt-3">
        <div class="col-md-8">
            {{ $basketElements->links() }}
        </div>
    </div>
    <div class="row justify-content-center mt-3">
        <div class="col-md-8">
            <div class="card">
                <div style=" margin:5px;">
                    Общая стоимость: {{$totalCost}}₽ 
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center mt-3">
        <div class="col-md-8">
            <div class="card">
                <form method="POST" id='makeOrder' action="{{route('orders.add')}}">
                    @csrf
                </form>
                <button type="submit" form='makeOrder' class="btn btn-primary">Сделать заказ</button>
            </div>
        </div>
    </div>
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
</div>
@endsection