<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\Post\PostInterface;
use Illuminate\Http\Request;

class OthersController extends Controller
{
    private $post;
    public function __construct(PostInterface $post)
    {
        $this->post = $post;
    }
    public function getFabric()
    {
        $fabrics = [];
        $db_fabricPost = $this->post->getByAny('shop_type', 'sofa_fabric');
        
        foreach($db_fabricPost as $fabric)
        {
            $image = \App\Models\Image::where('id', $fabric->images)->first();
            
            $fabrics[] = [
                
                'title' => $fabric->title,
                'image' => $image->full_size_directory??null,
                'qty' => $fabric->qty,
                'unit' => $fabric->unit,
                
            ];
        }
        

        return response()->json(compact('fabrics'));
    }
}
