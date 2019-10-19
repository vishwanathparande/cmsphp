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
                <div class="card-header">{{ __('Category') }}
                    <div class="pull-right">
                        <a class="btn btn-success" href="{{ route('categories.create') }}"> Create New</a>
                    </div>
                </div>

                <div class="card-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th width="280px">Action</th>
                        </tr>
                        @if(count($categories))
                        @foreach ($categories as $category)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->status }}</td>
                            <td>
                                <form action="{{ route('categories.destroy',$category->id) }}" method="POST">
                                    <a class="btn btn-primary" href="{{ route('categories.edit',$category->id) }}">Edit</a>
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="4" align="center">Category data not available.</td>                            
                        </tr>
                        @endif
                    </table>
                </div>
                {!! $categories->links() !!}
            </div>
        </div>
    </div>
</div>
@endsection
