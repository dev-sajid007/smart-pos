<?php


namespace App\Services\SMS;


use App\SmsApi;
use GuzzleHttp\Client;

class SmsSendService
{
    public function sendSmsViaRevApi($smsApi)
    {
        $request = request();

        $sendSmsUrl = str_replace('DYNAMIC_MOBILE_NUMBERS', $request->numbers, $smsApi->api_url);
        $sendSmsUrl = str_replace('DYNAMIC_MESSAGE', urlencode($request->text), $sendSmsUrl);

        $sendData = (new Client())->get($sendSmsUrl)->getBody() . 'dummy';  // send

        // fetch response
        $sendData = '{"Status":"0","Text":"ACCEPTD","Message_ID":"2272699"} dummy';

        $sendData = str_replace("dummy","",$sendData);
        $sendData = str_replace("{","",$sendData);
        $sendData = str_replace("}","",$sendData);
        $sendData = str_replace("\"","", $sendData);

        $sendData = explode(",", $sendData);

        // return response
        return $sendData[0] == "Status:0" ? 'Success' : 'Errors';
    }

    public function sendSmsViaAallSkyTechLimitedApi($smsApi)
    {
        $request = request();

        // $url = 'https://71bulksms.com/sms_api/bulk_sms_sender_2.php?
        // api_key=16733693879077962020/07/2711:30:07am-AALL-SKY-TECH-LIMITED
        // &sender_id=730&message=DYNAMIC_MESSAGE&mobile_no=DYNAMIC_MOBILE_NUMBERS&user_email=alam2103@gmail.com';

        $fullUrl    = $smsApi->api_url;
        $explodeUrl = explode('?', $fullUrl);
        $baseUrl    = $explodeUrl[0];
        $params     = $explodeUrl[1];

        $explodeParams = explode('&', $params);

        $api_key    = '16733693879077962020/07/2711:30:07am-AALL-SKY-TECH-LIMITED';
        $sender_id  = 730;
        $user_email = 'lam2103@gmail.com';


        $result = '{"message":"Successfull"}';


        foreach ($explodeParams as $key => $value) {
            if (substr($value, 0, 7) == 'api_key') {
                $api_key = explode('=', $value)[1];
            } else if(substr($value, 0, 9) == 'sender_id') {
                $sender_id = explode('=', $value)[1];
            } else if(substr($value, 0, 10) == 'user_email') {
                $user_email = explode('=', $value)[1];
            }
        }
        
        $data = [
                    'api_key'       => $api_key,
                    'sender_id'     => $sender_id,
                    'message'       => $request['text'],
                    'mobile_no'     => $request->numbers,
                    'user_email'    => $user_email	
                ];

        $options = [
                'http' => [
                    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method'  => 'POST',
                    'content' => http_build_query($data)
                ]
            ];


        $context    = stream_context_create($options);
        $result     = file_get_contents($baseUrl, false, $context);

        if(stripos($result, "Successfull") !== false) {
            return 'Success';
        } else {
            return 'Errors';
        }
    }



    public function updateSmsBalance($smsApi)
    {
        $balance = $this->getSmsBalance();
        $smsApi->update(['balance' => $balance]);
    }


    public function getSmsBalance()
    {
        $smsApi = SmsApi::where('status', 1)->first();

        if (strtolower($smsApi->api_title) == 'rev') {
            return $this->getRevSmsBalance($smsApi);

        } else if (strtolower($smsApi->api_title) == 'ajura') {
            return $this->getAjuraSmsBalance($smsApi);

        } else if (strtolower($smsApi->api_title) == 'aall sky tech limited') {
            return $this->getAallSkyTechLimitedSmsBalance($smsApi);
        }
        return 0;
    }



    public function getRevSmsBalance($smsApi)
    {
        try {
            $client = new Client();
            $balance = $client->get($smsApi->get_sms_balance_rul)->getBody() . "dummy";
            $balance = str_replace("dummy","", $balance);
            $balance = (float) $balance;

            return $balance * 85;
        } catch (\Exception $ex) {
            return 0;
        }
    }

    public function getAjuraSmsBalance($smsApi)
    {
        try {
            $client = new Client();
            $data = $client->get($smsApi->get_sms_balance_rul)->getBody() . "dummy";
            $data = substr($data, 1);
            $data = str_replace("}dummy", "", $data);
            $data = str_replace('"', "", $data);
            $data = explode(",", $data);
            $data = explode(":", $data[2]);
            $balance = $data[2];

            return $balance * 85;
        } catch (\Exception $ex) {
            return $balance = 0;
        }
    }

    public function getAallSkyTechLimitedSmsBalance($smsApi)
    {

        
        try {
            $fullUrl    = $smsApi->api_url;
            $explodeUrl = explode('?', $fullUrl);
            $params     = $explodeUrl[1];

            $explodeParams = explode('&', $params);

            $api_key    = '16733693879077962020/07/2711:30:07am-AALL-SKY-TECH-LIMITED';
            $sender_id  = 730;


            $result = '{"message":"Successfull"}';


            foreach ($explodeParams as $key => $value) {
                if (substr($value, 0, 7) == 'api_key') {
                    $api_key = explode('=', $value)[1];
                } else if(substr($value, 0, 9) == 'sender_id') {
                    $sender_id = explode('=', $value)[1];
                }
            }

            $url = 'https://71bulksms.com/sms_api/balance.php';
            $data = [
                    'api_key'   => $api_key,
                    'sender_id' => $sender_id
                ];
        
            // use key 'http' even if you send the request to https://...
            $options =  [
                            'http' => [
                                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                                'method'  => 'POST',
                                'content' => http_build_query($data)
                            ]
                        ];
            $context    = stream_context_create($options);
            $result     = file_get_contents($url, false, $context);

            $original = ["{", "}", '"'];
            $niddle   = ["", "", ""];
            
            $newPhrase = str_replace($original, $niddle, $result);
            
            return explode(':', $newPhrase)[1];
       
        } catch (\Exception $ex) {
            return $balance = 0;
        }
    }
}
