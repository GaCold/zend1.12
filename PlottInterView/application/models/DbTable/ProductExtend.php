<?php

class Application_Model_DbTable_ProductExtend extends Zend_Db_Table_Abstract {

    protected $_name = 'product_extend';
    protected $db;

    function __construct() {
        $this->db = Zend_Db_Table_Abstract::getDefaultAdapter();
    }

    public function getProductExtends() {
        return $this->db->query(
            $this->db->select()->from($this->_name)->where('invalid = 0 and product_id = 0')
        )->fetchAll();
    }

    public function updateProductExtend($id, $size) {
        $this->esCapString($size);
        return $this->db->update($this->_name, ['size' => $size], "id = {$id}");
    }

    public function deleteProductExtend($id) {
        return $this->db->delete($this->_name, ["id = {$id}"]);
    }

    public function deleteProductExtendByProductId($id) {
        return $this->db->delete($this->_name, ["product_id = {$id}"]);
    }

    public function createProductExtend($size) {
        $this->esCapString($size);
        return $this->db->insert($this->_name, [
            'size' => $size,
            'invalid' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function checkExistProductExtend($size, $id = 0) {
        $this->esCapString($size);
        $sql = '';
        if ($id) {
            $sql = " and id != {$id}";
        }
        return $this->db->query(
            $this->db->select()
            ->from($this->_name)
            ->where("product_id = 0 and invalid = 0 and size = '{$size}' {$sql}")
        )->fetch();
    }

    public function insertProductExtend($data) {
        foreach ($data as $value) {
            $this->db->insert($this->_name, $value);
        }
    }

    public function esCapString(&$str) {
        $search = ["\\", "\0", "\x1a", "'", '"'];
        $replace = ["\\\\", "\\0", "\Z", "\'", '\"'];
        $str = str_replace($search, $replace, $str);
    }

    public function getListProductExtendByProductId($product_id) {
        return $this->db->query(
            $this->db->select()->from($this->_name)->where("product_id = $product_id and invalid = 0")
        )->fetchAll();
    }

}