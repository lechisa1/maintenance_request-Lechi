<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;



class ProfileController extends Controller
{
    //
    public function show()
    {
        $user = auth()->user();

        return view('profile.show', compact('user'));
    }
 // make sure this is at the top

public function uploadImage(Request $request)
{
    try {
        $validated = $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120'
        ]);

        $user = auth()->user();
        $image = $request->file('profile_image');

        $filename = 'profile-'.$user->id.'-'.time().'.'.$image->getClientOriginalExtension();
        $path = 'profiles/' . $filename;

        // ✅ Use the GD driver instance
        $manager = new ImageManager(new Driver());

        $img = $manager->read($image->getRealPath());
        $img = $img->cover(200, 200); // Crop/resize

        Storage::disk('public')->put($path, (string) $img->toJpeg());

        if ($user->avatar_url) {
            $oldPath = str_replace(asset('storage/'), '', $user->avatar_url);
            Storage::disk('public')->delete($oldPath);
        }

        $user->avatar_url = asset('storage/' . $path);
        $user->save();

        return redirect()->back()->with('success', 'Profile image updated successfully!');
    } catch (\Exception $e) {
        \Log::error('Upload failed', ['error' => $e->getMessage()]);
        return redirect()->back()->with('error', 'Upload failed: ' . $e->getMessage());
    }
}



}