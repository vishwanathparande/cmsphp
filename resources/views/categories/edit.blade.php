@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Edit Category') }}<div class="pull-right">
                        <a class="btn btn-primary" href="{{ route('categories.index') }}"> Back</a>
                    </div></div>
                <div class="card-body">
                    <form method="POST" id="categories" action="{{ route('categories.update',$category->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') ? old('name') : $category->name }}" required autocomplete="name" autofocus>
                                @error('name')
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
                                    <?php if (old('status')) { ?>
                                        <option value="Active" <?php echo old('status') == 'Active' ? 'selected="selected"' : ''; ?>>Active</option>
                                        <option value="Inactive" <?php echo old('status') == 'Inactive' ? 'selected="selected"' : ''; ?>>Inactive</option>
                                    <?php } else { ?>
                                        <option value="Active" <?php echo $category->status == 'Active' ? 'selected="selected"' : ''; ?>>Active</option>
                                        <option value="Inactive" <?php echo $category->status == 'Inactive' ? 'selected="selected"' : ''; ?>>Inactive</option>
                                    <?php } ?>
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
        $("#categories").validate({
            onkeydown: false,
            onkeyup: false,
            onfocusin: false,
            onfocusout: false,
            errorElement: "div",
            rules: {
                name: "required",
                status: "required",
            },
            messages: {
                name: "Please enter name",
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
            $("#categories").submit();
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
    });
</script>
@endsection
