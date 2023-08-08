@extends('layouts.app')

@section('content')
<div class="container">
    @foreach ($ordersData as $key => $order)
        <div class="row justify-content-center mt-3">
            <div class="col-md-8">
                <div class="card">
                    <div style='display:flex; margin:5px; margin-left:10px;'>
                        <div style='display:flex; flex-direction:column'>
                            <div>Номер: {{$order->order_id}}</div>
                            <div>Дата: {{$order->date}}</div>
                            <div>Итоговая стоимость: {{$order->cost}}₽</div>
                        </div>
                        <div style='display:flex; padding:5px; padding-left:15px; align-items:center; width:500px;'>
                            <div>
                                Содержание:
                                <?php $products = count( json_decode($order->products) ) ?>
                                @foreach (json_decode($order->products) as $key => $value)
                                    {{$value->name}}: {{$value->count}} шт.@if ($key+1 !== $products),@endif
                                @endforeach
                            </div>
                            <form method="POST" style='margin-left: 10px;' action="{{route('orders.delete')}}">
                                @csrf
                                <input type="hidden" name='order_id' value="{{$order->order_id}}">
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
            {{ $ordersData->links() }}
        </div>
    </div>
</div>
@endsection