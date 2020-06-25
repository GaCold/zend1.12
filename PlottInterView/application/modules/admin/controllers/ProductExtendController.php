<?php

class Admin_ProductExtendController extends Zend_Controller_Action {

    public function init() {
        $this->_helper->layout->setLayout('admin');
        $auth = Zend_Auth::getInstance();
        if(!$auth->hasIdentity()) {
            //$this->_redirect("/admin/auth/login");
        }
    }

    public function indexAction() {
        $product_extend_db = new Application_Model_DbTable_ProductExtend();
        $this->view->product_extends = $product_extend_db->getProductExtends();
    }

    public function updateAction() {
        $this->_helper->viewRenderer->setNoRender(TRUE);
        if ($this->getRequest()->isPost()) {
            $id = $this->getRequest()->getParam('id');
            $size = $this->getRequest()->getParam('size');
            $product_extend_db = new Application_Model_DbTable_ProductExtend();
            $check = $product_extend_db->checkExistProductExtend($size, $id);
            if ($check) {
                $this->_helper->flashMessenger()->addMessage('Size name is exist', 'error');
            } else if ($product_extend_db->updateProductExtend($id, $size)) {
                $this->_helper->flashMessenger()->addMessage('Success', 'success');
            } else {
                $this->_helper->flashMessenger()->addMessage('Fail', 'error');
            }
            return $this->getResponse()->setRedirect('/admin/product-extend');
        }
    }

    public function createAction() {
        $product_extend_db = new Application_Model_DbTable_ProductExtend();
        if ($this->getRequest()->isPost()) {
            $size = $this->getRequest()->getParam('size');
            $check = $product_extend_db->checkExistProductExtend($size);
            if ($check) {
                $this->_helper->flashMessenger()->addMessage('Size name is exist', 'error');
            } else if ($product_extend_db->createProductExtend($size)) {
                $this->_helper->flashMessenger()->addMessage('Success', 'success');
            } else {
                $this->_helper->flashMessenger()->addMessage('Fail', 'error');
            }
            return $this->getResponse()->setRedirect('/admin/product-extend');
        }
    }

    public function deleteAction() {
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $product_id = $this->getRequest()->getParam('id');
        $cate_db = new Application_Model_DbTable_ProductExtend();
        if ($cate_db->deleteProductExtend($product_id)) {
            $this->_helper->flashMessenger()->addMessage('Success', 'success');
        } else {
            $this->_helper->flashMessenger()->addMessage('Fail', 'error');
        }
        return $this->getResponse()->setRedirect('/admin/product-extend');
    }
}

