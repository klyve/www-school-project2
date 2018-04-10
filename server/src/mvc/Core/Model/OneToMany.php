<?php namespace MVC\Core\Model;
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

interface OneToMany {
/**
 * @TODO: [getManyInstance description]
 * @param  [type] $instance [description]
 * @return [type]           [description]
 */
    public function getManyInstance($instance);
}
