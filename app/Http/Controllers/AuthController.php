<?php

namespace App\Http\Controllers;

use App\Models\User;
use Kreait\Firebase;
use App\Mail\TestMail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use App\Mail\OTPVerification;
use Spatie\Permission\Models\Role;
use Kreait\Firebase\ServiceAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{

    protected $code;
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
        if(!isset($user->email_verified_at)){
            return response()->json(['error' => 'You must verify your email first'], 404);
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



    public function randGen()
    {
        $randomInteger = mt_rand(1000, 9999);
        return $randomInteger;
    }

    public function verifyCode(Request $request){
        $data = $request->All();
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:users,id',
            'code'=>'required'
        ]);
        $id = json_decode($data["id"]);
        $user = User::find($id);
        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $mycode=json_decode($data['code']);
        $user_code=json_decode($user->email_verification_code);
        if($user_code===$mycode){
            $user->email_verified_at=now();
            $user->save();
            return response()->json([
                'message' => 'Email Verified Successfully.',
                'user' => $user,
            ], 200);
        }
        else{
            return response()->json([
                'message' => 'Incorrect Code Entered.',
            ], 200);
        }
    }
    public function sendOTP(Request $request)
    {
        $data = $request->All();
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:users,id',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $id = json_decode($data["id"]);
        $user = User::find($id);
        $code=$this->randGen();
        $this->code=$code;
        Mail::to($user->email)->send(new TestMail($code));
        $user->update([
            'email_verification_code' =>$code,
        ]);
        return response()->json([
            'message' => 'Code Sent Successfully.',
            'user' => $user,
            'code' => $this->code,
        ], 200);
    }

    public function forgetPassword(Request $request){
        $data = $request->All();
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:users,id',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $id = json_decode($data["id"]);
        $user = User::find($id);
        $user->password=Hash::make($request->password);
        $user->save();
        return response()->json([
            'message' => 'Password Reset Successfully.',
        ], 200);
    }

    public function resetPassword(Request $request){
        $data = $request->All();
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|exists:users,id',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $id = json_decode($data["id"]);
        $user = User::find($id);
        if(!$user){
            return response()->json(['message'=>'User doesnot Exists'],200);
        }
        if(Hash::check($request->old_password, $user->password)){
            $user->password=Hash::make($request->password);
            $user->save();
        }
        return response()->json([
            'message' => 'Password Reset Successfully.',
        ], 200);
    }
}
