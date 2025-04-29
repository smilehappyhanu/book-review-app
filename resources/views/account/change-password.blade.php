@extends('layouts.app')
@section('content')
<div class="container">
    @include('layouts.message')
    <div class="row my-5">
        <div class="col-md-3">
            <div class="card border-0 shadow-lg">
                <div class="card-header  text-white">
                    Welcome, {{Auth::user()->name}}                      
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        @if(Auth::user()->image != '')
                            <img src="{{asset('uploads/profile/thumb/'.Auth::user()->image)}}" class="img-fluid rounded-circle" alt="{{$user->name}}">
                        @endif                            
                    </div>
                    <div class="h5 text-center">
                        <strong>{{$user->name}}</strong>
                        <p class="h6 mt-2 text-muted">{{Auth::user()->reviews->count()}} Reviews</p>
                    </div>
                </div>
            </div>
            <div class="card border-0 shadow-lg mt-3">
                <div class="card-header  text-white">
                    Menu
                </div>
                <div class="card-body sidebar">
                   @include('account.sidebar')
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card border-0 shadow">
                <div class="card-header  text-white">
                    Change password
                </div>
                <div class="card-body">
                    <form action="{{route('account.handleChangePw')}}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="old_password" class="form-label">Old password</label>
                            <input type="text" value="{{old('old_password')}}" class="form-control @error('old_password') is-invalid  @enderror" placeholder="Old password" name="old_password" id="" />
                            @error('old_password')
                                <p class="invalid-feedback">{{$message}}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="new_password" class="form-label">New password</label>
                            <input type="text" value="{{old('new_password')}}" class="form-control @error('new_password') is-invalid  @enderror" placeholder="New password" name="new_password" id="" />
                            @error('new_password')
                                <p class="invalid-feedback">{{$message}}</p>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm password</label>
                            <input type="text" value="{{old('confirm_password')}}" class="form-control @error('confirm_password') is-invalid  @enderror" placeholder="Confirm password" name="confirm_password" id="" />
                            @error('confirm_password')
                                <p class="invalid-feedback">{{$message}}</p>
                            @enderror
                        </div>                 
                        <button class="btn btn-primary mt-2" type="submit">Update</button>     
                    </form>                
                </div>
            </div>                
        </div>
    </div>       
</div>
@endsection