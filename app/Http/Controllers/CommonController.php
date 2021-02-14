<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Interest;

class CommonController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();
        $collection = collect($request->interests);
        $user->interests()->sync($collection->map(function($interest) {
            return $interest['id'];
        }));
        return $request->interests;
    }

    public function showRelatedUsers()
    {
        $user = auth()->user();
        $users = Interest::getRelatedUsers($user->interests);
        return $users;
    }
}
