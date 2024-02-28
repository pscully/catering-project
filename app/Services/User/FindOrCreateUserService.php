<?php

namespace App\Services\User;

use App\Actions\Fortify\CreateNewUser;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class FindOrCreateUserService
{
    public function run(array $customerDetails): User
    {

        $user = User::where('email', $customerDetails['email'])->first();

        if (!$user) {

            $randomPassword = Str::random(10);

            $userData = [
                'first_name' => $customerDetails['first_name'],
                'last_name' => $customerDetails['last_name'],
                'email' => $customerDetails['email'],
                'phone_number' => $customerDetails['phone_number'],
                'password' => $randomPassword,
                'password_confirmation' => $randomPassword,
            ];

            $creator = new CreateNewUser();
            $user = $creator->create($userData);
            // TODO: Implement a secure way to inform the user about their account and password

            event(new Registered($user));

        }

        return $user;
    }
}
