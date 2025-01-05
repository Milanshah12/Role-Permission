<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user=auth()->user();
        $info=$user->profile()->first();
        return view('profile.edit', [
            'user' => $user,
            'info'=>$info
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
      $validated=$request->validated();

      $user=$request->user();

      $user->update([
        'name'=>$validated['name'],
        'email'=>$validated['email'],

      ]);
      if($user->profile){
        $user->profile->update([
            'address'=>$validated['address'],
            'number'=>$validated['number']
        ]);
      }else{
        $user->profile()->create([
            'address'=>$validated['address'],
            'number'=>$validated['number']
        ]);
      }
      return Redirect::route('profile.edit')->with('status', 'profile-updated');

    }


    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }


    public function profileDashboard(){

        // $email=session('userEmail');
        // $users=User::where('email',$email)->first();
        $users=Auth::user();
        $img=$users->getFirstMediaUrl('image');
        $profile=Null;
        if($users){
            $profile=$users->profile();

        }
        return view('profiles.profileDashboard',['users'=>$users,'profile'=>$profile,'img'=>$img]);
    }

    public function uplodephoto(Request $request)
    {
        // Validate the uploaded image
        $request->validate([
            'image' => 'required|image|mimes:png,jpg,jpeg,gif|max:2040',
        ]);

        // Get the currently authenticated user
        $user = auth()->user();

        // Check if a file is uploaded
        if ($request->hasFile('image')) {
            $user->clearMediaCollection('image');
           $user->addMediaFromRequest('image')->toMediaCollection('image');
        }

        // Redirect back with a success message
        return redirect()->back()->with('message', 'Profile photo updated successfully.');
    }

}
