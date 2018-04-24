<?php namespace MVC\Helpers;
/**
 * @TODO: BJARTE
 * Description of file
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

class File {


/**
 * @TODO [getClassNames description]
 * @param  [type] $namespace [description]
 * @return [type]            [description]
 */
    public static function getClassNames($namespace) {

        $namespaceRelativePath = str_replace('\\', DIRECTORY_SEPARATOR, $namespace);
        $includePathStr = get_include_path();
        $includePathArr = explode(PATH_SEPARATOR, $includePathStr);
        if(!in_array("", $includePathArr)) 
            $includePathArr[] = "";

        $classArr = [];
        foreach ($includePathArr as $includePath) {
            $path = APP_ROOT . $includePath . DIRECTORY_SEPARATOR . $namespaceRelativePath;
            if (is_dir($path)) {

                $dir = dir($path);

                while (false !== ($item = $dir->read())) {
                    $matches = array();
                    if (preg_match('/^(?<class>[^.].+)\.php$/', $item, $matches)) {
                        $classArr[] = $matches['class'];
                    }
                }
                $dir->close();
            }
        }
        return $classArr;
    }

    function openToWrite($dest) {
        return fopen($dest, "w");
    }
    
    function makeDirIfNotExist($dir) {

      if(!file_exists($dir)) {
          @mkdir($dir);
      }
    }

    // @return 1 if ERROR
    function moveFile($src, $dest) {

        $didMove = rename($src, $dest);
        if (!$didMove) {
            return 1;
        }
        return 0;
    }

    // @return 1 if ERROR
    function newFile($dest) {
        $output = fopen("$dest", "w");
        if(!$output) {
            return 1;
        }  
        return 0;
    }


}
