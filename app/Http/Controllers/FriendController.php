<?php

namespace App\Http\Controllers;

use App\Events\StoreAddFriend;
use App\Http\Requests\User\AddFriendRequest;
use App\Http\Resources\Friend\FriendResource;
use App\Http\Resources\Message\MessageResource;
use App\Models\Friend;
use App\Models\Like;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    public function friends(User $user)
    {
        $users = Friend::where('from_id', Auth::user()->id)->where('is_friend', '1')
            ->orwhere('to_id', Auth::user()->id)->where('is_friend', '1')
            ->get();
        $users = FriendResource::collection($users)->resolve();
        return inertia('Friends/MyFriends', compact('users'));
    }

    public function infoFriend(User $user_from, User $user_to)
    {
        $frCount = count(Friend::where('from_id', $user_from->id)->where('to_id', $user_to->id)->get());
        return response()->json([
            'frCount' => $frCount
        ]);
    }

    public function store(User $user_from, User $user_to, AddFriendRequest $request)
    {
        if (count(Friend::where('from_id', $user_from->id)->where('to_id', $user_to->id)->get()) < 1) {
            $friend = new Friend;
            $friend->from_id = $user_from->id;
            $friend->to_id = $user_to->id;
            $friend->req_from = $user_from->id;
            $friend->is_friend = "0";
            $friend->save();

            event(new StoreAddFriend($user_from->id, $user_to->id));

            return response()->json([
                'user_from' => $user_from->id,
                'user_to' => $user_to->id,
            ]);
        } else {
            return response()->json([
                'user_from' => 'null',
                'user_to' => 'null',
            ]);
        }
    }
}
