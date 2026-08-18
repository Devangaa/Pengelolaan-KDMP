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
        try {
            $session = session();
            $userId = $session->get('id');

            if (!$userId) {
                return html_error_response(AppConstants::MSG_LOGIN_REQUIRED, base_url('login'));
            }

            // Validate authorization
            if (!authorize_user_role(AppConstants::ROLE_KASIR)) {
                log_transaction('warning', 'Unauthorized product catalog access');
                return html_error_response(AppConstants::MSG_UNAUTHORIZED, base_url('dasbor'));
            }

            $productModel = new ProductModel();
            $categoryModel = new ProductCategoryModel();

            $categoryIdValidation = validate_category_id($this->request->getGet('category'));
            $categoryId = $categoryIdValidation['valid'] ? $categoryIdValidation['value'] : null;

            $searchValidation = validate_search_keyword($this->request->getGet('q'));
            $search = $searchValidation['valid'] ? $searchValidation['value'] : null;

            $sortValidation = validate_sort($this->request->getGet('sort') ?? 'popular');
            $sort = $sortValidation['valid'] ? $sortValidation['value'] : 'popular';

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
        } catch (\Exception $e) {
            log_transaction('error', 'Product catalog index failed', ['error' => $e->getMessage()]);
            return html_error_response('Terjadi kesalahan saat memuat katalog produk.', base_url('dasbor'));
        }
    }

    public function filterProducts()
    {
        try {
            $productModel = new ProductModel();
            $categoryModel = new ProductCategoryModel();

            $categoryIdValidation = validate_category_id($this->request->getGet('category'));
            $categoryId = $categoryIdValidation['valid'] ? $categoryIdValidation['value'] : null;

            $searchValidation = validate_search_keyword($this->request->getGet('q'));
            $search = $searchValidation['valid'] ? $searchValidation['value'] : null;

            $sortValidation = validate_sort($this->request->getGet('sort') ?? 'popular');
            $sort = $sortValidation['valid'] ? $sortValidation['value'] : 'popular';

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
        } catch (\Exception $e) {
            log_transaction('error', 'Product filter failed', ['error' => $e->getMessage()]);
            return html_error_response('Terjadi kesalahan saat memfilter produk.', base_url('katalog'));
        }
    }
}
