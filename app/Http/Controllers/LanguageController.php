<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;

class LanguageController extends Controller
{
    /**
     * Set the application locale.
     *
     * @param  string  $locale
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setLocale($locale)
    {
        \Log::info("LanguageController switching to: " . $locale);
        if (in_array($locale, ['en', 'ar'])) {
            Session::put('locale', $locale);
            App::setLocale($locale);
        }

        return redirect()->back();
    }
}
