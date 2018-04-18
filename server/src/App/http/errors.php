<?php
use MVC\Core\Language;

$errors = [

    'user' => [
        'email_exists' => [
            "error" => 1,
            "message" => Language::get('errors.user.email_exists'),
            "code" => 409,
        ],
        'invalid_password' => [
            'error' => 2,
            "message" => "Invalid password",
            "code" => 400,
        ],
        'authentication_required' => [
            'error' => 3,
            "message" => "Authentication required",
            "code" => 401,
        ],
    ],


];