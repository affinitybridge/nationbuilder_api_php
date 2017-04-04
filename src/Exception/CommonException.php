<?php

namespace Affinitybridge\NationBuilder\Exception;

class CommonException extends \RuntimeException {
    protected $messagePrefix = 'NationBuilder API error for ';
    protected $response = null;

    public function __construct($httpMethod, $restMethodPath, $response = null, $previous = null) {
        $this->response = $response;
        $code = $this->getCodeFromResponse($response);
        $message = $this->makeMessage($httpMethod, $restMethodPath, $response);
        parent::__construct($message, $code, $previous);
    }

    protected function getCodeFromResponse($response = null) {
        if (is_callable([$response, 'getStatusCode'])) {
            return $response->getStatusCode();
        }
        if (isset($response['statusCode'])) {
            return $response['statusCode'];
        }
        return 0;
    }

    public function getAdditionalDetailsHtml() {
        if (
            isset($this->response['body'])
            &&
            is_array($this->response['body'])
            &&
            (0 == count(array_diff(array_keys($this->response['body']), ['code', 'message'])))
        ) {
            return '<p>(' . $this->response['body']['code'] . ') ' . $this->response['body']['message'] . '</p>';
        }
        else {
            return '<pre>' . PHP_EOL . var_export($this->response, true) . PHP_EOL . '</pre>';
        }
    }

    protected function makeMessage($httpMethod, $restMethodPath, $response = null) {
        $httpMethod = (string) $httpMethod;
        $restMethodPath = (string) $restMethodPath;
        $code = $this->getCodeFromResponse($response);

        $message = $this->messagePrefix . $httpMethod . ' /' . $restMethodPath . '/: ' . $code;
        return $message;
    }
}
