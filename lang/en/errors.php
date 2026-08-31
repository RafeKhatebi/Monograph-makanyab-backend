<?php

return [
    '403' => [
        'title' => '403 Forbidden',
        'description' => 'You do not have permission to access this page.',
        'action' => 'Go to Home',
    ],
    '404' => [
        'title' => '404 Not Found',
        'description' => 'Sorry, the page you are looking for could not be found.',
        'action' => 'Go to Home',
    ],
    '419' => [
        'title' => '419 Page Expired',
        'description' => 'Your session has expired. Please refresh the page and try again.',
        'action' => 'Try Again',
    ],
    '429' => [
        'title' => '429 Too Many Requests',
        'description' => 'Too many requests were sent in a short time. Please wait and try again.',
        'action' => 'Go to Home',
    ],
    '500' => [
        'title' => '500 Server Error',
        'description' => 'Something went wrong on our side. Please try again later.',
        'action' => 'Go to Home',
    ],
    '505' => [
        'title' => '505 Server Error',
        'description' => 'Sorry, there is an error with the server.',
        'action' => 'Go to Home',
    ],
];
