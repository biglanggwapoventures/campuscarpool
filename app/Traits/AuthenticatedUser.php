<?php

namespace App\Traits;

use Dingo\Api\Routing\Helpers;

trait AuthenticatedUser
{
    use Helpers;

    public function user()
    {
        return $this->auth->user();
    }
}
