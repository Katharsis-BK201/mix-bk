<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

class RegisterBasic extends Controller
{
  public function index()
  {
    return view('content.authentications.auth-register-basic');
  }
  public function register(Request $request)
  {
    // dd($request->all());
    $validator = Validator::make($request->all(), [
      'name' => 'required|string|max:255',
      'email' => 'required|string|email|max:255',
      'password' => 'required|string|min:6',
    ]);
    $user = User::create([
      'name' => $request->name,
      'email' => $request->email,
      'password' => Hash::make($request->password),
    ]);


      return response()->json(['status' => 'success', 'message' => 'Registration successful'], 200);

    return redirect()->route('tables-basic')->with('success', 'Registration successful');
  }
  public function tableIndex()
    {
        $users = User::all(); // Fetch all users
        return view('content.tables.tables-basic', compact('users'));
    }

  public function updateUser(Request $request, $id){
    try{
      $decryptedId = Crypt::decrypt($id);
      $user = User::findOrFail($decryptedId);

      $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email',
      ]);

      $user->update([
        'name' => $request->name,
        'email' => $request->email,
      ]);

      return response()->json(['status' => 'success', 'message' => 'User updated successfully'], 200);

    }catch(\Exception $e){
      return response()->json(['status' => 'error', 'message' => 'User not found'], 404);
    }
  }

}
