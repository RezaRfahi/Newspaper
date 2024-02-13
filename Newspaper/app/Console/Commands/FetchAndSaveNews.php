<?php

namespace App\Console\Commands;

use App\Models\News;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FetchAndSaveNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news:fetch-and-save';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and save news data from APIs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {

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
            $nytResponse = Http::get('https://api.nytimes.com/svc/mostpopular/v2/viewed/1.json?api-key=5YNERjupayIWL7s9G2vPd7sdABpulxJb')->json()['results'];
            foreach ($nytResponse as $news) {
                News::create([
                    'title' => $news['title'],
                    'content' => $news['abstract'],
                    'source' => 'The New York Times',
                    'url' => $news['url'],
                    'published' => Carbon::parse($news['published_date'])->toDateTimeString(),
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Error occurred while fetching and saving news: ' . $e->getMessage());
            $this->error('An error occurred. Please check the logs for more information.');
        }
    }
}
