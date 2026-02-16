<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\Media\MediaInterface;
use App\Repositories\Term\TermInterface;
use App\Repositories\Variation\VariationInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Http\RedirectResponse;

class VariationController extends Controller
{
    protected $variation;
    /**
     * @var TermInterface
     */
    private $term;

    /**
     * variationController constructor.
     * @param VariationInterface $variation
     * @param MediaInterface $media
     * @param TermInterface $term
     */
    public function __construct(VariationInterface $variation, MediaInterface $media, TermInterface $term)
    {
        $this->media = $media;
        $this->variation = $variation;
        $this->term = $term;
    }

    /**
     * @return $this
     */
    public function variations()
    {
        $variations = $this->variation->getAll();
        return view('variation.variations', compact('variations'))
            ->with('variations', $variations);
    }

    /**
     * @param $id
     * @return $this
     */
    public function add_variation()
    {
        $medias = $this->media->getAll();
        $terms = $this->term->getAll()->toArray();
        return view('variation.form')->with(array('medias' => $medias, 'terms' => $terms));
    }

    /**
     * @param $id
     * @return $this
     */

    public function edit_variation($id)
    {
        if (isset($id)) {
            $variation = $this->variation->getById($id);
            $variations = $this->variation->getAll();
            $terms = $this->term->getAll()->toArray();
            return view('variation.variations')
                ->with('variation', $variation)
                ->with('terms', $terms)
                ->with('variations', $variations);
        }

    }

    /**
     * @param \App\Http\Controllers\Admin\Request $request
     * @param $id
     * @return $this|RedirectResponse
     */
    public function variation_update_save(Request $request, $id)
    {
        //$id = $request->get('variation_id');

        $d = $this->variation->getById($id);
        //dd($d);
        // store
        $attributes = [
            'user_id' => $request->get('user_id'),
            'label_name' => $request->get('label_name'),
            'show_on' => $request->get('show_on'),
            'field_name' => $request->get('field_name'),
            'field_values' => $request->get('field_values'),
            'field_type' => $request->get('field_type'),
            'field_attributes' => $request->get('field_attributes'),
            'is_active' => 1
        ];

        try {
            $this->variation->update($id, $attributes);
            return redirect('variations')->with('success', 'Successfully save changed');
        } catch (\Illuminate\Database\QueryException $ex) {
            return redirect('variations')->withErrors($ex->getMessage());
        }
    }

    /**
     * @param Request $request
     * @return $this
     * @internal param Request $request
     */
    public function store(Request $request)
    {
        // validate
        // read more on validation at
        $validator = Validator::make($request->all(),
            [
                'label_name' => 'required',
                'show_on' => 'required',
                'field_name' => 'required',
                'field_type' => 'required',
                'field_attributes' => 'required'
            ]
        );

        // process the login
        if ($validator->fails()) {
            return redirect('variations')
                ->withErrors($validator)
                ->withInput();
        } else {
            // store
            $attributes = [
                'user_id' => $request->get('user_id'),
                'label_name' => $request->get('label_name'),
                'show_on' => $request->get('show_on'),
                'field_name' => $request->get('field_name'),
                'field_values' => $request->get('field_values'),
                'field_type' => $request->get('field_type'),
                'field_attributes' => $request->get('field_attributes'),
                'is_active' => 1
            ];

            try {
                $this->variation->create($attributes);
                return redirect('variations')->with('success', 'Successfully save changed');
            } catch (\Illuminate\Database\QueryException $ex) {
                return redirect('variations')->withErrors($ex->getMessage());
            }
        }

    }

    /**
     * @param $id
     * @return $this
     */
    public function destroy($id)
    {
        try {
            $this->variation->delete($id);
            return redirect('variations')->with('success', 'Successfully deleted');
        } catch (\Illuminate\Database\QueryException $ex) {
            return redirect('variations')->withErrors($ex->getMessage());
        }
    }
}
