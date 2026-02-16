<?php
/**
 * Created by PhpStorm.
 * User: Samrat
 * Date: 12/4/2017
 * Time: 2:02 AM
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Media\ImageRepository;
use App\Repositories\Media\MediaInterface;
use Illuminate\Support\Facades\Request;

class ImageController extends Controller
{
    protected $image;

    /**
     * ImageController constructor.
     * @param ImageRepository $imageRepository
     * @param MediaInterface $media
     */
    public function __construct(ImageRepository $imageRepository, MediaInterface $media)
    {
        $this->image = $imageRepository;
        $this->media = $media;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $medias = $this->media->getAll();
        return view('media.index')->with('medias', $medias);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(array $att)
    {
        return $this->media->create($att);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //dd($filename);

        if (!$id) {
            return 0;
        }

        $response = $this->image->delete($id);

        return redirect()->back();
    }

    public function getUpload()
    {
    }

    public function postUpload()
    {
        $photo = Request::all();
        $response = $this->image->upload($photo);
        return $response;
    }

    /**
     * @return int
     */
    public function deleteUpload()
    {
    }
}
