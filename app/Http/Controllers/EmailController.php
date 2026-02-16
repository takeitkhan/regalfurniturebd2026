<?php

namespace App\Http\Controllers;

use Mail;
use App\Mail\Emailing;
use App\Repositories\Setting\SettingInterface;
use App\Repositories\Dashboard\DashboardInterface;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    private $data = [];
    private $setting;
    /**
     * @var DashboardInterface
     */
    private $dashboard;

    /**
     * HomeController constructor.
     * @param PaymentSettingInterface $setting
     * @param DashboardInterface $dashboard
     * @internal param array $data
     */
    public function __construct(SettingInterface $setting, DashboardInterface $dashboard)
    {
        $this->setting = $setting;
        $this->dashboard = $dashboard;
    }

    public function contact()
    {
        $settings = $this->setting->getAll();
        //dd($settings);
        $widgets = $this->dashboard->getAll();

        return view('frontend.pages.contact')
            ->with(['settings' => $settings, 'widgets' => $widgets]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function send_email()
    {
        request()->validate(
            [
                'email' => 'required|email',
                'name' => 'required',
                'number' => 'required|min:11|max:11',
                'description' => 'required|min:10',
            ]
        );

        $settings = $this->setting->getAll();

        $name = request()->get('name');
        $email = request()->get('email');
        $number = request()->get('number');
        $description = request()->get('description');

        $setting = $settings[0];

        $data = [
            'com_name' => $setting->com_name,
            'description' => $description,
            'name' => $name,
            'email' => $email,
            'number' => $number,
            'subject' => 'Message through contact us form',
            'description' => $description
        ];

        Mail::to($setting->com_email)->send(new Emailing($data));
        return back()->with('status', 'Successfully Send!');
    }
}
