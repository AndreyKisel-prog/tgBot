<?php

namespace App\Http\Controllers;

use App\Http\Requests\UrlRequest;
use App\Models\Url;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;
use App\Services\UrlService;

class UrlController extends Controller
{
    public function __construct(private UrlService $urlService) {}

    /**
     * @param string $id
     * @return RedirectResponse|Response
     */
    public function redirect(string $id): Response|RedirectResponse
    {
        $url = Url::whereId($id)->first();

        if (empty($url)) {
            return response()->view('not-found');
        }

        return Redirect::to($url->url);
    }

    /**
     * @param UrlRequest $request
     * @return Application|ResponseFactory|Response
     */
    public function create(UrlRequest $request): Response|Application|ResponseFactory
    {
        return response($this->urlService->getShortUrl($request->url));
    }
}
