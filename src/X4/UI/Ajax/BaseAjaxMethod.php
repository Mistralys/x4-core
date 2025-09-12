<?php

declare(strict_types=1);

namespace Mistralys\X4\UI\Ajax;

use AppUtils\ArrayDataCollection;
use AppUtils\ConvertHelper\JSONConverter;
use AppUtils\Request;
use Mistralys\X4\UI\UserInterface;
use Mistralys\X4\X4Application;

abstract class BaseAjaxMethod implements AjaxMethodInterface
{
    public const string RESPONSE_KEY_STATUS = 'status';
    public const string RESPONSE_KEY_MESSAGE = 'message';
    public const string RESPONSE_KEY_CODE = 'code';
    public const string RESPONSE_KEY_DATA = 'data';

    protected AjaxMethods $methods;
    protected UserInterface $ui;
    protected X4Application $application;
    protected Request $request;

    public function __construct(AjaxMethods $methods)
    {
        $this->methods = $methods;
        $this->ui = $methods->getUI();
        $this->application = $this->ui->getApplication();
        $this->request = $this->ui->getRequest();

        $this->init();
    }

    abstract protected function init() : void;

    public function process() : never
    {
        $this->preProcess();
        $this->_process();
    }

    abstract protected function _process() : never;
    abstract protected function preProcess() : void;

    protected function sendError(string $message, int $code, array $payload=array()) : never
    {
        $this->methods->sendError($message, $code, $payload);
    }

    protected function sendSuccess(string $message='', array $payload=array()) : never
    {
        $this->methods->sendSuccess($message, $payload);
    }

    /**
     * Utility method to send a JSON payload to another service
     * via HTTP POST, and receive a JSON response.
     *
     * The response must contain at least a "status" key, with
     * the value `success` or `error`. In case of `success`, the
     * response may contain a `data` key with an array value.
     * In case of `error`, the response may contain a `message`
     * key with a human-readable error message, and a `code` key
     * with a numeric error code.
     *
     * @param string $serviceURL
     * @param array $data
     * @return ArrayDataCollection
     * @throws AjaxMethodException
     * @throws JSONConverter\JSONConverterException
     */
    protected function sendJSONViaPost(string $serviceURL, array $data) : ArrayDataCollection
    {
        $ch = curl_init($serviceURL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, JSONConverter::var2json($data));
        $result = curl_exec($ch);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            throw new AjaxMethodException(
                'cURL error: ' . $curlError,
                '',
                500
            );
        }

        $response = json_decode($result, true);
        if (!is_array($response) || !isset($response[self::RESPONSE_KEY_STATUS])) {
            throw new AjaxMethodException(
                sprintf('Invalid response from server, no %s in response payload.', self::RESPONSE_KEY_STATUS),
                '',
                400
            );
        }

        if ($response[self::RESPONSE_KEY_STATUS] === 'success') {
            return ArrayDataCollection::create($response[self::RESPONSE_KEY_DATA] ?? array());
        }

        throw new AjaxMethodException(
            $response[self::RESPONSE_KEY_MESSAGE] ?? 'Unknown error.',
            '',
            $response[self::RESPONSE_KEY_CODE] ?? 400
        );
    }
}
