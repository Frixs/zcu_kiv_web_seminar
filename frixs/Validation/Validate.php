<?php

namespace Frixs\Validation;

use Frixs\Database\Connection as DB;
use Frixs\Captcha\Captcha;
use Frixs\Http\Input;

class Validate
{
    private $_passed = false,
        $_errors = array(),
        $_db     = null;
        
    public function __construct()
    {
        $this->_db = DB::getInstance();
    }
    
    /**
     * Check conditions
     *
     * @param array $source     Global $_POST or $_GET array
     * @param array $items      conditions
     * @return object           validated object
     */
    public function check($source, $items = [])
    {
        $normalInputExists;
        $fileInput = null;

        // Iterate all input items.
        foreach ($items as $item => $rules) {
            $normalInputExists = true;

            // Check if input exists.
            if (!isset($source[$item])) {
                // If item does not exist, check FILE inputs.
                $fileInput = Input::getFileData($item);
                if (!$fileInput)
                    return $this;

                $normalInputExists = false;
            }

            $value       = $normalInputExists ? $source[$item] : $fileInput;
            $valueStatus = $normalInputExists ? !empty($source[$item]) : ($fileInput['error'] > 0 ? false : true);

            // Iterate all rules in the item.
            foreach ($rules as $rule => $ruleValue) {
                if ($rule === 'required' && !$valueStatus) {
                    $this->addError("{$rule}|{$item}|");
                } else if ($valueStatus) {
                    switch ($rule) {
                        case 'min':
                            if (strlen($value) < $ruleValue) {
                                $this->addError("{$rule}|{$item}|{$ruleValue}");
                            }
                            break;
                        case 'max':
                            if (strlen($value) > $ruleValue) {
                                $this->addError("{$rule}|{$item}|{$ruleValue}");
                            }
                            break;
                        case 'matches':
                            if ($value != $source[$ruleValue]) {
                                $this->addError("{$rule}|{$ruleValue}|{$item}");
                            }
                            break;
                        case 'unique':
                            $check = $this->_db->selectAll($ruleValue, array("LOWER($item)", '=', strtolower($value)));
                            if ($check->count()) {
                                $this->addError("{$rule}|{$item}|");
                            }
                            break;
                        case 'captcha':
                            $captcha = new Captcha();
                            if (!$captcha->verify($ruleValue)) {
                                $this->addError("{$rule}||");
                            }
                            break;
                        case 'url':
                            if (filter_var($value, FILTER_VALIDATE_URL) === false) {
                                $this->addError("{$rule}|{$item}|");
                            }
                            break;
                        case 'contain':
                            if (strpos($value, $ruleValue) === false) {
                                $this->addError("{$rule}|{$item}|{$ruleValue}");
                            }
                            break;
                        case 'media':   // rule_value f.e. ("video|image")
                            $notfound = true;
                            foreach (explode("|", $ruleValue) as $media) {
                                if ($media == self::checkMediaURL($value)) {
                                    $notfound = false;
                                    break;
                                }
                            }
                            if ($notfound) {
                                $this->addError("{$rule}|{$item}|".str_replace("|", ", ", $ruleValue));
                            }
                            break;
                        case 'only_letters':
                            if (preg_match('/[^A-Za-z]/', $value)) {
                                $this->addError("{$rule}|{$item}|");
                            }
                            break;
                        case 'letters_numbers':
                            if (preg_match('/[^A-Za-z0-9]/', $value)) {
                                $this->addError("{$rule}|{$item}|");
                            }
                            break;
                        case 'letters_numbers_undersc':
                            if (preg_match('/[^A-Za-z0-9_]/', $value)) {
                                $this->addError("{$rule}|{$item}|");
                            }
                            break;
                        case 'letters_numbers_undersc_space':
                            if (preg_match('/[^A-Za-z0-9_ ]/', $value)) {
                                $this->addError("{$rule}|{$item}|");
                            }
                            break;
                        case 'only_numbers':
                            if (preg_match('/[^0-9]/', $value)) {
                                $this->addError("{$rule}|{$item}|");
                            }
                            break;
                        case 'color':
                            if ($value[0] == '#') {
                                for ($i = 1; $i < strlen($value); $i++) {
                                    if (preg_match('/[^A-Fa-f0-9]/', $value[$i])) {
                                        $this->addError("{$rule}|{$item}|");
                                        break;
                                    }
                                }
                            } else {
                                $this->addError("{$rule}|{$item}|");
                            }
                            break;
                        case 'date':   // rule_value f.e. ("1920|1080")
                            if (strtotime($value) === false) {
                                $this->addError("{$rule}|{$item}|");
                            }
                            break;
                        case 'file_size_max':
                            if (Input::getFileData($item, 'size') > ($ruleValue * 1000)) {
                                $this->addError("{$rule}|{$item}|{$ruleValue}");
                            }
                            break;
                        case 'file_type_allowed':   // rule_value f.e. ("jpg|png")
                            $notfound = true;
                            foreach (explode("|", $ruleValue) as $fileType) {
                                if ($fileType == Input::getFileData($item, 'extension')) {
                                    $notfound = false;
                                    break;
                                }
                            }
                            if($notfound) {
                                $this->addError("{$rule}|{$item}|".str_replace("|", ", ", $ruleValue));
                            }
                            break;
                        case 'file_img_size_max':   // rule_value f.e. ("1920|1080")
                            $dimension = explode("|", $ruleValue);
                            if (Input::getFileData($item,'dimension')[0] > $dimension[0] || Input::getFileData($item,'dimension')[1] > $dimension[1]) {
                                $this->addError("{$rule}|{$item}|".str_replace("|", "x", $ruleValue));
                            }
                            break;
                        case 'file_img_size_min':   // rule_value f.e. ("1920|1080")
                            $dimension = explode("|", $ruleValue);
                            if (Input::getFileData($item,'dimension')[0] < $dimension[0] || Input::getFileData($item,'dimension')[1] < $dimension[1]) {
                                $this->addError("{$rule}|{$item}|".str_replace("|", "x", $ruleValue));
                            }
                            break;
                    }
                } else {
                }
            }
        }
            
        if (empty($this->_errors)) {
            $this->_passed = true;
        }
        
        return $this;
    }
        
