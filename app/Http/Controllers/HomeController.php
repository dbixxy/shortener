<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Link;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

class HomeController extends Controller
{
    private static $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $links = Link::where('user_id',Auth::user()->id)->get();
        return view('home', ['links' => $links]);
    }

    public function show($shorten)
    {
        $link = Link::where('shorten', $shorten)->first();
        $link->views += 1;
        $link->save();

        return redirect($link->original);
    }

    public function store(Request $request)
    {

        if(empty($request->input('shorten'))){
            do{
                $shorten = $this->generateShortUrl();
                $link_count = Link::where('shorten', $shorten)->count();
            }while($link_count > 0);
            $request->replace(['shorten' => $shorten]);
        }

        $request->validate([
            'original' => 'required|active_url',
            'shorten' => 'unique:links,shorten|alpha_num|min:3|max:20',
        ]);

        $link = new Link;
        $link->user_id = Auth::user()->id;
        $link->original = $request->input('original');
        $link->shorten = $request->input('shorten');
        $link->save();

        return redirect(route('home'));
    }

    public function delete($id)
    {
        $link = Link::findOrFail($id);
        $link->delete(); //returns true/false

        return redirect(route('home'));
    }

    private function generateShortUrl() {
        // $uniqueId = uniqid();
        // $hash = md5($uniqueId);
        $shortUrl = substr(md5(time()),0,7);
        return $shortUrl;
    }
     
    public function qrCode($shorten)
    {
        return QrCode::size(200)->format('svg')->generate(env('APP_URL') . "/" . $shorten);
    }
}
