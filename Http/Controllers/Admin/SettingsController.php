<?php

namespace Modules\FeaturedProductGeneral\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Traits\HasMultiStoreModuleSettings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Modules\FeaturedProductGeneral\Models\FeaturedProduct;

class SettingsController extends Controller
{
    use HasMultiStoreModuleSettings;

    protected function getModuleSlug(): string
    {
        return 'featured-product-general';
    }

    protected function getDefaultSettings(): array
    {
        return [
            'enabled' => true,
            'title' => 'Featured Products',
            'max_products' => 12,
            'sort_order' => 0,
        ];
    }

    public function index(): Response
    {
        $data = $this->getMultiStoreData();

        $featuredProducts = FeaturedProduct::with('product.translations')
            ->ordered()
            ->get();

        $products = Product::with('translations')
            ->where('is_active', true)
            ->orderBy('sku')
            ->get(['id', 'sku', 'price', 'image']);

        $data['featuredProducts'] = $featuredProducts;
        $data['products'] = $products;

        return Inertia::render('FeaturedProductGeneral::Admin/Settings', $data);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'store_id' => 'required|exists:stores,id',
            'is_enabled' => 'boolean',
            'settings.enabled' => 'boolean',
            'settings.title' => 'required|string|max:255',
            'settings.max_products' => 'required|integer|min:1|max:50',
            'settings.sort_order' => 'integer|min:0',
        ]);

        return $this->saveStoreSettings($request);
    }

    public function addProduct(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $maxOrder = FeaturedProduct::max('sort_order') ?? 0;

        FeaturedProduct::updateOrCreate(
            ['product_id' => $validated['product_id']],
            ['sort_order' => $maxOrder + 1, 'is_active' => true]
        );

        return back()->with('success', 'Product added to featured.');
    }

    public function removeProduct(FeaturedProduct $featuredProduct): RedirectResponse
    {
        $featuredProduct->delete();

        return back()->with('success', 'Product removed from featured.');
    }

    public function reorder(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'products' => 'required|array',
            'products.*.id' => 'required|integer|exists:featured_products,id',
            'products.*.sort_order' => 'required|integer|min:0',
        ]);

        foreach ($validated['products'] as $item) {
            FeaturedProduct::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }

        return back()->with('success', 'Order updated.');
    }

    public function toggle(FeaturedProduct $featuredProduct): RedirectResponse
    {
        $featuredProduct->update(['is_active' => !$featuredProduct->is_active]);

        return back()->with('success', 'Status updated.');
    }
}