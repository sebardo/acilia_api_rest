<?php

namespace App\Tests\Api;

/**
 * Class CrudProductTest
 * @package App\Tests\Api
 */

class CrudProductTest extends BaseTest
{
    /**
     * Create product test
     * @return mixed
     */
    public function testCreateProduct()
    {
        //do call and get response and client
        list($response, $client) = $this->call('POST','/api/product',  '{"name": "Product test", "price": 1250.99, "currency": "EUR"}');

        //asserts
        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        $this->assertTrue(isset($response['status']));
        $this->assertTrue(isset($response['message']));
        $this->assertTrue(isset($response['id']));
        $this->assertEquals('success', $response['status']);
        $this->assertEquals('Product created!', $response['message']);
        $this->assertNotEmpty($response['id']);

        //return entity id for next test
        return $response['id'];
    }

    /**
     * Read product test
     * @depends testCreateProduct
     */
    public function testReadProduct($id)
    {
        //do call and get response and client
        list($response, $client) = $this->call('GET','/api/product/'.$id);

        //asserts
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertTrue(isset($response['id']));
        $this->assertTrue(isset($response['name']));
        $this->assertEquals('Product test', $response['name']);
        $this->assertEquals(1250.99, $response['price']);
        $this->assertEquals('EUR', $response['currency']);
        $this->assertNull($response['category']);
        $this->assertFalse($response['featured']);

        //return entity id for next test
        return $response['id'];
    }

    /**
     * Update product test
     * @depends testReadProduct
     */
    public function testUpdateProduct($id)
    {
        //create product to update product
        list($response, $client) = $this->call('POST','/api/category',  '{"name": "Category test"}');

        //do call and get response and client
        list($response, $client) = $this->call('PUT','/api/product/'.$id, '{"category": '.$response['id'].'}', $client);

        //asserts
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertTrue(isset($response['status']));
        $this->assertTrue(isset($response['message']));
        $this->assertTrue(isset($response['id']));
        $this->assertEquals('success', $response['status']);
        $this->assertEquals('Product updated!', $response['message']);
        $this->assertNotEmpty($response['id']);

        //read product to check product added
        list($response, $client) = $this->call('GET','/api/product/'.$id, null, $client);
        $this->assertTrue(isset($response['category']));
        $this->assertEquals('Category test', $response['category']);

        //return entity id for next test
        return $response['id'];
    }

    /**
     * Delete product test
     * @depends testUpdateProduct
     */
    public function testDeleteProduct($id)
    {
        //do call and get response and client
        list($response, $client) = $this->call('DELETE','/api/product/'.$id);

        //asserts
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertTrue(isset($response['status']));
        $this->assertTrue(isset($response['message']));
        $this->assertEquals('success', $response['status']);
        $this->assertEquals('Product deleted!', $response['message']);
    }

    /**
     * Product featured test
     *
     * To run the testcase:
     * @code
     * ./bin/phpunit --filter testFeaturedProduct tests/Api/CrudProductTest.php
     * @endcode
     *
     */
    public function testFeaturedProduct()
    {
        //create a featured product
        list($response, $client) = $this->call('POST','/api/product',  '{"name": "Product test", "price": 1000.25, "currency": "USD", "featured": true}');

        //read product to check product added
        list($response, $client) = $this->call('GET','/api/product/'.$response['id'], null, $client);
        $this->assertTrue($response['featured']);

        //do call and get response featured products
        list($response, $client) = $this->call('GET','/api/product/featured', null, $client);

        //asserts
        $this->assertGreaterThan(0, count($response));

        //now check conversor result
        list($response, $client) = $this->call('GET','/api/product/featured?currency=EUR', null, $client);

        foreach ($response as $product){
            $this->assertEquals('EUR', $product['currency']);
        }
    }
}
