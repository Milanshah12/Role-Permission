@extends('layouts.admin')

@section('content')
    <div class="continer mt-5">
        <div class="row">
            <div class="col-md-12">

                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif
                <div class="toast-container position-fixed top-0 end-0 p-3">
                    <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">

                        <div id="toast-body" class="toast-body mb-2 p-2 text-center" style="color:white;"></div>
                    </div>
                </div>
                <div class="card mt-3">
                    <div class="card-header">
                        <h1>Role</h1>
                        @can('Create role')
                            <button type="button" class="btn btn-primary float-end" data-coreui-toggle="modal"
                                data-coreui-target="#roleModal">
                                Add Role
                            </button>

                            {{-- <a href="{{ url('roles/create') }}" class="btn btn-primary float-end">Add Role</a> --}}
                        @endcan
                    </div>
                    <div class="cardbody">

                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Nmae</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $role)
                                    <tr>
                                        <td>{{ $role->id }}</td>
                                        <td>{{ $role->name }}</td>
                                        <td>
                                            @can('AssignPermissionToRole')
                                                <a href="{{ 'roles/' . $role->id . '/give-permission' }}"
                                                    class="btn btn-warning">Edit/Add role permission</a>
                                            @endcan

                                            @can('Edit role')
                                                <button type="button" class="btn btn-success" data-coreui-toggle="modal"
                                                    data-coreui-target="#editRoleModal" data-role-id="{{ $role->id }}">
                                                    Edit
                                                </button>
                                                {{-- <a href="{{ 'roles/' . $role->id . '/edit' }}" class="btn btn-success">Edit</a> --}}
                                            @endcan

                                            @can('Delete role')
                                                {{-- <a href="{{ 'roles/' . $role->id . '/delete' }}"
                                                    class="btn btn-danger">Delete</a> --}}
                                                <button class="btn btn-danger"
                                                    onclick="fetchData({{ $role->id }})">Delete</button>
                                            @endcan
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        function fetchData(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    console.log("Delete request initiated for ID:", id); // Debugging log
                    let xhttp = new XMLHttpRequest();
                    xhttp.open("GET", `/roles/${id}/delete`, true);
                    xhttp.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]')
                        .getAttribute('content')); // Add CSRF token
                    xhttp.onreadystatechange = function() {
                        if (this.readyState === 4) {
                            console.log("Server response:", this.responseText); // Debugging log
                            if (this.status === 200) {
                                try {
                                    const response = JSON.parse(this.responseText);
                                    $('#toast-body').text(response.message);
                                    $('#liveToast').removeClass('bg-danger').addClass('bg-success');
                                    let toast = new bootstrap.Toast($('#liveToast')[0]);
                                    toast.show();
                                } catch (error) {
                                    console.error('Error parsing response:', error);
                                }
                            } else {
                                console.error('Request failed with status:', this.status);
                                $('#toast-body').text('Failed to delete role.');
                                $('#liveToast').removeClass('bg-success').addClass('bg-danger');
                                let toast = new bootstrap.Toast($('#liveToast')[0]);
                                toast.show();
                            }
                        }
                    };
                    xhttp.send();
                }
            });
        }
    </script>


    @include('role-permission.roles.create')
    @include('role-permission.roles.edit')
@endsection
