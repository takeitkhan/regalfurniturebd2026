<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Media\MediaInterface;
use App\Repositories\Post\PostInterface;
use Illuminate\Http\Request;

class OthersController extends Controller
{
    private $post;
    private $media;
    public function __construct(
        PostInterface $post,
        MediaInterface $media)
    {
        $this->post = $post;
        $this->media = $media;
    }
    public function index(Request $request)
    {
        $fabric_post = [];
        $fabrics = [];
        $fabric_posts = \App\Models\Post::where('shop_type', 'sofa_fabric')->latest()->paginate(10);
        foreach($fabric_posts as $fabric)
        {
            $image = \App\Models\Image::where('id', $fabric->images)->first();
            
            $fabrics[] = [
                'id' => $fabric->id,
                'title' => $fabric->title,
                'image' => $image->full_size_directory??null,
                'qty' => $fabric->qty,
                'unit' => $fabric->unit,
                'is_active' => $fabric->is_active,
            ];
        }
        
        
      
        return view('admin.fabricPost.index', compact('fabrics'));
    }

    public function edit(Request $request)
    {
        $medias = $this->media->self()->latest()->paginate();
        if($request->id){
            $fabric_post = $this->post->getById($request->id);  
            return view('admin.fabricPost.fabricPostForm')->with(['fabric_post' => $fabric_post, 'medias' => $medias]);
        } 
        return view('admin.fabricPost.fabricPostForm', compact('medias'));
    }

    public function fabricPostStore(Request $request)
    {
        $attr = [
            
            'title' => $request->title,
            'images' => $request->images,
            'qty' => $request->qty,
            'unit' => $request->unit,
            'shop_type' => "sofa_fabric",
            'is_active' => $request->active
            
        ];

        $this->post->create($attr);

        return redirect()->back()->with("message", "Data Uploaded!");
    }

    public function fabricPostUpdate(Request $request, $id)
    {
        $attr = [
        
            'title' => $request->title,
            'images' => $request->images,
            'qty' => $request->qty,
            'unit' => $request->unit,
            'is_active' => $request->active
            
        ];
        
        $this->post->update($id, $attr);

        return redirect()->back()->with("message", "Data updated!");
    }

    public function fabricPostDelete($id)
    {
        $this->post->delete($id);

        return redirect()->back();
    }


}
