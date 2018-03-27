<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use DB;

class PassportController extends Controller
{

    public $successStatus = 200;

    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login() {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')->accessToken;
            return response()->json(['success' => $success], $this->successStatus);
        }
        else {
            return response()->json(['error'=>'Unauthorised'], 401);
        }
    }

    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'age' => 'required|numeric',
            'telephon' => 'required|regex:/[0-9]{10}/',
            'adress' => 'required',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        return response()->json(['success' => $this->createUser($request)], $this->successStatus);
    }

    /**
     * details api
     * Check email and create an user
     * @return User
     */
    private function createUser(Request $request) {
      $input = $request->all();
      $input['password'] = bcrypt($input['password']);
      $result = DB::select('select * from users where email = :email LIMIT 1', ['email' => $input['email']]);
      if (empty($result)) {
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')->accessToken;
        $success['name'] =  $user->name;
      } else {
        $success['error'] =  "Email already taken";
      }
      return $success;
    }

    /**
     * details api
     *
     * @return \Illuminate\Http\Response
     */
    public function getDetails()
    {
        $user = Auth::user();
        return response()->json(['users' => User::all()], $this->successStatus);
    }

    /**
     * details api
     *
     * @return \Illuminate\Http\Response
     */
    public function getUser()
    {
        $user = Auth::user();
        return response()->json(['user' => $user, 'resto' => $user->resto()->get()], $this->successStatus);
    }

    /**
     * delete user
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteUser()
    {
        $user = Auth::user();
        $restos = $user->resto()->get();
        foreach ($restos as $resto)
          $resto->menus()->delete();
        $user->avis()->delete();
        $user->resto()->delete();
        if ($user->delete())
          return response()->json(['success' => 'User delete with success'], $this->successStatus);
        else
          return response()->json(['error' => 'Try again'], 400);
    }
}
