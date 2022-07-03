<?php

namespace App\Http\Controllers;

use App\SmsApi;
use App\Services\SMS\SmsSendService;
use Illuminate\Http\Request;

class SmsSendController extends Controller
{

    public function sendSmsViaHttpGuzzle(Request $request)
    {
        $smsApi = SmsApi::where('status', 1)->first();

        if ($smsApi) {
            $service = new SmsSendService();

            if (strtolower($smsApi->api_title) == 'rev') {
                $status = $service->sendSmsViaRevApi($smsApi);

            } elseif (strtolower($smsApi->api_title) == 'aall sky tech limited') { 
                $status = $service->sendSmsViaAallSkyTechLimitedApi($smsApi);
            }
            $service->updateSmsBalance($smsApi);

            return $status;
        }
    }
}
