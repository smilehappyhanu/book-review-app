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
                    Add Book
                </div>
                <form action="{{route('books.store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control @error('title') is-invalid  @enderror" placeholder="Title" name="title" id="title" value="{{old('title')}}"/>
                            @error('title')
                                <p class="invalid-feedback">{{$message}}</p>                               
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="author" class="form-label">Author</label>
                            <input type="text" class="form-control @error('author') is-invalid  @enderror" placeholder="Author"  name="author" id="author" value="{{old('author')}}" />
                            @error('author')
                                <p class="invalid-feedback">{{$message}}</p>                               
                            @enderror
                        </div>
    
                        <div class="mb-3">
                            <label for="author" class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control" placeholder="Description" cols="30" rows="5"></textarea>
                        </div>
    
                        <div class="mb-3">
                            <label for="Image" class="form-label">Image</label>
                            <input type="file" class="form-control @error('image') is-invalid  @enderror"  name="image" id="image"/>
                            @error('image')
                                <p class="invalid-feedback">{{$message}}</p>                               
                            @enderror
                        </div>
    
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-control @error('status') is-invalid  @enderror">
                                <option value="1">Active</option>
                                <option value="0">Block</option>
                            </select>
                            @error('status')
                                <p class="invalid-feedback">{{$message}}</p>                               
                            @enderror
                        </div>
    
    
                        <button class="btn btn-primary mt-2" type="submit">Create</button>                     
                    </div>
                </form>
                
            </div>                
        </div>
    </div>       
</div>
@endsection