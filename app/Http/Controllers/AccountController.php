<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class AccountController extends Controller
{
    public function register () {
        return view('account.register');
    }

    public function handleRegister(Request $request) {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|max:24',
            'confirm_password' => 'required|min:8|max:24|same:password'
        ]);
        if($validator->fails()) {
            return redirect()->route('account.register')->withInput()->withErrors($validator);
        };
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = 'user';
        $user->save();

        return redirect()->route('account.login')->with('success','You have registered member successfully.');

    }

    public function login() {
        return view('account.login');
    }

    public function authenticate(Request $request) {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if($validator->fails()) {
            return redirect()->route('account.login')->withInput()->withErrors($validator);
        }
        if(Auth::attempt(['email' => $request->email,'password' => $request->password])) {
            return redirect()->route('account.profile')->with('success','You have login successfully.');

        } else {
            return redirect()->route('account.login')->with('error','Either email or password is not correct.');
        }
    }

    public function profile() {
        $user = User::find(Auth::user()->id);
        return view('account.profile',compact('user'));
    }

    public function handleUpdateProfile(Request $request) {
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.Auth::user()->id.',id'
        ];
        if(!empty($request->image)) {
            $rules['image'] = 'image';
        }

        $validator = Validator::make($request->all(),$rules);

        if($validator->fails()) {
            return redirect()->route('account.profile')->withInput()->withErrors($validator);
        }
        $user = User::find(Auth::user()->id);
        $user->name = $request->name;
        $user->email = $request->email;

        // Save image here
        if(!empty($request->image)){
            // Delete old image
            File::delete(public_path('uploads/profile/'.$user->image));
            File::delete(public_path('uploads/profile/thumb'.$user->image));

            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = time().'.'.$ext;
            $image->move(public_path('uploads/profile'),$imageName);
            $user->image = $imageName;
            
            // create thumb image
            $img = Image::make(public_path('uploads/profile/'.$imageName));
            $img->resize(150, 150);
            $img->save(public_path('uploads/profile/thumb/'.$imageName));
        }
       
        $user->save();

        return redirect()->route('account.profile')->with('success','You have updated profile successfully.');
    }

    public function logout() {
        Auth::logout();
        return redirect()->route('account.login')->with('success','You have logout successfully.');
    }

    public function myReview(Request $request) {
        $user = User::find(Auth::user()->id);
        $reviews = Review::where('user_id',Auth::user()->id)->where('status',1)->latest();
        if(!empty($request->search)){
            $reviews = $reviews->where('review','like','%'.$request->search.'%');
        }
        $reviews = $reviews->paginate(10);

        return view('account.my-review',compact('user','reviews'));
    }

    public function editMyReview($id) {
        $user = User::find(Auth::user()->id);
        $review = Review::find($id);
        return view('account.my-review-edit',compact('user','review'));
    }

    public function updateMyReview (Request $request,$id) {
        $review = Review::find($id);
        $validator = Validator::make($request->all(),[
            'review' => 'required|min:3'
        ]);
        if($validator->fails()){
            return redirect()->route('myReview.edit',$id)->withInput()->withErrors($validator);
        }
        $review->review = $request->review;
        $review->save();

        session()->flash('success','Update review successfully.');
        return redirect()->route('account.myReview');
    }

    public function deleteMyReview (Request $request) {
        $review = Review::find($request->id);
        if($review == null) {
            session()->flash('error','Record not found.');         
            return response()->json([
                'status' => false
            ]);
        }

        $review->delete();
        session()->flash('success','Deleted review successfully.');         
        return response()->json([
            'status' => true
        ]);
    }
}

