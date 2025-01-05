{{-- @extends('layouts.admin')

@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h1>Permission create</h1>
                        <a href="{{ url('permission') }}" class="btn btn-primary float-end">back</a>
                    </div>
                    <div class="cardbody">
                        <form action="{{ url('permission/' . $permission->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="">
                                    Permission
                                </label>
                                <input type="text" name="name" class="form-control"
                                    value="{{ $permission->name }}">

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




<div class="modal fade" id="editPermission" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Permission</h5>
                <button type="button" class="btn-close" data-coreui-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="edit-form">
                @csrf
                <input type="hidden" name="_method" value="PUT"> <!-- Simulate PUT request -->
                <input type="hidden" name="id" id="permission-id"> <!-- To store the permission ID -->
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="permission" class="form-label" style="font-weight: 500; color:#555">Permission
                            Name</label>
                        <input type="text" class="form-control" name="permission" id="permission"
                            style="border: 1px solid #202020; border-radius:5px; padding: 10px;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    $(document).on('click', '[data-coreui-target="#editPermission"]', function() {
        let permissionId = $(this).data('permission-id'); // Get permission ID
        $('#editPermission').data('permission-id', permissionId); // Store it in the modal for later use

        // Fetch the current permission data
        $.ajax({
            url: `/permission/${permissionId}`,
            method: 'GET',
            success: function(response) {
                $('#editPermission input[name="permission"]').val(response
                    .name); // Populate the input field
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                alert('Failed to load permission data.');
            }
        });
    });



    $(document).ready(function() {
        $('#edit-form').on('submit', function(e) {
            e.preventDefault();



            let permissionId = $('#editPermission').data(
            'permission-id'); // Retrieve stored permission ID
            if (!permissionId) {
                alert('Permission ID is missing.');
                return;
            }

            // Prepare the form data
            let formData = {
                _token: $('meta[name="csrf-token"]').attr('content'),

                permission: $('#editPermission input[name="permission"]').val()
            };

            // Send the PUT request
            $.ajax({
                url: `/permission/${permissionId}`, // Correct endpoint
                method: 'PUT',
                data: formData, // Pass form data
                success: function(response) {
                    if (response.success) {
                        // Display success message and reset form
                        $('#toast-body').text(response.message);
                        $('#liveToast').removeClass('bg-danger').addClass('bg-success');
                        let toast = new bootstrap.Toast($('#liveToast'));
                        toast.show();

                        $('#permission-form')[0].reset();
                        $('#editPermission').modal('hide'); // Close the modal
                    }
                },
                error: function(xhr) {
                    // Handle validation errors
                    let errors = xhr.responseJSON.errors;
                    let errorMessage = '';

                    $.each(errors, function(key, value) {
                        errorMessage += `<br>${value[0]}`;
                    });

                    $('#toast-body').html(errorMessage);
                    $('#liveToast').removeClass('bg-success').addClass('bg-danger');
                    let toast = new bootstrap.Toast($('#liveToast'));
                    toast.show();
                }
            });
        });
    })
</script>
