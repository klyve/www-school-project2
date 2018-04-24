<?php
/**
 * @TODO: Henrik finish commenting
 * This file with class SQL is used to insert, search, find and update the database, it takes
 * the data, the table and/or colums or selector and returns the finished query string.
 *
 *
 * PHP version 7
 *
 *
 * @category   Helpers
 * @package    Framework
 * @version    1.0
 * @link       https://bitbucket.org/klyve/imt2291-project1-spring2018
 * @since      File available since Release 1.0
 */

namespace MVC\Helpers;

class SQL {
    /** The insert function takes only the data () */
    public static function insert($data, $table) {
        $key = array_keys($data);
        $query ="INSERT INTO $table ( ". implode(',' , $key) .") VALUES(:".     implode(",:" , $key) .")";
        return $query;
    }
/**
 * @TODO: [search description]
 * @param  [type] $data    [description]
 * @param  [type] $table   [description]
 * @param  string $columns [description]
 * @return [type]          [description]
 */
    public static function search($data, $table, $columns = "*") {
        $select = "";
        foreach($data as $key => $value) {
            if($select !== "") {
                $select .= " OR $key LIKE CONCAT('%', :$key, '%')";
                continue;
            }
            $select = "$key LIKE CONCAT('%', :$key, '%')";

        }
        $query = "SELECT $columns FROM $table";
        if(count($data) > 0) {
            $query .= " WHERE $select";
        }

        return $query;
    }

    public static function limitResults($query, $limit) {
        $limit = (int)$limit;
        return $query . " LIMIT " . $limit;
        // return $query;
    }
/**
 * @TODO: [find description]
 * @param  [type] $data    [description]
 * @param  [type] $table   [description]
 * @param  string $columns [description]
 * @return [type]          [description]
 */
    public static function find($data, $table, $columns = "*") {
        $select = "";
        foreach($data as $key => $value) {
            if($select !== "") {
                $select .= " AND $key=:$key";
                continue;
            }
            $select = "$key=:$key";

        }
        $query = "SELECT $columns FROM $table";
        if(count($data) > 0) {
            $query .= " WHERE $select";
        }

        return $query;
    }
    // UPDATE table SET key=?, key=?
    /**
     * @TODO: [update description]
     * @param  [type] $data     [description]
     * @param  [type] $selector [description]
     * @param  [type] $table    [description]
     * @return [type]           [description]
     */
    public static function update($data, $selector, $table) {
        if(count($data) == 0) {
            return false;
        }
        $update = "";
        foreach($data as $key => $value) {
            if($update !== "") {
                $update .= ", $key=:$key";
                continue;
            }
            $update = "$key=:$key";
            // echo $update . "<br />";
        }
        $query = "UPDATE $table SET ";
        $where = "";
        foreach($selector as $key => $value) {
            if($where !== "") {
                $where .= " AND $key=:$key";
            }
            $where .= "$key=:$key";
        }
        $where = " WHERE ".$where;

        return $query.$update.$where;
    }
}
