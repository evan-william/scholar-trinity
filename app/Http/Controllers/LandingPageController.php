<?php

namespace App\Http\Controllers;

use App\Services\LandingContentService;
use Illuminate\View\View;

class LandingPageController extends Controller
{
    public function __invoke(LandingContentService $service): View
    {
        return view('landing.index', $service->publicPayload());
    }
}
