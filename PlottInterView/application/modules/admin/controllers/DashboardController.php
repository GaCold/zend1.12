<?php

class Admin_DashboardController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
        $this->_helper->layout->setLayout('admin');
    }

    public function indexAction() {
        // action body
    }


}

