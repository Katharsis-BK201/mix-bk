<?php

namespace App\Http\Controllers\authentications;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Http\Exceptions\Exception;


class LoginBasic extends Controller
{
  public function index()
    {
      return view('content.authentications.auth-login-basic');
    }

  public function login(Request $request)
    {
      try {
            // Throttle check handled by middleware

            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            if (Auth::attempt($request->only('email', 'password'))) {
                $request->session()->regenerate();

                return response()->json([
                    'status' => 'success',
                    'redirect' => '/dashboard'
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred. Please try again later.'
            ]);
        }
    }



  public function logout(Request $request)
    {
      Auth::logout();
      return redirect()->route('auth-login-basic');
    }

  public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();

    }
  public function providerAuth($provider)
      {

          $providerUser = Socialite::driver($provider)->stateless()->user();

          $user = User::where('provider_id', $providerUser->id)->first();


          if(!$user){
              $user = User::create([
                  'name' => $providerUser->name,
                  'email' => $providerUser->email,
                  'provider' => $provider,
                  'provider_id' => $providerUser->id,
                  'password' => bcrypt('password'),
              ]);
              Auth::login($user);

              $user = Auth::user();

              return redirect()->route('dashboard-analytics');
          }
              Auth::login($user);
              return redirect()->route('dashboard-analytics');

      }

}
