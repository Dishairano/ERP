<?php

namespace App\Http\Controllers\pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AccountSettingsAccount extends Controller
{
    public function index()
    {
        $authUser = Auth::user(); // Get the authenticated user
        return view('content.pages.account-settings', compact('authUser'));
    }


    public function update(Request $request)
    {
        // Start logging to trace the flow of execution
        logger()->info('Update method called');

        $authUser = Auth::user();
        logger()->info('Authenticated User:', ['user_id' => $authUser->id]);

        // Validate the request data
        try {
            $validatedData = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $authUser->id,
                'organization' => 'required|string|max:255',
                'phone_number' => 'string|max:255',
                'address' => 'string|max:255',
                'state' => 'string|max:255',
                'zip_code' => 'string|max:255',
                'country' => 'string|max:255',
                'language' => 'string|max:255',
                'time_zones' => 'string|max:255',
                'currency' => 'string|max:255',
            ]);
            logger()->info('Validation successful:', $validatedData);
        } catch (\Exception $e) {
            logger()->error('Validation failed:', ['error' => $e->getMessage()]);
            return redirect()->back()->withErrors($e->getMessage());
        }

        // Get the current authenticated user from the database to ensure it's updatable
        $user = User::find($authUser->id);
        if ($user) {
            logger()->info('User found:', ['user_id' => $user->id]);

            // Update the user
            $user->first_name = $request->input('first_name');
            $user->last_name = $request->input('last_name');
            $user->email = $request->input('email');
            $user->organization = $request->input('organization');
            $user->phone_number = $request->input('phone_number');
            $user->address = $request->input('address');
            $user->state = $request->input('state');
            $user->zip_code = $request->input('zip_code');
            $user->country = $request->input('country');
            $user->language = $request->input('language');
            $user->time_zones = $request->input('time_zones');
            $user->currency = $request->input('currency');

            // Save the user
            try {
                $user->save();
                logger()->info('User successfully updated:', $user->toArray());
            } catch (\Exception $e) {
                logger()->error('User update failed:', ['error' => $e->getMessage()]);
                return redirect()->back()->withErrors('User update failed: ' . $e->getMessage());
            }

            return redirect()->back()->with('success', 'Account updated successfully!');
        }

        logger()->error('User not found for updating');
        return redirect()->back()->withErrors('Unable to find user for updating');
    }



}