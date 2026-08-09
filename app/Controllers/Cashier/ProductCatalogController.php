<?php

namespace App\Controllers\Cashier;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\ProductModel;
use App\Models\ProductCategoryModel;
use App\Models\UserModel;

class ProductCatalogController extends BaseController
{
    public function index()
    {
        $productModel = new ProductModel();
        $categoryModel = new ProductCategoryModel();
        $session = session();

        $categoryId = $this->request->getGet('category');
        $search     = $this->request->getGet('q');
        $sort       = $this->request->getGet('sort') ?? 'popular';

        $perPage = 20;

        $products = $productModel
            ->getFilteredProducts($categoryId, $search, $sort)
            ->paginate($perPage, 'products');

        $pager = $productModel->pager;

        // cashier info for sidebar
        $cashier = [];
        $userId = $session->get('id');
        if ($userId) {
            $userModel = new UserModel();
            $user = $userModel->find($userId);
            if ($user) {
                $cashier = [
                    'name' => $user->name ?? $session->get('name'),
                    'email' => $user->email ?? $session->get('email'),
                    'avatar' => $user->avatar ?? null,
                ];
            }
        }

        if (empty($cashier)) {
            $cashier = [
                'name' => $session->get('name'),
                'email' => $session->get('email'),
                'avatar' => null,
            ];
        }

        $data = [
            'title'            => 'Katalog Produk - Kasir',
            'categories'       => $categoryModel->findAll(),
            'products'         => $products,
            'pager'            => $pager,
            'selectedCategory' => $categoryId,
            'searchKeyword'    => $search,
            'selectedSort'     => $sort,
            'cashier'          => $cashier,
        ];

        return view('cashier/product_catalog', $data);
    }

    public function filterProducts()
    {
        $productModel = new ProductModel();
        $categoryModel = new ProductCategoryModel();

        $categoryId = $this->request->getGet('category');
        $search     = $this->request->getGet('q');
        $sort       = $this->request->getGet('sort') ?? 'popular';

        $perPage = 20;

        $products = $productModel
            ->getFilteredProducts($categoryId, $search, $sort)
            ->paginate($perPage, 'products');

        $pager = $productModel->pager;
        $pager->only(['category', 'q', 'sort']);
        $pager->setPath('katalog/saring');

        $data = [
            'products' => $products,
            'pager'    => $pager,
            'categories' => $categoryModel->findAll(),
            'selectedCategory' => $categoryId,
            'searchKeyword' => $search,
            'selectedSort' => $sort,
            'filterAction' => base_url('katalog/saring'),
        ];

        return view('partials/product_list', $data);
    }
}
