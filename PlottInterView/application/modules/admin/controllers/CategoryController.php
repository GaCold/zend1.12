<?php
class Admin_CategoryController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
        $this->_helper->layout->setLayout('admin');
        $auth = Zend_Auth::getInstance();
        if(!$auth->hasIdentity()) {
           // $this->_redirect("/admin/auth/login");
        }
    }

    public function indexAction() {
        $cate_db = new Application_Model_DbTable_Category();
        $key_search = $this->getRequest()->getParam('key_search');
        $data = $cate_db->getAllCategories($key_search);
        $page = $this->_request->getParam('page',1); //get curent page param, default 1 if param not available.
        $adapter = new Zend_Paginator_Adapter_DbSelect($data); //adapter
        $paginator = new Zend_Paginator($adapter); // setup Pagination
        $paginator->setItemCountPerPage(5); // Items perpage, in this example is 10
        $paginator->setCurrentPageNumber($page); // current page

        $this->view->list_cate = $paginator;
        $this->view->key_search = $key_search;
    }

    public function createAction() {
        if ($this->getRequest()->isPost()) {
            $cate_name = $this->getRequest()->getParam('cate_name');
            $cate_db = new Application_Model_DbTable_Category();

            if($cate_db->checkExistName($cate_name)) {
                $this->_helper->flashMessenger()->addMessage('Category name is exist', 'error');
            }
            else {
                if ($cate_db->create($cate_name)) {
                    $this->_helper->FlashMessenger()->setNamespace('success')->addMessage('Success');
                    return $this->getResponse()->setRedirect('/admin/category');
                }
                else {
                    $this->_helper->flashMessenger()->addMessage('Fail', 'error');
                }
            }
            return $this->getResponse()->setRedirect('/admin/category');
        }
    }

    public function updateAction() {
        $cate_db = new Application_Model_DbTable_Category();
        $cate_id = $this->getRequest()->getParam('id');

        if ($this->getRequest()->isPost()) {
            $cate_id = $this->getRequest()->getParam('id');
            $cate_name = $this->getRequest()->getParam('cate_name');
            if($cate_db->checkExistName($cate_name, $cate_id)) {
                $this->_helper->flashMessenger()->addMessage('Category name is exist', 'error');
            }
            else if ($cate_db->update($cate_id, $cate_name)) {
                $this->_helper->flashMessenger()->addMessage('Success', 'success');
                return $this->getResponse()->setRedirect('/admin/category');
            }
            else {
                $this->_helper->flashMessenger()->addMessage('Fail', 'error');
            }
            return $this->getResponse()->setRedirect('/admin/category');
        }
        else if ($cate_id) {
            $cate_detail = $cate_db->getCategoryDetail($cate_id);
            $this->view->cate_detail = $cate_detail;
        }
        else {
            $this->_helper->flashMessenger()->addMessage('NotFound', 'error');
            return $this->getResponse()->setRedirect('/admin/category');
        }

    }

    public function deleteAction() {
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $cate_id = $this->getRequest()->getParam('id');
        $cate_db = new Application_Model_DbTable_Category();
        if ($cate_db->deleteCategory($cate_id)) {
            $this->_helper->flashMessenger()->addMessage('Success', 'success');
        }
        else {
            $this->_helper->flashMessenger()->addMessage('Fail', 'error');
        }
    }


}

