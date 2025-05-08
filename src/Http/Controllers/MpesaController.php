<?php
namespace Botble\Mpesa\Http\Controllers;

use Illuminate\Http\Request;
use Botble\Base\Http\Controllers\BaseController;
use Botble\Mpesa\Services\MpesaService;

class MpesaController extends BaseController
{
    public function stkPush(Request $req, MpesaService $s)  { return $s->initiateSTKPush($req->phone,$req->amount); }
    public function simulateC2B(Request $req, MpesaService $s){ return $s->simulateC2B($req->phone,$req->amount); }
    public function b2cPayment(Request $req, MpesaService $s){ return $s->b2cPayment($req->phone,$req->amount); }
    public function b2bPayment(Request $req, MpesaService $s){ return $s->b2bPayment($req->receiver,$req->amount); }
    public function accountBalance(Request $req, MpesaService $s){ return $s->accountBalance(); }
    public function transactionStatus(Request $req, MpesaService $s){ return $s->transactionStatus($req->conversationId); }
    public function reversal(Request $req, MpesaService $s){ return $s->reversal($req->conversationId,$req->amount); }
    public function registerURLs(Request $req, MpesaService $s){ return $s->registerURLs($req->validation_url,$req->confirmation_url); }

    public function handleCallback(Request $r)
    {
        \Log::info('M-Pesa Callback',$r->all());
        return response()->json(['ResultCode'=>0,'ResultDesc'=>'Accepted']);
    }
    public function handleValidation(Request $r)
    {
        \Log::info('M-Pesa Validation',$r->all());
        return response()->json(['ResultCode'=>0,'ResultDesc'=>'Accepted']);
    }
}
