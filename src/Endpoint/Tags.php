<?php

namespace Affinitybridge\NationBuilder\Endpoint;

use Affinitybridge\NationBuilder\Validator as Validator;

// @see http://nationbuilder.com/people_tags_api

class Tags extends EndpointAbstract {

  public function index($pageLimit = 10) {
    $response = $this->apiGet('tags', ['/limit' => [Validator::INT, 'maximum number of results to return']], ['limit' => $pageLimit]);

    $this->throwIfError($response);

    if (
        isset($response['body']['results'])
        &&
        is_array($response['body']['results'])
    ) {
        return $response['body'];
    }
    else {
        throw new \Exception('NationBuilder API did not return results for /tags: ' . $response['statusCode'] . ' (' . $response['body']['code'] . ') ' . $response['body']['message']);
    }
  }

  public function people($tag, $pageLimit = 10) {
    $response = $this->apiGet('tags/' . (string) $tag . '/people', ['/limit' => [Validator::INT, 'maximum number of results to return']], ['limit' => $pageLimit]);

    $this->throwIfError($response);

    if (
        isset($response['body']['results'])
        &&
        is_array($response['body']['results'])
    ) {
        return $response['body'];
    }
    else {
        throw new \Exception('NationBuilder API did not return results for /tags/' . (string) $tag . '/people: ' . $response['statusCode'] . ' (' . $response['body']['code'] . ') ' . $response['body']['message']);
    }
  }
}
