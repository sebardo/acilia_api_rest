<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
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
     *     request="name",
     *     description="Json request",
     *     required=true,
     *     @OA\JsonContent(
     *         type="string",
     *     )
     * )
     */
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $name = $data['name'];
        $description = $data['description'];

        if (empty($name) || empty($description)) {
            throw new NotFoundHttpException('Expecting mandatory parameters!');
        }

        $this->categoryRepository->saveCategory($name, $description);

        return new JsonResponse(['status' => 'Category created!'], Response::HTTP_CREATED);
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
    public function update($id, Request $request): JsonResponse
    {
        $category = $this->categoryRepository->findOneBy(['id' => $id]);
        $data = json_decode($request->getContent(), true);

        empty($data['name']) ? true : $category->setName($data['name']);
        empty($data['description']) ? true : $category->setDescription($data['description']);

        $updatedCategory = $this->categoryRepository->updateCategory($category);

        return new JsonResponse(['status' => 'Category updated!'], Response::HTTP_OK);
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

        return new JsonResponse(['status' => 'Category deleted'], Response::HTTP_OK);
    }
}
