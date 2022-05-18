<?php

namespace App\Http\Controllers;

use App\Services\CdnService;
use Illuminate\Http\Request;

class DoSpacesController extends Controller
{
    private $cdnService;

    public function __invoke(Request $request)
    {
        //
    }

    public function __construct(CdnService $cdnService)
    {
        $this->cdnService = $cdnService;
    }
}
