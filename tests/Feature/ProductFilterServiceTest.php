<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Services\ProductFilterService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\TestCase;

class ProductFilterServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $filterService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->filterService = new ProductFilterService();
    }

    public function test_filter_returns_paginated_results()
    {
        // Arrange: Create some sample products with specific attributes
        Product::factory()->count(5)->create([
            'price' => 150, // within the filter range 100-500
            'quantity' => 5, // within the filter range 1-10
            'color' => 'red', // matches the filter
            'fuel_type' => 'gasoline', // matches the filter
        ]);

        // Act: Simulate a GET request with filters
        $response = $this->get('/products', [
            'price_min' => 100,
            'price_max' => 500,
            'quantity_min' => 1,
            'quantity_max' => 10,
            'color' => ['red', 'blue'],
            'fuel_type' => ['gasoline'],
        ]);

        // Assert: Ensure the response is successful (status code 200)
        $response->assertStatus(200);

        // Assert: Check if the response has the correct structure
        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'name', 'price', 'quantity', 'color', 'fuel_type'],
            ]
        ]);

        // Assert: Check if the filtered products match the criteria
        $response->assertJsonFragment(['color' => 'red']);
        $response->assertJsonFragment(['fuel_type' => 'gasoline']);

        // Assert: Optionally check for pagination
        $response->assertJsonFragment(['per_page' => 10]);
    }









    public function test_getColors_returns_cached_colors()
    {
        // Arrange: Cache some colors
        Cache::shouldReceive('remember')
            ->once()
            ->with('product_colors', 86400, \Closure::class)
            ->andReturn(['red', 'blue', 'green']);

        // Act: Get colors from the service
        $colors = $this->filterService->getColors();

        // Assert: Check that colors are returned as expected
        $this->assertEquals(['red', 'blue', 'green'], $colors);
    }

    public function test_getAllFilters_returns_all_filters()
    {
        // Arrange: Cache for colors, fuel types, and engine types
        Cache::shouldReceive('remember')
            ->twice()
            ->withAnyArgs()
            ->andReturn(['red', 'blue']);

        // Act: Get all filters
        $filters = $this->filterService->getAllFilters();

        // Assert: Ensure all filters are present
        $this->assertArrayHasKey('colors', $filters);
        $this->assertArrayHasKey('fuel_types', $filters);
        $this->assertArrayHasKey('engine_types', $filters);
    }
}
