<?php

namespace App\Services;

use App\Models\Url;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class UrlChecker{
    public function checkUrlStatus(Url $url){
        $startTime = microtime(true);
        $response = Http::get($url->url);

        // Check how long it takes to check the URL
        logger('(UrlChecker) TotalTime: ' . $this->getTotalTime($startTime));

        if($url->active = 0){
            $this->resetActiveStatus($url->url);
        }

        if(!$response->ok()){
            // send a Notification per Slack/ Email?
            $this->changeActiveInDB($url->url);
            $this->updateLastChecked($url->url);
            return false;
        }

        $this->updateLastChecked($url->url);
        return $response->ok();
    }

    public function getTotalTime($startTime){
        return microtime(true) - $startTime;
    }

    public function changeActiveInDB(string $url){
        // Change in DB if Error404 occurs
        //TODO: refresh the Frontend
        DB::table('urls')
            ->where('url', $url)
            ->update(['active' => 0]);
    }

    public function resetActiveStatus(string $url){
        DB::table('urls')
            ->where('url', $url)
            ->update(['active' => 1]);
    }

    public function updateLastChecked(string $url){
        $timestamp = now();
        DB::table('urls')
            ->where('url', $url)
            ->update(['updated_at' => $timestamp]);
    }
}
