<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Repository\CommentRepository;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function addComment(Request $request)
    {
        $comment = new Comment([
            'post_id' => $request->postId,
            'content' => $request->commentContent,
            'user_id' => $request->userId,
            'parent_comment_id' => $request->parentCommentId
        ]);
        $commentRepository = new CommentRepository();
        $commentRepository->save($comment);

        return redirect()->route('posts.show', $request->post_id);
    }
}
