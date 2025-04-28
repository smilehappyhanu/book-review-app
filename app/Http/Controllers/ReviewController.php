<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function index (Request $request) {
        $user = User::find(Auth::user()->id);
        $reviews = Review::with('book','user')->select('reviews.*','books.title as title','users.name as name')->latest();
        $reviews = $reviews->leftJoin('books','books.id','reviews.book_id')
                          ->leftJoin('users','users.id','reviews.user_id');
        if(!empty($request->search)) {
            $reviews = $reviews->where('reviews.review','like','%'.$request->get('search').'%');
            $reviews = $reviews->orWhere('title','like','%'.$request->get('search').'%');
            $reviews = $reviews->orWhere('users.name','like','%'.$request->get('search').'%');
        }

        $reviews = $reviews->paginate(10);
        return view('review.list',compact('user','reviews'));
    }

    public function edit($id) {
        $review = Review::findOrFail($id);
        $user = User::find(Auth::user()->id);

        return view('review.edit',compact('user','review'));
    }

    public function update(Request $request,$id) {
        $validator = Validator::make($request->all(),[
            'review' => 'required|min:3',
            'status' => 'required'
        ]);
        if($validator->fails()) {
            return redirect()->route('reviews.edit',$id)->withInput()->withErrors($validator);
        }
        $review = Review::findOrFail($id);
        $review->review = $request->review;
        $review->status = $request->status;
        $review->save();

        session()->flash('success','You have updated review successfully.');
        return redirect()->route('reviews.index');
    }

    public function destroy(Request $request) {
        $review = Review::findOrFail($request->id);
        if($review == null) {
            session()->flash('error','Record not found');
            return response()->json([
                'status' => false
            ]);
        }

        $review->delete();
        session()->flash('success','Deleted review successfully');

        return response()->json([
            'status' => true,
        ]);
    }
}
