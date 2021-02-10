<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, Product::class);
        $this->manager = $manager;
    }

    /**
     * @param $name
     * @param $price
     * @param $category
     * @param $currency
     * @param $featured
     */
    public function saveProduct($product)
    {
        $this->manager->persist($product);
        $this->manager->flush();

        return $product;
    }

    /**
     * @param Product $product
     * @return Product
     */
    public function updateProduct(Product $product): Product
    {
        $this->manager->persist($product);
        $this->manager->flush();

        return $product;
    }

    /**
     * @param Product $product
     */
    public function removeProduct(Product $product)
    {
        $this->manager->remove($product);
        $this->manager->flush();
    }
}
