<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\ProductCategoryModel;

class GuestController extends BaseController
{
    protected $productModel;
    protected $productCategoryModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->productCategoryModel = new ProductCategoryModel();
    }

    public function index()
    {
        $featuredProducts = $this->productModel->getPopularProducts(10);

        $data = [
            'title'            => page_title('Beranda'),
            'featuredProducts' => $featuredProducts,
            'popular_products' => $featuredProducts,
            'categories'       => $this->productCategoryModel->findAll(),
        ];

        return view('guest/home', $data);
    }

    public function products()
    {
        $categoryId = $this->request->getGet('category');
        $search     = $this->request->getGet('q');
        $sort       = $this->request->getGet('sort') ?? 'popular';

        $perPage = 20;

        $products = $this->productModel
            ->getFilteredProducts($categoryId, $search, $sort)
            ->paginate($perPage, 'products');

        $data = [
            'title'            => page_title('Katalog Produk'),
            'categories'       => $this->productCategoryModel->findAll(),
            'products'         => $products,
            'pager'            => $this->productModel->pager,
            'selectedCategory' => $categoryId,
            'searchKeyword'    => $search,
            'selectedSort'     => $sort,
        ];

        return view('guest/products', $data);
    }

    public function filterProducts()
    {
        $categoryId = $this->request->getGet('category');
        $search     = $this->request->getGet('q');
        $sort       = $this->request->getGet('sort') ?? 'popular';

        $perPage = 20;

        $products = $this->productModel
            ->getFilteredProducts($categoryId, $search, $sort)
            ->paginate($perPage, 'products');

        $pager = $this->productModel->pager;
        $pager->only(['category', 'q', 'sort']);

        $pager->setPath('products/filter');

        $data = [
            'products'         => $products,
            'pager'            => $pager,
            'categories'       => $this->productCategoryModel->findAll(),
            'selectedCategory' => $categoryId,
            'searchKeyword'    => $search,
            'selectedSort'     => $sort,
        ];

        return view('partials/product_list', $data);
    }

    public function about()
    {
        $data = [
            'title' => page_title('Tentang Kami'),
        ];

        return view('guest/about', $data);
    }
}
