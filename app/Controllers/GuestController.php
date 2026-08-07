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
            'title'            => 'Beranda - Koperasi Desa Merah Putih',
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
            'title'            => 'Katalog Produk - Koperasi Desa Merah Putih',
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

        $data = [
            'products' => $products,
            'pager'    => $this->productModel->pager 
        ];

        return view('guest/partials/product_list', $data);
    }

    public function about()
    {
        $data = [
            'title' => 'Tentang Kami - Koperasi Desa Merah Putih',
        ];

        return view('guest/about', $data);
    }
}
