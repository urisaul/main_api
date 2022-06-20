<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ScrapeController extends Controller
{
    public function scrape_url(Request $request)
    {
        if ($url = $request->query("url_to_scrape")) {
            $content = Http::withHeaders([
                'User-Agent' => 'foo',
                'X-Second' => 'bar'
            ])->get($url)->body();
            libxml_use_internal_errors(true);
            $doc = new \DOMDocument();
            $doc->loadHTML($content);
            $xpath = new \DOMXPath($doc);

            $targets = [
                "target_1" => '//div[@class="docs_sidebar"]//li//h2',
                "target_2" => '//nav[@id="indexed-nav"]',
            ];

            $results = [];

            foreach ($targets as $target => $target_acc) {
                $titles = $xpath->evaluate($target_acc);
                $extractedTitles = [];
                foreach ($titles as $title) {
                    $extractedTitles[] = $title->textContent.PHP_EOL;
                }
                $results[$target] = $extractedTitles;
            }


            $res = [
                // "page_content" => $content,
                "extracted_data" => $results,
                "url" => $url,
            ];
            return $res;
        }
        return true;
    }
}
