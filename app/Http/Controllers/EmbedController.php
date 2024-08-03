<?php

namespace App\Http\Controllers;

use App\Models\Embed;

use Ophim\Core\Models\Movie;
use Illuminate\Support\Str;
use Ophim\Core\Models\Actor;
use Ophim\Core\Models\Category;
use Ophim\Core\Models\Director;
use Ophim\Core\Models\Episode;
use Ophim\Core\Models\Region;
use Ophim\Core\Models\Tag;
use Illuminate\Support\Facades\DB;


class EmbedController extends Controller
{
   public function index($id)
   {
        $embed = Embed::where('embed_id', $id)->firstOrFail();
        return view('welcome', ['url' => $embed->embed_url]);
   }

   function getYear($slug)
   {
        preg_match_all('/\d+/', $slug, $matches);

        $valid_year = null;
        foreach ($matches[0] as $number) {
            if ($number > 2000 && $number < 2025) {
                $valid_year = $number;
                break;
            }
        }

        return $valid_year;
   }

   public function crawler()
   {
     set_time_limit(0);
     ini_set('memory_limit', '-1');

      $json = file_get_contents(base_path('data_crawler/movies_updated.json'));
      $payloads = json_decode($json, true);

      $payloads =  array_reverse($payloads);

      foreach ($payloads as $payload) 
      {
        if (empty($payload['slug'])) {
            continue;
        }
        // DB::beginTransaction();

        try {
            $movie = Movie::where('slug', $payload['slug'])->first();

            if ($movie) {
                // $movie->language = !empty($payload['soundsub']) ? $payload['soundsub'] : 'TH/EN';
               
                // $movie->save();
                // DB::commit();
                continue;
            } else {
                if (count($payload['episodes']) > 1) {
                    $type = 'series';
                    $espisodeCurrent = count($payload['episodes']);
                    $espisodeTotal = count($payload['episodes']);
                } else {
                    $type = 'single';
                    $espisodeCurrent = null;
                    $espisodeTotal = null;
                }
                
                $img = explode("/", $payload['img']);
                $linkImg = '';

                if (count($img) > 1) {
                    $linkImg = end($img);
                    $linkImg = "/storage/images/" . $linkImg;
                }
                $slugNew = Str::slug($payload['name']);

                $movie = Movie::create([
                    'name' => $payload['name'],
                    'slug' => $slugNew,
                    'origin_name' => $payload['name'],
                    'content' => $payload['description'] ?? '',
                    'status' => 'completed',
                    'trailer_url' => $payload['trailer'] ?? '',
                    'thumb_url' => $linkImg,
                    'poster_url' => $linkImg,
                    'type' => $type,
                    'quality' => $payload['status'] ?: 'HD',
                    'language' => !empty($payload['soundsub']) ? $payload['soundsub'] : 'TH/EN',
                    'publish_year' => $this->getYear($slugNew),
                    'episode_current' => $espisodeCurrent,
                    'episode_total' => $espisodeTotal,
                ]);
            }

            $this->syncActors($movie, $payload);
            $this->syncDirectors($movie, $payload);
            $this->syncCategories($movie, $payload);
            $this->syncRegions($movie, $payload);
            $this->syncTags($movie, $payload);
            $this->updateEpisodes($movie, $payload);

            DB::commit();
        } catch (\Exception $e) {
            // DB::rollback();
            dd($payload, $e);
            continue;
        }
      }

   }

   protected function syncActors($movie, array $payload)
   {
       if (!isset($payload['actors']) || empty($payload['actors'])) return;

       $actors = explode(",", $payload['actors']);
       $data = [];

       foreach ($actors as $actor) {
           $data[] = Actor::firstOrCreate(['name' => trim($actor)])->id;
       }

       $movie->actors()->sync($data);
   }

   protected function syncDirectors($movie, array $payload)
   {
       if (!isset($payload['director']) || empty($payload['director'])) return;

       $directors = explode(",", $payload['director']);
       $data = [];
       foreach ($directors as $director) {
           if (!trim($director)) continue;
           $data[] = Director::firstOrCreate(['name' => trim($director)])->id;
       }
       $movie->directors()->sync($data);
   }

   protected function syncCategories($movie, array $payload)
   {
       if (!isset($payload['genres']) || empty($payload['genres'])) return;

       $genres = $payload['genres'];
       $data = [];

       foreach ($genres as $category) {
           if (!trim($category)) continue;
           $data[] = Category::firstOrCreate(['name' => trim($category)])->id;
       }

       $movie->categories()->sync($data);
   }

   protected function syncRegions($movie, array $payload)
   {
       if (!isset($payload['country']) || empty($payload['country'])) return;

       $countrys = explode(",", $payload['country']);
       $data = [];
       foreach ($countrys as $region) {
           if (!trim($region)) continue;

           $data[] = Region::firstOrCreate(['name' => trim($region)])->id;
       }
       $movie->regions()->sync($data);
   }

   protected function syncTags($movie, array $payload)
   {
       if (!isset($payload['tags']) || empty($payload['tags'])) return;

       $tags = $payload['tags'];
       $tags[] = 'ดูหนัง';
       $tags[] = 'ดูหนังออนไลน์';
       $tags[] = 'เข้าป่าหาชีวิต';
       $tags[] = 'ดูหนัง-' . $movie['slug'];

       $string = preg_replace('/[0-9]/', '', $movie['slug']);
       
       $string = str_replace('-', '', $string);
       $tags[] = $string;

       $data = [];
       foreach ($tags as $tag) {
        if (!trim($tag)) continue;

          $data[] = Tag::firstOrCreate(['name' => trim($tag)])->id;
        }

       $movie->tags()->sync($data);
   }

   protected function syncStudios($movie, array $payload)
   {
       if (!in_array('studios', $payload)) return;
   }

   protected function updateEpisodes($movie, $payload)
   {
       if (!isset($payload['episodes'])) return;
       $number = 1;

       foreach ($payload['episodes'] as $episode) {
           $id = explode('/', $episode['url']);
           $slug = Str::slug($episode['slug']);

           $link = explode("/", $episode['url']);
            $linkEncodeId = '';
            $linkEncodeUrl = '';

           if (count($link) > 1) {
                $linkEncodeUrl = end($link);
                $linkEncodeId = md5($linkEncodeUrl);
           }

           Episode::create([
               'id' => end($id) ?? null,
               'name' => "ตอนที่ $number",
               'movie_id' => $movie->id,
               'server' => "VIP#1",
               'type' => 'embed',
               'link' => $linkEncodeId,
               'slug' => $slug
           ]);
           $number++;
            try {
                Embed::create([
                    'embed_id' => $linkEncodeId,
                    'embed_url' => "playlist/$linkEncodeUrl"
               ]);
            } catch (\Exception $e) {
               return false;
            }
        
       } 
   }
}
