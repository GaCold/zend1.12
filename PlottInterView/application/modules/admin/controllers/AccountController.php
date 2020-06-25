<?php

class Admin_AccountController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
        $this->_helper->layout->setLayout('admin');
    }

    public function indexAction() {
        $page = $this->_request->getParam('page',1); //get curent page param, default 1 if param not available.
        $account_db = new Application_Model_DbTable_Account();
        $data = $account_db->getListAccount();
        $adapter = new Zend_Paginator_Adapter_DbSelect($data); //adapter
        $paginator = new Zend_Paginator($adapter); // setup Pagination
        $paginator->setItemCountPerPage(5); // Items perpage, in this example is 10
        $paginator->setCurrentPageNumber($page); // current page

        $this->view->list_account = $paginator;
    }

    public function deleteAction() {
        $id = $this->_request->getParam('id');
        if ($id) {
            $account_db = new Application_Model_DbTable_Account();
            if ($account_db->deleteAccount($id)) {
                $this->_helper->flashMessenger()->addMessage('Success', 'success');
            }
            else {
                $this->_helper->flashMessenger()->addMessage('Fail', 'error');
            }
        }
        return $this->getResponse()->setRedirect('/admin/account');
    }

    public function createAction() {
        if ($this->_request->isPost()) {
            $data = [
                'username' => $this->_request->getParam('username'),
                'password' => sha1($this->_request->getParam('password')),
                'email' => $this->_request->getParam('email'),
                'phone' => $this->_request->getParam('phone'),
                'address' => $this->_request->getParam('address'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $account_db = new Application_Model_DbTable_Account();
            if ($account_db->createAccount($data)) {
                $this->_helper->flashMessenger()->addMessage('Success', 'success');
            }
            else {
                $this->_helper->flashMessenger()->addMessage('Fail', 'error');
            }
            return $this->getResponse()->setRedirect('/admin/account');
        }
    }

    public function updateAction() {
        $id = $this->_request->getParam('id');
        if ($id) {
            $account_db = new Application_Model_DbTable_Account();

            $account_detail = $account_db->getAccountDetail($id);
            if ($this->_request->isPost()) {
                $data = [
                    'username' => $this->_request->getParam('username'),
                    'email' => $this->_request->getParam('email'),
                    'phone' => $this->_request->getParam('phone'),
                    'address' => $this->_request->getParam('address'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                if ($this->_request->getParam('password')) {
                    $data['password'] = sha1($this->_request->getParam('password'));
                }

                if ($account_db->updateAccount($id, $data)) {
                    $this->_helper->flashMessenger()->addMessage('Success', 'success');
                }else {
                    $this->_helper->flashMessenger()->addMessage('Fail', 'error');
                }
                return $this->getResponse()->setRedirect('/admin/account');
            }
            else if ($account_detail) {
                $this->view->account_detail = $account_detail;
            }
            else {
                $this->_helper->flashMessenger()->addMessage('NotFound', 'error');
                return $this->getResponse()->setRedirect('/admin/account');
            }
        }
        else {
            $this->_helper->flashMessenger()->addMessage('NotFound', 'error');
            return $this->getResponse()->setRedirect('/admin/account');
        }
    }
}