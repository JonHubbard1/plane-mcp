<?php

namespace Technoliga\PlaneMcp\Tests;

use PHPUnit\Framework\TestCase;
use Technoliga\PlaneMcp\PlaneClient;

class PlaneClientTest extends TestCase
{
    public function test_client_can_be_instantiated()
    {
        $config = [
            'base_url' => 'https://api.plane.so',
            'api_token' => 'test_token',
        ];

        $client = new PlaneClient($config);

        $this->assertInstanceOf(PlaneClient::class, $client);
    }

    public function test_client_has_required_properties()
    {
        $config = [
            'base_url' => 'https://api.plane.so',
            'api_token' => 'test_token',
        ];

        $client = new PlaneClient($config);

        $this->assertObjectHasProperty('httpClient', $client);
        $this->assertObjectHasProperty('baseUrl', $client);
        $this->assertObjectHasProperty('apiToken', $client);
    }
}