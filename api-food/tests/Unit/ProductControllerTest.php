<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase; 
use App\Http\Controllers\ProductController;
use App\Services\OpenFoodService;
use App\Models\Product;
use Illuminate\Http\Request;
use Mockery;

class ProductControllerTest extends TestCase
{
    protected $validProductCode;

    public function setUp(): void
    {
        parent::setUp();
        $this->validProductCode = '17';
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Testa o método 'show' para um produto válido.
     */
    public function testShowSuccess()
    {
        // Mock do model Product
        $mockProduct = Mockery::mock(Product::class);
        $mockProduct->shouldReceive('where')->with('code', $this->validProductCode)->andReturnSelf();
        $mockProduct->shouldReceive('first')->andReturn((object) ['id' => 1, 'code' => $this->validProductCode, 'name' => 'Test Product']);

        // Mock do serviço OpenFoodService
        $mockService = Mockery::mock(OpenFoodService::class);

        // Injeta o mock no controller
        $controller = new ProductController($mockService);
        $response = $controller->show($this->validProductCode); 

        // Verifica se o status de resposta é 200 (sucesso)
        $this->assertEquals(200, $response->status());

        // Verifica se a resposta contém o JSON
        $this->assertJson($response->getContent());

        // Verifica se a resposta contém o código do produto
        $this->assertStringContainsString($this->validProductCode, $response->getContent());
    }

    /**
     * Testa o método 'show' para um produto que não existe.
     */
    public function testShowNotFound()
    {
        // Mock do model Product
        $mockProduct = Mockery::mock(Product::class);
        $mockProduct->shouldReceive('where')->with('code', 'invalid-code')->andReturnSelf();
        $mockProduct->shouldReceive('first')->andReturn(null); 

        // Mock do serviço OpenFoodService
        $mockService = Mockery::mock(OpenFoodService::class);

        // Injeta o mock no controller
        $controller = new ProductController($mockService);
        $response = $controller->show('invalid-code'); 

        // Verifica se o status de resposta é 404 (não encontrado)
        $this->assertEquals(404, $response->status());

        // Verifica se a resposta contém o JSON
        $this->assertJson($response->getContent());

        // Verifica se a resposta contém a mensagem 'Product not found'
        $this->assertStringContainsString('Product not found', $response->getContent());
    }

    /**
     * Testa o método 'update' para uma atualização de produto bem-sucedida.
     */
    public function testUpdateSuccess()
    {
        // Mock do objeto Request
        $mockRequest = Mockery::mock(Request::class);
        $mockRequest->shouldReceive('all')->andReturn(['name' => 'Updated Product Name', 'price' => 99.99]); 

        // Mock do model Product
        $mockProduct = Mockery::mock(Product::class);
        $mockProduct->shouldReceive('where')->with('code', $this->validProductCode)->andReturnSelf();
        $mockProduct->shouldReceive('first')->andReturn((object) ['id' => 1, 'code' => $this->validProductCode, 'name' => 'Test Product']); 
        $mockProduct->shouldReceive('update')->with(['name' => 'Updated Product Name', 'price' => 99.99])->andReturn(true);

        // Mock do serviço OpenFoodService
        $mockService = Mockery::mock(OpenFoodService::class);

        // Injeta o mock no controller
        $controller = new ProductController($mockService);
        $response = $controller->update($mockRequest, $this->validProductCode); 

        // Verifica se o status de resposta é 200 (sucesso)
        $this->assertEquals(200, $response->status());

        // Verifica se a resposta contém o JSON
        $this->assertJson($response->getContent());

        // Verifica se a resposta contém a mensagem 'Product updated successfully'
        $this->assertStringContainsString('Product updated successfully', $response->getContent());
    }

    /**
     * Testa o método 'update' para um produto que não existe.
     */
    public function testUpdateNotFound()
    {
        // Mock do objeto Request
        $mockRequest = Mockery::mock(Request::class);
        $mockRequest->shouldReceive('all')->andReturn(['name' => 'New Product Name', 'price' => 100.00]); 

        // Mock do model Product
        $mockProduct = Mockery::mock(Product::class);
        $mockProduct->shouldReceive('where')->with('code', 'invalid-code') ->andReturnSelf();
        $mockProduct->shouldReceive('first')->andReturn(null); 

        // Mock do serviço OpenFoodService
        $mockService = Mockery::mock(OpenFoodService::class);

        // Injeta o mock no controller
        $controller = new ProductController($mockService);
        $response = $controller->update($mockRequest, 'invalid-code'); 

        // Verifica se o status de resposta é 404 (não encontrado)
        $this->assertEquals(404, $response->status());

        // Verifica se a resposta contém o JSON
        $this->assertJson($response->getContent());
        
        // Verifica se a resposta contém a mensagem 'Product not found'
        $this->assertStringContainsString('Product not found', $response->getContent());
    }
}
