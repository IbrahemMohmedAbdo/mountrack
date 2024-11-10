<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProductFilterService
{

    public function filter(Request $request)
    {
        $cacheKey = 'products_filter_' . md5(json_encode($request->all()));

        return Cache::remember($cacheKey, 300, function () use ($request) {
            $query = Product::query();

            if ($request->has('price_min') && $request->has('price_max')) {
                $query->whereBetween('price', [$request->price_min, $request->price_max]);
            }

            if ($request->has('quantity_min') && $request->has('quantity_max')) {
                $query->whereBetween('quantity', [$request->quantity_min, $request->quantity_max]);
            }

            if ($request->has('color')) {
                $query->whereIn('color', $request->color);
            }

            if ($request->has('fuel_type')) {
                $query->whereIn('fuel_type', $request->fuel_type);
            }

            return $query->paginate(10);
        });
    }



    public function getColors()
    {
        return Cache::remember('product_colors', 86400, function () {
            return Product::distinct()->pluck('color')->lazy();
        });
    }

    public function getFuelTypes()
    {
        return Cache::remember('product_fuel_types', 86400, function () {
            return Product::distinct()->pluck('fuel_type')->lazy();
        });
    }

    public function getEngineTypes()
    {
        return Cache::remember('product_engine_types', 86400, function () {
            return Product::distinct()->pluck('engine_type')->lazy();
        });
    }


    public function getAllFilters()
    {
        return [
            'colors' => $this->getColors(),
            'fuel_types' => $this->getFuelTypes(),
            'engine_types' => $this->getEngineTypes(),
        ];
    }


}
