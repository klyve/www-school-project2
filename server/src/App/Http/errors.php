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

    'playlist' => [
        'not_authorized' => [
            "error" => 1,
            "message" => Language::get('errors.playlist.not_authorized'),
            "code" => 401,
        ],
        'tag_conflict' => [
            "error" => 2,
            "message" => Language::get('errors.playlist.tag_conflict'),
            "code" => 409, 
        ],
        'not_found' => [
            "error" => 3,
            "message" => Language::get('errors.playlist.not_found'),
            "code" => 404, 
        ],

        'sql_insert_error' => [
            'error' => 8,
            'message' => Language::get('errors.playlist.sql_insert_error'),
            'code' => 500
        ],
    ],



    'not_found' => [
        "error" => 1,
        "message" => Language::get('errors.not_found'),
        "code" => 404,
    ],
    'sql_insert_error' => [
        'error' => 2,
        'message' => Language::get('errors.sql_insert_error'),
        'code' => 500
    ],
    'id_mismatch' => [
        'error' => 3,
        'message' => Language::get('errors.id_mismatch'),
        'code' => 400
    ],
];