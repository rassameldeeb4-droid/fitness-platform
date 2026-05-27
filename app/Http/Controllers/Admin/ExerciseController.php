<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exercise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExerciseController extends Controller
{
    public function index()
    {
        $exercises = Exercise::latest()->get();
        return view('admin.exercises.index', compact('exercises'));
    }

    public function create()
    {
        $categories = ['صدر', 'ظهر', 'أرجل', 'ذراع', 'كتف', 'بطن', 'كارديو'];
        $difficulties = ['beginner', 'intermediate', 'advanced'];
        return view('admin.exercises.create', compact('categories', 'difficulties'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'category' => 'required|string|max:255',
            'muscle_group' => 'required|string|max:255',
            'sets_default' => 'required|integer|min:1',
            'reps_default' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video_url' => 'nullable|string|max:255',
            'difficulty' => 'required|string|max:255',
            'equipment' => 'nullable|string|max:255',
            'calories_per_set' => 'nullable|integer|min:0',
            'video' => 'nullable|mimes:mp4,mov,avi,wmv|max:51200',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('video')) {
            $data['video_url'] = $request->file('video')->store('exercises/videos', 'public');
        }

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('exercises/images', 'public');
        }

        $data['is_active'] = $request->boolean('is_active', true);

        Exercise::create($data);

        return redirect()->route('admin.exercises.index')->with('success', 'تم إضافة التمرين بنجاح');
    }

    public function edit(Exercise $exercise)
    {
        $categories = ['صدر', 'ظهر', 'أرجل', 'ذراع', 'كتف', 'بطن', 'كارديو'];
        $difficulties = ['beginner', 'intermediate', 'advanced'];
        return view('admin.exercises.edit', compact('exercise', 'categories', 'difficulties'));
    }

    public function update(Request $request, Exercise $exercise)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'category' => 'required|string|max:255',
            'muscle_group' => 'required|string|max:255',
            'sets_default' => 'required|integer|min:1',
            'reps_default' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video_url' => 'nullable|string|max:255',
            'difficulty' => 'required|string|max:255',
            'equipment' => 'nullable|string|max:255',
            'calories_per_set' => 'nullable|integer|min:0',
            'video' => 'nullable|mimes:mp4,mov,avi,wmv|max:51200',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('video')) {
            if ($exercise->video_url && !filter_var($exercise->video_url, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($exercise->video_url);
            }
            $data['video_url'] = $request->file('video')->store('exercises/videos', 'public');
        }

        if ($request->hasFile('image')) {
            if ($exercise->image) {
                Storage::disk('public')->delete($exercise->image);
            }
            $data['image'] = $request->file('image')->store('exercises/images', 'public');
        }

        $data['is_active'] = $request->boolean('is_active', true);

        $exercise->update($data);

        return redirect()->route('admin.exercises.index')->with('success', 'تم تحديث التمرين بنجاح');
    }

    public function destroy(Exercise $exercise)
    {
        if ($exercise->image) {
            Storage::disk('public')->delete($exercise->image);
        }
        if ($exercise->video_url && !filter_var($exercise->video_url, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($exercise->video_url);
        }
        $exercise->delete();
        return redirect()->route('admin.exercises.index')->with('success', 'تم حذف التمرين بنجاح');
    }

    public function deleteImage(Exercise $exercise)
    {
        if ($exercise->image) {
            Storage::disk('public')->delete($exercise->image);
            $exercise->update(['image' => null]);
        }
        return back()->with('success', 'تم حذف الصورة');
    }

    public function deleteVideo(Exercise $exercise)
    {
        if ($exercise->video_url && !filter_var($exercise->video_url, FILTER_VALIDATE_URL)) {
            Storage::disk('public')->delete($exercise->video_url);
            $exercise->update(['video_url' => null]);
        }
        return back()->with('success', 'تم حذف الفيديو');
    }
}
