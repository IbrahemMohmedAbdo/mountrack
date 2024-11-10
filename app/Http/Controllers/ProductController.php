<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Actions\ListProductsAction;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    protected $listProductsAction;

    public function __construct(ListProductsAction $listProductsAction)
    {
        $this->listProductsAction = $listProductsAction;
    }

    public function index(Request $request)
    {
        $products = $this->listProductsAction->execute($request);
        return response()->json($products);
    }

    public function filters()
    {
        $filters = $this->listProductsAction->getFilters();
        return response()->json($filters);
    }

  
}
