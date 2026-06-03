<?php
namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\PostComment;
use App\Models\TrainerPost;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, $postId)
    {
        $data = $request->validate(['content' => 'required|string']);
        $post = TrainerPost::findOrFail($postId);
        PostComment::create([
            'post_id' => $post->id,
            'user_id' => auth()->id(),
            'content' => $data['content'],
        ]);
        return back()->with('success', 'تم إضافة التعليق');
    }

    public function destroy($id)
    {
        $comment = PostComment::where('user_id', auth()->id())->findOrFail($id);
        $comment->delete();
        return back()->with('success', 'تم حذف التعليق');
    }
}
