<?php

namespace Frixs\Http;

/**
 *  Token is for CSRF protection.
 *
 *  Every form has own hash code for own inputs. That is prevention to avoid wrong inputs from other form (or URL - $_GET).
 *  Token also prevent to submit form after page reload.
 *  <input type="hidden" name="token" value="<?php echo Token::generate(); ?>" />
 */
class Token
{
    /**
     * Generate a token stored in a session
     *
     * @return string       session value
     */
    public static function generate()
    {
        return Session::put(
            Config::get('session.token_name'), md5(uniqid())
        ); //= $_SESSION['token'] = md5(uniqid());
    }
        
    /**
         *  Check token value
         *
         *  @param token    token value
         *  @return         boolean, validation status
         */
    public static function check($token)
    {
        $tokenName = Config::get('session.token_name');
            
        if (Session::exists($tokenName) && $token === Session::get($tokenName)) {
            Session::delete($tokenName);
            return true;
        }
            
        return false;
    }
        
    /**
         *  Generate and flash token hashed name
         *
         *  @param name     session name
         *  @param generate TRUE - generate a new flash session
         *                  FALSE - get and destroy flash session
         *  @return         boolean, validation status
         */
    public static function flash($name, $generate = false)
    {
        if ($generate) {
            $hash = md5($name.'_'.rand());
            return Session::put($name, $hash);
        } else {
            $session = Session::get($name);
            Session::delete($name);
            return $session;
        }
    }
}

/* Examples ***
    <?php
        if(Input::exists())
        {
            if( Token::check(Input::get('token_inp')) )
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

        <input type="hidden" name="token_inp" value="<?php echo Token::generate(); ?>" />
        <input type="submit" value="Register" />
    </form>
*/
