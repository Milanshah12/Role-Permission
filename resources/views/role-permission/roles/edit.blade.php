{{-- @extends('layouts.admin')

@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h1>Edit Role</h1>
                        <a href="{{ url('roles') }}" class="btn btn-primary float-end">back</a>
                    </div>
                    <div class="cardbody">
<form action="{{url('roles/'.$roles->id)}}" method="POST">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label for="">
            Role
        </label>
        <input type="text" name="name" class="form-control" value="{{$roles->name}}">

    </div>
    @error('name')
    <div class="alert alert-danger">{{ $message }}</div>
@enderror
    <div class="mb-3">
        <button type="submit" class="btn btn-primary">Update</button>
    </div>
</form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection --}}




<div class="modal fade" id="editRoleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Role</h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editroleform">
                @csrf
                <input type="hidden" name="_method" value="PUT"> <!-- Simulate PUT request -->

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label" style="font-weight: 500; color:#555">Role Name</label>
                        <input type="text" class="form-control" name="role" id="role" style="border: 1px solid #202020; border-radius:5px; padding: 10px;">
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
   $(document).on('click', '[data-coreui-target="#editRoleModal"]', function () {
    var roleId = $(this).data('role-id');
    $('#editRoleModal').data('role-id', roleId);

    // Fetch role data and populate the modal form
    $.ajax({
        url: `/roles/${roleId}`,
        method: 'GET',
        success: function (response) {
            $('#editRoleModal input[name="role"]').val(response.name);
        },
        error: function (xhr) {
            console.error(xhr.responseText);
            alert('Failed to load role data.');
        }
    });
});

$(document).ready(function () {
    $('#editroleform').on('submit', function (e) {
        e.preventDefault(); // Prevent default form submission

        var roleId = $('#editRoleModal').data('role-id');

        let formData = {
            _token: $('meta[name="csrf-token"]').attr('content'), // CSRF token
            role: $('#editRoleModal input[name="role"]').val()
        };

        // Send AJAX PUT request
        $.ajax({
            url: `/roles/${roleId}`,
            method: 'PUT',
            data: formData,
            success: function (response) {
                if (response.success) {
                    // Display success message and reset the form
                    $('#toast-body').text(response.message);
                    $('#liveToast').removeClass('bg-danger').addClass('bg-success');
                    let toast = new bootstrap.Toast($('#liveToast'));
                    toast.show();

                    $('#editroleform')[0].reset(); // Reset form
                    $('#editRoleModal').modal('hide'); // Close the modal
                }
            },
            error: function (xhr) {
                // Handle validation errors
                let errors = xhr.responseJSON.errors;
                let errorMessage = '';

                $.each(errors, function (key, value) {
                    errorMessage += `<br>${value[0]}`;
                });

                $('#toast-body').html(errorMessage);
                $('#liveToast').removeClass('bg-success').addClass('bg-danger');
                let toast = new bootstrap.Toast($('#liveToast'));
                toast.show();
            }
        });
    });
});

</script>
