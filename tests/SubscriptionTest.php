<?php

namespace Tests;

use App\Gateway;
use App\Subscription;
use App\User;
use PHPUnit\Framework\TestCase;
use Tests\DummyGateway;

class SubscriptionTest extends TestCase
{
    /** @test */
    public function it_creates_a_stripe_subscription()
    {
        $this->markTestSkipped();
    }

    /** @test */
    public function creating_a_subscription_marks_user_as_subscribed()
    {
        $gateway = $this->createMock(Gateway::class);
        $subscription = new Subscription($gateway);
        $user = new User('Piotrek');
        $this->assertFalse($user->isSubscribed());
        $subscription->create($user);
        $this->assertTrue($user->isSubscribed());
    }
}
