@php
    $layout = auth()->user() && auth()->user()->hasRole('User') ? 'layouts.app' : 'layouts.admin';
@endphp

@extends($layout)


@section('content')
@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    @if (session('message'))
                        <span class="alert alert-success">
                            {{ session('message') }}
                        </span>
                    @endif
                    <div class="card-header bg-dark text-white">
                        <h3 class="card-title">User Profile</h3>
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            <div style="width: 150px; height:150px; margin: 0 auto; border: 1px solid black; padding: 10px;">
                                <img src="{{ str_replace('localhost', '127.0.0.1:8000', $img) }}" alt="profile"
                                    style="width: 100%; height: 100%;">




                                <a href="#" class="btn" data-bs-target="#exampleModalToggle" data-bs-toggle="modal"
                                    style="background-image:url('images/camera.png'); width: 25px; height:25px; border:none; background-size:contain; background-repeat: no-repeat; cursor: pointer;"
                                    aria-label="Change Profile Picture"></a>
                            </div>

                            <div class="container mt-3">
                                <p>{{ $users->name }}</p>
                                @if ($users->profile)
                                    <p>{{ $users->profile->address }}</p>
                                @endif
                                <p>
                                    @if ($users->profile)
                                        {{ $users->profile->number }} |
                                    @endif
                                    <a href="mailto:{{ $users->email }}">{{ $users->email }}</a>
                                </p>
                                <p>Member Since: {{ $users->created_at }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary">Edit Info</a>
                    </div>
                </div>
            </div>
        </div>

        <div>

            <div class="modal fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel"
                tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalToggleLabel">Change photo</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <form action="{{ route('profile.uplode') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Upload New Photo</label>
                                    <input type="file" name="image" id="photo" class="form-control"
                                        accept="image/*" onchange="previewPhoto(event)">
                                </div>
                                <div class="mb-3" id="preview-container" style="display: none; position: relative; width: fit-content;">

                                    <img src="#" alt="Photo Preview" id="photo-preview" style="border: 1px solid #ccc; padding: 5px; margin-top: 10px; max-height: 100px; max-width: 100px;">
                                    <span id="remove-icon" style="position: absolute; top: 5px; right: 5px; cursor: pointer; background: rgba(255, 0, 0, 0.7); color: white; padding: 2px 6px; border-radius: 50%; font-size: 14px;" onclick="removePreview()">×</span>
                                </div>
                                <button type="submit" class="btn btn-primary">Upload Photo</button>
                            </form>
                        </div>


                    </div>
                </div>
            </div>
        </div>

-
        <script>
           function previewPhoto(event) {
    const fileInput = event.target;
    const previewContainer = document.getElementById('preview-container');
    const preview = document.getElementById('photo-preview');


    if (fileInput.files && fileInput.files[0]) {
        const reader = new FileReader();

        reader.onload = function (e) {
            preview.src = e.target.result;
            previewContainer.style.display = 'block';

        };

        reader.readAsDataURL(fileInput.files[0]);
    } else {
        previewContainer.style.display = 'none';

    }
}

function removePreview() {
    const previewContainer = document.getElementById('preview-container');
    const preview = document.getElementById('photo-preview');
    const fileInput = document.getElementById('photo');


    // Reset the file input and hide preview
    fileInput.value = '';
    preview.src = '#';
    previewContainer.style.display = 'none';

}

        </script>
    @endsection
