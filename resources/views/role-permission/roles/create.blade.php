{{-- @extends('layouts.admin')

@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h1>Role create</h1>
                        <a href="{{ url('roles') }}" class="btn btn-primary float-end">back</a>
                    </div>
                    <div class="cardbody">
                        <form action="{{ url('roles') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="">
                                    Role
                                </label>
                                <input type="text" name="name" class="form-control">

                            </div>
                            @error('name')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection --}}

<div class="modal fade" id="roleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Role</h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
           <form id="role-form">
            @csrf
            <div class="modal-body">
                <div class="mb-3">
                    <label for="exampleFormControlInput1" class="form-label" style="font-weight: 500; color:#555">Roles Name</label>
                    <input type="text" class="form-control" name="role" id="role" style="border: 1px solid #202020; border-radius:5px; padding: 10px;">
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success" data-coreui-timeout="2000" data-coreui-toggle="loading-button">Save changes</button>
            </div>
           </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('#role-form').on('submit',function(e){
            e.preventDefault();

            var formData={
                _token: $('input[name="_token"]').val(),
                role: $('#role').val(),
            };

            $.ajax({
                url:'roles',
                method:"POST",
                data:formData,
                success:function(response){
                    if (response.success) {
                        $('#toast-body').text(response.message);
                        $('#liveToast').removeClass('bg-danger').addClass('bg-success');
                        var toast = new bootstrap.Toast($('#liveToast'));
                    toast.show();
                    $('#role-form')[0].reset();

                    $('#roleModal').modal('hide');
                    }
                },
                error: function(xhr) {
                    var errors = xhr.responseJSON.errors;
                    var errorMessage = '';

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
