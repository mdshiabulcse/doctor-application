<?php

use Twilio\Rest\Client;

function send_ms($msg, $status, $code){
    return $res=[
        'status'=>$status,
        'message'=>$msg,
    ];
    return response()->json($res,$code);
}

if (!function_exists('twilio_env')){
    function twilio_env(){
        $sid = getenv("TWILIO_ACCOUNT_SID");
        $token = getenv("TWILIO_AUTH_TOKEN");
        $verificationSid = getenv("TWILIO_VERIFICATION_SID");
        $twilio = new Client($sid, $token);

        $verification = $twilio->verify->v2->services($verificationSid);
        return $verification;

    }
}


/* Start:: number to word conversion */
if (!function_exists('numberToWords')) {
    function numberToWords($number)
    {
        $ones = array(
            0 => 'Zero',
            1 => 'One',
            2 => 'Two',
            3 => 'Three',
            4 => 'Four',
            5 => 'Five',
            6 => 'Six',
            7 => 'Seven',
            8 => 'Eight',
            9 => 'Nine'
        );

        $tens = array(
            10 => 'Ten',
            20 => 'Twenty',
            30 => 'Thirty',
            40 => 'Forty',
            50 => 'Fifty',
            60 => 'Sixty',
            70 => 'Seventy',
            80 => 'Eighty',
            90 => 'Ninety'
        );

        $hundreds = array(
            100 => 'Hundred',
            1000 => 'Thousand',
            1000000 => 'Million',
            1000000000 => 'Billion',
            1000000000000 => 'Trillion',
            1000000000000000 => 'Quadrillion',
            1000000000000000000 => 'Quintillion'
        );

        if ($number < 10) {
            return $ones[$number];
        } elseif ($number < 100) {
            if ($number % 10 == 0) {
                return $tens[$number];
            } else {
                return $tens[floor($number / 10) * 10] . '-' . $ones[$number % 10];
            }
        } elseif ($number < 1000) {
            if ($number % 100 == 0) {
                return $ones[floor($number / 100)] . ' ' . $hundreds[100];
            } else {
                return $ones[floor($number / 100)] . ' ' . $hundreds[100] . ' and ' . numberToWords($number % 100);
            }
        } else {
            foreach (array_reverse($hundreds, true) as $key => $value) {
                if ($number >= $key) {
                    $remainder = $number % $key;
                    $quotient = floor($number / $key);
                    if ($remainder == 0) {
                        return numberToWords($quotient) . ' ' . $value;
                    } else {
                        return numberToWords($quotient) . ' ' . $value . ' ' . numberToWords($remainder);
                    }
                }
            }
        }
    }
}

/* End:: number to word conversion */
