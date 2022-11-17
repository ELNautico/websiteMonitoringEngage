<?php

namespace App\Services;

use App\Models\Url;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;
use Throwable;

class UrlChecker{
    public function checkUrlStatus(Url $url){
        $startTime = microtime(true);

        try {
            $response = Http::timeout(5)->get($url->url);
        }catch (Throwable $e){
            logger('Error: ' . $e);
        }

        $responseCheck = isset($response);

        if(($responseCheck === false)){
            $this->updateOnErr($url->url);
        }
        else{
            $this->resetActiveStatus($url->url);
            $this->totalTime($url->url, $startTime);

            if(!$response->ok()){
                // send a Notification per Slack/ Email?
                $this->changeActiveInDB($url->url);
                $this->updateLastChecked($url->url);
                return false;
            }
            $this->updateLastChecked($url->url);

            return $response->ok();
        }
    }

    public function getTotalTime($startTime){
        return microtime(true) - $startTime;
    }

    public function changeActiveInDB(string $url){
        // Change in DB if Error404 occurs
        DB::table('urls')
            ->where('url', $url)
            ->update(['active' => 0]);
    }

    public function resetActiveStatus(string $url){
        DB::table('urls')
            ->where('url', $url)
            ->update(['active' => 1]);
    }

    public function updateOnErr(string $url){
        DB::table('urls')
            ->where('url', $url)
            ->update(['requestTime' => 5]);

        DB::table('urls')
            ->where('url', $url)
            ->update(['active' => 0]);

        $this->updateLastChecked($url);
    }

    public function updateLastChecked(string $url){
        $timestamp = now();
        DB::table('urls')
            ->where('url', $url)
            ->update(['updated_at' => $timestamp]);
    }

    public function totalTime(string $url, $startTime){
        DB::table('urls')
            ->where('url', $url)
            ->update(['requestTime' => $this->getTotalTime($startTime)]);
    }
}
