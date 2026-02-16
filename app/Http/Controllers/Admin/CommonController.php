<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SeoSettings;
use App\Repositories\TagGallery\TagGalleryInterface;
use App\Repositories\Slider\SliderInterface;
use App\Repositories\Term\TermInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CommonController extends Controller
{
    private $slider;
    private $tag_gallery;
    private $term;

    public function __construct(
        SliderInterface $slider,
        TagGalleryInterface $tag_gallery,
        TermInterface $term
    )
    {
        $this->slider = $slider;
        $this->tag_gallery = $tag_gallery;
        $this->term = $term;
    }

    public function sliders(Request $request)
    {
        $q                           = $request->get('q');
        $col                         = $request->get('column');
        $query                       = $q && $col;
        $sliders                     = $query ? $this->slider->getByAny($col,$q) : $this->slider->getAll();
        $slider = [];


        if($request->id)
            $slider = $this->slider->getById($request->id);


        return response()->view('admin.slider.index',compact('sliders','query','q','col','slider'));
    }

    public function sliderStore(Request $request)
    {
        $attr = [
            'image_id' => $request->image_id,
            'title' => $request->title,
            'description' => $request->description,
            'color_code' => $request->color_code,
            'text_color' => $request->text_color,
            'border_bottom' => $request->border_bottom,
            'type' => $request->type,
            'url' => $request->url,
            'internal' => $request->internal,
            'device' => $request->device,
            'position' => $request->position,
            'active' => $request->active
        ];

        $slider = $this->slider->create($attr);

        Cache::forget('home-sliders-'.($request->type??0).'-'.($request->device??0));

        return redirect()->back();
    }

    public function sliderUpdate(Request $request,$id)
    {
        $attr = [
            'image_id' => $request->image_id,
            'title' => $request->title,
            'description' => $request->description,
            'color_code' => $request->color_code,
            'text_color' => $request->text_color,
            'border_bottom' => $request->border_bottom,
            'type' => $request->type,
            'url' => $request->url,
            'internal' => $request->internal,
            'device' => $request->device,
            'position' => $request->position,
            'active' => $request->active
        ];

        $slider = $this->slider->update($id,$attr);

        Cache::forget('home-sliders-'.($request->type??0).'-'.($request->device??0));


        return redirect()->back();
    }

    public function sliderDelete($id)
    {
        $delete = $this->slider->delete($id);
        Cache::forget('home-sliders-'.(0).'-'.(0));
        Cache::forget('home-sliders-'.(1).'-'.(0));
        return redirect()->back();
    }




    public function catgallary(Request $request)
    {
        $q                           = $request->get('q');
        $col                         = $request->get('column');
        $query                       = $q && $col;
        $tag_galleries                     = $query ? $this->tag_gallery->getByAny($col,$q) : $this->tag_gallery->getAll();
        $tag_gallery = [];
        $termz= $this->term->getAll();

        $terms = [];

        foreach($termz as $term){
            $terms[$term->id] = $term->name;
        }

        // dd($terms);

        if($request->id)
            $tag_gallery = $this->tag_gallery->getById($request->id);


        return response()->view('admin.catgallary.index',compact('tag_galleries','query','q','col','tag_gallery','terms'));
    }

    public function catgallaryStore(Request $request)
    {
        $attr = [
            'image_id' => $request->image_id,
            'category_id' => $request->category_id,
            'url' => $request->url,
            'url_type' => $request->url_type,
            'active' => $request->active
        ];

        $tag_gallery = $this->tag_gallery->create($attr);

        $term = $this->term->getById($request->category_id);

        if($term){
            Cache::forget('home-tag-gallery-'.$term->seo_url);
            Cache::forget('home-tag-gallery-');
        }

        return redirect()->back();
    }

    public function catgallaryUpdate(Request $request,$id)
    {
        $attr = [
            'image_id' => $request->image_id,
            'category_id' => $request->category_id,
            'url' => $request->url,
            'url_type' => $request->url_type,
            'active' => $request->active
        ];

        $tag_gallery = $this->tag_gallery->update($id,$attr);

        $term = $this->term->getById($request->category_id);

        if($term){
            Cache::forget('home-tag-gallery-'.$term->seo_url);
            Cache::forget('home-tag-gallery-');
        }

        return redirect()->back();
    }

    public function catgallaryDelete($id)
    {
        $delete = $this->tag_gallery->delete($id);

        return redirect()->back();
    }

    public function seoSettings(Request $r){
        $seo_meta = [
            'meta_title' => $r->meta_title ?? null,
            'meta_description' => $r->meta_description ?? null,
            'meta_keywords' => $r->meta_keywords ?? null,
        ];
        $attr = [
            'post_id' => $r->post_id,
            'post_type' => $r->post_type,
            'seo_meta' => json_encode($seo_meta),
        ];
        if($r->seo_id){
            $done = SeoSettings::where('id', $r->seo_id)->update($attr);
        }else{
            $done = SeoSettings::create($attr);
        }
        if($done){
            return redirect()->back()->with(['success' => 'Successfully saved']);
        }else {
            return redirect()->back()->with(['error' => 'Something has error']);
        }
    }



}
