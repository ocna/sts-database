<?php
class Admin_Model_UserMapper
{
    const TABLE_NAME = 'Admin_Model_DbTable_User';
    protected $dbTable;

    public function setDbTable($dbTable)
    {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (! $dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->dbTable = $dbTable;
        return $this;
    }

    public function getDbTable()
    {
        if (null === $this->dbTable) {
            $this->setDbTable(self::TABLE_NAME);
        }
        return $this->dbTable;
    }

    public function save(Admin_Model_User $user)
    {}

    public function find($id, Admin_Model_User $user)
    {}

    public function fetchAll()
    {
        $resultSet = $this->getDbTable()
            ->fetchAll();
        $entries = array();
        foreach ($resultSet as $row) {
            $entries[] = $this->buildDomainObject($row);
        }
        return $entries;
    }

    private function buildDomainObject($row)
    {
        $object = new Admin_Model_User();
        $parameters = array();
        foreach ($row as $key => $value) {
            $func = create_function('$c', 'return strtoupper($c[1]);');
            $parameter = preg_replace_callback('/_([a-z])/', $func, $key);
            $parameters[$parameter] = $value;
        }
        return $object->setOptions($parameters);
    }
}

