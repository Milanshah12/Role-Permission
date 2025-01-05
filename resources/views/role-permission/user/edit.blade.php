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
                    <div class="alert alert-success">{{session('status')}}</div>

                    @endif
                    <div class="card-body">
                        <form action="{{ url('users/'.$user->id) }}" method="POST">
                            @csrf
                            @method('PUT') <!-- Ensures it's treated as an update request -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Name</label>
                                    <input type="text" name="name" value="{{ $user->name }}" class="form-control" placeholder="Enter name">
                                </div>
                                @error('name')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                                <div class="col-md-6 mb-3">
                                    <label>Email</label>
                                    <input type="email" name="email" readonly value="{{ $user->email }}" class="form-control" placeholder="Enter email">
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Password</label>
                                    <input type="password" name="password" class="form-control" placeholder="Enter password">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Roles</label>
                                    <select name="roles[]" class="form-control" multiple>
                                        <option value="">Select role</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role }}" {{ $user->hasRole($role) ? 'selected' : '' }}>{{ $role }}</option>
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

<div class="modal fade" id="editUser" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalToggleLabel">Edit User</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <form id="edit-form" style="border-radius: 10px padding:10px;">
                    @csrf
                    <input type="hidden" name="_method" value="PUT"> <!-- to simulate PUT request -->
                    <div class="mb-3">
                    <label style="font-weight: 500; color: #555;">Name</label>
                    <input type="text" name="name" class="form-control" placeholder="Enter name" style="border: 1px solid #202020; border-radius:5px; padding: 10px;">
                    </div>

                    <div class="mb-3">
                    <label style="font-weight: 500; color:#555">Email</label>
                    <input type="email" name="email" readonly class="form-control" placeholder="Enter email" style="border: 1px solid #202020; border-radius:5px; padding:10px;">
                    </div>

                    <div class="mb-3">
                    <label style="font-weight: 500; color:#555">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Enter password" style="border: 1px solid #202020; border-radius:5px; padding: 10px;">
                    </div>

                    <div class="mb-3">
                    <label style="font-weight: 500; color:#555">Roles</label>
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
                    @error('roles')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                    <br>
                    <button type="submit" class="btn btn-success">Update</button>
                    <button id="exportPdf1" class="btn btn-primary">Export PDF</button>

                </form>

            </div>

        </div>
        <script>
$(document).on('click', '[data-bs-target="#editUser"]', function() {
    let userId = $(this).data('user-id');

    // Fetch user details
    $.ajax({
        url: `/users/${userId}`,
        method: 'GET',
        success: function(response) {
            // Populate user fields
            $('#editUser input[name="name"]').val(response.name);
            $('#editUser input[name="email"]').val(response.email);
            $('#editUser input[name="password"]').val(''); // Clear password field
            $('#editUser select[name="roles[]"]').val(response.roles).trigger('change');
            $('#editUser').data('user-id', userId); // Store user ID in modal
        },
        error: function(xhr) {
            console.error(xhr.responseText);
            alert('Failed to load user data.');
        }
    });
});
document.getElementById('exportPdf1').addEventListener('click', function () {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            // Add user details to the PDF
            doc.text("User Information", 40, 10);
            doc.text(`Name: ${$('#editUser input[name="name"]').val()}`, 40, 20);
            doc.text(`Email: ${$('#editUser input[name="email"]').val()}`, 40, 30);
            doc.text(`Roles: ${$('#editUser select[name="roles[]"]').val().join(', ')}`, 40, 40);

            // Save the PDF
            doc.save("user_information.pdf");
        });

$(document).ready(function() {
    // Form submission handler
    $('#edit-form').on('submit', function(e) {
        e.preventDefault();

        let userId = $('#editUser').data('user-id'); // Retrieve user ID
        let formData = {
            _token: $('meta[name="csrf-token"]').attr('content'),
            name: $('#editUser input[name="name"]').val(),
            email: $('#editUser input[name="email"]').val(),
            password: $('#editUser input[name="password"]').val(),
            roles: $('#editUser select[name="roles[]"]').val()
        };

        // AJAX request to update user
        $.ajax({
            url: `/users/${userId}`,
            method: 'PUT',
            data: formData,
            success: function(response) {
                if (response.success) {
                    // Show success toast
                    $('#toast-body').text(response.message);
                    $('#liveToast').removeClass('bg-danger').addClass('bg-success');
                    let toast = new bootstrap.Toast($('#liveToast'));
                    toast.show();

                    // Reset and hide modal
                    $('#edit-form')[0].reset();
                    $('#editUser').modal('hide');
                }
            },
            error: function(xhr) {
                let errors = xhr.responseJSON.errors;
                let errorMessage = 'Please fix the following errors:<br>';

                $.each(errors, function(key, value) {
                    errorMessage += `<br>${value[0]}`;
                });

                // Show error toast
                $('#toast-body').html(errorMessage);
                $('#liveToast').removeClass('bg-success').addClass('bg-danger');
                let toast = new bootstrap.Toast($('#liveToast'));
                toast.show();
            }
        });
    });
});

        </script>
