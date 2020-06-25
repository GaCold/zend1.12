<?php

class ProductController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
        $category_db = new Application_Model_DbTable_Category();
        $product_db = new Application_Model_DbTable_Product();

        $this->view->list_cate =  $category_db->getListCategory();
        $this->view->best_seller = $product_db->getListTopNewUpdateProduct(0, 3);
    }

    public function indexAction() {
        $type = $this->getRequest()->getParam('type');
        $product_db = new Application_Model_DbTable_Product();
        if ($type == 1) {
            $list_product = $product_db->getListTopNewProduct(0, 50);
        }
        else{ 
            $list_product = $product_db->getListTopViewProduct(50, 0);
        }
        
        
        $this->view->list_product =  $list_product;
    }


    public function detailAction() {
        $product_id = $this->getRequest()->getParam('id');
        $product_db = new Application_Model_DbTable_Product();
        $product_extend_db = new Application_Model_DbTable_ProductExtend();
        
        if (!$product_id) {
            return $this->getResponse()->setRedirect('/');
        }
        $product_db->updateTotalView($product_id);
        $product_detail = $product_db->getProductDetail($product_id);

        $this->view->product_detail = $product_detail;
        $this->view->product_extend = $product_extend_db->getListProductExtendByProductId($product_id);
    }


}

