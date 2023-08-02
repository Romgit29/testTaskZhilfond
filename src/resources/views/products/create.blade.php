@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center mt-3">
        <div class="col-md-8">
            <div class="card">
                <form method="POST" action="{{route('products.store')}}" style='display:flex; flex-direction:column'>
                    @csrf
                    <div style='margin:15px; margin-bottom:0;'>Название</div>
                    <input type="text" name='name' style='margin:15px; margin-bottom:0;'>
                    <div style='margin:15px; margin-bottom:0;'>Цена</div>
                    <input type="text" name='cost' style='margin:15px;'>
                    <button type='submit' style='margin:15px;' class="btn btn-primary">Добавить</button>
                </form>
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
</div>
@endsection