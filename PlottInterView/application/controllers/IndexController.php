<?php

class IndexController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
        $product_db = new Application_Model_DbTable_Product();
        $this->view->best_seller = $product_db->getListTopNewUpdateProduct(0, 3);
    }

    public function indexAction() {
        $key = $this->getRequest()->getParam('key');
        $category_db = new Application_Model_DbTable_Category();
        $product_db = new Application_Model_DbTable_Product();
        $list_product_by_cate = [];
        $list_cate = $category_db->getListCategory();
        foreach ($list_cate as $item) {
            $list_product_by_cate[$item['category_name']] = $product_db->getProductByCategory($item['id'], 3, 0, $key);
            $list_product_by_cate[$item['category_name']] = $product_db->getProductByCategory($item['id'], 3, 0, $key);
            $list_product_by_cate[$item['category_name']] = $product_db->getProductByCategory($item['id'], 3, 0, $key);
        }
        $list_product_new = $product_db->getListTopNewProduct(0, 3, $key);
        $list_product_view = $product_db->getListTopViewProduct(3, 0, $key);

        $this->view->list_product_by_cate = $list_product_by_cate;
        $this->view->list_product_view = $list_product_view;
        $this->view->list_product_new = $list_product_new;
        $this->view->list_cate = $list_cate;

    }

}
