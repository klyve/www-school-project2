<?php namespace MVC\Database\Schema;
/**
 * @TODO: BJARTE
 * Description of file
 *
 *
 * PHP version 7
 *
 *
 * @category   Database
 * @package    Framework
 * @version    1.0
 * @link       https://bitbucket.org/klyve/imt2291-project1-spring2018
 * @since      File available since Release 1.0
 */

class TableProp {
    private $fieldName;
    private $type;
    private $_length = 0;
    private $allowNull = true;
    private $default = 'NULL';
    private $isZerofill = false;
    private $isUnsigned = false;
    private $isBinary = false;
    private $increment = false;
    private $isUnique = false;

/**
 * @TODO: [__construct description]
 * @param [type] $name [description]
 */
    public function __construct($name) {
        $this->fieldName = $name;
    }
    /**
     * @TODO: [build description]
     * @return [type] [description]
     */
    public function build() {
        $str = '`' . $this->fieldName . '` ';
        $str .= $this->type . (($this->_length > 0) ? '(' . $this->_length . ')' : '');
        $str .= (($this->isUnsigned) ? ' UNSIGNED' : '');
        $str .= (($this->allowNull) ? '' : ' NOT NULL');
        $str .= (($this->default !== 'NULL' && $this->type === 'DATETIME') ? " DEFAULT " . $this->default . "" : '');
        $str .= (($this->default !== 'NULL' && $this->type !== 'DATETIME') ? " DEFAULT '" . $this->default . "'" : '');
        $str .= (($this->default === 'NULL' && $this->allowNull) ? " DEFAULT NULL": '');
        $str .= (($this->increment) ? ' AUTO_INCREMENT' : '');
        $str .= ',';
        return $str;
    }
    /**
     * @TODO: [print description]
     * @return [type] [description]
     */
    public function print() {
        echo $this->fieldName . " ";
        echo $this->type . " ";
        echo (($this->allowNull) ? "True " : "False ");
        echo $this->default . " ";
        echo $this->_length . "\n";
        return $this;
    }
/**
 * @TODO: [length description]
 * @param  [type] $value [description]
 */
    public function length($value) {
        $this->_length = $value;
    }
    /**
     * @TODO: [__call description]
     * @param  [type] $methodName [description]
     * @param  [type] $args       [description]
     * @return [type]             [description]
     */
    public function __call($methodName, $args) {
        $this->type = $methodName;
        if(isset($args[0]))
            $this->_length = $args[0];
        return $this;
     }
     /**
      * @TODO: [datetime description]
      * @return [type] [description]
      */
    public function datetime() {
      $this->type = 'DATETIME';
      return $this;
    }
    /**
     * @TODO: [date description]
     * @return [type] [description]
     */
    public function date() {
      $this->type = 'DATE';
      return $this;
    }
    /**
     * @TODO: [autoIncrement description]
     * @return [type] [description]
     */
    public function autoIncrement() {
        $this->increment = true;
        return $this;
    }
    /**
     * @TODO: [unique description]
     * @return [type] [description]
     */
    public function unique() {
        $this->isUnique = true;
        return $this;
    }
    /**
     * @TODO: [notNull description]
     * @return [type] [description]
     */
    public function notNull() {
        $this->allowNull = false;
        return $this;
    }
    /**
     * @TODO: [default description]
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    public function default($value) {
        $this->default = $value;
        return $this;
    }
    /**
     * @TODO: [unsigned description]
     * @return [type] [description]
     */
    public function unsigned() {
        $this->isUnsigned = true;
        return $this;
    }

    /**
     * Sets isUnsigned to false.
     * @return altered TableProp.
     */
    public function signed(){
        $this->isUnsigned = false;
        return $this;
    }
    /**
     * @TODO: [zerofill description]
     * @return [type] [description]
     */
    public function zerofill() {
        $this->isZerofill = true;
        return $this;
    }
    /**
     * @TODO: [binary description]
     * @return [type] [description]
     */
    public function binary() {
        $this->isBinary = true;
        return $this;
    }
    /**
     * @TODO: [extra description]
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
    public function extra($value) {
        $this->extra = $value;
        return $this;
    }

}
