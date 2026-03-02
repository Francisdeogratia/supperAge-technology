<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiMarketplaceController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('q', '');

        $query = DB::table('marketplace_products')
            ->leftJoin('marketplace_stores', 'marketplace_products.store_id', '=', 'marketplace_stores.id')
            ->whereNull('marketplace_products.deleted_at')
            ->where('marketplace_products.status', 'active')
            ->select(
                'marketplace_products.id',
                'marketplace_products.name',
                'marketplace_products.description',
                'marketplace_products.price',
                'marketplace_products.images',
                'marketplace_products.stock',
                'marketplace_stores.id as store_id',
                'marketplace_stores.name as store_name',
                'marketplace_stores.logo as store_logo',
            )
            ->orderBy('marketplace_products.created_at', 'desc');

        if ($search) {
            $query->where('marketplace_products.name', 'like', '%' . $search . '%');
        }

        $products = $query->paginate(20);

        return response()->json([
            'products'     => collect($products->items())->map(fn($p) => $this->formatProduct($p)),
            'total'        => $products->total(),
            'current_page' => $products->currentPage(),
            'last_page'    => $products->lastPage(),
        ]);
    }

    private function formatProduct($product): array
    {
        $images = json_decode($product->images, true) ?? [];
        return [
            'id'          => $product->id,
            'name'        => $product->name,
            'description' => $product->description ?? null,
            'price'       => (float) $product->price,
            'images'      => $images,
            'stock'       => (int) ($product->stock ?? 0),
            'store'       => [
                'id'   => $product->store_id,
                'name' => $product->store_name,
                'logo' => $product->store_logo,
            ],
        ];
    }
}
