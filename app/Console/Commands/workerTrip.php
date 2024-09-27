<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class workerTrip extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'worker:Trip';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'worker:Trip';

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


        $per_page = 15;

        $url = "https://www.tripadvisor.com/Restaurant_Review-g1191174-d10489143-Reviews-O_Pefkos-Porto_Koufo_Sithonia_Halkidiki_Region_Central_Macedonia.html?filterLang=ALL";

        $output = shell_exec('lynx --source '.$url);





        $reviousCountRegex = '/<span class="reviews_header_count">\((.*?)\)/m';

        //$re = '/class="partial_entry" >(.*)<\/p>/m';
        preg_match_all($reviousCountRegex, $output, $matches, PREG_SET_ORDER, 0);

        $reviewsCount = $matches[0][1];
        $pages = ceil((intval($reviewsCount))/$per_page);

        echo "Total Pages: ";
        print_r($pages);

        $urls = [];
        //Loop through pages to build array of urls
        for ($i=0; $i <=($pages*15-15); $i+=15) {
            if ($i == 0) {
                $tempUrl = "https://www.tripadvisor.com/Restaurant_Review-g1191174-d10489143-Reviews-O_Pefkos-Porto_Koufo_Sithonia_Halkidiki_Region_Central_Macedonia.html?filterLang=ALL";
            } else {
                $tempUrl = "https://www.tripadvisor.com/Restaurant_Review-g1191174-d10489143-Reviews-"."or".$i."-O_Pefkos-Porto_Koufo_Sithonia_Halkidiki_Region_Central_Macedonia.html?filterLang=ALL";
            }

            $urls[] = $tempUrl;
        }

        print_r($urls);

        echo "Waiting 5 seconds ...";
        sleep(5);

        foreach($urls as $url) {
            $output = shell_exec('lynx --display_charset=utf-8 --source '.$url);
            $re = '/<div class="quote.*noQuotes\'>(.*?)<\/span>/m';
            preg_match_all($re, $output, $matches, PREG_SET_ORDER, 0);
            echo $url;
            print_r($matches);
            sleep(3);
        }

    }
}
