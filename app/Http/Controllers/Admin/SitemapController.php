<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Sitemap\SitemapGenerator;
use App\Models\Language;
use App\Models\Sitemap;
use Illuminate\Support\Facades\Session;

class SitemapController extends Controller
{
    public function index(Request $request){
        $data['langs'] = Language::all();
        $data['sitemaps'] = Sitemap::orderBy('id', 'DESC')->paginate(10);
        return view('admin.sitemap.index',$data);
    }

    public function store(Request $request){
        $data = new Sitemap();
        $input = $request->all();

        @mkdir(public_path('assets/front/sitemaps'), 0755, true);
        $filename = 'sitemap'.uniqid().'.xml';
        SitemapGenerator::create($request->sitemap_url)->writeToFile(public_path('assets/front/sitemaps/'.$filename));
        $input['filename']    = $filename;
        $input['sitemap_url'] = $request->sitemap_url;
        $data->fill($input)->save();

        Session::flash('success', __('Sitemap Generate Successfully'));
        return "success";
    }

    public function download(Request $request) {
        return response()->download(public_path('assets/front/sitemaps/'.$request->filename));
    }

    public function update(Request $request)
    {
        $data  = Sitemap::find($request->id);
        $input = $request->all();
        @unlink(public_path('assets/front/sitemaps/'.$data->filename));

        $filename = 'sitemap'.uniqid().'.xml';
        SitemapGenerator::create($data->sitemap_url)->writeToFile(public_path('assets/front/sitemaps/'.$filename));
        $input['filename']  = $filename;

        $data->update($input);
        Session::flash('success', __('Feed updated successfully!'));
        return back();
    }

    public function delete($id) {
    $sitemap = Sitemap::find($id);
    @unlink(public_path('assets/front/sitemaps/'.$sitemap->filename));
    $sitemap->delete();

    Session::flash('success', __('Sitemap file deleted successfully!'));
    return back();
    }
}
