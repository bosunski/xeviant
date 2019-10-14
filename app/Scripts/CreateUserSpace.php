<?php

namespace App\Scripts;

use App\User;
use Throwable;

class CreateUserSpace extends Script
{
    /**
     * @var User
     */
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return string
     * @throws Throwable
     */
    public function script(): string
    {
       return view('scripts.users.create-user-directory', ['user' => $this->user])->render();
    }
}
