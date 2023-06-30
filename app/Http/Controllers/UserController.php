<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function index()
    {
        $users = User::where('userType', 'customer')
            ->orderBy('id')
            ->paginate(5);

        return view('users.index', compact('users'))->with(request()->input('page'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.adminCreate');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the input
        $request->validate([
            'username' => 'required|unique:user',
            'password' => 'required|confirmed|min:8|regex:/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@#$%^&+=])?[A-Za-z\d@#$%^&+=]*$/',
            'email' => 'required|email|unique:user',
            'name' => 'required|regex:/^[a-zA-Z\s]+$/',
            'address' => 'required',
            'postcode' => 'required|numeric|digits:5',
            'city' => 'required',
            'phoneNumber' => 'required|numeric|digits_between:10,11',
            'userType'=> 'required',
        ]);

        // Create a user
        User::create($request->all());

        // Redirect the user and send friendly messages
        return redirect()->route('user')->with('success', 'Staff account created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function showUserProfile()
    {
        $users = DB::table('users')->orderBy('id');
            return view('users.userprofile', compact('users'))->with(request()->input('page'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user')); 
    }

    /**
     * Update the specified resource in storage.
     */
    
     public function update(Request $request, User $user)
    {
        // Validate the input
        $rules = [
            'username' => 'required',
            'email' => 'required|email',
        ];
        
        if ($user->userType === 'customer') {
            $rules = array_merge($rules, [
                'password' => 'nullable|min:8|regex:/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@#$%^&+=])?[A-Za-z\d@#$%^&+=]*$/',
                'new_password' => 'nullable|confirmed|min:8|regex:/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@#$%^&+=])?[A-Za-z\d@#$%^&+=]*$/',
                'name' => 'nullable|regex:/^[a-zA-Z\s]+$/',
                'address' => 'nullable',
                'postcode' => 'nullable|numeric|digits:5',
                'city' => 'nullable',
                'phoneNumber' => 'nullable|numeric|digits_between:10,11',
            ]);

        } elseif ($user->userType === 'staff') {
            $rules = array_merge($rules, [
                'password' => 'nullable|min:8|regex:/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@#$%^&+=])?[A-Za-z\d@#$%^&+=]*$/',
                'new_password' => 'nullable|confirmed|min:8|regex:/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@#$%^&+=])?[A-Za-z\d@#$%^&+=]*$/',
                'name' => 'required|regex:/^[a-zA-Z\s]+$/',
                'address' => 'required',
                'postcode' => 'required|numeric|digits:5',
                'city' => 'required',
                'phoneNumber' => 'required|numeric|digits_between:10,11',
            ]);
        }

        $request->validate($rules);

        // Check if the current password is correct
        if ($request->filled('password') && !Hash::check($request->password, $user->password)) {
            return redirect()->back()->with('error', 'The current password is incorrect.');
        }

        // Update only the necessary fields
        $user->fill($request->only(['username', 'email']));

        // Check if a new password is provided and update it
        if ($request->filled('new_password')) {
            $user->password = $request->input('new_password');
        }

        // Update additional fields if the user is staff
        if ($user->userType === 'staff') {
            $user->name = $request->input('name');
            $user->address = $request->input('address');
            $user->postcode = $request->input('postcode');
            $user->city = $request->input('city');
            $user->phoneNumber = $request->input('phoneNumber');
            $user->save();
        }

        if ($user->userType === 'customer') {
            $user->name = $request->input('name');
            $user->address = $request->input('address');
            $user->postcode = $request->input('postcode');
            $user->city = $request->input('city');
            $user->phoneNumber = $request->input('phoneNumber');
            $user->save();
        }

        // Determine the appropriate success message based on user type
        $successMessage = '';
        if (auth()->user()->id === $user->id) {
            $successMessage = 'User Profile updated successfully.';
        } else {
                $successMessage = 'Customer Account updated successfully.';
        }

        // Redirect with the success message
        if (auth()->user()->id === $user->id) {
            return redirect()->route('users.userprofile')->with('success_message', $successMessage);
        } else {
            return redirect()->route('user')->with('success_message', $successMessage);
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if (auth()->user()->id == $user->id) {
            //delete the user
            $user->delete();
            //redirect the user to the register page
            return redirect('/register')->with('success', 'Your account has been deleted successfully');
        } else {
            // The staff member is deleting another user's account, redirect to the index page
            $user->delete();
            return redirect()->route('user')->with('success','User has been deleted');
        }
    }

    public function customerIndex()
{
    $users = User::where('userType', 'customer')->paginate(5);

    $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><users></users>');

    foreach ($users as $user) {
        $recordXml = $xml->addChild('user');
        $recordXml->addChild('id', htmlspecialchars($user->id, ENT_XML1, 'UTF-8'));
        $recordXml->addChild('username', htmlspecialchars($user->username, ENT_XML1, 'UTF-8'));
        $recordXml->addChild('email', htmlspecialchars($user->email, ENT_XML1, 'UTF-8'));
    }

    $xmlString = $xml->asXML();
    $xmlPath = public_path('xml/user.xml');
    file_put_contents($xmlPath, $xmlString);

    $xslPath = public_path('xml/user.xsl');
    $html = $this->transformXML($xmlPath, $xslPath);

    return response($html)->header('Content-type', 'text/html');
}

    private function transformXML($xmlPath, $xslPath)
    {
        $xml = new \DOMDocument();
        $xml->load($xmlPath);

        $xsl = new \DOMDocument();
        $xsl->load($xslPath);

        $proc = new \XSLTProcessor();
        $proc->importStylesheet($xsl);

        // Set the CSRF token as a parameter
        $csrfToken = csrf_token();
        $proc->setParameter('', 'csrf_token', $csrfToken);

        return $proc->transformToXml($xml);
    }



}
