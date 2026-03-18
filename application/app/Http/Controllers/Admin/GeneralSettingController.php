<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Frontend;
use App\Models\GeneralSetting;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Image;
use Illuminate\Support\Facades\Cache;

class GeneralSettingController extends Controller
{
    public function index()
    {
        $pageTitle = 'Global Settings';
        $timezones = timezone_identifiers_list();
        $currentTimezone = array_search(config('app.timezone'),$timezones);
        return view('Admin::setting.general', compact('pageTitle', 'timezones', 'currentTimezone'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string',
            'cur_text' => 'required|string|max:40',
            'cur_sym' => 'required|string|max:40',
            'base_color' => 'nullable', 'regex:/^[a-f0-9]{6}$/i',
            'theme_two_base_color' => 'nullable', 'regex:/^[a-f0-9]{6}$/i',
            'theme_three_base_color' => 'nullable', 'regex:/^[a-f0-9]{6}$/i',
            'theme_four_base_color' => 'nullable', 'regex:/^[a-f0-9]{6}$/i',
            'theme_five_base_color' => 'nullable', 'regex:/^[a-f0-9]{6}$/i',
            'theme_five_secondary_color' => 'nullable', 'regex:/^[a-f0-9]{6}$/i',
            'secondary_color' => 'nullable', 'regex:/^[a-f0-9]{6}$/i',
            'timezone' => 'required|integer',
            'rtl' => 'required|integer|in:1,2',
        ]);

        $general = GeneralSetting::first();
        $general->site_name = $request->site_name;
        $general->cur_text = $request->cur_text;
        $general->cur_sym = $request->cur_sym;
        $general->base_color = $request->base_color??$general->base_color;
        $general->theme_two_base_color = $request->theme_two_base_color??$general->theme_two_base_color;
        $general->theme_three_base_color = $request->theme_three_base_color??$general->theme_three_base_color;
        $general->theme_four_base_color = $request->theme_four_base_color??$general->theme_four_base_color;

        $general->theme_five_base_color = $request->theme_five_base_color??$general->theme_five_base_color;
        $general->theme_five_secondary_color = $request->theme_five_secondary_color??$general->theme_five_secondary_color;
        $general->secondary_color = $request->secondary_color;
        $general->kv = $request->kv ? Status::ENABLE : Status::DISABLE;
        $general->ev = $request->ev ? Status::ENABLE : Status::DISABLE;
        $general->en = $request->en ? Status::ENABLE : Status::DISABLE;
        $general->sv = $request->sv ? Status::ENABLE : Status::DISABLE;
        $general->sn = $request->sn ? Status::ENABLE : Status::DISABLE;
        $general->force_ssl = $request->force_ssl ? Status::ENABLE : Status::DISABLE;
        $general->secure_password = $request->secure_password ? Status::ENABLE : Status::DISABLE;
        $general->registration = $request->registration ? Status::ENABLE : Status::DISABLE;
        $general->agree = $request->agree ? Status::ENABLE : Status::DISABLE;
        $general->rtl = $request->rtl;
        $general->save();

        Cache::put('GeneralSetting', $general);

        $timezones = timezone_identifiers_list();
        $timezone = $timezones[$request->timezone] ?? 'UTC';
        $timezoneFile = config_path('timezone.php');
        $content = '<?php $timezone = "'.$timezone.'" ?>';
        file_put_contents($timezoneFile, $content);

        $notify[] = ['success', 'Updated successfully'];
        return back()->withNotify($notify);
    }

    public function logoIcon()
    {
        $pageTitle = 'Logo & Favicon';
        return view('Admin::setting.logo_icon', compact('pageTitle'));
    }

