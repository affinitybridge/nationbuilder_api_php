<?php

namespace Affinitybridge\NationBuilder\Exception;

class ApiErrorException extends CommonException {
    protected $messagePrefix = 'NationBuilder API returned an error for ';

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
                (1 === preg_match('{error|not_found|bad_request|missing_parameters|not_acceptable}i', $response['body']['code']))
            )
        );
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
