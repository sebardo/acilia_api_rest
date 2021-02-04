<?php

namespace App\Tests\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * ClassBaseTest
 * @package App\Tests\Api
 */

abstract class BaseTest extends WebTestCase
{
    /**
     * Get the access token
     * @param $client
     * @return mixed
     */
    protected function getToken($client)
    {
        //get token
        $client->request('POST', '/token', [
            'grant_type' => 'client_credentials',
            'client_id' => 'de2396e3ee0bc41c1c60fb1658be96ec',
            'client_secret' => 'a576c347cb938debb1c11b28e08080ad06d71d433a68f62ec762e210355ec1efdb9118d95070780efc9e66b2b232e77b27d41127402cc7a1c9c71f4347a8e583',
            'scope' => ''
        ]);
        return  json_decode($client->getResponse()->getContent(), true);
    }

    /**
     * Generic call for requests
     * @param $method
     * @param $uri
     * @param null $content
     * @return array
     */
    protected function call($method, $uri, $content=null, $client=null)
    {
        if(is_null($client)) $client = static::createClient();

        //get token
        $response = $this->getToken($client);

        $client->request(
            $method,
            $uri,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$response['access_token'],
            ],
            $content
        );

        //return array response
        return  [json_decode($client->getResponse()->getContent(), true), $client];
    }
}
