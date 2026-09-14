<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Services\ProductService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

//mantenedor de productos WEB. la imagen llega como archivo y la guarda el service
class ProductController extends Controller
{
    public function __construct(private readonly ProductService $productService)
    {
    }

    //2.1 listar
    public function index(): View
    {
        return view('pages.products.index', ['products' => $this->productService->getAll()]);
    }

    public function create(): View
    {
        return view('pages.products.create');
    }

    //2.3 agregar. validated() trae el UploadedFile en 'image', el service lo mueve a public/images/products
    public function store(StoreProductRequest $request): RedirectResponse
    {
        $this->productService->create($request->validated());

        return redirect()->route('products.index')->with('success', 'Producto creado correctamente.');
    }

    //2.2 ver uno
    public function show(int $id): View
    {
        $product = $this->productService->findById($id) ?? abort(404);

        return view('pages.products.show', compact('product'));
    }

    public function edit(int $id): View
    {
        $product = $this->productService->findById($id) ?? abort(404);

        return view('pages.products.edit', compact('product'));
    }

    //2.4 actualizar. si no mandan imagen nueva se queda la q tenia
    public function update(UpdateProductRequest $request, int $id): RedirectResponse
    {
        $product = $this->productService->findById($id) ?? abort(404);

        $this->productService->update($product, $request->validated());

        return redirect()->route('products.show', $id)->with('success', 'Producto actualizado correctamente.');
    }

    //2.5 eliminar (el service borra tambien el archivo de la imagen)
    public function destroy(int $id): RedirectResponse
    {
        $product = $this->productService->findById($id) ?? abort(404);

        $this->productService->delete($product);

        return redirect()->route('products.index')->with('success', 'Producto eliminado correctamente.');
    }
}
