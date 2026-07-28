<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ContactMessageRequest;
use App\Models\ContactMessage;
use Illuminate\Http\JsonResponse;

class ContactMessageController extends Controller
{
    public function store(ContactMessageRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $message = ContactMessage::create($validated + [
            'user_id' => $request->user()?->id,
        ]);

        return response()->json($message, 201);
    }
}
