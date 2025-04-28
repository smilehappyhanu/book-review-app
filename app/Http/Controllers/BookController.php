<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class BookController extends Controller
{
    public function index(Request $request) {
        $user = User::find(Auth::user()->id);
        $books = Book::latest();
        // Handle search here
        if(!empty($request->get('search'))) {
            $books = $books->where('title','like','%'.$request->get('search').'%');
            $books = $books->orWhere('author','like','%'.$request->get('search').'%');
        }       
        $books = $books->paginate(10);
        return view('books.list',compact('user','books'));
    }

    public function create(){
        $user = User::find(Auth::user()->id);
        return view('books.create',compact('user'));
    }

    public function store(Request $request) {
        $rules = [
            'title' => 'required|min:5',
            'author' => 'required|min:3',
            'status' => 'required'
        ];
        
        if(!empty($request->image)) {
            $rules['image'] = 'image';
        }

        $validator = Validator::make($request->all(),$rules);

        if($validator->fails()) {
            return redirect()->route('books.create')->withInput()->withErrors($validator);
        }

        $book = new Book();
        $book->title = $request->title;
        $book->author = $request->author;
        $book->description = $request->description;
        $book->status = $request->status;

        // Upload image here
        if(!empty($request->image)) {
            $image = $request->image;
            $extension = $image->getClientOriginalExtension();
            $imageName = time().'.'.$extension;
            $image->move(public_path('uploads/books'),$imageName);
            $book->image = $imageName;

            // Save as thumbnail
            $img = Image::make(public_path('uploads/books/'.$imageName));
            $img->resize(150,150);
            $img->save(public_path('uploads/books/thumb/'.$imageName));
        }

        $book->save();

        return redirect()->route('books.index')->with('success','You have created book successfully.');

    }

    public function edit($id) {
        $user = User::find(Auth::user()->id);
        $book = Book::findOrFail($id);
        return view('books.edit',compact('user','book'));
    }

    public function update(Request $request,$id) {
        $rules = [
            'title' => 'required|min:5',
            'author' => 'required|min:3',
            'status' => 'required'
        ];
        
        if(!empty($request->image)) {
            $rules['image'] = 'image';
        }

        $validator = Validator::make($request->all(),$rules);

        if($validator->fails()) {
            return redirect()->route('books.edit',$id)->withInput()->withErrors($validator);
        }

        $book = Book::findOrFail($id);
        $book->title = $request->title;
        $book->author = $request->author;
        $book->description = $request->description;
        $book->status = $request->status;

        // Upload image here
        $oldImage = $book->image;
        if(!empty($request->image)) {
            // Delete old image
            if(!empty($book->image)){
                File::delete(public_path('uploads/books/'.$oldImage));
                File::delete(public_path('uploads/books/thumb/'.$oldImage));
            }

            $image = $request->image;
            $extension = $image->getClientOriginalExtension();
            $imageName = time().'.'.$extension;
            $image->move(public_path('uploads/books'),$imageName);
            $book->image = $imageName;

            // Save as thumbnail
            $img = Image::make(public_path('uploads/books/'.$imageName));
            $img->resize(150,150);
            $img->save(public_path('uploads/books/thumb/'.$imageName));

        }  else {
            $book->image = $oldImage;
        }

        $book->save();

        return redirect()->route('books.index')->with('success','You have updated book successfully.');

    }

    public function destroy(Request $request) {
        $book = Book::find($request->id);
        if($book == null) {
            session()->flash('error','Record not found');
            return response()->json([
                'status' => true,
                'message' => 'Record not found'
            ]);
        } else {
            File::delete(public_path('uploads/books/'.$book->image));
            File::delete(public_path('uploads/books/thumb/'.$book->image));
            $book->delete();
            session()->flash('success','Delete book successfully.');
            return response()->json([
                'status' => true,
                'message' => 'Delete book successfully.'
            ]);
        }
    }
}
