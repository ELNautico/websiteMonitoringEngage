<?php

namespace App\Http\Controllers;

use App\Events\CheckSite;
use App\Models\Url;
use Illuminate\Validation\Rule;

class HomeController extends Controller
{
    public function index()
    {
        return view('index', [
            'urls' => Url::all(),
        ]);
    }

    public function store(){
        // validate Request, must be a unique url to avoid doubles
        $attributes = request()?->validate([
            'url' => ['required', 'url' , Rule::unique('urls', 'url')],
            'searchQ' => ['string', 'max:255', 'nullable'],
        ]);

        event(new CheckSite(Url::create($attributes)));
        return redirect('/');
    }

    public function destroy($id){
        Url::find($id)->delete();
        return redirect('/');
    }

    public function update($id){
        event(new CheckSite(Url::find($id)));
        return redirect('/');
    }

    public function updateAll(){
        $urls = Url::all();
        $urls->each(fn($url) => event(new CheckSite($url)));
        return redirect('/');
    }
}
