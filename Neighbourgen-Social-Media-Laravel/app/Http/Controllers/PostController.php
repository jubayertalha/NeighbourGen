<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use App\Models\Reaction;
use App\Models\Comment;
use DateTime;

class CommmentDto{
    public $creator;
    public $comment;
    public $currentUser;

    public function __construct($comment){
        $this->creator = User::where('id', $comment->user_id)->first()->name;
        $this->comment = $comment;
        $this->currentUser = session()->get('user');
    }
}    

class PostController extends Controller
{
    public function addPost(Request $request)
    {
        $validate = $request->validate([
            'content' => 'required'
            ],
            [
            'content.required'=>'Enter Your Post Please!'
            ]
        );
        $neighbourhood_id = User::where('id', session()->get('user'))->first()->neighbourhood_id;
        $post = new Post();
        $post->title = "My first post";
        $post->content = $request->input('content');
        $post->user_id = $request->session()->get('user');
        $post->neighbourhood_id = $neighbourhood_id;
        $post->created_at  = new DateTime();
        $post->save();
        return redirect()->route('home');
    }

    public function deletePost($id){
        $post = Post::find($id);
        $comments = Comment::where('post_id', $id)->get();
        foreach($comments as $comment){
            $comment->delete();
        }
        $reactions = Reaction::where('post_id', $id)->get();
        foreach($reactions as $reaction){
            $reaction->delete();
        }
        $post->delete();
        return redirect()->route('profile');
    }

    public function editPost($id){
        $post = Post::find($id);
        return view('pages.post.editPost')->with('post', $post);
    }

    public function editPostSubmit(Request $request, $id){
        $post = Post::find($id);
        $post->content = $request->input('content');
        $post->save();
        return redirect()->route('post', ['id' => $id]);
    }

    public function addReaction(Request $request){
        $post_id = $request->input('post_id');
        $user_id = session()->get('user');
        $hasReacted = $request->input('hasReacted');
        if($hasReacted == "true"){
            $reaction = Reaction::where('post_id', $post_id)
                ->where('user_id', $user_id)
                ->first();
            $reaction->delete();
            return redirect()->route('home');
        }
        else{
            $reaction = new Reaction();
            $reaction->post_id = $post_id;
            $reaction->user_id = $user_id;
            $reaction->reaction = "Like";
            $reaction->save();
            return redirect()->route('home');
        }
    }

    public function post($id){
        $currentUser = session()->get('user');
        $post = Post::find($id);
        $user = User::find($post->user_id);
        $reactions = Reaction::where('post_id', $id)->get();
        $comments = Comment::where('post_id', $id)->get();
        $reactionCount = Reaction::where('post_id', $id)->count();
        $commentCount = Comment::where('post_id', $id)->count();
        $hasReacted = Reaction::where('post_id', $id)
            ->where('user_id', session()->get('user'))
            ->count();
        $commentDtos = array();
        foreach($comments as $comment){
            $commentDto = new CommmentDto($comment);
            array_push($commentDtos, $commentDto);
        }
        return view('pages.post.post')->with('post', $post)->with('currentUser', $currentUser)
            ->with('user', $user)
            ->with('reactions', $reactions)
            ->with('reactionCount', $reactionCount)
            ->with('hasReacted', $hasReacted)
            ->with('commentDtos', $commentDtos)
            ->with('commentCount', $commentCount);
    }

    public function addComment(Request $request){
        $validate = $request->validate([
            'comment' => 'required'
            ],
            [
            'comment.required'=>'Enter Your Comment Please!'
            ]
        );
        $comment = new Comment();
        $comment->comment = $request->input('comment');
        $comment->user_id = session()->get('user');
        $comment->post_id = $request->input('post_id');
        $comment->created_at = new DateTime();
        $comment->save();
        return redirect()->route('post', ['id' => $request->input('post_id')]);
    }

    public function addReactionFromPost(Request $request){
        $post_id = $request->input('post_id');
        $user_id = session()->get('user');
        $hasReacted = $request->input('hasReacted');
        if($hasReacted == "true"){
            $reaction = Reaction::where('post_id', $post_id)
                ->where('user_id', $user_id)
                ->first();
            $reaction->delete();
            return redirect()->route('post', ['id' => $post_id]);
        }
        else{
            $reaction = new Reaction();
            $reaction->post_id = $post_id;
            $reaction->user_id = $user_id;
            $reaction->reaction = "Like";
            $reaction->save();
            return redirect()->route('post', ['id' => $post_id]);
        }
    }

    public function deleteComment($id){
        $comment = Comment::find($id);
        $post_id = $comment->post_id;
        $comment->delete();
        return redirect()->route('post', ['id' => $post_id]);
    }

    public function updateComment($id){
        $comment = Comment::find($id);
        return view('pages.post.comment')->with('comment', $comment);
    }

    public function updateCommentSubmit(Request $request, $id){
        $comment = Comment::find($id);
        $comment->comment = $request->input('comment');
        $comment->save();
        return view('pages.post.comment')->with('comment', $comment);
    }

}
