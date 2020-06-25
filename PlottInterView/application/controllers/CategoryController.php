<?php

class CategoryController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
        $category_db = new Application_Model_DbTable_Category();
        $product_db = new Application_Model_DbTable_Product();
        $id = $this->getRequest()->getParam('id');
        if (!$id) {
        	return $this->getResponse()->setRedirect('/');
        }
        $this->view->list_cate =  $category_db->getListCategory();
        $this->view->list_product =  $product_db->getProductByCategory($id, 20, 0);
    }
}

