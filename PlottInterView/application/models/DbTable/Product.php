<?php

class Application_Model_DbTable_Product extends Zend_Db_Table_Abstract {

    protected $_name = 'product';
    protected $_name_cate = 'category';
    protected $_name_ext = 'product_extend';
    protected $db;

    protected $_referenceMap    = array(
        'ProductExtend' => array(
            'columns'           => array('id'),
            'refTableClass'     => 'ProductExtend',
            'refColumns'        => array('product_id')
        ),
    );

    function __construct() {
        $this->db = Zend_Db_Table_Abstract::getDefaultAdapter();
    }

    public function getListTopBestSeller() {
        $db = Zend_Db_Table_Abstract::getDefaultAdapter();
        return $db->query($db->select()
            ->from($this->_name)
            ->where('invalid = 0')
        )->fetchAll();
    }

    public function getListTopNewProduct($offset, $limit, $search = '') {
        $sql = '';
        if ($search) {
            $sql = "and t.product_name like '%{$search}%'";
        }
        return $this->db->query($this->db->select()
            ->from(['t' => $this->_name], 't.*')
            ->join(['cate' => $this->_name_cate],'t.product_category_id = cate.id', ['cate.category_name'])
            ->joinLeft(['ex' => $this->_name_ext], 'ex.product_id = t.id', ['ex.price'])
            ->where("t.invalid = 0 " . $sql)
            ->group('t.id')
            ->order('t.created_at desc')
            ->limit($limit, $offset)
        )->fetchAll();
    }

    public function getListTopViewProduct($limit, $offset, $search = '') {
        $sql = '';
        if ($search) {
            $sql = "and t.product_name like '%{$search}%'";
        }
        return $this->db->query($this->db->select()
            ->from(['t' => $this->_name], 't.*')
            ->join(['cate' => $this->_name_cate],'t.product_category_id = cate.id', ['cate.category_name'])
            ->joinLeft(['ex' => $this->_name_ext], 'ex.product_id = t.id', ['ex.price'])
            ->where("t.invalid = 0 " . $sql)
            ->group('t.id')
            ->order('t.total_view desc')
            ->limit($limit, $offset)
        )->fetchAll();
    }

    public function getProductByCategory($cate_id, $limit, $offset, $search = '') {
        $sql = '';
        if ($search) {
            $sql = "and t.product_name like '%{$search}%'";
        }
        return $this->db->query($this->db->select()
            ->from(['t' => $this->_name])
            ->join(['cate' => $this->_name_cate],'t.product_category_id = cate.id', ['cate.category_name'])
            ->joinLeft(['ex' => $this->_name_ext], 'ex.product_id = t.id', ['ex.price'])
            ->where("t.product_category_id = '{$cate_id}' and t.invalid = 0 " . $sql)
            ->group('t.id')
            ->order('t.total_view desc')
            ->limit($limit, $offset)
        )->fetchAll();
    }

    public function updateTotalView($product_id) {
        $this->db->update($this->_name, ['total_view' =>  new Zend_Db_Expr('total_view+1')], 'id = '.$product_id);
    }

    public function getProductDetail($product_id) {
        return $this->db->query($this->db->select()
            ->from(['t' => $this->_name])
            ->joinLeft(['ex' => 'product_extend'], 'ex.product_id = t.id', ['ex.size', 'ex.price', 'ex.id as product_extend_id'])
            ->where("t.id = {$product_id} and t.invalid = 0 and ex.total_balance > 0")
        )->fetch();
    }

    public function getProducts($search, $cate_id) {
        $sql = '';
        if ($search) {
            $sql = " and (product_name like '%{$search}%' or cate.category_name like '%{$search}%')";
        }

        if ($cate_id) {
            $sql .= " and cate.id = {$cate_id}";
        }
        $select = $this->getDefaultAdapter()->select();
        $select->from(['t' => $this->_name],'*')
            ->joinLeft(
                ['cate' => $this->_name_cate],
                'cate.id = t.product_category_id and cate.invalid = 0',
                ['cate.category_name', 'cate.id as cate_id']
            )
            ->where('t.invalid = 0' . $sql);
        return $select;
    }

    public function deleteProduct($product_id) {
        $this->db->update($this->_name, ['invalid' =>  1], "id = $product_id");
    }

    public function createProduct($data) {
        $this->db->insert($this->_name, $data);
        return $this->db->lastInsertId();
    }

    public function updateProduct($product_id, $data)  {
        $this->db->update($this->_name, $data, "id = $product_id");
    }

    public function getListTopNewUpdateProduct($offset, $limit, $search = '') {
        $sql = '';
        if ($search) {
            $sql = "and t.product_name like '%{$search}%'";
        }
        return $this->db->query($this->db->select()
            ->from(['t' => $this->_name])
            ->join(['cate' => $this->_name_cate],'t.product_category_id = cate.id', ['cate.category_name'])
            ->where("t.invalid = 0 " . $sql)
            ->order('t.updated_at desc')
            ->limit($limit, $offset)
        )->fetchAll();
    }
}

