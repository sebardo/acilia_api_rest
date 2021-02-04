<?php

namespace App\Tests\Service;

use App\Entity\Product;
use App\Service\ConversorManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ConversorManagerTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    /**
     * Test exchange method
     *
     * To run the testcase:
     * @code
     * ./bin/phpunit --filter testExchange tests/Service/ConversorManagerTest.php
     * @endcode
     *
     */
    public function testExchange()
    {
        //test exchange method
        $conversorManager = new ConversorManager($this->entityManager);
        $response = $conversorManager->getExchange('EUR', 'USD');

        //asserst
        $this->assertTrue(isset($response['rates']));
        $this->assertTrue(isset($response['base']));
        $this->assertTrue(isset($response['date']));
        $this->assertEquals('EUR', $response['base']);
    }

    /**
     * Test convertProductPrice method
     *
     * To run the testcase:
     * @code
     * ./bin/phpunit --filter testConvertProductPrice tests/Service/ConversorManagerTest.php
     * @endcode
     *
     */
    public function testConvertProductPrice()
    {
        $priceEur = 1000;
        $product = new Product();
        $product->setName('Product test');
        $product->setPrice(1000);
        $product->setCurrency('EUR');

        //test exchange method
        $conversorManager = new ConversorManager($this->entityManager);
        $response = $conversorManager->convertProductPrice($product, 'USD');

        //asserst
        $this->assertGreaterThan($priceEur ,$response);
    }
}
