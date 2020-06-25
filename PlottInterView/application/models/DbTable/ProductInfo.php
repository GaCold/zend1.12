<?php

class Application_Model_DbTable_ProductInfo extends Zend_Db_Table_Abstract {

    protected $_name = 'product_info';
    protected $db;

    function __construct() {
        $this->db = Zend_Db_Table_Abstract::getDefaultAdapter();
    }

    public function getProductInfo($product_id) {
        return $this->db->query($this->db->select()
            ->from($this->_name)
            ->where("product_id = {$product_id}")
        )->fetchAll();
    }

}

