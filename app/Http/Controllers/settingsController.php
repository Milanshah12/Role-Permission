<?php

namespace App\Http\Controllers;

use App\Settings\GeneralSettings;
use Illuminate\Http\Request;

class settingsController extends Controller
{
    // Show settings page
    public function show(GeneralSettings $settings)
    {
        return view('role-permission.settings', [
            'settings' => $settings
        ]);
    }

    // Update settings
    public function update(Request $request, GeneralSettings $settings)
    {
        // Validate the input
        $request->validate([
            'title' => 'required|string|max:255',
            'timezone' => 'required|string|max:255',
            'theme' => 'required|in:light,dark',
            'font' => 'required|string|in:Arial,Helvetica,Roboto,Georgia',

        ]);

        // Update settings


        $settings->theme = $request->input('theme');
        $settings->title = $request->input('title');

        $settings->Timezone = $request->input('timezone');
        $settings->font = $request->input('font');


        $settings->save();


        // Redirect back with success message
        return redirect()->route('settings.show')->with('success', 'Settings updated successfully!');
    }
}
