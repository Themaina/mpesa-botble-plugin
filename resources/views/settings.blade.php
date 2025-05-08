
@extends('core/base::layouts.master')
@section('content')
<div class="card">
  <div class="card-header"><h3>M-Pesa Settings</h3></div>
  <div class="card-body">
    {!! Form::open(['route'=>'mpesa.settings.update','method'=>'POST']) !!}
      @php
        $fields = [
          'mpesa_consumer_key'=>'Consumer Key',
          'mpesa_consumer_secret'=>'Consumer Secret',
          'mpesa_short_code'=>'Shortcode',
          'mpesa_passkey'=>'Passkey',
          'mpesa_environment'=>'Environment',
          'mpesa_validation_url'=>'Validation URL',
          'mpesa_confirmation_url'=>'Confirmation URL',
          'mpesa_callback_url'=>'Callback URL',
          'mpesa_b2c_initiator'=>'B2C Initiator',
          'mpesa_b2c_password'=>'B2C Security Credential',
          'mpesa_b2b_initiator'=>'B2B Initiator',
          'mpesa_reversal_initiator'=>'Reversal Initiator'
        ];
      @endphp
      @foreach($fields as $f=>$label)
        <div class="form-group">
          <label>{{ $label }}</label>
          <input type="{{ strpos($f,'url')!==false?'url':'text' }}"
                 name="{{ $f }}" class="form-control"
                 value="{{ setting($f) }}">
        </div>
      @endforeach
      <button class="btn btn-primary mt-3">Save Settings</button>
    {!! Form::close() !!}
  </div>
</div>
@endsection
