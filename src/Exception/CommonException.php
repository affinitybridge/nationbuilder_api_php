<?php

namespace Affinitybridge\NationBuilder\Exception;

class CommonException extends \RuntimeException {
    protected $messagePrefix = 'NationBuilder API error for ';
    protected $response = null;

    public function __construct($httpMethod, $restMethodPath, $response = null, $previous = null) {
        $code = $this->getCodeFromResponse($response);
        $message = $this->makeMessage($httpMethod, $restMethodPath, $response);
        parent::__construct($message, $code, $previous);
        $this->response = $response;
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

    protected function makeMessage($httpMethod, $restMethodPath, $response = null) {
        $httpMethod = (string) $httpMethod;
        $restMethodPath = (string) $restMethodPath;
        $code = $this->getCodeFromResponse($response);

        $details = '';
        if (isset($response['body']['code'])) {
            $details .= ' (' . $response['body']['code'] . ')';
        }
        if (isset($response['body']['message'])) {
            $details .= ' ' . $response['body']['message'];
        }

        if ('' == $details) {
            $details = ' <pre>' . PHP_EOL . var_export($response, true) . PHP_EOL . '</pre>';
        }

        $message = $this->messagePrefix . $httpMethod . ' /' . $restMethodPath . '/: ' . $code . $details;
        return $message;
    }
}
