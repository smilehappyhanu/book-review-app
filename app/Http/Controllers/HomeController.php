<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index (Request $request) {
        $books = Book::withCount('reviews')->withSum('reviews','rating')->latest()->where('status',1);
        if($request->search != '') {
            $books = $books->where('title','like','%'.$request->get('search').'%');
            $books = $books->orWhere('author','like','%'.$request->get('search').'%');
        }
        
        $books = $books->paginate(8);
        
        return view('home',compact('books'));
    }

    public function bookDetail($id) {
        $book = Book::with([
            'reviews.user',
            'reviews' => function($query){
                $query->where('status',1);
        }])->withCount('reviews')->withSum('reviews','rating')->findOrFail($id);
        
        if($book == null) {
            abort(404);
        }
        $relatedBooks = Book::where('status',1)->where('id','!=',$id)->withCount('reviews')->withSum('reviews','rating')->inRandomOrder()->take(3)->get();

        // Count review book
        $countReview = Review::where('status',1)->where('book_id',$id)->count();

        // Show review
        
        return view('detail',compact('relatedBooks','book','countReview'));
    }

    public function bookReview(Request $request) {
        $validator = Validator::make($request->all(),[
            'review' => 'required|min:3',
            'rating' => 'required'
        ]);
        if($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
        $review = new Review();
        $review->review = $request->review;
        $review->rating = $request->rating;
        $review->user_id = Auth::user()->id;
        $review->book_id = $request->book_id;
        $review->status = 0;
        $review->save();

        session()->flash('success','You have reviewed book successfully.');
        return response()->json([
            'status' => true,
        ]);

    }
}
