<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Neighbourhood;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Reaction;

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

class UserController extends Controller
{
    public function profileEdit(){
        $id = session()->get('user');
        $neighbourhoods = Neighbourhood::all();
        $user = User::where('id', $id)->first();
        return view('pages.user.edit')->with('user', $user)->with('neighbourhoods', $neighbourhoods);
    }

    public function updateProfile(Request $request){
        $id = session()->get('user');
        $user = User::where('id', $id)->first();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->save();
        return redirect()->route('profile');
    }

    public function profile(){
        $id = session()->get('user');
        $user = User::where('id', $id)->first();
        $neighbourhood = Neighbourhood::where('id', $user->neighbourhood_id)->first();
        $posts = Post::where('user_id', $id)->get();
        $postDtos = array();
        foreach($posts as $post){
            $creator = User::where('id', $post->user_id)->first();
            $comments = Comment::where('post_id', $post->id)->count();
            $reactions = Reaction::where('post_id', $post->id)->count();
            $hasReacted = Reaction::where('post_id', $post->id)->where('user_id', $id)->count();
            $postDto = new PostUserDto($creator, $post, $comments, $reactions, $hasReacted);
            array_push($postDtos, $postDto);
        }
        return view('pages.user.profile')->with('user', $user)->with('neighbourhood', $neighbourhood)->with('postDtos', $postDtos);
    }
}
