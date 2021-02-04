<?php

namespace App\Tests\Api;

/**
 * Class ApiAccessTest
 * @package App\Tests\Api
 */

class ApiAccessTest extends BaseTest
{
    /**
     * Test retrieve access token
     */
    public function testToken()
    {
        $client = static::createClient();

        //get token
        $response = $this->getToken($client);

        //asserts
        $this->assertTrue(isset($response['token_type']));
        $this->assertTrue(isset($response['expires_in']));
        $this->assertTrue(isset($response['access_token']));
        $this->assertEquals('Bearer', $response['token_type']);
        $this->assertEquals('3600', $response['expires_in']);
        $this->assertNotEmpty($response['access_token']);

    }

    /**
     * Check security api unauthorized access
     */
    public function testUnauthorizedAccess()
    {
        $client = static::createClient();

        //try to access to endpoint without accecss token
        $client->request('GET', '/api/categories');

        //assert 401 http response Unauthorized
        $this->assertEquals(401, $client->getResponse()->getStatusCode());
    }

    /**
     * Check security api authorized access
     */
    public function testAuthorizedAccess()
    {
        //do call and get response and client
        list($response, $client) = $this->call('GET','/api/categories');

        //asserts
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