    public function logoIconUpdate(Request $request)
    {

        $request->validate([
            'logo' => ['nullable', 'image',new FileTypeValidate(['jpg','jpeg','png'])],
            'logo_dark' => ['nullable', 'image',new FileTypeValidate(['jpg','jpeg','png'])],
            'favicon' => ['nullable', 'image',new FileTypeValidate(['png'])],
        ]);


        $path = getFilePath('logoIcon');
        if ($request->hasFile('logo')) {
            try {
                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                }
                fileUploader($request->logo,$path,filename:'logo.png');
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload the logo'];
                return back()->withNotify($notify);
            }
        }
        if ($request->hasFile('logo_dark')) {
            try {
                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                }

                fileUploader($request->logo_dark,$path,filename:'logo_dark.png');
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload the logo'];
                return back()->withNotify($notify);
            }
        }

        if ($request->hasFile('favicon')) {
            try {
                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                }

                fileUploader($request->favicon,$path,filename:'favicon.png');
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload the favicon'];
                return back()->withNotify($notify);
            }
        }
        $notify[] = ['success', 'Logo & favicon has been updated successfully'];
        return redirect()->to(url()->previous() . '#refresh')->withNotify($notify);
    }

    public function cookie(){
        $pageTitle = 'GDPR Cookie';
        $cookie = Frontend::where('data_keys','cookie.data')->firstOrFail();
        return view('Admin::setting.cookie',compact('pageTitle','cookie'));
    }

    public function cookieSubmit(Request $request){
        $request->validate([
            'short_desc'=>'required|string|max:255',
            'description'=>'required',
        ]);
        $cookie = Frontend::where('data_keys','cookie.data')->firstOrFail();
        $cookie->data_values = [
            'short_desc' => $request->short_desc,
            'description' => $request->description,
            'status' => $request->status ? Status::ENABLE : Status::DISABLE,
        ];
        $cookie->save();
        $notify[] = ['success','Cookie policy has been updated successfully'];
        return back()->withNotify($notify);
    }


    public function maintenance()
    {
        $pageTitle = 'Maintenance Mode';
        $maintenance = Frontend::where('data_keys','maintenance.data')->firstOrFail();
        return view('Admin::setting.maintenance',compact('pageTitle','maintenance'));
    }

    public function maintenanceSubmit(Request $request){
        $request->validate([
            'description'=>'required',
        ]);
        $maintenance = Frontend::where('data_keys','maintenance.data')->firstOrFail();

        $general = GeneralSetting::first();
        $general->maintenance_mode = $request->status ? Status::ENABLE : Status::DISABLE;
        $general->save();

        Cache::put('GeneralSetting', $general);


        $maintenance->data_values = [
            'description' => $request->description,
        ];
        $maintenance->save();

        $notify[] = ['success','Maintenance mode has been updated successfully'];
        return back()->withNotify($notify);
    }

    public function customCss()
    {
        $pageTitle = 'Custom CSS';
        $file = activeTemplate(true) . 'css/custom.css';
        $file_content = @file_get_contents($file);
        return view('Admin::setting.custom_css', compact('pageTitle', 'file_content'));
    }


    public function customCssSubmit(Request $request)
    {
        $file = activeTemplate(true) . 'css/custom.css';
        if (!file_exists($file)) {
            fopen($file, "w");
        }
        file_put_contents($file, $request->css);
        $notify[] = ['success', 'CSS updated successfully'];
        return back()->withNotify($notify);
    }


        public function socialiteCredentials()
    {
        $pageTitle = 'Social Login Credentials';
        return view('Admin::setting.social_credential', compact('pageTitle'));
    }

    public function updateSocialiteCredentialStatus($key)
    {
        $general = gs();
        $credentials = $general->socialite_credentials;
        try {
            $credentials->$key->status = $credentials->$key->status == 1 ? 0 : 1;
        } catch (\Throwable $th) {
            abort(404);
        }

        $general->socialite_credentials = $credentials;
        $general->save();

        $notify[] = ['success', 'Status changed successfully'];
        return back()->withNotify($notify);
    }

    public function updateSocialiteCredential(Request $request, $key)
    {
        $general = gs();
        $credentials = $general->socialite_credentials;
        try {
            $credentials->$key->client_id = $request->client_id;
            $credentials->$key->client_secret = $request->client_secret;
        } catch (\Throwable $th) {
            abort(404);
        }
        $general->socialite_credentials = $credentials;
        $general->save();

        $notify[] = ['success', ucfirst($key) . ' credential updated successfully'];
        return back()->withNotify($notify);
    }
}
