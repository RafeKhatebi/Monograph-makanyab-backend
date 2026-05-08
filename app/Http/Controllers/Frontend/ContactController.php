<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
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

        return back()->with('success', 'Thank you for contacting us! We will get back to you soon.');
    }
}
