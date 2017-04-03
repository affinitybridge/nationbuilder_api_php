<?php

namespace Affinitybridge\NationBuilder\Endpoint;

use Affinitybridge\NationBuilder\Validator as Validator;
use Affinitybridge\NationBuilder\Exception\ApiErrorException as ApiErrorException;
use Affinitybridge\NationBuilder\Exception\UnexpectedResponseStructureException as UnexpectedResponseStructureException;

// @see http://nationbuilder.com/people_tags_api

class Tags extends EndpointAbstract {

  public function index($pageLimit = 10) {
    $response = $this->apiGet('tags', ['/limit' => [Validator::INT, 'maximum number of results to return']], ['limit' => $pageLimit]);

    if (ApiErrorException::isError($response)) {
        throw new ApiErrorException('GET', 'tags', $response);
    }

    if (
        isset($response['body']['results'])
        &&
        is_array($response['body']['results'])
    ) {
        return $response['body'];
    }

    throw new UnexpectedResponseStructureException('GET', 'tags', $response);
  }

  public function people($tag, $pageLimit = 10) {
    $response = $this->apiGet('tags/' . (string) $tag . '/people', ['/limit' => [Validator::INT, 'maximum number of results to return']], ['limit' => $pageLimit]);

    if (ApiErrorException::isError($response)) {
        throw new ApiErrorException('GET', 'tags/' . (string) $tag . '/people', $response);
    }

    if (
        isset($response['body']['results'])
        &&
        is_array($response['body']['results'])
    ) {
        return $response['body'];
    }

    throw new UnexpectedResponseStructureException('GET', 'tags/' . (string) $tag . '/people', $response);
  }
}
