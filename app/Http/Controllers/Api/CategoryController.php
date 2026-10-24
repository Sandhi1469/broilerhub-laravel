<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryController extends Controller
{
    /**
     * Menampilkan daftar semua kategori beserta jumlah produknya.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Category::withCount('products');

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where('category', 'like', "%{$search}%");
        }

        $categories = $query->orderBy('category_id', 'asc')->get();

        return CategoryResource::collection($categories);
    }

    /**
     * Menyimpan kategori baru ke database.
     */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $category = Category::create($request->validated());

        return (new CategoryResource($category))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Menampilkan detail kategori spesifik beserta produk di dalamnya.
     */
    public function show(string|int $id): CategoryResource|JsonResponse
    {
        $category = Category::with('products')->withCount('products')->find($id);

        if (!$category) {
            return response()->json([
                'message' => 'Category not found',
            ], 404);
        }

        return new CategoryResource($category);
    }

    /**
     * Memperbarui informasi kategori.
     */
    public function update(UpdateCategoryRequest $request, string|int $id): CategoryResource|JsonResponse
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'message' => 'Category not found',
            ], 404);
        }

        $category->update($request->validated());

        return new CategoryResource($category);
    }

    /**
     * Menghapus kategori dari database (cascade delete produk terkait).
     */
    public function destroy(string|int $id): JsonResponse
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json([
                'message' => 'Category not found',
            ], 404);
        }

        $category->delete();

        return response()->json([
            'message' => 'Category deleted successfully',
        ], 200);
    }
}

