<?php

use App\Http\Controllers\UsersController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('users', UsersController::class);
Route::post('users/{id}', function (Request $request ,$id) {
    $request->validate([
        'name' => 'required',
        'email' => 'required|email',
    ]);

    $user = User::find($id);

    if ($request->name)
        $user->name = $request->name;
    if ($request->email)
        $user->email = $request->email;
    if ($request->password)
        $user->password = $request->password;

    if ($request->hasFile('image')) {
        $imageName = time().'.'.$request->image->extension();
        $request->image->move(public_path('images'), $imageName);

        $user->image_url = $imageName;

        $user->save();

        return response()->json(['message' => "Uploaded and saved"]);
    }

    $user->save();

    return response()->json(['message' => 'Updated a user without image']);
});
