<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Dingo\Api\Routing\Helpers;


class AuthenticatedUser extends Controller
{
    use Helpers;

    public function __invoke()
    {
        return $this->response->array([
            'data' =>  $this->auth->user()
        ]);
    }
}
