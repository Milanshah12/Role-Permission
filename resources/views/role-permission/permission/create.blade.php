{{-- @extends('layouts.admin')

@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h1>Create Permission</h1>
                        <a href="{{ url('permission') }}" class="btn btn-primary float-end">Back</a>
                    </div>
                    <div class="card-body">
                        <form action="{{ url('permission') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Permission</label>
                                <input type="text" name="name" class="form-control" placeholder="Enter permission name" required>
                            </div>
                            @error('name')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <div class="mb-3">
                                <button type="submit" class="btn btn-success">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection --}}

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Permission</h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="permission-form">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label"
                            style="font-weight: 500; color:#555">Permission Name</label>
                        <input type="text" class="form-control" name="permission" id="permission"
                            style="border: 1px solid #202020; border-radius:5px; padding: 10px;">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-coreui-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success" data-coreui-timeout="2000"
                        data-coreui-toggle="loading-button">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#permission-form').on('submit', function(e) {
            e.preventDefault();

            var formdata = {
                _token: $('input[name="_token"]').val(),
                permission: $('#permission').val(),
            };

            $.ajax({
                url: 'permission',
                method: 'POST',
                data: formdata,
                success: function(response) {
                    if (response.success) {
                        $('#toast-body').text(response.message);
                        $('#liveToast').removeClass('bg-danger').addClass('bg-success');
                        var toast = new bootstrap.Toast($('#liveToast'));
                        toast.show();
                        $('#permission-form')[0].reset();
                        $('#exampleModal').modal('hide');
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
