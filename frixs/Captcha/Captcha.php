<?php

namespace Frixs\Captcha;

use Frixs\Config\Config;

class Captcha
{
    /* Google recaptcha API url */
    private $googleUrl = null,
            $secret    = null;

    public function __construct()
    {
        $this->secret    = Config::get('captcha.g_recaptcha.secret_key');
        $this->googleUrl = Config::get('captcha.g_recaptcha.site_verify_url');
    }

    private function verifyCaptcha($response)
    {
        $url = $this->googleUrl."?secret=".$this->secret."&response=".$response;
            
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 15);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        $curlData = curl_exec($curl);

        curl_close($curl);

        $res = json_decode($curlData, true);
        if ($res['success'] == 'true') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Method to verify captcha result
     *
     * @param [type] $response      captcha response: $_POST['g-recaptcha-response']
     * @return bool                 boolean of validation
     */
    public function verify($response)
    {
        $captchaResult = 'false';
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (!empty($response)) {
                $verified = self::verifyCaptcha($response);

                if ($verified) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }
}
