<?php

namespace Tests\Feature;

use App\Actions\ListProductsAction;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Mockery;
use Tests\TestCase;

class ListProductsControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $listProductsAction;

    protected function setUp(): void
    {
        parent::setUp();
        $this->listProductsAction = Mockery::mock(ListProductsAction::class);
    }

    public function test_index_returns_paginated_products()
    {
        // Arrange: Create products and mock action execution
        Product::factory()->count(5)->create();
        $mockQuery = Mockery::mock(\Illuminate\Pagination\LengthAwarePaginator::class);
        $this->listProductsAction->shouldReceive('execute')->once()->andReturn($mockQuery);

        // Act: Call index method in the controller
        $response = $this->getJson('/products');

        // Assert: Ensure the response returns JSON
        $response->assertStatus(200);
    }

    public function test_filters_returns_filters()
    {
        // Arrange: Mock filters
        $this->listProductsAction->shouldReceive('getFilters')->once()->andReturn([
            'colors' => ['red', 'blue'],
            'fuel_types' => ['gasoline'],
            'engine_types' => ['v6']
        ]);

        // Act: Call filters method in the controller
        $response = $this->getJson('/products/filters');

        // Assert: Ensure the response contains the filters
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'colors',
            'fuel_types',
            'engine_types',
        ]);
    }
}
