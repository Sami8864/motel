<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;


class AuthController extends Controller
{
    public function store(Request $request)
    { {
            // Validate the request data
            $req =  Validator::make($request->All(), [
                'password' => 'required|string|min:6|confirmed',
                'id_no' => 'nullable|string|unique:users,id_no',
                'email' => 'nullable|email|unique:users,email',
            ]);
            if ($req->fails()) {
                return response()->json(['errors' => $req->errors()], 422);
            }
            // Check if either email or CNIC is provided
            if (!$request->has('email') && !$request->has('id_no')) {
                return response()->json(['error' => 'Either email or ID card no is required.'], 422);
            }

            $user = new User([
                'password' => Hash::make($request->password),
                'email' => $request->email,
                'id_no' => $request->id_no ?? NULL,

            ]);
            $role = Role::where('name', 'user')->get();

            $user->syncRoles($role);
            // Save the user
            $user->save();

            return response()->json([
                'message' => 'User created successfully.', 'code' => 200,
                'user' => $user
            ], 200);
        }
    }
    public function login(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'identity' => 'required|string', // Accepts either email or CNIC
            'password' => 'required|string',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Check if the user exists by email or CNIC
        $user = User::where('email', $request->identity)
            ->orWhere('id_no', $request->identity)
            ->first();

        // Check if the user exists
        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        // Check if the provided password matches
        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Invalid credentials.'], 401);
        }
        auth()->login($user); // Manually authenticate the user
        $token = $user->createToken('AuthToken')->accessToken;

        return response()->json([
            'message' => 'Login successful.',
            'user' => $user,
            'access_token' => $token
        ], 200);
    }

    public function sendOTP()
    {

        $serviceAccount = ServiceAccount::fromJsonFile(config_path('firebasesdk.php'));

        $firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->create();
        $auth = $firebase->getAuth();

        // Send verification code to the phone number
        $phoneNumber = '+923075108200'; // Replace with the recipient phone number
        $auth->startPhoneNumberVerification($phoneNumber);
    }
}
