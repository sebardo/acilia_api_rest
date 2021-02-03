<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Service\ConversorManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProductController
 * @package App\Controller
 *
 * @Route(path="/api/")
 */
class ProductController
{

    private $productRepository;
    private $categoryRepository;

    public function __construct(ProductRepository $productRepository, CategoryRepository $categoryRepository)
    {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @Route("product", name="add_product", methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $name = $data['name'];
        $price = $data['price'];
        $cateogry = isset($data['category']) ? $this->categoryRepository->find($data['category']) : null;
        $currency = isset($data['currency']) ? $data['currency'] : null;
        $featured = isset($data['featured']) ? $data['featured'] : false;

        if (empty($name) || empty($price)) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        $this->productRepository->saveProduct($name, $price, $cateogry, $currency, $featured);

        return new JsonResponse(['status' => 'Product created!'], Response::HTTP_CREATED);
    }

    /**
     * @Route("product/{id}", name="get_one_product", methods={"GET"}, requirements={ "id" = "^(?!featured).+" })
     */
    public function get($id): JsonResponse
    {
        $product = $this->productRepository->findOneBy(['id' => $id]);

        $data = [
            'id' => $product->getId(),
            'name' => $product->getName(),
            'price' => $product->getPrice(),
            'category' => is_null($product->getCategory()) ? null : $product->getCategory()->getName(),
            'currency' => $product->getCurrency(),
            'featured' => $product->getFeatured(),
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("products", name="get_all_products", methods={"GET"})
     */
    public function getAll(): JsonResponse
    {
        $products = $this->productRepository->findAll();
        $data = [];

        foreach ($products as $product) {
            $data[] = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'category' => is_null($product->getCategory()) ? null : $product->getCategory()->getName(),
                'currency' => $product->getCurrency(),
                'featured' => $product->getFeatured(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("product/{id}", name="update_product", methods={"PUT"})
     */
    public function update($id, Request $request): JsonResponse
    {
        $product = $this->productRepository->findOneBy(['id' => $id]);
        $data = json_decode($request->getContent(), true);

        empty($data['name']) ? true : $product->setName($data['name']);
        empty($data['price']) ? true : $product->setPrice($data['price']);
        empty($data['category']) ? true : $product->setCategory($this->categoryRepository->find($data['category']));
        empty($data['currency']) ? true : $product->setCurrency($data['currency']);
        empty($data['featured']) ? true : $product->setFeatured($data['featured']);

        $updatedProduct = $this->productRepository->updateProduct($product);

        return new JsonResponse(['status' => 'Product updated!'], Response::HTTP_OK);
    }

    /**
     * @Route("product/{id}", name="delete_product", methods={"DELETE"})
     */
    public function delete($id): JsonResponse
    {
        $product = $this->productRepository->findOneBy(['id' => $id]);

        $this->productRepository->removeProduct($product);

        return new JsonResponse(['status' => 'Product deleted'], Response::HTTP_OK);
    }

    /**
     * @Route("product/featured", name="featured_products", methods={"GET"})
     */
    public function featured(Request $request, ConversorManager $conversor): JsonResponse
    {
        $products = $this->productRepository->findBy(['featured' => true]);
        $data = $request->query->all();

        $returnValues = [];
        foreach ($products as $product) {
            $item = [
                'id' => $product->getId(),
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'category' => is_null($product->getCategory()) ? null : $product->getCategory()->getName(),
                'currency' => $product->getCurrency(),
            ];

            $currency = null;
            //Just if currency filter is setted and product have a diferent currency convert do proccess convertion
            if(isset($data['currency']) &&  ($product->getCurrency() != $data['currency'])){
                $item['price'] = $conversor->convertProductPrice($product, $data['currency']);
                $item['currency'] = $data['currency'];
            }

            $returnValues[] = $item;
        }

        return new JsonResponse($returnValues, Response::HTTP_OK);
    }
}
