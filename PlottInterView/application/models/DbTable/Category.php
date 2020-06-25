<?php

class Application_Model_DbTable_Category extends Zend_Db_Table_Abstract {

    protected $_name = 'category';
    protected $db;

    function  __construct() {
        $this->db = Zend_Db_Table_Abstract::getDefaultAdapter();
    }

    public function getListCategory() {
        return $this->db->query(
            $this->db->select()
            ->from($this->_name)
            ->where('invalid = 0')
        )->fetchAll();
    }

    public function create($cate_name) {
        $this->esCapString($cate_name);
        $data = [
            'category_name' => $cate_name,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'invalid' => 0
        ];

        return $this->db->insert($this->_name, $data);
    }

    public function getCategoryDetail($id) {
        return $this->db->query(
            $this->db->select()
                ->from($this->_name)
                ->where("id = {$id} and invalid = 0")
        )->fetch();
    }

    public function update($cate_id, $cate_name) {
        $this->esCapString($cate_name);
        return $this->db->update($this->_name, [
            'category_name' => $cate_name,
            'updated_at' => date('Y-m-d H:i:s'),
        ], "id = {$cate_id}");
    }

    public function checkExistName($name, $id = 0) {
        $this->esCapString($name);
        $sql = '';
        if ($id) {
            $sql = " and id != '{$id}'";
        }
        return $this->db->query(
            $this->db->select()
                ->from($this->_name)
                ->where("category_name = '{$name}' {$sql} and invalid = 0")
        )->fetch();
    }

    public function getAllCategories($search) {
        $this->esCapString($search);
        $sql = '';
        if ($search) {
            $sql = " and category_name like '%{$search}%'";
        }
        $select = $this->getDefaultAdapter()->select();
        $select->from($this->_name,'*')->where('invalid = 0' . $sql);
        return $select;
    }

    public function deleteCategory($cate_id) {
        return $this->db->update($this->_name, [
            'invalid' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
        ], "id = {$cate_id}");
    }

    protected function esCapString(&$str) {
        $search = ["\\", "\0", "\x1a", "'", '"'];
        $replace = ["\\\\", "\\0", "\Z", "\'", '\"'];
        $str = str_replace($search, $replace, $str);
    }
}

