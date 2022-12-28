<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Reaction;
use App\Models\Neighbourhood;
use App\Models\Token;
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

class PostApiController extends Controller
{
    public function addPost(Request $request)
    {
        $header = $request->header('Authorization');
        $user_active = Token::where('isValid', true)->where('token', $header)->first()->user_id;
        $neighbourhood_id = User::where('id', $user_active)->first()->neighbourhood_id;
        $post = new Post();
        $post->title = $request->input('title');
        $post->content = $request->input('content');
        $post->user_id = $user_active;
        $post->neighbourhood_id = $neighbourhood_id;
        $post->created_at  = new DateTime();
        $post->save();
        return redirect()->route('home');
    }

    public function post(Request $request, $id){
        $header = $request->header('Authorization');
        $user_id = Token::where('isValid', true)->where('token', $header)->first()->user_id;
        $post = Post::find($id);
        $user = User::find($post->user_id);
        $reactions = Reaction::where('post_id', $id)->get();
        $comments = Comment::where('post_id', $id)->get();
        $reactionCount = Reaction::where('post_id', $id)->count();
        $commentCount = Comment::where('post_id', $id)->count();
        $hasReacted = Reaction::where('post_id', $id)
            ->where('user_id', $user_id)
            ->count();
        $commentDtos = array();
        foreach($comments as $comment){
            $commentDto = new CommmentDto($comment);
            array_push($commentDtos, $commentDto);
        }
        return response()->json([
            'post' => $post,
            'user' => $user,
            'reactions' => $reactions,
            'comments' => $commentDtos,
            'reactionCount' => $reactionCount,
            'commentCount' => $commentCount,
            'hasReacted' => $hasReacted,
            'currentUser' => $user_id
        ]);
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
        return redirect()->route('home');
    }

    public function editPostSubmit(Request $request, $id){
        $post = Post::find($id);
        $post->content = $request->input('content');
        $post->save();
        return redirect()->route('post', ['id' => $id]);
    }

    public function addReaction(Request $request){
        $post_id = $request->input('post_id');
        $user_id = Token::where('isValid', true)->first()->user_id;
        $hasReacted = $request->input('hasReacted');
        if($hasReacted == "true"){
            $reaction = Reaction::where('post_id', $post_id)
                ->where('user_id', $user_id)
                ->first();
            $reaction->delete();
            return response()->json([
                'hasReacted' => "unliked"
            ]);
        }
        else{
            $reaction = new Reaction();
            $reaction->post_id = $post_id;
            $reaction->user_id = $user_id;
            $reaction->reaction = "Like";
            $reaction->save();
            return response()->json([
                'hasReacted' => "liked"
            ]);
        }
    }

    public function addComment(Request $request, $id){
        if(Post::find($id) == null){
            return response()->json([
                'comment' => "not added"
            ]);
        }
        $header = $request->header('Authorization');
        $user_id = Token::where('isValid', true)->where('token', $header)->first()->user_id;
        $comment = new Comment();
        $comment->comment = $request->input('comment');
        $comment->user_id = $user_id;
        $comment->post_id = $id;
        $comment->created_at = new DateTime();
        $comment->save();
        return response()->json([
            'comment' => "added"
        ]);
    }

    public function addReactionFromPost(Request $request){
        $post_id = $request->input('post_id');
        $user_id = Token::where('isValid', true)->first()->user_id;
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
        return response()->json([
            'comment' => $comment
        ]);
    }

    public function updateCommentSubmit(Request $request, $id){
        $comment = Comment::find($id);
        $comment->comment = $request->input('comment');
        $comment->save();
        return redirect()->route('post', ['id' => $comment->post_id]);
    }
    
}
