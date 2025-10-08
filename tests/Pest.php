<?php

declare(strict_types=1);

use Outerweb\FilamentSettings\Tests\Fixtures\Models\User;
use Outerweb\FilamentSettings\Tests\TestCase;

uses(TestCase::class)
    ->in(__DIR__)
    ->beforeEach(function () {
        /** @var User $user */
        $user = User::factory()->create();

        /** @var TestCase $this */
        $this->actingAs($user);
    });
