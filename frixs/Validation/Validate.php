<?php

//TODO move to Middleware
namespace Frixs\Validate;

class Validate
{
    private $_passed = false,
        $_errors = array(),
        $_db     = null;
        
    public function __construct()
    {
        $this->_db = Db::getInstance();
    }
    
    /**
     * Check conditions
     *
     * @param array $source     Global $_POST or $_GET array
     * @param array $items      conditions
     * @return object           validated object
     */
    public function check($source, $items = array())
    {
        foreach ($items as $item => $rules) {
            foreach ($rules as $rule => $rule_value) {
                $value = trim($source[$item]);
                $item = escape($item);

                if (($rule === 'required' && empty($value)) && $rule_value) {
                    $this->addError("{$rule}|{$item}|");
                } elseif (!empty($value)) {
                    switch ($rule) {
                        case 'min':
                            if (strlen($value) < $rule_value) {
                                $this->addError("{$rule}|{$item}|{$rule_value}");
                            }
                            break;
                        case 'max':
                            if (strlen($value) > $rule_value) {
                                $this->addError("{$rule}|{$item}|{$rule_value}");
                            }
                            break;
                        case 'matches':
                            if ($value != $source[$rule_value]) {
                                $this->addError("{$rule}|{$rule_value}|{$item}");
                            }
                            break;
                        case 'unique':
                            $check = $this->_db->selectAll($rule_value, array("LOWER($item)", '=', strtolower($value)));
                            if ($check->count()) {
                                $this->addError("{$rule}|{$item}|");
                            }
                            break;
                        case 'captcha':
                            $captcha = new Captcha();
                            if (!$captcha->verify($rule_value)) {
                                $this->addError("{$rule}||");
                            }
                            break;
                        case 'url':
                            if (filter_var($value, FILTER_VALIDATE_URL) === false) {
                                $this->addError("{$rule}|{$item}|");
                            }
                            break;
                        case 'contain':
                            if (strpos($value, $rule_value) === false) {
                                $this->addError("{$rule}|{$item}|{$rule_value}");
                            }
                            break;
                        case 'media':   // rule_value f.e. ("video|image")
                            $notfound = true;
                            foreach (explode("|", $rule_value) as $media) {
                                if ($media == self::checkMediaURL($value)) {
                                    $notfound = false;
                                    break;
                                }
                            }
                            if ($notfound) {
                                $this->addError("{$rule}|{$item}|".str_replace("|", ", ", $rule_value));
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
                        case 'letters_numbers_separators':
                            if (preg_match('/[^A-Za-z0-9_]/', $value)) {
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
                    }
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
    public static function checkMediaURL($url)
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
            $validate = new Validate();
            $validation = $validate->check($_POST, array(
                'username'       => array(
                    'required' => true,
                    'min'      => 3,
                    'max'      => 20,
                    'unique'   => 'phpbb_users'
                ),
                'password'       => array(
                    'required' => true,
                    'min'      => 6,
                    'max'      => 30
                ),
                'password_again' => array(
                    'required' => true,
                    'matches'  => 'password'
                ),
                'name'           => array(
                    'required' => true,
                    'min'      => 3,
                    'max'      => 50
                )
            ));

            if( $validation->passed() )
            {
                echo "passed";
            }
            else
            {
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