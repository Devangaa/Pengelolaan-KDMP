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
        try {
            $featuredProducts = $this->productModel->getPopularProducts(10);

            $data = [
                'title'            => page_title('Beranda'),
                'featuredProducts' => $featuredProducts,
                'popular_products' => $featuredProducts,
                'categories'       => $this->productCategoryModel->findAll(),
            ];

            return view('guest/home', $data);
        } catch (\Exception $e) {
            log_transaction('error', 'Guest home page failed', ['error' => $e->getMessage()]);
            return html_error_response('Terjadi kesalahan saat memuat beranda.', base_url('/'));
        }
    }

    public function products()
    {
        try {
            $categoryIdValidation = validate_category_id($this->request->getGet('category'));
            $categoryId = $categoryIdValidation['valid'] ? $categoryIdValidation['value'] : null;

            $searchValidation = validate_search_keyword($this->request->getGet('q'));
            $search = $searchValidation['valid'] ? $searchValidation['value'] : null;

            $sortValidation = validate_sort($this->request->getGet('sort') ?? 'popular');
            $sort = $sortValidation['valid'] ? $sortValidation['value'] : 'popular';

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
        } catch (\Exception $e) {
            log_transaction('error', 'Guest products page failed', ['error' => $e->getMessage()]);
            return html_error_response('Terjadi kesalahan saat memuat katalog produk.', base_url('/'));
        }
    }

    public function filterProducts()
    {
        try {
            $categoryIdValidation = validate_category_id($this->request->getGet('category'));
            $categoryId = $categoryIdValidation['valid'] ? $categoryIdValidation['value'] : null;

            $searchValidation = validate_search_keyword($this->request->getGet('q'));
            $search = $searchValidation['valid'] ? $searchValidation['value'] : null;

            $sortValidation = validate_sort($this->request->getGet('sort') ?? 'popular');
            $sort = $sortValidation['valid'] ? $sortValidation['value'] : 'popular';

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
        } catch (\Exception $e) {
            log_transaction('error', 'Guest filter products failed', ['error' => $e->getMessage()]);
            return html_error_response('Terjadi kesalahan saat memfilter produk.', base_url('products'));
        }
    }

    public function about()
    {
        $data = [
            'title' => page_title('Tentang Kami'),
        ];

        return view('guest/about', $data);
    }
}
