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
                    Books
                </div>
                <div class="card-body pb-0">    
                    <div class="d-flex justify-content-between">
                        <a href="{{route('books.create')}}" class="btn btn-primary">Add Book</a> 
                        <form action="" method="GET" id="formSearch">
                            <div class="d-flex">
                                <input type="text" class="form-control" placeholder="Keyword Search" name="search" id="search" value="{{Request::get('search')}}">
                                <button type="submit" class="btn btn-primary ms-2">Search</button>  
                                <a class="btn btn-success ms-2" type="submit" href="{{route('books.index')}}">Reset</a>
                            </div>     
                        </form>      
                    </div>      
                   
                    <table class="table  table-striped mt-3">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Rating</th>
                                <th>Status</th>
                                <th width="150">Action</th>
                            </tr>
                            <tbody>
                                @if($books->isNotEmpty())
                                    @foreach ($books as $book)
                                        <tr>
                                            <td>{{$book->id}}</td>
                                            <td>{{$book->title}}</td>
                                            <td>{{$book->author}}</td>
                                            <td>3.0 (3 Reviews)</td>
                                            <td>
                                                @if($book->status == 1) 
                                                    <span class="text-success">Active</span>
                                                @else
                                                    <span class="text-danger">Block</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="#" class="btn btn-success btn-sm"><i class="fa-regular fa-star"></i></a>
                                                <a href="{{route('books.edit',$book->id)}}" class="btn btn-primary btn-sm"><i class="fa-regular fa-pen-to-square"></i>
                                                </a>
                                                <a href="javascript:void(0);" onclick="deleteBook('{{$book->id}}')" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach                             
                                @else
                                <tr>
                                    <td colspan="6">Record not found.</td>
                                </tr>
                                @endif

                            </tbody>
                        </thead>
                    </table>   
                    @if($books->isNotEmpty())
                    {{$books->links()}}    
                    @endif           
                </div>
                
            </div>                
        </div>
    </div>       
</div>
@endsection
@section('customJs')
<script type="text/javascript">
    function deleteBook(id) {
        if(confirm("Are you sure want to delete this record?")) {
            $.ajax({
                url: '{{route("books.delete")}}',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "id": id
                },
                type: 'delete',
                dataType: 'json',
                success: function(response) {
                    if(response.status == true) {
                        window.location.href = "{{route('books.index')}}";
                    }
                }
            })
        }
        
    }
</script>
@endsection