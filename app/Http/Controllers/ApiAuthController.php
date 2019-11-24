<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use App\User;
use Illuminate\Http\Request;
use Qauth;

class ApiAuthController extends Controller
{

    public function login(Request $request)
    {
    $this->validateLogin($request);

    $oauth = User::where(['users.email' => $request->client_email, 'oauth_clients.secret' => $request->client_secret])->join('oauth_clients','users.id', '=', 'oauth_clients.user_id')->select('oauth_clients.secret', 'oauth_clients.id')->orderBy('oauth_clients.created_at', 'DESC')->first();

    if($oauth != NULL){
        $http = new Client;
        $response = $http->post(url('oauth/token'), [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => $oauth->id,
                'client_secret' => $oauth->secret,
                'username' => $request->email,
                'password' => $request->password,
                'scope' => '',
            ],
        ]);
    }
    else{
        $response = "Invalid input";
    }

    return json_decode((string) $response->getBody(), true);
}

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
            'client_email' => 'required|email',
            'client_secret' => 'required|string',
        ]);
    }
}
