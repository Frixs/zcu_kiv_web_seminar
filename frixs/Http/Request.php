<?php

namespace Frixs\Http;

use Frixs\Routing\Router;
use Frixs\Config\Config;
use Frixs\Session\Session;
use Frixs\Http\Input;
use Frixs\Language\Lang;

class Request
{
    /**
     * Data to flash them in views.
     *
     * @var array
     */
    protected $_data = [];

    /**
     * Stored validated inputs in Validation instance.
     *
     * @var \Frixs\Validation\Validate
     */
    protected $_validation = null;

    /**
     * Return array key reserved for inputs.
     *
     * @var string
     */
    protected $inputDataArrayKey = 'input_data';

    /**
     * Return array key reserved for input error messages.
     *
     * @var string
     */
    protected $inputErrorArrayKey = 'input_errors';

    /**
     * Redirect back to previous page. If it doest exist go bach to root
     *
     * @return void
     */
    protected function goBack()
    {
        if (Router::previousPageAddress()) {
            Router::redirectTo(Router::previousPageAddress(), true);
        }

        Router::redirectTo(Config::get('app.root'), true);
    }

    /**
     * Add value to data session.
     *
     * @param string $key
     * @param mixed  $value
     * @return void
     */
    protected function addReturnValue($key, $value)
    {
        $this->_data[$key] = $value;
        $this->unsetDataSession();
        Session::put(Config::get('session.request_data_name'), $this->_data);
    }

    /**
     * Unset session with requested data.
     *
     * @return void
     */
    public function unsetDataSession()
    {
        Session::delete(Config::get('session.request_data_name'));
    }

    /**
     * Get data value.
     *
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
        if (Session::exists(Config::get('session.request_data_name'))) {
            $requestData = Session::get(Config::get('session.request_data_name'));
            return isset($requestData[$key]) ? $requestData[$key] : null;
        }

        return null;
    }

    /**
     * Get input data value.
     *
     * @param string $key
     * @return mixed
     */
    public function getInput($key)
    {
        $inputData = $this->get($this->inputDataArrayKey);
        if ($inputData) {
            return isset($inputData[$key]) ? $inputData[$key] : null;
        }

        return null;
    }

    /**
     * Add input data ($_POST) value.
     *
     * @return void
     */
    public function addInputs()
    {
        $this->addReturnValue($this->inputDataArrayKey, Input::getAll('post'));
    }

    /**
     * Get string error message.
     *
     * @param string $key
     * @return string
     */
    public function getInputError($key)
    {
        $inputErrorData = $this->get($this->inputErrorArrayKey);
        if ($inputErrorData) {
            return isset($inputErrorData[$key]) ? implode(' ', $inputErrorData[$key]) : null;
        }

        return null;
    }

    /**
     * Transfer validation error data to readable error messages and save it to return session.
     *
     * @param \Frixs\Validation\Validate $validation
     * @return void
     */
    public function addInputErrors($validation)
    {
        $errorMessages = [];

        foreach ($validation->errors() as $item) {
            /* type|input_name|rule */
            $arrString = explode("|", $item);

            $type       = $arrString[0];
            $input      = $arrString[1];
            $rule       = $arrString[2] ? $arrString[2] : "";
        
            /* Input name example: input-name_01 */
            $inputNamePieces = explode('_', $input);
            $inputName = (isset($inputNamePieces[1]) && is_numeric($inputNamePieces[1])) ? $inputNamePieces[0] : $input;
            $inputName = strtoupper(str_replace('-', ' ', $inputName));

            $sentese = Lang::get('validation.'. $type, ['input' => $inputName, 'rule' => $rule]);
            $errorMessages[$input][] = $sentese;
        }

        $this->addReturnValue($this->inputErrorArrayKey, $errorMessages);
    }
}
