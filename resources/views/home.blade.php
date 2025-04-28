@extends('layouts.app')
@section('content')
<div class="container mt-3 pb-5">
    <div class="row justify-content-center d-flex mt-5">
        <div class="col-md-12">
            <div class="d-flex justify-content-between">
                <h2 class="mb-3">Books</h2>
                <div class="mt-2">
                    <a href="{{route('home')}}" class="text-dark">Clear</a>
                </div>
            </div>
            <div class="card shadow-lg border-0">
                <div class="card-body">
                    <form action="" method="GET">
                        <div class="row">
                            <div class="col-lg-11 col-md-11">
                                <input type="text" class="form-control form-control-lg" placeholder="Search by title" name="search" id="search" value="{{Request::get('search')}}">
                            </div>
                            <div class="col-lg-1 col-md-1">
                                <button class="btn btn-primary btn-lg w-100"><i class="fa-solid fa-magnifying-glass"></i></button>                                                                    
                            </div>                                                                                               
                        </div>
                    </form>
                </div>
            </div>
            <div class="row mt-4">
                @if($books->isNotEmpty())
                    @foreach($books as $book)
                        <div class="col-md-4 col-lg-3 mb-4">
                            <div class="card border-0 shadow-lg">
                                @if($book->image != '')
                                    <a href="{{route('book.detail',$book->id)}}"><img src="{{asset('uploads/books/'.$book->image)}}" alt="" class="card-img-top"></a>
                                @else
                                    <a href="{{route('book.detail',$book->id)}}"><img src="https://placehold.co/600x400?text=No image" alt="" class="card-img-top"></a>
                                @endif
                                    <div class="card-body">
                                    <h3 class="h4 heading"><a href="#">{{$book->title}}</a></h3>
                                    <p>by {{$book->author}}</p>

                                    @php
                                        if($book->reviews_count > 0) {
                                            $avgRating = $book->reviews_sum_rating/$book->reviews_count; 
                                        } else {
                                            $avgRating = 0;
                                        }

                                        $avgRatingPer = $avgRating*100/5
                                    @endphp

                                    <div class="star-rating d-inline-flex ml-2" title="">
                                        <span class="rating-text theme-font theme-yellow">{{$avgRating}}</span>
                                        <div class="star-rating d-inline-flex mx-2" title="">
                                            <div class="back-stars ">
                                                <i class="fa fa-star " aria-hidden="true"></i>
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                <i class="fa fa-star" aria-hidden="true"></i>
                                                <i class="fa fa-star" aria-hidden="true"></i>
            
                                                <div class="front-stars" style="width: {{$avgRatingPer}}%">
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                    <i class="fa fa-star" aria-hidden="true"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="theme-font text-muted">({{$book->reviews_count}} Reviews)</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                <h2 class="text-warning">Data is not available</h2>
                @endif
                <nav aria-label="Page navigation " >
                    @if($books->isNotEmpty())
                    {{$books->links()}}
                    @endif
                  </nav>    
                
            </div>
        </div>
    </div>
</div>  
@endsection
@section('customJs')
<script type="text/javascript">

</script>
@endsection