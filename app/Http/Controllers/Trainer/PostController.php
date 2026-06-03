<?php
namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\TrainerPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        $posts = TrainerPost::where('trainer_id', auth()->id())
            ->with('comments.user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('trainer.posts', compact('posts'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'content' => 'required|string',
            'image' => 'nullable|image|max:5120',
        ]);

        $post = new TrainerPost;
        $post->trainer_id = auth()->id();
        $post->content = $data['content'];
        if ($request->hasFile('image')) {
            $post->image = $request->file('image')->store('posts', 'public');
        }
        $post->save();

        return redirect()->route('trainer.posts')->with('success', 'تم نشر المنشور');
    }

    public function destroy($id)
    {
        $post = TrainerPost::where('trainer_id', auth()->id())->findOrFail($id);
        if ($post->image) Storage::disk('public')->delete($post->image);
        $post->delete();
        return redirect()->route('trainer.posts')->with('success', 'تم حذف المنشور');
    }
}
