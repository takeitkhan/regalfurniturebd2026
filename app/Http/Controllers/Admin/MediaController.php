<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\Media\MediaInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class MediaController extends Controller
{
    private $media;

    /**
     * TodoController constructor.
     * @param MediaInterface $media
     * @internal param TodoRepository $todo
     */
    public function __construct(MediaInterface $media)
    {
        $this->media = $media;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $search_key = $request->get('q');
        if (!empty($search_key)) {

            $options = array(
                'search_key' => !empty($search_key) ? $search_key : null,
                'limit' => !empty($limit) ? $limit : 24,
                'offset' => 0
            );

            $medias = $this->media->getMediaOnSearch($options);

            //dd($medias);
        } else {
            $medias = $this->media->getAll();
        }

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
        //
    }

    /**
     * unnecessary method or a single file uploader
     * This was the first try
     * @param Request $request
     */
    public function showUploadFile(Request $request)
    {

        // input field catcher
        $file = $request->file;
        // folder name chooser
        $date = date('Y-m');
        $folder_name = str_replace(':', '', $date);

        if (!is_dir('storage/uploads/' . $folder_name)) {
            mkdir('./storage/uploads/' . $folder_name, 0777, TRUE);
        }
        //Move Uploaded File
        $upload_path = './storage/uploads/' . $folder_name;

        $successfully_moved = $file->move($upload_path, $file->getClientOriginalName());
        if ($successfully_moved == true) {
            $options = array(
                'file_name' => $file->getClientOriginalName(),
                'file_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'file_extension' => $file->getClientOriginalExtension(),
                'file_directory' => $upload_path,
                'status' => 1,
                'user_id' => Auth::user()->id
            );
            $this->create($options);
        } else {
            echo 'failed';
        }
    }

}
