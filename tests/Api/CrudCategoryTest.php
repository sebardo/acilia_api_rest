<?php

namespace App\Tests\Api;

/**
 * Class CrudCategoryTest
 * @package App\Tests\Api
 */

class CrudCategoryTest extends BaseTest
{
    /**
     * Create category test
     * @return mixed
     */
    public function testCreateCategory()
    {
        //do call and get response and client
        list($response, $client) = $this->call('POST','/api/category',  '{"name": "Category test"}');

        //asserts
        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        $this->assertTrue(isset($response['status']));
        $this->assertTrue(isset($response['message']));
        $this->assertTrue(isset($response['id']));
        $this->assertEquals('success', $response['status']);
        $this->assertEquals('Category created!', $response['message']);
        $this->assertNotEmpty($response['id']);

        //return entity id for next test
        return $response['id'];
    }

    /**
     * Read category test
     * @depends testCreateCategory
     */
    public function testReadCategory($id)
    {
        //do call and get response and client
        list($response, $client) = $this->call('GET','/api/category/'.$id);

        //asserts
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertTrue(isset($response['id']));
        $this->assertTrue(isset($response['name']));
        $this->assertEquals('Category test', $response['name']);
        $this->assertNull($response['description']);
        $this->assertNotEmpty($response['id']);
        $this->assertNotEmpty($response['name']);

        //return entity id for next test
        return $response['id'];
    }

    /**
     * Update category test
     * @depends testReadCategory
     */
    public function testUpdateCategory($id)
    {
        //do call and get response and client
        list($response, $client) = $this->call('PUT','/api/category/'.$id, '{"description": "Description category test"}');

        //asserts
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertTrue(isset($response['status']));
        $this->assertTrue(isset($response['message']));
        $this->assertTrue(isset($response['id']));
        $this->assertEquals('success', $response['status']);
        $this->assertEquals('Category updated!', $response['message']);
        $this->assertNotEmpty($response['id']);

        //return entity id for next test
        return $response['id'];
    }

    /**
     * Delete category test
     * @depends testUpdateCategory
     */
    public function testDeleteCategory($id)
    {
        //do call and get response and client
        list($response, $client) = $this->call('DELETE','/api/category/'.$id);

        //asserts
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertTrue(isset($response['status']));
        $this->assertTrue(isset($response['message']));
        $this->assertEquals('success', $response['status']);
        $this->assertEquals('Category deleted!', $response['message']);
    }
}
