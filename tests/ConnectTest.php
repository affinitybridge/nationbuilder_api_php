<?php

namespace Affinitybridge\NationBuilder\Tests;

use Affinitybridge\NationBuilder\Environment\Callback as CallbackEnvironment;
use Affinitybridge\NationBuilder\Container as Container;
use Affinitybridge\NationBuilder\Api as Api;

class ConnectTest extends \PHPUnit_Framework_TestCase
{
    protected $credentials = [];

    protected function setUp()
    {
        $filename = dirname(dirname(__FILE__)) . '/.test_credentials.json';
        if (file_exists($filename)) {
            $this->credentials = json_decode(file_get_contents($filename), true);
        }
    }

    public function assertPreConditions()
    {
        $this->assertArrayHasKey('api_base_url', $this->credentials);
        $this->assertArrayHasKey('access_token', $this->credentials);
    }

    public function testPeopleIndex()
    {
        $dummyContainer = new Container();
        $env = new CallbackEnvironment($dummyContainer);
        $callbacks = new CallbacksHelper($this->credentials);

        $env->setCallback('credentialsLoad', [$callbacks, 'credentialsLoad']);

        $env->setCallback('httpCall', [$callbacks, 'httpCall']);

        $api = new Api(['environment' => $env]);
        $connection = $api->connect('dummyConnectionId');
        $people = $connection->people();

        $result = $people->index();

        $this->assertArrayHasKey('results', $result);
        $this->assertArrayHasKey('next', $result);
        $this->assertArrayHasKey('prev', $result);

        $this->assertInternalType('array', $result['results'], 'Retrieving the index of people should result in an array.');
        foreach ($result['results'] as $abbrPerson) {
            $this->assertArrayHasKey('id', $abbrPerson);
            $this->assertInternalType('integer', $abbrPerson['id']);
        }
    }
}
