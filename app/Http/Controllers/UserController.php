<?php

namespace App\Http\Controllers;

use App\Events\SendLikeEvent;
use App\Http\Requests\User\SendLikeRequest;
use App\Models\Like;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Termwind\Components\Li;

class UserController extends Controller
{
    public function show(User $user)
    {
        $likesCount = count(Like::where('to_like', $user->id)->get());
        return inertia('User/Show', compact('user', 'likesCount'));
    }

    public function sendLike(User $user, SendLikeRequest $request)
    {
        $checkLikeC = Like::where('from_like', Auth::user()->id)->where('to_like', $user->id)->get();
        if (count($checkLikeC) < 1) {
            $like = new Like;
            $like->from_like = Auth::user()->id;
            $like->to_like = $user->id;
            $like->like = 1;
            $like->save();
            $checkLike = count(Like::where('from_like', Auth::user()->id)->where('to_like', $user->id)->get());


            $likeStr = "Пользователь " . $user->name . " поставил Вам лайк!";

            event(new SendLikeEvent($likeStr, $user->id, $checkLike));

            return response()->json([
                'like_str' => $likeStr,
                'like_count' => $checkLike
            ]);
        } else {
            return response()->json([
                'like_str' => "null",
                'like_count' => count(Like::where('from_like', Auth::user()->id)->where('to_like', $user->id)->get())
            ]);
        }
    }

    public function infoLike(User $user) {
        $myCount = count(Like::where('from_like', Auth::user()->id)->where('to_like', $user->id)->get());
        return response()->json([
            'myCount' => $myCount
        ]);
    }

    public function allUsers()
    {
        $users = User::all();
        return inertia('User/AllUsers', compact('users'));
    }
}
