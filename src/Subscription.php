<?php

namespace App;

class Subscription
{

    public function __construct(protected Gateway $gateway, protected Mailer $mailer)
    {
    }
    public function create(User $user)
    {
        $receipt = $this->gateway->create();
        $user->markAsSubscribed();
        $this->mailer->deliver('Your receipt number is: ' . $receipt);
    }
}
