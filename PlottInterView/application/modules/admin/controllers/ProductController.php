<?php

class Admin_ProductController extends Zend_Controller_Action {

    public function init() {
        $this->_helper->layout->setLayout('admin');
        $auth = Zend_Auth::getInstance();
        if(!$auth->hasIdentity()) {
            //$this->_redirect("/admin/auth/login");
        }
    }

    public function indexAction() {
        $product_db = new Application_Model_DbTable_Product();
        $cate_db = new Application_Model_DbTable_Category();
        $key_search = $this->getRequest()->getParam('key_search');
        $cate_id = $this->getRequest()->getParam('cate_id');
        $data = $product_db->getProducts($key_search, $cate_id);
        $page = $this->_request->getParam('page',1);
        $adapter = new Zend_Paginator_Adapter_DbSelect($data);
        $paginator = new Zend_Paginator($adapter);
        $paginator->setItemCountPerPage(5);
        $paginator->setCurrentPageNumber($page);


        $this->view->list_cate = $cate_db->getListCategory();
        $this->view->list_product = $paginator;
        $this->view->key_search = $key_search;
        $this->view->cate_id = $cate_id;
    }

    public function updateAction() {
        $product_id = $this->getRequest()->getParam('id');
        $cate_db = new Application_Model_DbTable_Category();
        $product_extend_db = new Application_Model_DbTable_ProductExtend();
        $product_color_db = new Application_Model_DbTable_ProductColor();
        $product_db = new Application_Model_DbTable_Product();

        if ($this->getRequest()->isPost()) {
            $files = $_FILES['image'];
            $img_path = [];
            for ($i = 0; $i < 3; $i++) {
                if (isset($files['tmp_name'][$i]) && $files['tmp_name'][$i]) {
                    $name = time() . rand(1000, 9999) . $files['name'][$i];
                    $img_path[$i+1] = $name;
                    move_uploaded_file($files['tmp_name'][$i], $_SERVER["DOCUMENT_ROOT"] . '/upload/'.$name);
                }
                
            }
            
            $data = [
                'product_name' => $this->getRequest()->getParam('product_name'),
                'product_category_id' => $this->getRequest()->getParam('cate_id'),
                'product_unit' => $this->getRequest()->getParam('product_unit'),
                'product_code' => $this->getRequest()->getParam('product_code'),
                'title' => $this->getRequest()->getParam('title'),
                'description' => $this->getRequest()->getParam('description'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            for ($i = 0; $i < 3; $i++) {
                $tmp = $i + 1;
                if (isset($img_path[$tmp])) {
                   $data['img' .$tmp] = $img_path[$tmp];
                }
            }
           

            $sizes = $this->getRequest()->getParam('size');
            $colors = $this->getRequest()->getParam('color');
            $prices = $this->getRequest()->getParam('price');
            $total_balance = $this->getRequest()->getParam('total_balance');
            $product_db->updateProduct($product_id, $data);

            $data_product_extend = [];
            foreach ($sizes as $key => $size) {
                $color_detail = $product_color_db->getProductColorById($colors[$key]);
                $data_product_extend[] = [
                    'product_id' => $product_id,
                    'size' => $size,
                    'price' => $prices[$key],
                    'color_name' => $color_detail['color_name'],
                    'color_code' => $color_detail['rgb'],
                    'total_balance' => $total_balance[$key],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
            }
            

            $product_extend_db->deleteProductExtendByProductId($product_id);
            $product_extend_db->insertProductExtend($data_product_extend);
            
            $this->_helper->flashMessenger()->addMessage('Success', 'success');
            return $this->getResponse()->setRedirect('/admin/product');
        }

        $this->view->categories = $cate_db->getListCategory();
        $this->view->product_colors = $product_color_db->getProductColors();;
        $this->view->product_extends = $product_extend_db->getProductExtends();   
        $this->view->products = $product_db->getProductDetail($product_id);   
        $this->view->sizes = $product_extend_db->getListProductExtendByProductId($product_id);   
    }

    public function createAction() {
        $cate_db = new Application_Model_DbTable_Category();
        $product_extend_db = new Application_Model_DbTable_ProductExtend();
        $product_color_db = new Application_Model_DbTable_ProductColor();
        $product_db = new Application_Model_DbTable_Product();

        if ($this->getRequest()->isPost()) {
            $files = $_FILES['image'];
            $img_path = [];
            for ($i = 0; $i < 3; $i++) {
                $name = time() . rand(1000, 9999) . $files['name'][$i];
                $img_path[$i+1] = $name;
                move_uploaded_file($files['tmp_name'][$i], $_SERVER["DOCUMENT_ROOT"] . '/upload/'.$name);
            }
            
            $data = [
                'product_name' => $this->getRequest()->getParam('product_name'),
                'product_category_id' => $this->getRequest()->getParam('cate_id'),
                'product_unit' => $this->getRequest()->getParam('product_unit'),
                'product_code' => $this->getRequest()->getParam('product_code'),
                'title' => $this->getRequest()->getParam('title'),
                'description' => $this->getRequest()->getParam('description'),
                'img1' => $img_path[1],
                'img2' => $img_path[2],
                'img3' => $img_path[3],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $sizes = $this->getRequest()->getParam('size');
            $colors = $this->getRequest()->getParam('color');
            $prices = $this->getRequest()->getParam('price');
            $total_balance = $this->getRequest()->getParam('total_balance');
            $product_id =  $product_db->createProduct($data);

            $data_product_extend = [];
            foreach ($sizes as $key => $size) {
                $color_detail = $product_color_db->getProductColorById($colors[$key]);
                $data_product_extend[] = [
                    'product_id' => $product_id,
                    'size' => $size,
                    'price' => $prices[$key],
                    'color_name' => $color_detail['color_name'],
                    'color_code' => $color_detail['rgb'],
                    'total_balance' => $total_balance[$key],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
            }

            $product_extend_db->insertProductExtend($data_product_extend);
            
            $this->_helper->flashMessenger()->addMessage('Success', 'success');
            return $this->getResponse()->setRedirect('/admin/product');
        }

        $this->view->categories = $cate_db->getListCategory();
        $this->view->product_colors = $product_color_db->getProductColors();;
        $this->view->product_extends = $product_extend_db->getProductExtends();
    }

    public function deleteAction() {
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $product_id = $this->getRequest()->getParam('id');
        $cate_db = new Application_Model_DbTable_Product();
        if ($cate_db->deleteProduct($product_id)) {
            $this->_helper->flashMessenger()->addMessage('Success', 'success');
        }
        else {
            $this->_helper->flashMessenger()->addMessage('Fail', 'error');
        }
    }


}

