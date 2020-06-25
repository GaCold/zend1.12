<?php

class Admin_ProductColorController extends Zend_Controller_Action {

    public function init() {
        $this->_helper->layout->setLayout('admin');
        $auth = Zend_Auth::getInstance();
        if(!$auth->hasIdentity()) {
            //$this->_redirect("/admin/auth/login");
        }
    }

    public function indexAction() {
        $product_extend_db = new Application_Model_DbTable_ProductColor();
        $this->view->product_colors = $product_extend_db->getProductColors();
    }

    public function updateAction() {
        $this->_helper->viewRenderer->setNoRender(TRUE);
        if ($this->getRequest()->isPost()) {
            $id = $this->getRequest()->getParam('id');
            $color = $this->getRequest()->getParam('color');
            $rgb = $this->getRequest()->getParam('rgb');
            $product_color_db = new Application_Model_DbTable_ProductColor();
            $check = $product_color_db->checkExistProductColor($color, $id);
            if ($check) {
                $this->_helper->flashMessenger()->addMessage('Size name is exist', 'error');
            } else if ($product_color_db->updateProductColor($id, $color, $rgb)) {
                $this->_helper->flashMessenger()->addMessage('Success', 'success');
            } else {
                $this->_helper->flashMessenger()->addMessage('Fail', 'error');
            }
            return $this->getResponse()->setRedirect('/admin/product-color');
        }
    }

    public function createAction() {
        $product_color_db = new Application_Model_DbTable_ProductColor();
        if ($this->getRequest()->isPost()) {
            $color = $this->getRequest()->getParam('color');
            $rgb = $this->getRequest()->getParam('rgb');
            $check = $product_color_db->checkExistProductColor($color);
            if ($check) {
                $this->_helper->flashMessenger()->addMessage('Size name is exist', 'error');
            } else if ($product_color_db->createProductColor($color, $rgb)) {
                $this->_helper->flashMessenger()->addMessage('Success', 'success');
            } else {
                $this->_helper->flashMessenger()->addMessage('Fail', 'error');
            }
        }
        return $this->getResponse()->setRedirect('/admin/product-color');
    }

    public function deleteAction() {
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $product_id = $this->getRequest()->getParam('id');
        $cate_db = new Application_Model_DbTable_ProductColor();
        if ($cate_db->deleteProductColor($product_id)) {
            $this->_helper->flashMessenger()->addMessage('Success', 'success');
        } else {
            $this->_helper->flashMessenger()->addMessage('Fail', 'error');
        }
        return $this->getResponse()->setRedirect('/admin/product-color');
    }

}

