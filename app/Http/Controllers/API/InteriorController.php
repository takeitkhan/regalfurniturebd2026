<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Interior;
use App\Models\InteriorImage;
use App\Models\Term;
use Illuminate\Http\Request;

class InteriorController extends Controller
{

    public function all(Request $request){


        $interiors = Interior::with('image:id,full_size_directory,icon_size_directory','images.image:id,full_size_directory,icon_size_directory');

        $category = null;
        if($request->seo_url && $request->seo_url != ""){
            
            $category = Term::where('seo_url',$request->seo_url)->select('id','name','seo_url')->first();

            if($category){
                $interiors = $interiors->where('category_id',$category->id);
            }

        }

        $interiors = $interiors->paginate(10);

        return response()->json(compact('interiors','category'));

    }

    public function show(Request $request){

        // $interiors = [];
        $slug = $request->slug;
        $interior = Interior::with('images.image:id,full_size_directory,icon_size_directory')->where('slug',$slug)->first();


        return response()->json(compact('interior'));

    }

    public function category(Request $request){

        $parentCat = Term::where('seo_url',$request->seo_url)->select('id','name','seo_url')->first();
        $parent = null;
        $category = [];

        if($parentCat){
            $parent = [
                'name' => $parentCat->name,
                'seo_url' => $parentCat->seo_url
            ];
            $db_category = \App\Models\Term::where('parent', $parentCat->id)->where('is_published',1)->with('page_img')->orderBy('serial', "ASC")->get();
            foreach($db_category as $cat){
   
               $category[] = [
                   'name' => $cat->name,
                   'seo_url' => $cat->seo_url,
                   'description' => $cat->description,
                   'image_url' => $cat->page_img->full_size_directory??''
               ];
   
            }

        }
    
         return response()->json(compact('parent','category'));
    }

    public function parent_cats()
    {
        $data = [
            [
                'name' => 'Industrial rack',
                'seo_url' => 'industrial-rack-01',
                'description' => 'Some description',
                'image_url' => 'storage/uploads/fullsize/2021-05/1.jpg'
            ],
            // [
            //     'title' => '',
            //     'seo_url' => ''
            // ],
            [
                'name' => 'Kitchen Cabinet',
                'seo_url' => 'kitchen-cabinet-0718',
                'description' => 'a little description',
                'image_url' => 'storage/uploads/fullsize/2021-05/1.jpg'
            ]
        ];

        return response()->json([
            'data' => $data
        ]);
    }

}
