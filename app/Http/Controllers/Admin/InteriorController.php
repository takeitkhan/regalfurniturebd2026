<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Interior;
use App\Models\InteriorImage;
use App\Models\Image;
use App\Repositories\Media\MediaInterface;
use App\Repositories\Term\TermInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InteriorController extends Controller
{

    private $media;
    private $term;

    public function __construct(MediaInterface $media, TermInterface $term)
    {
        $this->media = $media;
        $this->term = $term;
    }
    
    // Interior Index method which showing all interior data
    public function interiorIindex(){
        
        $interiorImage = [];
        $interior_list = Interior::all();
        // foreach($interior_list as $interior){
        //     $interiorImage[$interior->id] = $interior->image->full_size_directory??null;
        // }
        // dd($interiorImage);
        return view("admin.interior.index", compact("interior_list"));
    }


    // This method showing form for upload intrerior data
    public function create(Request $request) {

                $tab = ($request->tab??"basic");
                $tab = "admin.interior.$tab";
                $db_terms = $this->term->self()->whereIn('parent',[689, 718,720, 721])->get();

                foreach($db_terms as $term){
                    $terms[$term->id] = ($term->parent_cat->name??""). " - " . $term->name;
                }
                $medias = $this->media->getAll();

                
            return response()->view('admin.interior.form',compact('tab','terms','medias'));
    }


    // this method store interior data 
    public function store(Request $request){

        $user_id = auth()->id();
        
        
        $interiors = [
            'user_id' => $user_id,
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'sub_title' => $request->sub_title,
            'image_id' => $request->image_id,
            'category_id' => $request->category_id,
            'active' => 1
        ];

        $result = Interior::create($interiors);

        if($result){
            return redirect()->back()->with('success','Data Uploaded');
        }else{
            return  redirect()->back()->with("error","Something want wrong");
        }

    }

    // This method edit interior data 

    public function edit(Request $request, $id){
           

        $interior = Interior::find($id);
        
        $interior_images = InteriorImage::where('interior_id', $id)->get();
        // foreach($interior_images as $interior){
        //     dd($interior->image);
        // }
        
        $terms = null;
        $db_terms = $this->term->self()->whereIn('parent',[689, 718,720, 721])->get();
        
        foreach($db_terms as $term){
            $terms[$term->id] = ($term->parent_cat->name??""). " - " . $term->name;
        }
        $tab = ($request->tab??"basic");
        $tab = "admin.interior.$tab";
        $medias = $this->media->self()->orderBy('id', 'DESC')->paginate(12);
        return response()->view('admin.interior.form',compact('tab','terms', 'interior','medias', 'interior_images'));
    }


    // update section here
    public function update(Request $request, $id){
        $user_id = auth()->id();
        $slug = Str::slug($request->title);
        
        $interiors = [
            'user_id' => $user_id,
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'sub_title' => $request->sub_title,
            'image_id' => $request->image_id,
            'category_id' => $request->category_id,
            'active' => 1
        ];

        $result = Interior::where('id', $id)->update($interiors);

        if($result){
            return redirect()->back()->with('success','Data Updated');
        }else{
            return  redirect()->back()->with("error","Something want wrong");
        }
    }

    // interior delete section here
    public function destroy($id){
        Interior::where('id', $id)->delete();
        return redirect()->back()->with('success','Data Deleted');
    }


    // Interior Image section here and this method store interior image data
    public function interiorImageStore(Request $request) {
        
        
        $interiors = [
            'interior_id' => $request->interior_id,
            'image_id' => $request->image_id,
            'title' => $request->title,
            'caption' => $request->caption,
            'info' => $request->info,
        
        ];

        $result = InteriorImage::create($interiors);

        if($result){
            return redirect()->back()->with('success','Data Uploaded');
        }else{
            return  redirect()->back()->with("error","Something want wrong");
        }
    }

    // Update Section Here....
    public function interiorImageUpdate(Request $request, $id) {
        
        
        $interiors = [
            'interior_id' => $request->interior_id,
            'image_id' => $request->image_id,
            'title' => $request->title,
            'caption' => $request->caption,
            'info' => $request->info,
        
        ];

        $result = InteriorImage::where('id', $id)->update($interiors);

        if($result){
            return redirect()->back()->with('success','Data Updated');
        }else{
            return  redirect()->back()->with("error","Something want wrong");
        }
    }

    // interior image delete section here

    public function interiorImageDelete($id){
        InteriorImage::where('id', $id)->delete();
        return redirect()->back()->with('success','Data Deleted');
    }

}
