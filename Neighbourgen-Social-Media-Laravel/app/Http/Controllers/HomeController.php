<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Reaction;
use App\Models\Neighbourhood;

use Illuminate\Http\Request;

class PostDto{
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

class HomeController extends Controller
{
    public function home(){
        $id = session()->get('user');
        $neighbourhood_id = User::where('id', $id)->first()->neighbourhood_id;
        $neighbourhood = Neighbourhood::where('id', $neighbourhood_id)->first()->name;
        $posts = Post::where('neighbourhood_id', $neighbourhood_id)->get();
        $postDtos = array();
        foreach($posts as $post){
            $creator = User::where('id', $post->user_id)->first();
            $comments = Comment::where('post_id', $post->id)->count();
            $reactions = Reaction::where('post_id', $post->id)->count();
            $hasReacted = Reaction::where('post_id', $post->id)->where('user_id', $id)->count();
            $postDto = new PostDto($creator, $post, $comments, $reactions, $hasReacted);
            array_push($postDtos, $postDto);
        }
        return view('pages.home')->with('postDtos', $postDtos)->with('neighbourhood', $neighbourhood);
    }
}
