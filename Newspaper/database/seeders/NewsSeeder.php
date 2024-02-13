<?php

namespace Database\Seeders;

use App\Models\News;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch and save news from The Guardian API
        $guardianResponse = Http::get('https://content.guardianapis.com/search?api-key=test')->json()['response']['results'];

        foreach ($guardianResponse as $news) {
            News::create([
            'title' => $news['webTitle'],
            'content' => '', // Add content extraction logic here
            'source' => 'The Guardian',
            'url' => $news['webUrl'],
            'published' => Carbon::parse($news['webPublicationDate'])->toDateTimeString(),
        ]);
        }

        // Fetch and save news from The New York Times API
        $nytResponse = Http::get('https://api.nytimes.com/svc/archive/v1/2024/1.json?api-key=5YNERjupayIWL7s9G2vPd7sdABpulxJb')->json()['response']['docs'];
        foreach ($nytResponse as $news) {
            News::create([
            'title' => $news['headline']['main'],
            'content' => $news['lead_paragraph'],
            'source' => 'The New York Times',
            'url' => $news['web_url'],
            'published' => Carbon::parse($news['pub_date'])->toDateTimeString(),
        ]);
        }
    }
}
