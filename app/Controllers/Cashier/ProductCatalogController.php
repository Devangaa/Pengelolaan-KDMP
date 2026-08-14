<?php

namespace App\Controllers\Cashier;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\ProductCategoryModel;
use Config\AppConstants;

class ProductCatalogController extends BaseController
{
    public function index()
    {
        $session = session();
        $userId = $session->get('id');

        if (!$userId) {
            return redirect()->to(base_url('login'))->with('error', AppConstants::MSG_LOGIN_REQUIRED);
        }

        // Validate authorization
        if (!authorize_user_role(AppConstants::ROLE_KASIR)) {
            log_transaction('warning', 'Unauthorized product catalog access');
            return redirect()->to(base_url('dasbor'))->with('error', AppConstants::MSG_UNAUTHORIZED);
        }

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

        $data = [
            'title'            => page_title('Katalog Produk'),
            'categories'       => $categoryModel->findAll(),
            'products'         => $products,
            'pager'            => $pager,
            'selectedCategory' => $categoryId,
            'searchKeyword'    => $search,
            'selectedSort'     => $sort,
            'cashier'          => current_cashier_data(),
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
