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
            'client_id' => getenv('CLIENT_ID'),
            'client_secret' => getenv('CLIENT_SECRET'),
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
