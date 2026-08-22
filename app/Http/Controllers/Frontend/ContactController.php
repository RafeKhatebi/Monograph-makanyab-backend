<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\StoreContactRequest;
use App\Models\ContactMessage;

class ContactController extends Controller
{
    public function index()
    {
        return view('pages.contact.index');
    }

    public function store(StoreContactRequest $request)
    {
        $validated = $request->validated();

        ContactMessage::create($validated + [
            'user_id' => auth()->id(),
        ]);

        return back()->with('success', __('messages.contact_thanks'));
    }
}
