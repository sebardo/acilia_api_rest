<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Service\ValidatorManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

/**
 * Class CategoryController
 * @package App\Controller
 *
 * @Route(path="/api/")
 */
class CategoryController
{
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Add category
     *
     * This call add a new category with mandatory params like : name and description.
     *
     * @Route("category", name="add_category", methods={"POST"})
     * @OA\RequestBody(
     *     description="Json request",
     *     required=true,
     *     @OA\JsonContent(
     *         type="string",
     *     )
     * )
     */
    public function add(Request $request, ValidatorManager $validatorManager): JsonResponse
    {
        //validation
        $validEntity = $validatorManager->validate($request, new Category(), ['create']);
        if($validEntity instanceof JsonResponse) return $validEntity;

        //save entity
        $category = $this->categoryRepository->saveCategory($validEntity);

        return new JsonResponse(['status' => 'success', 'message' => 'Category created!', 'id' => $category->getId()], Response::HTTP_CREATED);
    }

    /**
     * Get a category detail
     *
     * This call return a category detail.
     *
     * @Route("category/{id}", name="get_one_category", methods={"GET"})
     */
    public function get($id): JsonResponse
    {
        $category = $this->categoryRepository->findOneBy(['id' => $id]);

        $data = [
            'id' => $category->getId(),
            'name' => $category->getName(),
            'description' => $category->getDescription(),
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * Update a category
     *
     * This call modify a category data.
     *
     * @Route("category/{id}", name="update_category", methods={"PUT"})
     * @OA\RequestBody(
     *     request="name",
     *     description="Json request",
     *     required=true,
     *     @OA\JsonContent(
     *         type="string",
     *     )
     * )
     */
    public function update($id, Request $request, ValidatorManager $validatorManager): JsonResponse
    {
        $category = $this->categoryRepository->findOneBy(['id' => $id]);

        //validation
        $validEntity = $validatorManager->validate($request, $category);
        if($validEntity instanceof JsonResponse) return $validEntity;

        $updatedCategory = $this->categoryRepository->updateCategory($validEntity);

        return new JsonResponse(['status' => 'success', 'message' => 'Category updated!', 'id' => $updatedCategory->getId()], Response::HTTP_OK);
    }

    /**
     * Delete a category
     *
     * @Route("category/{id}", name="delete_category", methods={"DELETE"})
     */
    public function delete($id): JsonResponse
    {
        $category = $this->categoryRepository->findOneBy(['id' => $id]);

        $this->categoryRepository->removeCategory($category);

        return new JsonResponse(['status' => 'success', 'message' => 'Category deleted!'], Response::HTTP_OK);
    }

    /**
     * List all categories
     *
     * This call return all categories.
     *
     * @Route("categories", name="get_all_categories", methods={"GET"})
     */
    public function getAll(): JsonResponse
    {
        $categories = $this->categoryRepository->findAll();
        $data = [];

        foreach ($categories as $category) {
            $data[] = [
                'id' => $category->getId(),
                'name' => $category->getName(),
                'description' => $category->getDescription(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

}
