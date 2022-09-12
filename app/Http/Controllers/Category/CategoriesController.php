<?php

namespace App\Http\Controllers\Category;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;

use App\Repositories\CategoryRepository;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class CategoriesController extends Controller
{
    /**
     * Response trait to handle return responses.
     */
    use ResponseTrait;

    /**
     * Category Repository class.
     *
     * @var CategoryRepository
     */
    public $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->middleware('auth:api', ['except' => ['indexAll']]);
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @OA\GET(
     *     path="/api/categories",
     *     tags={"Categories"},
     *     summary="Get Category List",
     *     description="Get Category List as Array",
     *     operationId="index1",
     *     security={{"bearer":{}}},
     *     @OA\Response(response=200,description="Get Category List as Array"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function index1(): JsonResponse
    {
        try {
            $data = $this->categoryRepository->getAll();
            return $this->responseSuccess($data, 'Category List Fetch Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\GET(
     *     path="/api/categories/view/all",
     *     tags={"Categories"},
     *     summary="All Categories - Publicly Accessible",
     *     description="All Categories - Publicly Accessible",
     *     operationId="indexAll1",
     *     @OA\Parameter(name="perPage", description="perPage, eg; 20", example=20, in="query", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="All Categories - Publicly Accessible" ),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function indexAll1(Request $request): JsonResponse
    {
        try {
            $data = $this->categoryRepository->getPaginatedData($request->perPage);
            return $this->responseSuccess($data, 'Category List Fetched Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\GET(
     *     path="/api/categories/view/search",
     *     tags={"Categories"},
     *     summary="All Categories - Publicly Accessible",
     *     description="All Categories - Publicly Accessible",
     *     operationId="search1",
     *     @OA\Parameter(name="perPage", description="perPage, eg; 20", example=20, in="query", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="search", description="search, eg; Test", example="Test", in="query", @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="All Categories - Publicly Accessible" ),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function search1(Request $request): JsonResponse
    {
        try {
            $data = $this->categoryRepository->searchCategory($request->search, $request->perPage);
            return $this->responseSuccess($data, 'Category List Fetched Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\POST(
     *     path="/api/categories",
     *     tags={"Categories"},
     *     summary="Create New Category",
     *     description="Create New Category",
     *     operationId="store1",
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="name", type="string", example="Category 1"),
     *              @OA\Property(property="type", type="string", example="Description"),
     *              @OA\Property(property="status", type="integer", example=1),
     *          ),
     *      ),
     *      security={{"bearer":{}}},
     *      @OA\Response(response=200, description="Create New Category" ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function store1(CategoryRequest $request): JsonResponse
    {
        try {
            $product = $this->categoryRepository->create($request->all());
            return $this->responseSuccess($product, 'New Category Created Successfully !');
        } catch (\Exception $exception) {
            return $this->responseError(null, $exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\GET(
     *     path="/api/categories/{id}",
     *     tags={"Categories"},
     *     summary="Show Category Details",
     *     description="Show Category Details",
     *     operationId="show1",
     *     security={{"bearer":{}}},
     *     @OA\Parameter(name="id", description="id, eg; 1", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Show Category Details"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function show1($id): JsonResponse
    {
        try {
            $data = $this->categoryRepository->getByID($id);
            if (is_null($data)) {
                return $this->responseError(null, 'Category Not Found', Response::HTTP_NOT_FOUND);
            }

            return $this->responseSuccess($data, 'Category Details Fetch Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\PUT(
     *     path="/api/categories/{id}",
     *     tags={"Categories"},
     *     summary="Update Category",
     *     description="Update Category",
     *     @OA\Parameter(name="id", description="id, eg; 1", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="name", type="string", example="Category 1"),
     *              @OA\Property(property="type", type="string", example="Description"),
     *              @OA\Property(property="status", type="integer", example=1),
     *          ),
     *      ),
     *     operationId="update1",
     *     security={{"bearer":{}}},
     *     @OA\Response(response=200, description="Update Category"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function update1(CategoryRequest $request, $id): JsonResponse
    {
        try {
            $data = $this->categoryRepository->update($id, $request->all());
            if (is_null($data))
                return $this->responseError(null, 'Category Not Found', Response::HTTP_NOT_FOUND);

            return $this->responseSuccess($data, 'Category Updated Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\DELETE(
     *     path="/api/categories/{id}",
     *     tags={"Categories"},
     *     summary="Delete Category",
     *     description="Delete Category",
     *     operationId="destroy1",
     *     security={{"bearer":{}}},
     *     @OA\Parameter(name="id", description="id, eg; 1", required=true, in="path", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Delete Category"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function destroy1($id): JsonResponse
    {
        try {
            $product =  $this->categoryRepository->getByID($id);
            if (empty($product)) {
                return $this->responseError(null, 'Category Not Found', Response::HTTP_NOT_FOUND);
            }

            $deleted = $this->categoryRepository->delete($id);
            if (!$deleted) {
                return $this->responseError(null, 'Failed to delete the product.', Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return $this->responseSuccess($product, 'Category Deleted Successfully !');
        } catch (\Exception $e) {
            return $this->responseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
