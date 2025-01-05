@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                <div class="card mt-3">
                    <div class="card-header">
                        <h1>Employees</h1>
                    </div>
                    <div class="card-body">
                        <!-- Table to List All Users -->
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>

                                            <a href="#" class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#userProfileModal-{{ $user->id }}">View Profile</a>
                                        </td>
                                    </tr>


                                    <div class="modal fade" id="userProfileModal-{{ $user->id }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">User Profile</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-center">

                                                    <div
                                                        style="width: 150px; height:150px; margin: 0 auto; border: 1px solid black; padding: 10px;">
                                                        <img src="{{ $user->profile ? asset('uploads/' . $user->profile->image) : asset('images/pro.webp') }}"
                                                            alt="profile" style="width: 100%; height:100%;"
                                                            class="rounded-circle">
                                                    </div>

                                                    <div class="mt-3">
                                                        <p><strong>Name:</strong> {{ $user->name }}</p>
                                                        @if ($user->profile)
                                                            <p><strong>Address:</strong> {{ $user->profile->address }}
                                                            </p>
                                                            <p><strong>Number:</strong> {{ $user->profile->number }}</p>
                                                        @endif
                                                        <p><strong>Email:</strong>
                                                            <a
                                                                href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                                                        </p>
                                                        <p><strong>Member Since:</strong>
                                                            {{ $user->created_at->format('M d, Y') }}</p>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">

                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
