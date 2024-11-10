<?php

namespace App\Actions;
use App\Services\ProductFilterService;
use Illuminate\Http\Request;

class ListProductsAction
{
    protected $filterService;

    public function __construct(ProductFilterService $filterService)
    {
        $this->filterService = $filterService;
    }

    public function execute(Request $request)
    {
        $query = $this->filterService->filter($request);
        return $query;
    }

    public function getFilters()
    {
        return $this->filterService->getAllFilters();
    }
}
