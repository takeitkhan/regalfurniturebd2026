<?php

namespace App\Http\Controllers\Admin;

use Validator;
use App\Repositories\Setting\SettingInterface;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    private $setting;

    /**
     * settingController constructor.
     */
    public function __construct(SettingInterface $setting)
    {
        $this->setting = $setting;
    }

    /**
     * @return $this
     */
    public function settings()
    {
        $settings = $this->setting->getAll();
        //owndebugger($settings);
        return view('menu.settings', compact('settings'))
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
            return view('menu.settings')
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
        //dd($request->get('com_name'));
        // store
        $attributes = [
            'com_name' => $request->get('com_name'),
            'com_slogan' => $request->get('com_slogan'),
            'com_eshtablished' => $request->get('com_eshtablished'),
            'com_licensecode' => $request->get('com_licensecode'),
            'com_logourl' => $request->get('com_logourl'),
            'com_headerurl' => $request->get('com_headerurl'),
            'header_bg' => $request->get('header_bg'),
            'com_phone' => $request->get('com_phone'),
            'order_phone' => $request->get('order_phone'),
            'com_email' => $request->get('com_email'),
            'com_address' => $request->get('com_address'),
            'com_addressgooglemap' => $request->get('com_addressgooglemap'),
            'com_website' => $request->get('com_website'),
            'com_analytics' => $request->get('com_analytics'),
            'com_chat_box' => $request->get('com_chat_box'),
            'com_metatitle' => $request->get('com_metatitle'),
            'com_metadescription' => $request->get('com_metadescription'),
            'com_metakeywords' => $request->get('com_metakeywords'),
            'com_workinghours' => $request->get('com_workinghours'),
            'com_adminname' => $request->get('com_adminname'),
            'com_adminphone' => $request->get('com_adminphone'),
            'com_adminemail' => $request->get('com_adminemail'),
            'com_adminphotourl' => $request->get('com_adminphotourl'),
            'com_facebookpageid' => $request->get('com_facebookpageid'),
            'com_favicon' => $request->get('com_favicon'),
            'com_timezone' => $request->get('com_timezone'),
            'special_notification_product_single_page' => $request->get('special_notification_product_single_page'),
            'showroom_location_popup' => $request->get('showroom_location_popup'),
        ];

        try {
            $this->setting->update($d->id, $attributes);
            return redirect('settings')->with('success', 'Successfully save changed');
        } catch (\Illuminate\Database\QueryException $ex) {
            return redirect('settings')->withErrors($ex->getMessage());
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
                'com_name' => 'required',
                'com_phone' => 'required',
                'com_email' => 'required',
                'com_adminname' => 'required'
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
                'com_name' => $request->get('com_name'),
                'com_slogan' => $request->get('com_slogan'),
                'com_eshtablished' => $request->get('com_eshtablished'),
                'com_licensecode' => $request->get('com_licensecode'),
                'com_logourl' => $request->get('com_logourl'),
                'com_headerurl' => $request->get('com_headerurl'),
                'header_bg' => $request->get('header_bg'),
                'com_phone' => $request->get('com_phone'),
                'order_phone' => $request->get('order_phone'),
                'com_email' => $request->get('com_email'),
                'com_address' => $request->get('com_address'),
                'com_addressgooglemap' => $request->get('com_addressgooglemap'),
                'com_website' => $request->get('com_website'),
                'com_analytics' => $request->get('com_analytics'),
                'com_chat_box' => $request->get('com_chat_box'),
                'com_metatitle' => $request->get('com_metatitle'),
                'com_metadescription' => $request->get('com_metadescription'),
                'com_metakeywords' => $request->get('com_metakeywords'),
                'com_workinghours' => $request->get('com_workinghours'),
                'com_adminname' => $request->get('com_adminname'),
                'com_adminphone' => $request->get('com_adminphone'),
                'com_adminemail' => $request->get('com_adminemail'),
                'com_adminphotourl' => $request->get('com_adminphotourl'),
                'com_facebookpageid' => $request->get('com_facebookpageid'),
                'com_favicon' => $request->get('com_favicon'),
                'com_timezone' => $request->get('com_timezone'),
                'special_notification_product_single_page' => $request->get('special_notification_product_single_page'),
                 'showroom_location_popup' => $request->get('showroom_location_popup'),
            ];

            try {
                $this->setting->create($attributes);
                return redirect('settings')->with('success', 'Successfully save changed');
            } catch (\Illuminate\Database\QueryException $ex) {
                return redirect('settings')->withErrors($ex->getMessage());
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
            return redirect('settings')->with('success', 'Successfully deleted');
        } catch (\Illuminate\Database\QueryException $ex) {
            return redirect('settings')->withErrors($ex->getMessage());
        }
    }
}
