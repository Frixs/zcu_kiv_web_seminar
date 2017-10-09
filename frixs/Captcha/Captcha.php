<?php

//TODO separate recaptcha
namespace Frixs\Captcha;

class Captcha
{
    /* Google recaptcha API url */
    private $google_url = "https://www.google.com/recaptcha/api/siteverify";
    private $secret = null;

    public function __construct()
    {
        $this->secret = Config::get('captcha.g_recaptcha.secret_key');
    }

    public function VerifyCaptcha($response)
    {
        $url = $this->google_url."?secret=".$this->secret."&response=".$response;
            
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
                $verified = self::VerifyCaptcha($response);

                if ($verified) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }
}
