{{-- @extends('layouts.admin')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3>Add User</h3>
                        <a href="{{ url('users') }}" class="btn btn-primary">Back</a>
                    </div>
                    @if (session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif
                    <div class="card-body">
                        <form action="{{ url('users') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Name</label>
                                    <input type="text" name="name" class="form-control" placeholder="Enter name" value="{{ old('name') }}">
                                    @error('name')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" placeholder="Enter email" value="{{ old('email') }}">
                                    @error('email')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Password</label>
                                    <input type="password" name="password" class="form-control" placeholder="Enter password">
                                    @error('password')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Roles</label>
                                    <select name="roles[]" class="form-control" multiple>
                                        <option value="">Select role</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role }}" {{ in_array($role, old('roles', [])) ? 'selected' : '' }}>
                                                {{ $role }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('roles')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-success">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection --}}












<div class="modal fade" id="AddUser" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalToggleLabel">Add User</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="user-form" style="border-radius: 10px padding:10px;">
                    @csrf
                    <div class="row">
                        <div class="mb-3">
                        <label class="form-label" style="font-weight: 500; color: #555;">Name</label>
                        <input type="text" name="name" id="name" class="form-control"
                            placeholder="Enter name" value="{{ old('name') }}" style="border: 1px solid #202020; border-radius:5px; padding: 10px;">
                        </div>

                        <div class="mb-3">
                        <label class="form-label" style="font-weight: 500; color: #555;">Email</label>
                        <input type="email" name="email" id="email" class="form-control"
                            placeholder="Enter email" value="{{ old('email') }}" style="border: 1px solid #202020; border-radius:5px; padding: 10px;">

                        </div>

                        <div class="mb-3">
                            <label class="form-label" style="font-weight: 500; color: #555;">Password</label>
                            <input type="password" name="password" id="password" class="form-control"
                                placeholder="Enter password" style="border: 1px solid #202020; border-radius:5px; padding: 10px;">

                        </div>


                        <div class="mb-3">
                            <label class="form-label" style="font-weight: 500; color: #555;">Roles</label>
                            <select name="roles[]" class="form-control" multiple style="border: 1px solid #202020; border-radius:5px; padding: 10px;">
                                <option value="">Select role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role }}"
                                        {{ in_array($role, old('roles', [])) ? 'selected' : '' }}>
                                        {{ $role }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

<br>


                        <div class="text-end">
                            <button type="submit" class="btn btn-success" data-coreui-timeout="2000" data-coreui-toggle="loading-button">Add</button>

                        </div>
                </form>
            </div>


        </div>
    </div>
</div>
</div>


<script>
    $(document).ready(function() {
        $('#user-form').on('submit', function(e) {
            e.preventDefault();

            var formData = {
                _token: $('input[name="_token"]').val(),
                name: $('#name').val(),
                email: $("#email").val(),
                password: $('input[name="password"]').val(),
                roles: $('select[name="roles[]"]').val()
            };

            $.ajax({
                url: '/users',
                method: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        $('#toast-body').text(response.message);
                        $('#liveToast').removeClass('bg-danger').addClass('bg-success');
                        var toast = new bootstrap.Toast($('#liveToast'));
                    toast.show();
                    $('#user-form')[0].reset();
                    }
                },
                error: function(xhr) {
                    var errors = xhr.responseJSON.errors;
                    var errorMessage = 'please fix the error:<br>';

                    $.each(errors, function(key, value) {
                        errorMessage += `<br>${value[0]}`;
                    });
                    $('#toast-body').html(errorMessage);
                $('#liveToast').removeClass('bg-success').addClass('bg-danger');
                var toast = new bootstrap.Toast($('#liveToast'));
                toast.show();

                }

            });
        });
    });
</script>
