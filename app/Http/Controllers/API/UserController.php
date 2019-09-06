<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{



    public function __construct()
    {
      // Laravel Passport #Consuming Your API With JavaScript
      $this->middleware('auth:api');
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::latest()->paginate(5);
    }




    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = new User;

        $this->validate($request, [
          'name' => 'required|string|max:191',
          'email' => 'required|string|email|max:191|unique:users',
          'type' => 'required',
          'password' => 'required|string|min:8'
        ]);

        $user->create([
          'name' => request('name'),
          'email' => request('email'),
          'bio' => request('bio'),
          'type' => request('type'),
          'password' => Hash::make(request('password'))
        ]);
    }




    // /**
    //  * Display the specified resource.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function show($id)
    // {
    //     //
    // }




    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $this->validate($request, [
          'name' => 'required|string|max:191',
          //  email,' . $user->id  ===>  yes, it is forbidden to use an existing email, but allow only that id which email is, to make changes
          'email' => 'required|string|email|max:191|unique:users,email,' . $user->id,
          'password' => 'sometimes|required|min:8'
        ]);

        // if someone decide to update its own password, then hash this password
        if (!empty($request->password)) {
          $request->merge(['password' => Hash::make(request('password'))]);
        }

        $user->update($request->all());
    }




    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
    }




    // search form (app.js, master.blade.php, Users.vue, UserController@search, api.php)
    public function search()
    {

        if ($search = \Request::get('q')) {
          return User::where(function($query) use ($search) {
            $query->where('name','LIKE',"%$search%")->orWhere('email','LIKE',"%$search%")->orWhere('type','LIKE',"%$search%");
          })->paginate(5);
        }

        // this below solves the problem with empty search field error
        else {
          return User::latest()->paginate(5);
        }

    }


    // display profile info data (UserController@profile, Profile.vue, api.php)
    public function displayProfileInfo()
    {
        return auth()->user();
    }



    // update profile settings (UserController@updateProfileInfo, Profile.vue, api.php)
    public function updateProfileInfo(Request $request)
    {
      $user = auth()->user();

      $this->validate($request, [
        'name' => 'required|string|max:191',
        //  email,' . $user->id  ===>  yes, it is forbidden to use an existing email, but allow only that id which email is, to make changes
        'email' => 'required|string|email|max:191|unique:users,email,' . $user->id,
        'password' => 'sometimes|required|min:6'
      ]);

      // $currentPhoto = profile.png | $request->photo = base64
      $currentPhoto = $user->photo;

      if ($request->photo != $currentPhoto) {
        // example: 1553349468.png
        $name = time() . '.' . explode('/', explode(';', $request->photo)[0])[1];

        // http://image.intervention.io - it takes a base64 string and changes it to an image
        \Image::make($request->photo)->save(public_path('./img/profile/') . $name);

        auth()->user()->photo = $name;

        // delete an old picture during uploading the new
        $userPhoto = public_path('./img/profile/') . $currentPhoto;
        if (file_exists($userPhoto)) {
          // unlink() function deletes a file
          unlink($userPhoto);
        }
      }

      // if someone decide to update its own password, then hash this password
      if (!empty($request->password)) {
        $request->merge(['password' => Hash::make(request('password'))]);
      }

      $user->update($request->all());
    }



}
