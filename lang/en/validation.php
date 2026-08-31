<?php

return [
    'required' => 'The :attribute field is required.',
    'email' => 'The :attribute must be a valid email address.',
    'unique' => 'The :attribute has already been taken.',
    'confirmed' => 'The :attribute confirmation does not match.',
    'current_password' => 'The password is incorrect.',
    'image' => 'The :attribute must be an image.',
    'max' => [
        'string' => 'The :attribute may not be greater than :max characters.',
        'file' => 'The :attribute may not be greater than :max kilobytes.',
    ],
    'attributes' => [
        'name' => 'name', 'username' => 'username', 'email' => 'email', 'password' => 'password',
        'current_password' => 'current password', 'profile_picture' => 'profile picture',
        'description' => 'description', 'images' => 'images',
    ],
];