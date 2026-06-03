<?php
namespace App\Http\Controllers\Trainer;

use App\Http\Controllers\Controller;
use App\Models\TrainerReel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReelController extends Controller
{
    public function index()
    {
        $reels = TrainerReel::where('trainer_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(12);
        return view('trainer.reels', compact('reels'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video' => 'required|mimetypes:video/mp4,video/quicktime,video/x-msvideo|max:51200',
        ]);

        $reel = new TrainerReel;
        $reel->trainer_id = auth()->id();
        $reel->title = $data['title'];
        $reel->description = $data['description'] ?? null;
        $reel->video = $request->file('video')->store('reels', 'public');
        $reel->save();

        return redirect()->route('trainer.reels')->with('success', 'تم رفع الريل');
    }

    public function destroy($id)
    {
        $reel = TrainerReel::where('trainer_id', auth()->id())->findOrFail($id);
        if ($reel->video) Storage::disk('public')->delete($reel->video);
        $reel->delete();
        return redirect()->route('trainer.reels')->with('success', 'تم حذف الريل');
    }
}
