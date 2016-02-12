<?php

namespace Affinitybridge\NationBuilder\Endpoint;

use Affinitybridge\NationBuilder\Validator as Validator;

// @see http://nationbuilder.com/people_tags_api

class Tags extends EndpointAbstract {

  public function index($pageLimit = 10) {
    return $this->apiGet('tags', ['/limit' => [Validator::INT, 'maximum number of results to return']], ['limit' => $pageLimit]);
  }

  public function people($tag, $pageLimit = 10) {
    return $this->apiGet('tags/' . (string) $tag . '/people', ['/limit' => [Validator::INT, 'maximum number of results to return']], ['limit' => $pageLimit]);
  }
}
