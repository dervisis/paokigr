<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class paokiCrawlTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'paoki:crawlTest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'paoki:crawlTest';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {


            $urlToFetch = 'https://www.paok24.com/category/podosfairo/feed';

            $userAgent = 'Googlebot/2.1 (http://www.googlebot.com/bot.html)';

            $curl = curl_init($urlToFetch);
            curl_setopt($curl, CURLOPT_USERAGENT, $userAgent);
            curl_setopt($curl, CURLOPT_AUTOREFERER, true);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1 );
            curl_setopt($curl, CURLOPT_TIMEOUT, 2 );

            $html = curl_exec( $curl );

            $html = @mb_convert_encoding($html, 'HTML-ENTITIES', 'utf-8');

            // Matching a href From News Feed
            $re = '/<title>(.*)<\/title>/m';

            preg_match_all($re, $html, $matches, PREG_SET_ORDER, 0);

            if ( count($matches) > 0 ) {
                print_r($matches);
                die();

                $torrent['torrent_file'] = $matches2[0][1];

                $re = '/<fieldset class=\'download\'><legend><b>Details<\/b><\/legend><table cellpadding=\'3\' border=\'0\' width=\'100%\'>(.*)<\/table>/sU';
                preg_match_all($re, $html2, $matches, PREG_SET_ORDER, 0);

                $details = $matches[0][1];

                $re = '/<tr><td align=\'left\'><b>Title:<\/b><\/td><td>(.*)<\/td>/sU';
                preg_match_all($re, $details, $matches, PREG_SET_ORDER, 0);

                $torrent['title'] = $matches[0][1];

                $re = '/<tr><td align=\'left\'><b>Total Size:<\/b><\/td><td>(.*)<\/td>/sU';
                preg_match_all($re, $details, $matches, PREG_SET_ORDER, 0);

                $torrent['size'] = $matches[0][1];

                $re = '/<tr><td align=\'left\'><b>Info Hash:<\/b><\/td><td>(.*)<\/td>/sU';
                preg_match_all($re, $details, $matches, PREG_SET_ORDER, 0);

                $torrent['info_hash'] = $matches[0][1];

                $re = '/<tr><td align=\'left\'><b>Date Added:<\/b><\/td><td>(.*)<\/td>/sU';
                preg_match_all($re, $details, $matches, PREG_SET_ORDER, 0);

                $torrent['added_at'] = $matches[0][1];

                $updateComic = DB::table('comics')
                    ->where('id', $unprocessedTorrentLink->id)
                    ->update([
                        'title'             => $torrent['title'],
                        'size'              => $torrent['size'],
                        'info_hash'         => $torrent['info_hash'],
                        'torrent_file'      => $torrent['torrent_file'],
                        'torrent_processed' => 1,
                        'added_at'          => $torrent['added_at'],
                    ]);

                echo " SUCCESS";
            } else {
                echo " FAIL";
            }

            echo "\n";



        }
}
