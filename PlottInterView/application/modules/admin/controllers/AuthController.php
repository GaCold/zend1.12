<?php

class Admin_AuthController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
        $this->_helper->layout->disableLayout();
    }

    public function loginAction() {

        if (Zend_Auth::getInstance()->hasIdentity()) {
            $this->_redirect('/admin/category');
        }

        $account_db = new Application_Model_DbTable_Account();
    }

    public function registerAction() {

    }

    public function logoutAction() {
        Zend_Auth::getInstance()->clearIdentity();
        $this->_redirect("/admin/auth/login");
    }


}

