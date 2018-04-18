<?php

$config = [
    'database' => [
        'host' => '127.0.0.1',
        'database' => 'mvc',
        'username' => 'root',
        'password' => ''
    ],
    'token' => [
        'secret' => 'wgh%#Q(%!=!?#%(!i0eohjweopghj=!#%)w2eoghp2q=3gjh9023',
        'expiration' => date("Y-m-d H:i:s", strtotime('+24 hours', strtotime((new DateTime())->format('Y-m-d H:i:s')))),
        'issuer' => 'KrusKontroll.com',
    ],
    'usergroups' => [
      'student' => 1,
      'lecturer' => 2,
      'moderator' => 3,
      'admin' => 4,
      'owner' => 5
    ],
    'defaults' => [
        'language' => [
            'default' => 'en',
            'path' => 'app/locales',
            'available' => [
                [
                    'name' => 'English',
                    'key' => 'en'
                ],[
                    'name' => 'Norwegian',
                    'key' => 'no'
                ]
            ]
        ],
        'view' => [
            'path' => 'Views',
            'engine' => 'twig'
        ]
    ],
    'filepaths' => [
        'images' => 'public/media/images',
        'videos' => 'public/media/videos'
    ],
    'usergroups' => [
        'owner' => 5,
        'admin' => 4,
        'moderator' => 3,
        'lecturer' => 2,
        'user' => 1
    ]
];
