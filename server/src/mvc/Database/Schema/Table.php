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

class Table {

    // protected $name = '';

    private $props = [];
    private $primaryKey;
    public $name = '';
    public $charset = 'utf8';

/**
 * @param $name @TODO describe this parameter
 */
    public function __construct($name) {
        $this->name = $name;
    }
/**
 * @param $name @TODO describe this parameter
 * @return props @TODO describe whats returned
 */
    private function addItem($name) {
        $this->props[] = new TableProp($name);
        return $this->props[count($this->props)-1];
    }

/**
 * @param $name @TODO describe this parameter
 * @return addItem @TODO describe whats returned
 */
    public function primary($name) {
        $this->primaryKey = $name;
        return $this->addItem($name)->int(11)->notNull()->unsigned();
    }

/**
 * @param $name @TODO describe this parameter
 * @return addItem @TODO describe whats returned
 */
    public function string($name) {
        return $this->addItem($name)->varchar(255);
    }

/**
 * @param $name @TODO describe this parameter
 * @return addItem @TODO describe whats returned
 */
    public function number($name) {
        return $this->addItem($name)->int(11);
    }

/**
 * @param $name @TODO describe this parameter
 * @return addItem @TODO describe whats returned
 */
    public function field($name) {
        return $this->addItem($name);
    }

/**
 * @return [$deleted, $created] @TODO describe whats returned
 */
    public function timestamps() {
      $created = $this->addItem('created_at')->datetime()->notNull()->default('CURRENT_TIMESTAMP');
      $deleted = $this->addItem('deleted_at')->datetime()->default('NULL');
      return [$deleted, $created];
    }
    /**
     * @TODO: [date description]
     * @param  [type] $name [description]
     */
    public function date($name) {
      return $this->addItem($name)->date();
    }

/**
 * @return $str @TODO describe whats returned
 */
    public function build() {
        $str = 'CREATE TABLE IF NOT EXISTS `'.$this->name.'` (';
        for($i = 0; $i < count($this->props); ++$i) {
            $str .= $this->props[$i]->build();
        }
        $str .= 'PRIMARY KEY (`'.$this->primaryKey.'`)';
        $str .= ') ENGINE=InnoDB DEFAULT CHARSET='.$this->charset.';';
        return $str;
    }

/**
 * @return string @TODO describe whats returned
 */
    public function destroy() {
        return 'DROP TABLE IF EXISTS '.$this->name;
    }

/**
 * @return string @TODO describe whats returned
 */
    public function truncate() {
        return 'TRUNCATE TABLE '.$this->name;
    }
}
