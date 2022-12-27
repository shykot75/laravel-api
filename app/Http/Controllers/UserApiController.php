<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class UserApiController extends Controller
{
    public function showUser($id= null){
        if($id == null){
            $users = User::all();
            return response()->json(['users' => $users], 200);
        }
        else{
            $users = User::find($id);
            return response()->json(['users' => $users], 200); // 200 er mane success
        }
    }


    public function storeUser(Request $request){

        $data = $request->all();

        $rules = [
            'name' => 'required',
            'email' => 'required|email:rfc,dns|email|unique:users',
            'password' => 'required|min:8|max:25',
        ];
        $customMessage = [
            'name.required' => 'Name Should Required',
            'email.required' => 'Email Should Required',
            'email.unique' => 'Email should be unique',
            'password.required' => 'Password Should Required',
        ];

        $validator = Validator::make($data,$rules,$customMessage);

        if($validator->fails()){
            return response()->json($validator->errors(),422); // 422 er mane kono validation error paile kaj korbe
        }


        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();
        $message = 'User Created Successfully.';
        return response()->json(['message' => $message],201); // 201 means created
    }


    public function storeMultiUser(Request $request){

        $data = $request->all();

        $rules = [
            'users.*.name' => 'required',
            'users.*.email' => 'required|email:rfc,dns|email|unique:users',
            'users.*.password' => 'required|min:8|max:25',
        ];
        $customMessage = [
            'users.*.name.required' => 'Name Should Required',
            'users.*.email.required' => 'Email Should Required',
            'users.*.email.unique' => 'Email should be unique',
            'users.*.password.required' => 'Password Should Required',
        ];

        $validator = Validator::make($data,$rules,$customMessage);

        if($validator->fails()){
            return response()->json($validator->errors(),422); // 422 er mane kono validation error paile kaj korbe
        }

        foreach($data['users'] as $newUser){
            $user = new User();
            $user->name = $newUser['name'];
            $user->email = $newUser['email'];
            $user->password = bcrypt($newUser['password']);
            $user->save();
            $message = 'Users Created Successfully.';
        }
        return response()->json(['message' => $message],201); // 201 means created


    }


    public function updateUser(Request $request, $id){

        $data = $request->all();

        $rules = [
            'name' => 'required',
//            'email' => 'required|email:rfc,dns|email|unique:users',
            'password' => 'required|min:8|max:25',
        ];
        $customMessage = [
            'name.required' => 'Name Should Required',
//            'email.required' => 'Email Should Required',
//            'email.unique' => 'Email should be unique',
            'password.required' => 'Password Should Required',
        ];

        $validator = Validator::make($data,$rules,$customMessage);

        if($validator->fails()){
            return response()->json($validator->errors(),422); // 422 er mane kono validation error paile kaj korbe
        }


        $user = User::findOrFail($id);
        $user->name = $request->name;
//        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();
        $message = 'User Updated Successfully.';
        return response()->json(['message' => $message],202); // 202 means Updated
    }


    public function updateUserSingleRecord(Request $request, $id){

        $data = $request->all();

        $rules = [
            'name' => 'required',
//            'email' => 'required|email:rfc,dns|email|unique:users',
//            'password' => 'required|min:8|max:25',
        ];
        $customMessage = [
            'name.required' => 'Name Should Required',
//            'email.required' => 'Email Should Required',
//            'email.unique' => 'Email should be unique',
//            'password.required' => 'Password Should Required',
        ];

        $validator = Validator::make($data,$rules,$customMessage);

        if($validator->fails()){
            return response()->json($validator->errors(),422); // 422 er mane kono error paile kaj korbe
        }


        $user = User::findOrFail($id);
        $user->name = $request->name;
//        $user->email = $request->email;
//        $user->password = bcrypt($request->password);
        $user->save();
        $message = 'User Updated Successfully for single record.';
        return response()->json(['message' => $message],202); // 202 means Updated
    }


    public function deleteUser($id){
        $user = User::findOrFail($id);
        $user->delete();
        $message = 'User Deleted Successfully.';
        return response()->json(['message' => $message],200);
    }

    public function deleteUserJson(Request $request){
        User::where('id',$request->id)->delete();
        $message = 'User Deleted Successfully.';
        return response()->json(['message' => $message],200);
    }

    public function deleteMultipleUser($ids){
        $ids = explode(',' , $ids);
        User::whereIn('id',$ids)->delete();
        $message = 'Multi User Deleted Successfully.';
        return response()->json(['message' => $message],200);
    }
    public function deleteMultipleUserJson(Request $request){
        $data = $request->all();
        User::whereIn('id',$data['ids'])->delete();
        $message = 'Multiple User Deleted Successfully with json.';
        return response()->json(['message' => $message],200);
    }

    public function SecureStoreUser(Request $request){

        $data = $request->all();
        $rules = [
            'name' => 'required',
            'email' => 'required|email:rfc,dns|email|unique:users',
            'password' => 'required|min:8|max:25',
        ];
        $customMessage = [
            'name.required' => 'Name Should Required',
            'email.required' => 'Email Should Required',
            'email.unique' => 'Email should be unique',
            'password.required' => 'Password Should Required',
        ];
        $validator = Validator::make($data,$rules,$customMessage);
        if($validator->fails()){
            return response()->json($validator->errors(),422); // 422 er mane kono validation error paile kaj korbe
        }


        $header = $request->header('Authorization');
        if($header == null){
            $message = 'Authorization Required';
            return response()->json(['message' => $message],201); // 201 means created
        }
        else{
            if($header == 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJuYW1lIjoiU2FrIiwiZW1haWwiOiJzYWtAZ21haWwuY29tIiwicGFzc3dvcmQiOiIxMjM0NTY3OCJ9.oRronowALJEbc1pS4iiTBzCSBure-FXx_VhkV7-4TD4'){
                $user = new User();
                $user->name = $request->name;
                $user->email = $request->email;
                $user->password = bcrypt($request->password);
                $user->save();
                $message = 'User Created Successfully.';
                return response()->json(['message' => $message],201); // 201 means created
            }
            else{
                $message = 'Authorization Token Invalid';
                return response()->json(['message' => $message],201); // 201 means created
            }
        }

    }


    // User Registration With Passport
    public function registerUserWithPassport(Request $request){
        $data = $request->all();

        $rules = [
            'name' => 'required',
            'email' => 'required|email:rfc,dns|email|unique:users',
            'password' => 'required|min:8|max:25',
        ];
        $customMessage = [
            'name.required' => 'Name Should Required',
            'email.required' => 'Email Should Required',
            'email.unique' => 'Email should be unique',
            'password.required' => 'Password Should Required',
        ];

        $validator = Validator::make($data,$rules,$customMessage);

        if($validator->fails()){
            return response()->json($validator->errors(),422); // 422 er mane kono validation error paile kaj korbe
        }


        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();


        if(Auth::attempt(['email' =>$data['email'], 'password' => $data['password']])){
            $user = User::where('email',$data['email'])->first();
            $access_token = $user->createToken($data['email'])->accessToken;
            User::where('email',$data['email'])->update([
                'access_token' => $access_token
            ]);
            $message = 'User Registered Successfully.';
            return response()->json([
                'message'       => $message,
                'access_token'  => $access_token
            ],201); // 201 means created
        }
        else{
            $message = 'Sorry!! Something Went Wrong..';
            return response()->json(['message' => $message],422); // 422 means error
        }

    }


    // User Login With Passport
    public function loginUserWithPassport(Request $request){
        $data = $request->all();

        $rules = [
            'email' => 'required|email:rfc,dns|email|exists:users',
            'password' => 'required|min:8|max:25',
        ];
        $customMessage = [
            'email.required' => 'Email Should Required',
            'email.exists' => 'Email does not Exist',
            'password.required' => 'Password Should Required',
        ];

        $validator = Validator::make($data,$rules,$customMessage);

        if($validator->fails()){
            return response()->json($validator->errors(),422); // 422 er mane kono validation error paile kaj korbe
        }

        if(Auth::attempt(['email' =>$data['email'], 'password' => $data['password']])){
            $user = User::where('email',$data['email'])->first();
            $access_token = $user->createToken($data['email'])->accessToken;
            User::where('email',$data['email'])->update([
                'access_token' => $access_token
            ]);
            $message = 'User Registered Successfully.';
            return response()->json([
                'message'       => $message,
                'access_token'  => $access_token
            ],201); // 201 means created
        }
        else{
            $message = 'Login Failed! Email or Password Invalid..';
            return response()->json(['message' => $message],422); // 422 means error
        }
    }

    public function showUserByAuth($id=null){
        if($id == null){
            $users = User::all();
            return response()->json(['users' => $users], 200);
        }
        else{
            $users = User::find($id);
            return response()->json(['users' => $users], 200); // 200 er mane success
        }
    }

    public function storeUserByAuth(Request $request){
        $data = $request->all();
        $rules = [
            'name' => 'required',
            'email' => 'required|email:rfc,dns|email|unique:users',
            'password' => 'required|min:8|max:25',
        ];
        $customMessage =[
            'name.required' => 'Name Should Required',
            'email.required' => 'Email Should Required',
            'email.unique' => 'Email should be unique',
            'password.required' => 'Password Should Required',
        ];
        $validator = Validator::make($data, $rules, $customMessage);
        if($validator->fails()){
            return response()->json($validator->errors(),422); // 422 er mane kono validation error paile kaj korbe
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->access_token = $user->createToken('bearer_token')->accessToken;
        $user->save();
        $message = 'User Registered  with Passport.';
        return response()->json([
            'message'       => $message,
            'access_token'  => $user->access_token
        ],201); // 201 means created
    }

    public function getUserByResource(){
        $users = User::all();
        $result = UserResource::collection($users);
        return api_response('success', 'All User Showing', $result, Response::HTTP_OK);

    }
    public function getSpecificUserByResource($id=null){
        $user = User::find($id);
//        $result = UserResource::collection($user);
//        return api_response('success', 'User Found', $result, Response::HTTP_OK);
        if($user){
            return api_response('Success', 'User Found', $user, Response::HTTP_OK);
        }
        else{
            $user = null;
            return api_response('Failed', 'User Not Found', $user, Response::HTTP_UNPROCESSABLE_ENTITY);
        }


    }





}
