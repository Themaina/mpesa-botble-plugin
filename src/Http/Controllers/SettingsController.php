<?php
namespace Botble\Mpesa\Http\Controllers;

use Illuminate\Http\Request;
use Botble\Base\Http\Controllers\BaseController;

class SettingsController extends BaseController
{
    public function index()
    {
        page_title()->setTitle('M-Pesa Settings');
        return view('plugins.mpesa::settings');
    }

    public function update(Request $request)
    {
        $fields = [
            'mpesa_consumer_key',
            'mpesa_consumer_secret',
            'mpesa_short_code',
            'mpesa_passkey',
            'mpesa_environment',
            'mpesa_validation_url',
            'mpesa_confirmation_url',
            'mpesa_b2c_initiator',
            'mpesa_b2c_password',
            'mpesa_b2b_initiator',
            'mpesa_reversal_initiator',
        ];
        foreach ($fields as $f) {
            setting()->set($f, $request->input($f));
        }
        setting()->save();

        return redirect()->back()->with('success_msg', 'M-Pesa settings saved.');
    }
}
