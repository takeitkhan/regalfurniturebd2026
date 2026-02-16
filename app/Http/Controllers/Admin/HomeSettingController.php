<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\HomeSetting\HomeSettingInterface;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeSettingController extends Controller
{
    private $setting;

    /**
     * settingController constructor.
     * @param HomeSettingInterface $homesetting
     */
    public function __construct(HomeSettingInterface $homesetting)
    {
        $this->setting = $homesetting;
    }

    /**
     * @return $this
     */
    public function settings()
    {
        $settings = $this->setting->getAll();

        return view('menu.home_settings', compact('settings'))
            ->with('settings', $settings);
    }

    /**
     * @param $id
     * @return $this
     */

    public function edit_setting($id)
    {
        if (isset($id)) {
            $setting = $this->setting->getById($id);
            return view('menu.home_settings')
                ->with('setting', $setting);
        }
    }

    /**
     * @param \App\Http\Controllers\Admin\Request $request
     * @param $id
     * @return $this|RedirectResponse
     */
    public function setting_update_save(Request $request, $id)
    {
        $d = $this->setting->getById($id);

        // store
        $attributes = [
            'cat_first' => $request->get('cat_first'),
            'cat_second' => $request->get('cat_second'),
            'cat_third' => $request->get('cat_third'),
            'cat_fourth' => $request->get('cat_fourth'),
            'cat_fifth' => $request->get('cat_fifth'),
            'cat_sixth' => $request->get('cat_sixth'),
            'cat_seventh' => $request->get('cat_seventh'),
            'cat_eighth' => $request->get('cat_eighth'),
            'main_slider' => $request->get('main_slider'),
            'home_slider' => $request->get('home_slider'),
            'home_banner_one' => $request->get('home_banner_one'),
            'home_banner_two' => $request->get('home_banner_two'),
            'home_banner_three' => $request->get('home_banner_three'),
            'home_brand' => $request->get('home_brand'),
            'flash_banner' => $request->get('flash_banner'),
            'home_category' => $request->get('home_category'),
            'explore_products' => $request->get('explore_products')
        ];

        try {
            $this->setting->update($d->id, $attributes);
            return redirect('homesettings')->with('success', 'Successfully save changed');
        } catch (\Illuminate\Database\QueryException $ex) {
            return redirect('homesettings')->withErrors($ex->getMessage());
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
                'cat_first' => 'required',
                'cat_second' => 'required',
                'cat_third' => 'required'
            ]
        );

        // process the login
        if ($validator->fails()) {
            return redirect('settings')
                ->withErrors($validator)
                ->withInput();
        } else {
            // store
            $attributes = [
                'cat_first' => $request->get('cat_first'),
                'cat_second' => $request->get('cat_second'),
                'cat_third' => $request->get('cat_third'),
                'cat_fourth' => $request->get('cat_fourth'),
                'cat_fifth' => $request->get('cat_fifth'),
                'cat_sixth' => $request->get('cat_sixth'),
                'cat_seventh' => $request->get('cat_seventh'),
                'cat_eighth' => $request->get('cat_eighth'),
                'main_slider' => $request->get('main_slider'),
                'home_slider' => $request->get('home_slider'),
                'home_banner_one' => $request->get('home_banner_one'),
                'home_banner_two' => $request->get('home_banner_two'),
                'home_banner_three' => $request->get('home_banner_three'),
                'flash_banner' => $request->get('flash_banner'),
                'home_brand' => $request->get('home_brand'),
                'home_category' => $request->get('home_category'),
                'explore_products' => $request->get('explore_products')
            ];

            try {
                $this->setting->create($attributes);
                return redirect('homesettings')->with('success', 'Successfully save changed');
            } catch (\Illuminate\Database\QueryException $ex) {
                return redirect('homesettings')->withErrors($ex->getMessage());
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
            $this->setting->delete($id);
            return redirect('homesettings')->with('success', 'Successfully deleted');
        } catch (\Illuminate\Database\QueryException $ex) {
            return redirect('homesettings')->withErrors($ex->getMessage());
        }
    }
}
