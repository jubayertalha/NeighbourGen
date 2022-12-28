<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Reaction;
use App\Models\Neighbourhood;
use App\Models\Token;

class PostUserDto{
    public $creator;
    public $post;
    public $comments;
    public $reactions;
    public $hasReacted;

    public function __construct($creator, $post, $comments, $reactions, $hasReacted){
        $this->creator = $creator;
        $this->post = $post;
        $this->comments = $comments;
        $this->reactions = $reactions;
        $this->hasReacted = $hasReacted;
    }
}

class UserApiController extends Controller
{
    public function profileEdit(){
        $header = $request->header('Authorization');
        $user_id = Token::where('isValid', true)->where('token', $header)->first()->user_id;
        $user = User::where('id', $user_id)->first();
        $neighbourhoods = Neighbourhood::all();
        return response()->json($user);
    }

    public function updateProfile(Request $request){
        $header = $request->header('Authorization');
        $user_id = Token::where('isValid', true)->where('token', $header)->first()->user_id;
        $user = User::where('id', $user_id)->first();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->save();
        return redirect()->route('profile');
    }

    public function profile(Request $request){
        $header = $request->header('Authorization');
        $user_id = Token::where('isValid', true)->where('token', $header)->first()->user_id;
        $user = User::where('id', $user_id)->first();
        $neighbourhood = Neighbourhood::where('id', $user->neighbourhood_id)->first();
        $posts = Post::where('user_id', $user_id)->get();
        $postDtos = array();
        foreach($posts as $post){
            $creator = User::where('id', $post->user_id)->first();
            $comments = Comment::where('post_id', $post->id)->count();
            $reactions = Reaction::where('post_id', $post->id)->count();
            $hasReacted = Reaction::where('post_id', $post->id)->where('user_id', $user_id)->count();
            $postDto = new PostUserDto($creator, $post, $comments, $reactions, $hasReacted);
            array_push($postDtos, $postDto);
        }
        return response()->json(['user' => $user, 'neighbourhood' => $neighbourhood, 'postDtos' => $postDtos]);
    }
}
