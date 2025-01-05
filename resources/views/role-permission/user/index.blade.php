@extends('layouts.admin')

@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                <ul class="nav nav-tabs" id="userTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="active-users-tab" data-bs-toggle="tab" href="#active-users"
                            role="tab">Active Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="soft-deleted-users-tab" data-bs-toggle="tab" href="#soft-deleted-users"
                            role="tab">Soft Deleted Users</a>
                    </li>
                </ul>
                <div class="toast-container position-fixed top-0 end-0 p-3">
                    <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">

                        <div id="toast-body" class="toast-body mb-2 p-2 text-center" style="color:white;"></div>
                    </div>
                </div>
                <div class="tab-content mt-3">
                    <div class="tab-pane fade show active" id="active-users" role="tabpanel">
                        <div class="d-flex justify-content-evenly mb-3">
                            <h1>Active Users</h1>
                            @can('Create user')
                                <button class="btn btn-primary" data-bs-target="#AddUser" data-bs-toggle="modal">Add
                                    User</button>
                                {{-- <a href="{{ url('users/create') }}" class="btn btn-primary">Create User</a> --}}

                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                        Export
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                                        <li><button class="dropdown-item" id="exportPdf">Export PDF</button></li>
                                        <li><button class="dropdown-item" id="exportExcel">Export to Excel</button></li>
                                    </ul>
                                </div>

                            @endcan


                        </div>
                        <table id="active-users-table" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                    <div class="tab-pane fade" id="soft-deleted-users" role="tabpanel">
                        <h1>Soft Deleted Users</h1>
                        <table id="soft-deleted-users-table" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>





    @include('role-permission.user.create')
    @include('role-permission.user.edit')


    <script>
        function confirmDelete(userId, deleteUrl) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: deleteUrl,
                        method: 'DELETE',

                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'), // Include CSRF token
                        },
                        success: function(response) {
                            if (response.success) {
                                // Show success toast
                                $('#toast-body').text(response.message);
                                $('#liveToast').removeClass('bg-danger').addClass('bg-success');
                                var toast = new bootstrap.Toast($('#liveToast'));
                                toast.show();

                                // Optionally, remove the deleted user from the table or refresh the page
                                $(`#user-row-${userId}`)
                            .remove(); // Example if you have a row with user ID
                            }
                        },
                        error: function(xhr) {
                            // Handle errors
                            var errorMessage = xhr.responseJSON?.message || 'An error occurred.';
                            $('#toast-body').text(errorMessage);
                            $('#liveToast').removeClass('bg-success').addClass('bg-danger');
                            var toast = new bootstrap.Toast($('#liveToast'));
                            toast.show();
                        },
                    });
                }
            });
        }

        function confirmAccess(userId, impersonateUrl) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You will be impersonating this user!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, impersonate!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirect to the impersonate URL
                    window.location.href = impersonateUrl;
                }
            });
        }
    </script>

{{-- pdf --}}


<script>
    document.getElementById('exportExcel').addEventListener('click', function () {
        const table = document.getElementById('active-users-table');
        const rows = Array.from(table.querySelectorAll('tbody tr')).map(row => {
            const cells = row.querySelectorAll('td');
            return [
                cells[0].textContent,  // Id
                cells[1].textContent,  // Name
                cells[2].textContent,  // Email
                cells[3].textContent   // Role
            ];
        });

        // Create the worksheet from rows
        const ws = XLSX.utils.aoa_to_sheet([['Id', 'Name', 'Email', 'Role'], ...rows]);

        // Create a new workbook
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, 'User List');

        // Write the Excel file
        XLSX.writeFile(wb, 'user_list.xlsx');
    });
</script>
<script>
    document.getElementById('exportPdf').addEventListener('click', function () {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();


        doc.text("User List", 10, 10);

        const table = document.getElementById('active-users-table');
        const rows = Array.from(table.querySelectorAll('tbody tr')).map(row => {
            const cells = row.querySelectorAll('td');
            return [
                cells[0].textContent,
                cells[1].textContent,
                cells[2].textContent,
                cells[3].textContent
            ];
        });


        doc.autoTable({
            head: [['Id', 'Name', 'Email', 'Role']],
            body: rows,
            startY: 20
        });

        // Save the PDF
        doc.save("allUser.pdf");
    });
</script>



    <script>
        $(function() {

            var activeTable = $('#active-users-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('users.index') }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'role',
                        name: 'role',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
            });

            var softDeletedTable = $('#soft-deleted-users-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('users.index') }}',
                    data: {
                        type: 'soft_deleted'
                    },
                },
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'role',
                        name: 'role',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
            });


            $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
                var target = $(e.target).attr("href");
                if (target === '#soft-deleted-users') {
                    softDeletedTable.columns.adjust();
                } else if (target === '#active-users') {
                    activeTable.columns.adjust();
                }
            });
        });
    </script>
@endsection
