<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateLandingContentRequest;
use App\Services\LandingContentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LandingPageAdminController extends Controller
{
    public function edit(LandingContentService $service): View
    {
        return view('admin.landing.edit', $service->adminPayload());
    }

    public function update(UpdateLandingContentRequest $request, LandingContentService $service): RedirectResponse
    {
        $service->update($request->validated());

        return redirect()->route('admin.landing.edit')->with('status', 'Landing page content updated.');
    }
}