    /**
     * Check if URL is linked to an image or a video
     *
     * @param string $url   media url
     * @return string       returns video|image or null
     */
    protected static function checkMediaURL($url)
    {
        if ($url) {
            if (preg_match('/(\.jpg|\.png|\.gif|\.bmp)$/', $url)) {
                return 'image';
            } elseif (strpos($url, "youtube.com/embed") !== false) {
                return 'video';
            }
        }
            
        return null;
    }
    
    /**
     * Push error to the error array
     *
     * @param string $error     error string
     * @return void
     */
    private function addError($error)
    {
        $this->_errors[] = $error;
    }
    
    /**
     * Errors which occured
     *
     * @return object       error validation object
     */
    public function errors()
    {
        return $this->_errors;
    }

    /**
     * Validate validation
     *
     * @return bool     success of validation
     */
    public function passed()
    {
        return $this->_passed;
    }
}

/* Examples ***
    <?php
        if(Input::exists())
        {
            $validation = (new Validate())->check(Input::all('post'), [
                'username'       => [
                    'required' => true,
                    'min'      => 3,
                    'max'      => 20,
                    'unique'   => 'phpbb_users'
                ],
                'password'       => [
                    'required' => true,
                    'min'      => 6,
                    'max'      => 30
                ],
                'password_again' => [
                    'required' => true,
                    'matches'  => 'password'
                ],
                'name'           => [
                    'required' => true,
                    'min'      => 3,
                    'max'      => 50
                ]
            ]);

            if ($validation->passed()) {
                echo "passed";
            } else {
                print_r($validation->errors());
            }
        }
    ?>

    <form action="#" method="post">
        <div class="field">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" value="" autocomplete="off" />
        </div>
        <div class="field">
            <label for="password">Choose your password</label>
            <input type="password" name="password" id="password" value="" />
        </div>
        <div class="field">
            <label for="password_again">Enter your password again</label>
            <input type="password" name="password_again" id="password_again" value="" />
        </div>
        <div class="field">
            <label for="name">Your name</label>
            <input type="text" name="name" id="name" value="" />
        </div>

        <input type="submit" value="Register" />
    </form>
*/
