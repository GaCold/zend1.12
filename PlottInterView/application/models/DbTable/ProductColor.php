<?php

class Application_Model_DbTable_ProductColor Extends Zend_Db_Table_Abstract {

    protected $_name = 'product_color';

    protected $db;

    function __construct() {
        $this->db = Zend_Db_Table_Abstract::getDefaultAdapter();
    }

    public function getProductColors() {
        return $this->db->query(
            $this->db->select()->from($this->_name)->where('product_extend_id = 0')
        )->fetchAll();
    }

    public function updateProductColor($id, $color, $rgb) {
        $this->esCapString($rgb);
        $this->esCapString($color);

        return $this->db->update($this->_name, ['color_name' => $color, 'rgb' => $rgb], "id = {$id}");
    }

    public function deleteProductColor($id) {
        return $this->db->delete($this->_name, ["id = {$id}"]);
    }

    public function createProductColor($color, $rgb) {
        $this->esCapString($color);
        $this->esCapString($rgb);
        return $this->db->insert($this->_name, [
            'color_name' => $color,
            'product_extend_id' => 0,
            'rgb' => $rgb,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function checkExistProductColor($color, $id = 0) {
        $sql = '';
        if ($id) {
            $sql = " and id != {$id}";
        }
        $this->esCapString($color);
        return $this->db->query(
            $this->db->select()
                ->from($this->_name)
                ->where("product_extend_id = 0 and color_name = '{$color}' {$sql}")
        )->fetch();
    }

    protected function esCapString(&$str) {
        $search = ["\\", "\0", "\x1a", "'", '"'];
        $replace = ["\\\\", "\\0", "\Z", "\'", '\"'];
        $str = str_replace($search, $replace, $str);
    }

    public function getProductColorById($id) {
        return $this->db->query(
            $this->db->select()->from($this->_name)->where("id = '{$id}'")
        )->fetch();
    }
}

