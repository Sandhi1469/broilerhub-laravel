<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Menampilkan katalog produk dengan pencarian, filter, sorting, dan pagination.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Product::with('category');

        // 1. Pencarian teks pada nama atau deskripsi
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // 2. Filter berdasarkan Kategori
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        // 3. Filter berdasarkan Rentang Harga
        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->input('min_price'));
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->input('max_price'));
        }

        // 4. Filter ketersediaan stok
        if ($request->boolean('in_stock')) {
            $query->where('stock', '>', 0);
        }

        // 5. Sorting dengan whitelist kolom yang diizinkan
        $allowedSorts = ['product_id', 'name', 'price', 'stock', 'created_at'];
        $sortBy = in_array($request->input('sort_by'), $allowedSorts, true)
            ? $request->input('sort_by')
            : 'product_id';
        $order = strtolower($request->input('order', 'asc')) === 'desc' ? 'desc' : 'asc';
        $query->orderBy($sortBy, $order);

        // 6. Pagination dengan batas aman (maksimum 50 item per halaman)
        $perPage = (int) $request->input('per_page', 10);
        $perPage = max(1, min($perPage, 50));

        $products = $query->paginate($perPage);

        return ProductResource::collection($products);
    }

    /**
     * Menyimpan produk baru ke dalam database dengan manajemen upload gambar yang aman.
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $uploadedPath = null;

        try {
            // 1. Upload file gambar jika disertakan
            if ($request->hasFile('img')) {
                $uploadedPath = $request->file('img')->store('products', 'public');
                $validated['img'] = $uploadedPath;
            }

            // 2. Otomatisasi generate slug jika tidak disediakan
            if (empty($validated['slug'])) {
                $baseSlug = Str::slug($validated['name']);
                $slug = $baseSlug;
                $counter = 1;
                while (Product::where('slug', $slug)->exists()) {
                    $slug = "{$baseSlug}-{$counter}";
                    $counter++;
                }
                $validated['slug'] = $slug;
            }

            $product = Product::create($validated);
            $product->load('category');

            return (new ProductResource($product))
                ->response()
                ->setStatusCode(201);
        } catch (\Throwable $e) {
            // Jika proses penyimpanan database gagal, hapus file baru yang sempat di-upload agar tidak menjadi file yatim
            if ($uploadedPath && Storage::disk('public')->exists($uploadedPath)) {
                Storage::disk('public')->delete($uploadedPath);
            }
            throw $e;
        }
    }

    /**
     * Menampilkan detail spesifik dari satu produk berdasarkan ID.
     */
    public function show(string|int $id): ProductResource|JsonResponse
    {
        $product = Product::with('category')->find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Product not found',
            ], 404);
        }

        return new ProductResource($product);
    }

    /**
     * Memperbarui informasi produk dan memperbarui file gambar jika ada.
     */
    public function update(UpdateProductRequest $request, string|int $id): ProductResource|JsonResponse
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Product not found',
            ], 404);
        }

        $validated = $request->validated();
        $oldImagePath = $product->img;
        $uploadedPath = null;

        try {
            // 1. Manajemen upload gambar baru
            if ($request->hasFile('img')) {
                $uploadedPath = $request->file('img')->store('products', 'public');
                $validated['img'] = $uploadedPath;
            }

            // 2. Regenerasi slug jika nama produk diperbarui tanpa menyertakan slug
            if (isset($validated['name']) && empty($validated['slug'])) {
                $baseSlug = Str::slug($validated['name']);
                $slug = $baseSlug;
                $counter = 1;
                while (Product::where('slug', $slug)->where('product_id', '!=', $product->product_id)->exists()) {
                    $slug = "{$baseSlug}-{$counter}";
                    $counter++;
                }
                $validated['slug'] = $slug;
            }

            // 3. Perbarui database
            $product->update($validated);

            // 4. Jika update database berhasil dan ada gambar baru, bersihkan gambar lama dari disk
            if ($uploadedPath && !empty($oldImagePath) && $oldImagePath !== $uploadedPath) {
                if (Storage::disk('public')->exists($oldImagePath)) {
                    Storage::disk('public')->delete($oldImagePath);
                }
            }

            $product->load('category');

            return new ProductResource($product);
        } catch (\Throwable $e) {
            // Jika proses penyimpanan database gagal, hapus file baru yang sempat di-upload
            if ($uploadedPath && Storage::disk('public')->exists($uploadedPath)) {
                Storage::disk('public')->delete($uploadedPath);
            }
            throw $e;
        }
    }

    /**
     * Menghapus produk dan file fisik gambar dari penyimpanan.
     */
    public function destroy(string|int $id): JsonResponse
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'message' => 'Product not found',
            ], 404);
        }

        $imagePath = $product->img;

        // Hapus record produk dari database
        $product->delete();

        // Hapus file gambar terkait jika ada dan dikelola oleh aplikasi
        if (!empty($imagePath) && Storage::disk('public')->exists($imagePath)) {
            Storage::disk('public')->delete($imagePath);
        }

        return response()->json([
            'message' => 'Product deleted successfully',
        ], 200);
    }
}
