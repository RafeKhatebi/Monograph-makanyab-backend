<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactMessageController extends Controller
{
    public function index(Request $request): View
    {
        $messages = ContactMessage::query()
            ->with('user:id,name,email')
            ->when($request->search, function ($query, string $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('telephone', 'like', "%{$search}%")
                        ->orWhere('subject', 'like', "%{$search}%");
                });
            })
            ->when($request->status === 'unread', fn ($query) => $query->whereNull('read_at'))
            ->when($request->status === 'read', fn ($query) => $query->whereNotNull('read_at'))
            ->when(
                $request->status === 'archived',
                fn ($query) => $query->whereNotNull('archived_at'),
                fn ($query) => $query->whereNull('archived_at')
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.contact-messages.index', compact('messages'));
    }

    public function show(ContactMessage $contactMessage): View
    {
        $contactMessage->markAsRead();

        return view('admin.contact-messages.show', [
            'message' => $contactMessage->load('user:id,name,email'),
        ]);
    }

    public function markUnread(ContactMessage $contactMessage): RedirectResponse
    {
        $contactMessage->forceFill(['read_at' => null])->save();

        return back()->with('success', 'Contact message marked as unread.');
    }

    public function archive(ContactMessage $contactMessage): RedirectResponse
    {
        $contactMessage->archive();

        return back()->with('success', 'Contact message archived.');
    }

    public function restore(ContactMessage $contactMessage): RedirectResponse
    {
        $contactMessage->restoreFromArchive();

        return back()->with('success', 'Contact message restored.');
    }

    public function destroy(ContactMessage $contactMessage): RedirectResponse
    {
        $contactMessage->delete();

        return redirect()
            ->route('admin.contact-messages.index')
            ->with('success', 'Contact message deleted.');
    }
}
