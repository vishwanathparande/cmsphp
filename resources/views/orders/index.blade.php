@extends('layouts.app')

@section('content')
@if ($message = Session::get('success'))
<div class="alert alert-success">
    <p>{{ $message }}</p>
</div>
@endif
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Orders') }}
                </div>

                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>Order Id</th>
                            <th>User</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price(Per unit) Rs.</th>
                            <th>Total Price</th>
                            <th>status</th>
                        </tr>
                        @if(count($orders))
                        @foreach ($orders as $category)
                        @php $orderProducts = $category->toArray(); @endphp
                        @php $rowSpan = count($orderProducts['order_product']); @endphp
                        @php $count = 0; @endphp
                        @foreach ($orderProducts['order_product'] as $orderProduct)
                        <tr>
                            @php if ($count == 0) {@endphp
                            <td rowspan="{{ $rowSpan }}">{{ $category->id }}</td>
                            <td rowspan="{{ $rowSpan }}">{{ $orderProducts['user']['name']}}</td>
                            @php $count++; @endphp
                            @php } @endphp
                            <td>{{$orderProduct['product']['title'] }}</td>
                            <td>{{$orderProduct['quantity'] }}</td>
                            <td>{{number_format($orderProduct['product']['price'],2) }}</td>
                            <td>{{number_format($orderProduct['product']['price'] * $orderProduct['quantity'],2) }}</td>
                            <td>{{$orderProduct['status'] }}</td>
                        </tr>
                        @endforeach
                        @endforeach
                        @else
                        <tr>
                            <td colspan="4" align="center">Order data not available.</td>                            
                        </tr>
                        @endif
                    </table>
                </div>
                {!! $orders->links() !!}
            </div>
        </div>
    </div>
</div>
@endsection
