<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignUpRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class ApiController extends Controller
{
    public function options()
    {
        return response('OK',200);
//            ->header('Access-Control-Allow-Origin', '*')
//            ->header('Access-Control-Allow-Methods', 'POST,PUT,DELETE,OPTIONS,PATCH')
//            ->header('Access-Control-Allow-Headers', 'Content-Type,Authorization');
    }

    public function signup(SignUpRequest $request)
    {
        $user = \App\User::create($request->validated());

        return response()->json([
            'id' => $user->id
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $user = \App\User::where('phone', $request->phone)->first();

        if($user->password === $request->password) {

            $token = $user->createToken();

            return response()->json([
                'token' => $user->auth_api
            ], 201);
        }

        return response()->json([
            'login' => ['Incorrect login or password']
        ], 404);
    }

    public function logout()
    {
        $user = Auth::user();
        $user->auth_api = time();
        $user->save();

        return response('OK',200);
    }

    public function photo(Request $request)
    {
        $validator = Validator::make($request->all(), [
           'photo' => 'required|file|mimes:jpg,jpeg,png|max:2048'
        ]);

        if($validator->fails()) return response()->json($validator->errors(), 422);
        $path = $request->file('photo')->store(Auth::id());

        $photo = new \App\Photo();
        $photo->user_id = Auth::id();
        $photo->url = asset('storage/'.$path);
        $photo->path = $path;
        $photo->save();

        return response()->json($photo, 201);
    }

    public function photos(Request $request)
    {
        $photos = \App\Photo::where('user_id', Auth::id())->get();

        return response()->json($photos, 200);
    }

    public function delete(Request $request, \App\Photo $photo)
    {
        if (Gate::denies('can-delete-photo', $photo))
            throw new HttpResponseException(response('Need authorization', 403));

        Storage::delete($photo->path);
        $photo->delete();

        return response('Deleted', 204);

        return response()->json(\App\Photo::find(10)->users, 200);
    }

    public function share(Request $request, \App\User $user)
    {
//        return response('ok', 200);
        $validator = Validator::make($request->all(), [
            'photos' => 'required|array',
            'photos.*' => 'numeric|exists:photos,id'
        ]);

        if ($validator->fails()) {
            throw new HttpResponseException(response()->json($validator->errors(),401) );
        }

        $user->photos()->sync($request->photos);

        return response('ok', 200);

    }

    public function getUser(Request $request)
    {
        if(!$request->has('search'))
            return response()->json([], 400);


        $users = \App\User::where('first_name', 'LIKE', "%".$request->search."%")
            ->orWhere('surname', 'LIKE', "%".$request->search."%")
            ->orWhere('phone', 'LIKE', "%".$request->search."%")
            ->where('id', '!=', Auth::id())
            ->get();


        return response()->json(['users' => $users], 200);

    }
}
