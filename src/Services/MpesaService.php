<?php
namespace Botble\Mpesa\Services;

use Illuminate\Support\Facades\Http;

class MpesaService
{
    protected $short, $pass, $key, $secret, $env;

    public function __construct()
    {
        $this->short   = setting('mpesa_short_code');
        $this->pass    = setting('mpesa_passkey');
        $this->key     = setting('mpesa_consumer_key');
        $this->secret  = setting('mpesa_consumer_secret');
        $this->env     = setting('mpesa_environment')=='live'?'api':'sandbox';
    }

    protected function token()
    {
        $url = "https://{$this->env}.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials";
        $res = Http::withBasicAuth($this->key,$this->secret)->get($url)->json();
        return $res['access_token'] ?? null;
    }

    public function initiateSTKPush($phone,$amt)
    {
        $t = $this->token()?:abort(500,'Auth failed');
        $ts = now()->format('YmdHis');
        $pwd= base64_encode("{$this->short}{$this->pass}{$ts}");
        return Http::withToken($t)->post("https://{$this->env}.safaricom.co.ke/mpesa/stkpush/v1/processrequest",[
            'BusinessShortCode'=>$this->short,'Password'=>$pwd,'Timestamp'=>$ts,
            'TransactionType'=>'CustomerPayBillOnline','Amount'=>(int)$amt,
            'PartyA'=>$phone,'PartyB'=>$this->short,'PhoneNumber'=>$phone,
            'CallBackURL'=>setting('mpesa_confirmation_url'),
            'AccountReference'=>'Order','TransactionDesc'=>'Order Payment'
        ])->json();
    }

    public function simulateC2B($phone,$amt)
    {
        $t=$this->token(); $url="https://{$this->env}.safaricom.co.ke/mpesa/c2b/v1/simulate";
        return Http::withToken($t)->post($url,[
            'ShortCode'=>$this->short,'CommandID'=>'CustomerPayBillOnline',
            'Amount'=>(int)$amt,'Msisdn'=>$phone,'BillRefNumber'=>'Botble'
        ])->json();
    }

    public function b2cPayment($phone,$amt)
    {
        $t=$this->token(); $url="https://{$this->env}.safaricom.co.ke/mpesa/b2c/v1/paymentrequest";
        return Http::withToken($t)->post($url,[
            'InitiatorName'=>setting('mpesa_b2c_initiator'),'SecurityCredential'=>setting('mpesa_b2c_password'),
            'CommandID'=>'BusinessPayment','Amount'=>(int)$amt,'PartyA'=>$this->short,
            'PartyB'=>$phone,'Remarks'=>'Payout','QueueTimeOutURL'=>setting('mpesa_confirmation_url'),
            'ResultURL'=>setting('mpesa_callback_url')
        ])->json();
    }

    public function b2bPayment($receiver,$amt)
    {
        $t=$this->token(); $url="https://{$this->env}.safaricom.co.ke/mpesa/b2b/v1/paymentrequest";
        return Http::withToken($t)->post($url,[
            'Initiator'=>$this->short,'SecurityCredential'=>setting('mpesa_reversal_initiator'),
            'CommandID'=>'BusinessToBusiness','Amount'=>(int)$amt,
            'PartyA'=>$this->short,'PartyB'=>$receiver,'Remarks'=>'B2B',
            'QueueTimeOutURL'=>setting('mpesa_confirmation_url'),
            'ResultURL'=>setting('mpesa_callback_url')
        ])->json();
    }

    public function accountBalance()
    {
        $t=$this->token(); $url="https://{$this->env}.safaricom.co.ke/mpesa/accountbalance/v1/query";
        return Http::withToken($t)->post($url,[
            'Initiator'=>$this->short,'SecurityCredential'=>setting('mpesa_b2c_password'),
            'CommandID'=>'AccountBalance','PartyA'=>$this->short,
            'IdentifierType'=>'4','QueueTimeOutURL'=>setting('mpesa_confirmation_url'),
            'ResultURL'=>setting('mpesa_callback_url')
        ])->json();
    }

    public function transactionStatus($convId)
    {
        $t=$this->token(); $url="https://{$this->env}.safaricom.co.ke/mpesa/transactionstatus/v1/query";
        return Http::withToken($t)->post($url,[
            'Initiator'=>$this->short,'SecurityCredential'=>setting('mpesa_b2c_password'),
            'CommandID'=>'TransactionStatusQuery','TransactionID'=>$convId,
            'PartyA'=>$this->short,'IdentifierType'=>'4',
            'QueueTimeOutURL'=>setting('mpesa_confirmation_url'),
            'ResultURL'=>setting('mpesa_callback_url')
        ])->json();
    }

    public function reversal($convId,$amt)
    {
        $t=$this->token(); $url="https://{$this->env}.safaricom.co.ke/mpesa/reversal/v1/request";
        return Http::withToken($t)->post($url,[
            'Initiator'=>$this->short,'SecurityCredential'=>setting('mpesa_reversal_initiator'),
            'CommandID'=>'TransactionReversal','TransactionID'=>$convId,
            'Amount'=>(int)$amt,'PartyA'=>$this->short,
            'ReceiverParty'=>$this->short,'RecieverIdentifierType'=>'4',
            'QueueTimeOutURL'=>setting('mpesa_confirmation_url'),
            'ResultURL'=>setting('mpesa_callback_url')
        ])->json();
    }

    public function registerURLs($validation,$confirmation)
    {
        $t=$this->token(); $url="https://{$this->env}.safaricom.co.ke/mpesa/c2b/v1/registerurl";
        return Http::withToken($t)->post($url,[
            'ShortCode'=>$this->short,'ResponseType'=>'Completed',
            'ConfirmationURL'=>$confirmation,'ValidationURL'=>$validation
        ])->json();
    }
}
