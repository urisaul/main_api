<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ScrapeController extends Controller
{
    public function scrape_url(Request $request)
    {
        if ($url = $request->query("url_to_scrape")) {
            $content = Http::get($url);
            $res = [
                "page_content" => $content,
                "url" => $url,
            ];
            return $res;
        }
        return true;
    }
}
