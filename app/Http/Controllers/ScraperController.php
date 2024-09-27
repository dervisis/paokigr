<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Vedmant\FeedReader\Facades\FeedReader;

use App\Spiders\paokiSpider;
use RoachPHP\Roach;

use App\Models\Article;
use DB;

class ScraperController extends Controller
{
    public function sdnaScraper() {
        Roach::startSpider(paokiSpider::class);
    }

    public function index() {
        /** @var \SimplePie $f */
        $f = FeedReader::read('https://www.paok24.com/category/podosfairo/feed/');
        $result = [
            'title' => $f->get_title(),
            'description' => $f->get_description(),
            'permalink' => $f->get_permalink(),
            'link' => $f->get_link(),
            'copyright' => $f->get_copyright(),
            'language' => $f->get_language(),
            'image_url' => $f->get_image_url(),
            'author' => $f->get_author()
        ];

        foreach ($f->get_items(0, $f->get_item_quantity()) as $item) {

            $i['title'] = $item->get_title();
            $i['description'] = $item->get_description();
            $i['id'] = $item->get_id();
            $i['content'] = $item->get_content();
            $i['thumbnail'] = $item->get_thumbnail();
            $i['category'] = $item->get_category();
            $i['categories'] = $item->get_categories();
            $i['author'] = $item->get_author();
            $i['authors'] = $item->get_authors();
            $i['contributor'] = $item->get_contributor();
            $i['copyright'] = $item->get_copyright();
            $i['date'] = $item->get_date();
            $i['updated_date'] = $item->get_updated_date();
            $i['local_date'] = $item->get_local_date();
            $i['permalink'] = $item->get_permalink();
            $i['link'] = $item->get_link();
            $i['links'] = $item->get_links();
            $i['enclosure'] = $item->get_enclosure();
            $i['audio_link'] = $item->get_enclosure()->get_link();
            $i['enclosures'] = $item->get_enclosures();
            $i['latitude'] = $item->get_latitude();
            $i['longitude'] = $item->get_longitude();
            $i['source'] = $item->get_source();

            $result['items'][] = $i;
        }

        foreach ($result['items'] as $res) {
            //$res['content'] = @mb_convert_encoding($res['content'], 'HTML-ENTITIES', 'utf-8');
            $re = '/src="(.*)" class="webfeedsFeaturedVisual wp-post-image/m';

            preg_match_all($re, $res['content'], $matches, PREG_SET_ORDER, 0);

            $re2 = '/<p>(.*)<\/p>/m';

            preg_match_all($re2, $res['content'], $matches2, PREG_SET_ORDER, 0);

            $res['date'] = date("Y-m-d H:i:s", strtotime($res['date']));

            $content = "";
            foreach($matches2 as $m) {
                $content .= $m[0];
            }

            // VALIDATIONS

            $temp = Article::where('link', '=', $res['link'])->first();
            //print_r($temp);
            if ($temp === null) {
                // NEW METHOD
                $article = new Article;
                $article->title = $res['title'];
                $article->category = $res['category']->get_label();
                $article->image_url = $matches[0][1];
                $article->short_desc = $matches2[0][1];
                $article->content = $content;
                $article->link = $res['link'];
                $article->pub_date = $res['date'];
                $article->source = "paok24";
                $article->save();
                echo "Record inserted successfully.<br/>";
            } else {
                echo "Article exists\n";
            }
        }

        return true;

    }

    public function view() {
        $articles = Article::orderBy('pub_date', 'DESC')->get();

        return view('welcome', compact('articles'));
    }

}
