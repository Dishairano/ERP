<?php

namespace App\Http\Controllers\HRM;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class StaffManagementController extends Controller
{
    // Display all staff members
    public function index()
    {
        $staff = User::all();
        return view('hrm.staff-management.index', compact('staff'));
    }

    // Show the form for creating a new staff member
    public function create()
    {
        return view('hrm.staff-management.create');
    }

    public function store(Request $request)
    {
        Log::info('Store method called', ['request_data' => $request->all()]);

        // Validate the incoming request data
        $validatedData = $request->validate([
            'first_name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        Log::info('Validation passed', ['validated_data' => $validatedData]);

        try {
            // Ensure password is hashed correctly before saving to the database
            $user = User::create([
                'first_name' => $validatedData['first_name'],
                'email' => $validatedData['email'],
                'password' => bcrypt($validatedData['password']),  // Hash the password
            ]);

            Log::info('User created successfully', ['user' => $user]);

            return redirect()->route('hrm.staff-management')->with('success', 'Staff member added successfully');
        } catch (\Exception $e) {
            // Log the error if something goes wrong
            Log::error('Error occurred while creating user', ['error' => $e->getMessage()]);
            return redirect()->back()->withErrors('An error occurred while creating the user.');
        }
    }


    // Show the form for editing an existing staff member
    public function edit($id)
    {
        $staff = User::findOrFail($id);
        return view('hrm.staff-management.edit', compact('staff'));
    }

    // Update an existing staff member in the database
    public function update(Request $request, $id)
    {
        // Validate request data
        $request->validate([
            'name' => 'required',  // Consistently use 'name'
            'email' => 'required|email|unique:users,email,' . $id,  // Unique check except for the current user
        ]);

        // Find and update the staff member
        $staff = User::findOrFail($id);
        $staff->update($request->only(['name', 'email']));  // Only update the fields you want

        // Redirect to the staff management page after successful update
        return redirect()->route('hrm.staff-management')->with('success', 'Staff member updated successfully');
    }

    // Delete a staff member
    public function destroy($id)
    {
        // Find and delete the staff member
        $staff = User::findOrFail($id);
        $staff->delete();

        // Redirect to the staff management page after successful deletion
        return redirect()->route('hrm.staff-management')->with('success', 'Staff member deleted successfully');
    }
}