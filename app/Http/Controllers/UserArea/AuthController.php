<?php
/**
 * AuthController.php
 *
 * @copyright Chongyi <xpz3847878@163.com>
 * @link      https://insp.top
 */

namespace App\Http\Controllers\UserArea;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function loginRequest(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|exists:users',
            'password' => 'required',
        ]);

        if (auth()->attempt($request->only(['email', 'password']))) {
            if ($request->ajax()) {
                return [];
            }

            //
        }

        //
    }

    public function logoutRequest()
    {
        auth()->logout();
    }
}