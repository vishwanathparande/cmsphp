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
                <div class="card-header">{{ __('Products') }}
                    <div class="pull-right">
                        <a class="btn btn-success" href="{{ route('products.create') }}"> Create New</a>
                    </div>
                </div>

                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>No</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Image</th>
                            <th>Price</th>
                            <th>status</th>
                            <th width="280px">Action</th>
                        </tr>
                        @if(count($products))
                        @foreach ($products as $product)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>{{ $product->title }}</td>
                            <td>{{ $product->category->name }}</td>
                            <td>{{ $product->description }}</td>
                            <td><img src="{{ asset($product->image) }}" height="30px" width="30px"></td>
                            <td>{{ number_format($product->price,2) }}</td>
                            <td>{{ $product->status }}</td>
                            <td>
                                <form action="{{ route('products.destroy',$product->id) }}" method="POST">
                                    <a class="btn btn-primary" href="{{ route('products.edit',$product->id) }}">Edit</a>
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="4" align="center">Product data not available.</td>                            
                        </tr>
                        @endif
                    </table>
                </div>
                {!! $products->links() !!}
            </div>
        </div>
    </div>
</div>
@endsection
