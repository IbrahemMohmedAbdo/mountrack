<?php

namespace Tests\Unit;

use App\Actions\ListProductsAction;
use App\Services\ProductFilterService;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;
use Mockery;

class ListProductsActionTest extends TestCase
{
    protected $filterService;
    protected $listProductsAction;

    protected function setUp(): void
    {
        parent::setUp();
        $this->filterService = Mockery::mock(ProductFilterService::class);
        $this->listProductsAction = new ListProductsAction($this->filterService);
    }

    public function test_execute_calls_filter_service_and_returns_query()
    {
        // Arrange: Mock the filter method to return a mock query
        $mockQuery = Mockery::mock(\Illuminate\Pagination\LengthAwarePaginator::class);
        $this->filterService->shouldReceive('filter')->once()->andReturn($mockQuery);

        // Act: Execute the action
        $request = Request::create('/products', 'GET');
        $result = $this->listProductsAction->execute($request);

        // Assert: Ensure that the result is the mocked query
        $this->assertEquals($mockQuery, $result);
    }

    public function test_getFilters_calls_filter_service_and_returns_filters()
    {
        // Arrange: Mock the getAllFilters method to return a predefined filters array
        $this->filterService->shouldReceive('getAllFilters')->once()->andReturn([
            'colors' => ['red', 'blue'],
            'fuel_types' => ['gasoline'],
            'engine_types' => ['v6']
        ]);

        // Act: Get filters
        $filters = $this->listProductsAction->getFilters();

        // Assert: Ensure that filters are returned as expected
        $this->assertArrayHasKey('colors', $filters);
        $this->assertArrayHasKey('fuel_types', $filters);
        $this->assertArrayHasKey('engine_types', $filters);
    }
}
