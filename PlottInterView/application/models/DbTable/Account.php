<?php

class Application_Model_DbTable_Account extends Zend_Db_Table_Abstract {

    protected $_name = 'account';
    protected $db;

    function  __construct() {
        $this->db = Zend_Db_Table_Abstract::getDefaultAdapter();
    }

    public function createAccount($data) {
        return $this->db->insert($this->_name, $data);
    }

    public function updateAccount($id, $data) {
        return $this->db->update($this->_name, $data, "id = {$id}");
    }

    public function getListAccount() {
        $select = $this->getDefaultAdapter()->select();
        $select->from($this->_name,'*')->where('invalid = 0');
        return $select;
    }

    public function getAccountDetail($id) {
        return $this->db->query(
            $this->db->select()->from($this->_name)
            ->where("id = {$id}")
        )->fetch();
    }

    public function deleteAccount($id) {
        return $this->db->delete($this->_name, ["id = {$id}"]);
    }

}

