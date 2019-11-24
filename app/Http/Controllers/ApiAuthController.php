<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class ApiAuthController extends Controller
{

    public function login(Request $request)
    {
    $this->validateLogin($request);

    $http = new Client;

    $response = $http->post(url('oauth/token'), [
        'form_params' => [
            'grant_type' => 'password',
            'client_id' => '3',
            'client_secret' => 'HOI7DINvHqhdpWjR3okcrD3KZG26l8W2mc0xkr58',
            'username' => $request->email,
            'password' => $request->password,
            'scope' => '',
        ],
    ]);

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
            'email' => 'required|string',
            'password' => 'required|string',
        ]);
    }
}
