<?php

namespace App;

class Subscription
{

    public function __construct(protected Gateway $gateway)
    {
    }
    public function create(User $user)
    {
        $this->gateway->create();
        $user->markAsSubscribed();
    }
}
