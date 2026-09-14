<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Services\ProductService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

//CRUD de productos por API (requerimiento 2). sale_price nunca se manda: lo calcula el modelo.
//la imagen por api viene como string (ruta o url); si se manda multipart con archivo tambien sirve
#[OA\Tag(name: 'Productos', description: 'Requerimiento 2: catálogo de productos (precios con IVA 19%)')]
class ProductController extends Controller
{
    use ApiResponse;

    public function __construct(private readonly ProductService $productService)
    {
    }

    //2.1 listar todos → 200
    #[OA\Get(path: '/api/products', tags: ['Productos'], summary: '2.1 Listar todos los productos', security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(response: 200, description: 'Listado obtenido'),
            new OA\Response(response: 401, description: 'No autenticado'),
        ])]
    public function index(): JsonResponse
    {
        return $this->successResponse($this->productService->getAll(), 'Listado de productos obtenido correctamente.');
    }

    //2.2 obtener por id → 200 · 404
    #[OA\Get(path: '/api/products/{id}', tags: ['Productos'], summary: '2.2 Obtener un producto por su ID', security: [['bearerAuth' => []]],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 1)],
        responses: [
            new OA\Response(response: 200, description: 'Producto encontrado'),
            new OA\Response(response: 404, description: 'No existe un producto con ese id'),
            new OA\Response(response: 401, description: 'No autenticado'),
        ])]
    public function show(int $id): JsonResponse
    {
        $product = $this->productService->findById($id);

        if (! $product) {
            return $this->errorResponse(__('No existe un producto con el id :id.', ['id' => $id]), 404);
        }

        return $this->successResponse($product, 'Producto encontrado.');
    }

    //2.3 agregar → 201 · 422
    #[OA\Post(path: '/api/products', tags: ['Productos'], summary: '2.3 Agregar un nuevo producto', security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(
            required: ['sku', 'name', 'short_description', 'long_description', 'image', 'net_price', 'current_stock', 'minimum_stock', 'low_stock', 'high_stock'],
            properties: [
                new OA\Property(property: 'sku', type: 'string', example: 'AUD-050'),
                new OA\Property(property: 'name', type: 'string', example: 'Audífonos inalámbricos'),
                new OA\Property(property: 'short_description', type: 'string', example: 'Cancelación de ruido'),
                new OA\Property(property: 'long_description', type: 'string', example: 'Audífonos over-ear con 30 h de batería y estuche de carga.'),
                new OA\Property(property: 'image', type: 'string', example: 'images/logo.png', description: 'Ruta o URL de la única imagen del producto'),
                new OA\Property(property: 'net_price', type: 'integer', example: 59990, description: 'Precio neto en CLP. El precio de venta se calcula solo (+19% IVA)'),
                new OA\Property(property: 'current_stock', type: 'integer', example: 20),
                new OA\Property(property: 'minimum_stock', type: 'integer', example: 2),
                new OA\Property(property: 'low_stock', type: 'integer', example: 5, description: 'Debe ser >= minimum_stock'),
                new OA\Property(property: 'high_stock', type: 'integer', example: 100, description: 'Debe ser >= low_stock'),
            ]
        )),
        responses: [
            new OA\Response(response: 201, description: 'Producto creado (incluye sale_price calculado)'),
            new OA\Response(response: 422, description: 'Datos inválidos o vacíos'),
            new OA\Response(response: 401, description: 'No autenticado'),
        ])]
    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = $this->productService->create($request->validated());

        return $this->successResponse($product, 'Producto creado correctamente.', 201);
    }

    //2.4 actualizar por id → 200 · 404 · 422 (PUT o PATCH parcial)
    #[OA\Put(path: '/api/products/{id}', tags: ['Productos'], summary: '2.4 Actualizar un producto por su ID (PUT o PATCH)', security: [['bearerAuth' => []]],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 1)],
        requestBody: new OA\RequestBody(required: true, content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'sku', type: 'string', example: 'TEC-001'),
                new OA\Property(property: 'name', type: 'string', example: 'Teclado mecánico TKL Pro'),
                new OA\Property(property: 'short_description', type: 'string', example: 'Switches red'),
                new OA\Property(property: 'long_description', type: 'string', example: 'Descripción larga actualizada.'),
                new OA\Property(property: 'image', type: 'string', example: 'images/logo.png'),
                new OA\Property(property: 'net_price', type: 'integer', example: 49990, description: 'Si cambia, sale_price se recalcula'),
                new OA\Property(property: 'current_stock', type: 'integer', example: 30),
                new OA\Property(property: 'minimum_stock', type: 'integer', example: 5, description: 'Los 3 umbrales van juntos'),
                new OA\Property(property: 'low_stock', type: 'integer', example: 10),
                new OA\Property(property: 'high_stock', type: 'integer', example: 100),
            ]
        )),
        responses: [
            new OA\Response(response: 200, description: 'Producto actualizado'),
            new OA\Response(response: 404, description: 'No existe un producto con ese id'),
            new OA\Response(response: 422, description: 'Datos inválidos o vacíos'),
            new OA\Response(response: 401, description: 'No autenticado'),
        ])]
    public function update(UpdateProductRequest $request, int $id): JsonResponse
    {
        $product = $this->productService->findById($id);

        if (! $product) {
            return $this->errorResponse(__('No existe un producto con el id :id.', ['id' => $id]), 404);
        }

        $product = $this->productService->update($product, $request->validated());

        return $this->successResponse($product, 'Producto actualizado correctamente.');
    }

    //2.5 eliminar por id → 200 · 404 (el service borra tambien el archivo de imagen si era subido)
    #[OA\Delete(path: '/api/products/{id}', tags: ['Productos'], summary: '2.5 Eliminar un producto por su ID', security: [['bearerAuth' => []]],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'), example: 3)],
        responses: [
            new OA\Response(response: 200, description: 'Producto eliminado'),
            new OA\Response(response: 404, description: 'No existe un producto con ese id'),
            new OA\Response(response: 401, description: 'No autenticado'),
        ])]
    public function destroy(int $id): JsonResponse
    {
        $product = $this->productService->findById($id);

        if (! $product) {
            return $this->errorResponse(__('No existe un producto con el id :id.', ['id' => $id]), 404);
        }

        $this->productService->delete($product);

        return $this->successResponse(null, 'Producto eliminado correctamente.');
    }
}
