@extends('layouts.admin')

@section('content')
    <div class="continer mt-5">
        <div class="row">

            <div class="col-md-12">
                @if (session('status'))
                <div class="alert alert-success">{{session('status')}}</div>

                @endif
                <div class="card">
                    <div class="card-header">
                        <h1>Role:{{$role->name}}</h1>
                        <a href="{{ url('roles') }}" class="btn btn-primary float-end">back</a>
                    </div>
                    <div class="cardbody">
<form action="{{url('roles/'.$role->id.'/give-permission')}}" method="POST">
    @csrf
    @method('PUT')
    <div class="mb-3">
        @error('permission')
            <span class="text-danger">
                {{$message}}
            </span>
        @enderror
        <label for="">
            Permission
        </label>
<div class="row">
    @foreach ($permission as $per)

    <div class="col-md-2">
        <label for="">
        <input type="checkbox" name="permission[]" value="{{$per->name}}"
        {{in_array($per->id,$rolepermission) ? 'checked' : ''}}
        class="control-form">
        {{$per->name}}
    </label>
    </div>
    @endforeach
</div>
    </div>
    <div class="mb-3">
        <button type="submit" class="btn btn-primary">Update</button>
    </div>
</form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
