@extends('layouts.app')

@section('content')
@if ($errors->any())
<div class="alert alert-danger" style="display: block;">
    <strong>Whoops!</strong> There were some problems with your input.
</div>
@endif
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Edit New') }}<div class="pull-right">
                        <a class="btn btn-primary" href="{{ route('products.index') }}"> Back</a>
                    </div></div>
                <div class="card-body">
                    <form method="POST" id="products" action="{{ route('products.update',$product->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Title') }}</label>
                            <div class="col-md-6">
                                <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') ? old('title') : $product->title }}" required autofocus>
                                @error('title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Category') }}</label>
                            <div class="col-md-6">
                                <select class="form-control" id="category_id" name="category_id" >
                                    <option  value="">Select Category</option>                                    
                                    @foreach ($categories as $key => $category)
                                    @if (old('category_id'))
                                    <option value="{{$key}}" <?php echo old('category_id') == $key ? 'selected="selected"' : ''; ?>>{{$category}}</option>
                                    @else
                                    <option value="{{$key}}" <?php echo $product->category_id == $key ? 'selected="selected"' : ''; ?>>{{$category}}</option>
                                    @endif
                                    @endforeach
                                </select>
                                @error('category_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Description') }}</label>
                            <div class="col-md-6">
                                <input id="description" type="text" class="form-control @error('description') is-invalid @enderror" name="description" value="{{ old('description')  ? old('description') : $product->description }}" required autofocus>
                                @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Image') }}</label>
                            <div class="col-md-6">
                                <input id="image" type="file" class="@error('image') is-invalid @enderror" name="image" value="{{ old('image') }}" autofocus>
                                <img src="{{ asset($product->image) }}" height="30px" width="30px">
                                @error('image')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Price') }}</label>
                            <div class="col-md-6">
                                <input id="price" type="text" class="form-control @error('price') is-invalid @enderror" name="price" value="{{ old('price') ? old('price') : $product->price }}" required autofocus>
                                @error('price')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="status" class="col-md-4 col-form-label text-md-right">{{ __('Status') }}</label>
                            <div class="col-md-6">
                                <select ds="status" class="form-control"  name="status" required>
                                    @if (old('status'))
                                    <option value="Active" <?php echo old('status') == 'Active' ? 'selected="selected"' : ''; ?>>Active</option>
                                    <option value="Inactive" <?php echo old('status') == 'Inactive' ? 'selected="selected"' : ''; ?>>Inactive</option>
                                    @else
                                    <option value="Active" <?php echo $product->status == 'Active' ? 'selected="selected"' : ''; ?>>Active</option>
                                    <option value="Inactive" <?php echo $product->status == 'Inactive' ? 'selected="selected"' : ''; ?>>Inactive</option>
                                    @endif
                                </select>
                                @error('status')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button id="submitbutton" type="submit" class="btn btn-primary">
                                    {{ __('Save') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $("#products").validate({
            onkeydown: false,
            onkeyup: false,
            onfocusin: false,
            onfocusout: false,
            errorElement: "div",
            rules: {
                title: "required",
                category_id: "required",
                description: "required",
                price: "floatRegex",
                status: "required",
            },
            messages: {
                title: "Please enter title",
                category_id: "Please select category",
                description: "Please enter description",
                price: "Price should be numeric only",
                status: "Please select status",
            },
            submitHandler: function (form) {
                form.submit();
                return false;
            },
            errorPlacement: function (error, element) {
                showError(error, element);
            }
        });

        $("#submitbutton").click(function () {
            $("#products").submit();
            return false;
        });

        function showError(error, element)
        {
            error.insertAfter(element);
            error.removeClass('valid-feedback').addClass('invalid-feedback');
            element.removeClass('is-valid').addClass('is-invalid');
        }
        $(document.body).on('keypress', '.form-control', function () {
            $(this).parent().find('.invalid-feedback').remove();
            $(this).removeClass('is-invalid').addClass('is-valid');
        });

        $.validator.addMethod("floatRegex", function (value, element) {
            return this.optional(element) || /^[0-9.]+$/i.test(value);
        })
    });
</script>
@endsection
