<?php namespace MVC\Core;
/**
 * @TODO: BJARTE
 * Description of file
 *
 *
 * PHP version 7
 *
 *
 * @category   Core
 * @package    Framework
 * @version    1.0
 * @link       https://bitbucket.org/klyve/imt2291-project1-spring2018
 * @since      File available since Release 1.0
 */

use \MVC\Helpers\SQL;

class Model {

    private $_excludes = ['table', '_excludes', 'exclude', '_dirty', '_findVars', 'protected', '_protected'];
    private $_dirty = false;
    private $_findVars = [];

    public $_protected = ['protected', '_protected', 'table'];
/**
 * @TODO: [__construct description]
 */
    public function __construct() {}

/**
 * @param $includeExcluded @TODO describe this parameter
 * @return $compileVars @TODO describe whats returned
 */
    private function findObjects($includeExcluded = false) {
        $vars = get_object_vars($this);
        $compileVars = [];
        $userExcludes = [];
        if(isset($this->exclude)) {
            $userExcludes = $this->exclude;
        }
        foreach($vars as $key => $value) {
            if(is_numeric($key)) continue;

            if(!in_array($key, $this->_excludes) && !in_array($key, $userExcludes)) {
                $compileVars[$key] = $value;
            }
        }
        return $compileVars;
    }

/**
 * @TODO: [findClasses description]
 * @return [type] [description]
 */
    public function findClasses() {

        // $vars = $this->findObjects(true);
        $vars = get_object_vars($this);
        $findMany = [];
        $findOne = [];
        foreach($vars as $key => $value) {
            if(is_numeric($key)) continue;
            if(in_array($key, $this->_excludes)) continue;
            if(gettype($value) === 'string' && class_exists($value)) {
                $findOne[$key] = $value;
            }else if(gettype($value) === 'array' && count($value) > 0 && gettype($value[0]) === 'string' && class_exists($value[0])) {
                $findMany[$key] = $value[0];
            }
        }
        $this->getSingleDependencies($findOne);
        $this->getManyDependencies($findMany);
    }

/**
 * @param $findOne @TODO describe this parameter
 */
    private function getSingleDependencies($findOne) {
        foreach($findOne as $key => $value) {
            $instance = DependencyInjection::inject($value);
            $this->$key = $instance->getSingleInstance($this);
        }
    }

/**
 * @param $findMany @TODO describe this parameter
 */
    private function getManyDependencies($findMany) {
        foreach($findMany as $key => $value) {
            $instance = DependencyInjection::inject($value);
            $this->$key = $instance->getManyInstance($this);
        }
    }

/**
 * @param $data @TODO describe this parameter
 * @param $selector @TODO describe this parameter
 * @return $this @TODO describe whats returned
 */
    public function find($data, $selector = "*") {
        // echo "<hr />";
        $query = SQL::find($data, $this->table, $selector);
        $dbi = Database::instance();
        $stmt = $dbi->prepare($query);
        $stmt->execute($data);
        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch();
            
            $this->_findVars = $data;
            $this->_dirty = true;
            foreach($row as $key => $value) {
                $this->$key = $value;
            }
            $this->findClasses();
        }
        return $this;
    }

/**
 * @param $data @TODO describe this parameter
 * @param $selector @TODO describe this parameter
 * @return $ret @TODO describe whats returned
 */
    public function search($data, $limit = 0, $selector = "*") {
        $query = SQL::search($data, $this->table, $selector);
        if($limit !== 0) {
            $query = SQL::limitResults($query, $limit);
        }
        
        $dbi = Database::instance();
        $stmt = $dbi->prepare($query);
        
        $stmt->execute($data);
        $ret = [];

        if($stmt->rowCount() > 0) {
            $ret = $stmt->fetchAll(\PDO::FETCH_CLASS, get_called_class());
            foreach($ret as $retval) {
                $retval->findClasses();
            }
        }
        return $ret;
    }


/**
 * @param $data @TODO describe this parameter
 * @param $selector @TODO describe this parameter
 * @return $ret @TODO describe whats returned
 */
    public function all($data = [], $selector = "*") {
        $query = SQL::find($data, $this->table, $selector);
        $dbi = Database::instance();
        $stmt = $dbi->prepare($query);
        $stmt->execute($data);
        $ret = [];
        if($stmt->rowCount() > 0) {
            $ret = $stmt->fetchAll(\PDO::FETCH_CLASS, get_called_class());
            foreach($ret as $retval) {
                $retval->findClasses();
            }
        }
        return $ret;
    }


/**
 * @return update @TODO describe whats returned
 * @return create @TODO describe whats returned
 */
    public function save() {
        $compileVars = $this->findObjects();

        if($this->_dirty)
            return $this->update($this->_findVars);

        return $this->create($compileVars);
    }

/**
 * @param $data @TODO describe this parameter
 * @param $selector @TODO describe this parameter
 */
    public function update($selector, $data = false) {
        if(!$data) {
            $data = $this->findObjects();
        }

        $query = SQL::update($data, $selector, $this->table);
        $queryData = array_merge($data, $selector);

        $dbi = Database::instance();
        $stmt = $dbi->prepare($query);
        $stmt->execute($queryData);
    }

/**
 * @param $data @TODO describe this parameter
 * @return $dbi->lastInsertId() @TODO describe whats returned
 */
    public function create($data = []) {
        $query = SQL::insert($data, $this->table);

        $dbi = Database::instance();
        $stmt = $dbi->prepare($query);
        $stmt->execute($data);
        foreach($data as $key => $value) {
            $this->$key = $value;
        }
        return $dbi->lastInsertId();
    }

/**
 * @TODO: [saveUnique description]
 */
    public function saveUnique() {
        $compileVars = $this->findObjects();
        $query = SQL::find($compileVars, $this->table);
        $dbi = Database::instance();
        $stmt = $dbi->prepare($query);
        $stmt->execute($compileVars);
        if($stmt->rowCount() == 0) {
            $this->save();
        }
    }


    public function exportData() {
        $objects = get_object_vars($this);

        $protected = array_merge($this->_protected, $this->_excludes);
        if(isset($this->protected)) {
            $protected = array_merge($protected, $this->protected);
        }

        $ret = [];
        foreach($objects as $key => $value) {
            if(!in_array($key, $protected) && !is_integer($key)) {
                $ret[$key] = $value;
            }
        }
        

        return $ret;
    }

}
