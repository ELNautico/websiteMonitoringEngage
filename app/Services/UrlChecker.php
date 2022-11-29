<?php

namespace App\Services;

use App\Models\Url;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Throwable;

class UrlChecker{
    public function checkUrlStatus(Url $url){
        // as soon as the process starts, set start time
        $startTime = microtime(true);

        try {
            $response = Http::timeout(5)->get($url->url);
            // reset Status for every new check
            $this->resetActiveStatus($url->url);
            $this->totalTime($url->url, $startTime);

            // Does HTML get found? -> set new status
            if($this->checkIfQueryExists($url) === true){
                $this->updateOnSuccessQuery($url->url);
            }else{
                $this->updateOnFailedQuery($url->url);
            }

            $this->updateLastChecked($url->url);
            return $response->ok();

        }catch (Throwable $e){
            // If anything fails, set to false.
            $this->updateOnFailedHTTP($url->url);
            $this->updateOnFailedQuery($url->url);
        }
        return $response->ok();
    }

    public function getTotalTime($startTime){
        return microtime(true) - $startTime;
    }

    public function resetActiveStatus(string $url){
        DB::table('urls')
            ->where('url', $url)
            ->update(['active' => 1]);
    }

    public function updateOnFailedHTTP(string $url){
        DB::table('urls')
            ->where('url', $url)
            ->update(['requestTime' => 5]);

        DB::table('urls')
            ->where('url', $url)
            ->update(['active' => 0]);

        $this->updateLastChecked($url);
    }

    public function updateOnFailedQuery(string $url){
        DB::table('urls')
            ->where('url', $url)
            ->update(['foundQuery' => 0]);
    }

    public function updateOnSuccessQuery(string $url){
        DB::table('urls')
            ->where('url', $url)
            ->update(['foundQuery' => 1]);
    }

    public function updateLastChecked(string $url){
        DB::table('urls')
            ->where('url', $url)
            ->update(['updated_at' => now()]);
    }

    public function totalTime(string $url, $startTime){
        DB::table('urls')
            ->where('url', $url)
            ->update(['requestTime' => $this->getTotalTime($startTime)]);
    }
    public function checkIfQueryExists(Url $url){
        $html = file_get_contents($url->url);
        return str_contains($html, $url->searchQ);
    }
}
