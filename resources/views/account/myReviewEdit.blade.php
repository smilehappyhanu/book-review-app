qqqq@extends('layouts.app')
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
                        <p class="h6 mt-2 text-muted">5 Reviews</p>
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
                    Edit My Review
                </div>
                <form action="{{route('myReview.update',$review->id)}}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="review" class="form-label">Review</label>
                            <textarea name="review" id="review" class="form-control" placeholder="Review" cols="30" rows="5">{{old('review',$review->review)}}</textarea>
                            @error('review')
                                <p class="invalid-feedback">{{$message}}</p>                               
                            @enderror
                        </div>
                 <button class="btn btn-primary mt-2" type="submit">Update</button>                     
                    </div>
                </form>
                
            </div>                
        </div>
    </div>       
</div>
@endsection