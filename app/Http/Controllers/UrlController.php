<?php

namespace App\Http\Controllers;

use App\Models\Url;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;

class UrlController extends Controller
{
    /**
     * @param string $id
     * @return RedirectResponse|Response
     */
    public function redirect(string $id)
    {
        $url = Url::whereId($id)->first();

        if (empty($url)) {
            return response()->view('not-found');
        }

        return Redirect::to($url->url);
    }
}
