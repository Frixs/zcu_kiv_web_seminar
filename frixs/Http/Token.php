<?php

namespace Frixs\Http;

use Frixs\Config\Config;
use Frixs\Session\Session;
use Frixs\Http\Input;

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
     * Generated token for the current stream.
     *
     * @var string
     */
    protected static $_token = null;

    /**
     * Get or generate if does not exist a token stored in a session.
     *
     * @return string       session/token value
     */
    public static function get()
    {
        if (self::$_token) {
            return self::$_token;
        }
        
        return self::$_token = Session::put(Config::get('session.token_name'), self::generate());
    }

    /**
     * Generate a token.
     *
     * @return string       token value
     */
    protected static function generate()
    {
        return md5(uniqid());
    }
    
    /**
     * Check if token value exists.
     * Check if token input from correct form exists.
     * Delete used token on success.
     *
     * If $name is null then will be used default input token name.
     *
     * @param string $tokenName      token name
     * @return bool
     */
    public static function validation($name = null)
    {
        $tokenName      = Config::get('session.token_name');
        $flashedInput   = $name ? self::flashTokenInput($name) : Config::get('session.token_name');

        $token     = Input::get($flashedInput); // = null, if input does not exist.
        
        if (Session::exists($tokenName) && $token === Session::get($tokenName)) {
            Session::delete($tokenName);
            return true;
        }
            
        return false;
    }
    
    /**
     * Flash token input name.
     *
     * @param string $name
     * @return string
     */
    public static function flashTokenInput($name)
    {
        $session = Session::get(Config::get('session.token_name') .'_'. $name);
        Session::delete(Config::get('session.token_name') .'_'. $name);
        return $session;
    }

    /**
     * Create token input name.
     *
     * If $name is null then will be used default input token name.
     *
     * @param string $name
     * @return string
     */
    public static function createTokenInput($name = null)
    {
        if (!$name) {
            return Config::get('session.token_name');
        }

        $hash = md5($name . rand());
        return Session::put(Config::get('session.token_name') .'_'. $name, $hash);
    }
}

/* Examples ***
    <?php
        if(Input::exists()) {
            if(Token::validation('request_name')) {
                ...
            }
        }
    ?>

    <form action="#" method="post">
        <!-- TOKEN -->
        <input type="hidden" name="<?= Token::createTokenInput('request_name') ?>" value="<?= Token::get() ?>" />
        {{-- TOKEN --}}
		<input type="hidden" name="{{ instance('Token')::createTokenInput('request_name') }}" value="{{ instance('Token')::get() }}" />
        
        <!-- TOKEN with default input name -->
        <input type="hidden" name="<?= Token::createTokenInput() ?>" value="<?= Token::get() ?>" />
        {{-- TOKEN with default input name --}}
		<input type="hidden" name="{{ instance('Token')::createTokenInput() }}" value="{{ instance('Token')::get() }}" />
    </form>
*/
