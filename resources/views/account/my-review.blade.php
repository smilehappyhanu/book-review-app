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
                    My Reviews
                </div>
                <div class="card-body pb-0">     
                    <div class="d-flex justify-content-end">
                        <form action="" method="GET" id="formSearch">
                            <div class="d-flex">
                                <input type="text" class="form-control" placeholder="Keyword Search" name="search" id="search" value="{{Request::get('search')}}">
                                <button type="submit" class="btn btn-primary ms-2">Search</button>  
                                <a class="btn btn-success ms-2" type="submit" href="{{route('account.myReview')}}">Reset</a>
                            </div>     
                        </form>      
                    </div>             
                    <table class="table  table-striped mt-3">
                        <thead class="table-dark">
                            <tr>
                                <th>Review</th>
                                <th>Book</th>
                                <th>Rating</th>
                                <th>Review date</th>
                                <th>Status</th>                                  
                                <th width="100">Action</th>
                            </tr>
                            <tbody>
                                @if($reviews->isNotEmpty())
                                    @foreach($reviews as $review)
                                        <tr>
                                            <td>{{$review->review}}</td>                                        
                                            <td>{{$review->book->title}}</td>
                                            <td>{{$review->rating}}</td>
                                            <td>{{ \Carbon\Carbon::parse($review->created_at)->format('d M, Y')}}</td>
                                            <td>
                                                @if($review->status == 1) 
                                                    <span class="text-success">Active</span>
                                                @else
                                                    <span class="text-danger">Block</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{route('myReview.edit',['id' => $review->id])}}" class="btn btn-primary btn-sm"><i class="fa-regular fa-pen-to-square"></i>
                                                </a>
                                                <a href="javascript:void(0);" onclick="deleteMyReview('{{$review->id}}')" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <h5>Record not found.</h5>  
                                @endif                       
                            </tbody>
                        </thead>
                    </table>   
                    <nav aria-label="Page navigation " >
                        <ul class="pagination">
                            @if($reviews->isNotEmpty())
                            {{$reviews->links()}}
                            @endif
                        </ul>
                      </nav>                  
                </div>
                
            </div>                
        </div>
    </div>       
</div>
@endsection
@section('customJs')
<script type="text/javascript">
   function deleteMyReview(id) { 
    if(confirm("Are you sure want to delete this record")) {
        $.ajax({
            url: '{{route("myReview.delete")}}',
            type:'delete',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: {
                "_token": "{{ csrf_token() }}",
                "id": id
            },
            dataType: 'json',
            success: function(response) {
                window.location.href = '{{route("account.myReview")}}';
            }
        })
    }
   }
</script>
@endsection