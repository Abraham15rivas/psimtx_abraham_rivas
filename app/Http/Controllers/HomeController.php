<?php

namespace App\Http\Controllers;

use App\Models\Interest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function interests()
    {
        return Interest::all()->toJson();
    }

    public function getAllUsers()
    {
        return User::all()->where('role_id', '!=', 1)->toJson();
    }

    public function changeStatus($id)
    {
        $user = User::findOrFail($id);
        $user->update(['status' => !$user->status]);
        return true;
    }

    public function changePassword($id, Request $request)
    {
        $request->validate([
            'password' => ['required', 'string', 'min:6', 'max:16', 'regex:/^[*.#?¿]+$/u'],
        ]);
        $user = User::findOrFail($id);
        $user->update(['password' => Hash::make($request->password)]);
        return true;
    }
}
