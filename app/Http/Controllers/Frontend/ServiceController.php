<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Service;

class ServiceController extends Controller
{
    public function show(Service $service)
    {
        abort_if(! $service->is_active, 404);

        $service->load(['category', 'media']);

        return view('pages.services.show', compact('service'));
    }
}
