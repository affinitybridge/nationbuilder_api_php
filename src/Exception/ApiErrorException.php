<?php

namespace Affinitybridge\NationBuilder\Exception;

class ApiErrorException extends \RuntimeException {
    protected $response = null;

    public static function isError($response) {
        return (
            (400 <= $response['statusCode'])
            ||
            (
                isset($response['body']['code'], $response['body']['message'])
                &&
                // DO NOT add "no_matches". The "people/match" method does return
                // that, but it's not really an error. Instead it just should
                // return an empty set.
                (1 === preg_match('{error|not_found|bad_request|not_acceptable}i', $response['body']['code']))
            )
        );
    }

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

        $message = 'NationBuilder API returned an error for ' . $httpMethod . ' /' . $restMethodPath . '/: ' . $code . $details;
        return $message;
    }

    // A collection of errors encountered so far:
    //
    // 400
    // {
    //     "code": "bad_request",
    //     "message": "Bad Request."
    // }
    //
    // 400
    // {
    //     "code": "missing_parameters",
    //     "message": "Missing Parameters.",
    //     "parameters": [
    //         "location: lat, lng required"
    //     ]
    // }
    //
    // 400
    // {
    //     "code": "missing_parameters",
    //     "message": "Missing Parameters.",
    //     "parameters": [
    //         "person"
    //     ]
    // }
    //
    // The following is really NOT AN ERROR:
    // 400
    // {
    //     "code": "no_matches",
    //     "message": "No people matched the given criteria.",
    // }
    //
    // 404
    // {
    //     "code": "not_found",
    //     "message": "Record not found"
    // }
    //
    // 406
    // {
    //     "code": "not_acceptable",
    //     "message": "I can only talk JSON. Please set 'Accept' and 'Content-Type' to 'application/json' in your http request header."
    // }
    //
    // 409 ("Resource Conflict". Haven't seen "code" nor "message" for that one.)
    //
    // 500
    // {
    //     "code": "server_error",
    //     "message": "You have encountered a server error."
    // }
}
