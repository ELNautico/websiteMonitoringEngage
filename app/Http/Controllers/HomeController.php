<?php

namespace App\Http\Controllers;

use App\Events\CheckSite;
use App\Models\Url;
use App\Services\UrlChecker;
use Illuminate\Validation\Rule;


class HomeController
{
    public function index(UrlChecker $urlChecker)
    {
        return view('index', [
            'urls' => Url::all(),
        ]);
    }

    public function store(){
        // validate Request, must be a unique url to avoid doubles
        $attributes = request()?->validate([
            'url' => ['required', 'url' , Rule::unique('urls', 'url')]
        ]);

        $newUrl = Url::create($attributes);
        event(new CheckSite($newUrl));

        return redirect('/');
    }

    public function destroy($id){
        Url::find($id)->delete();
        return redirect('/');
    }

    public function update($id){
        // For some reason $url = ID of the Url?
        $url = Url::find($id);
        event(new CheckSite($url));
        return redirect('/');
    }

    public function updateAll(){
        $urls = Url::all();
        $urls->each(fn($url) => event(new CheckSite($url)));
        return redirect('/');
    }
}
