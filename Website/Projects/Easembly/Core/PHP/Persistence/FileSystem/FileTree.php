<?php
/**
 * Created by PhpStorm.
 * User: Judah
 * Date: 10/1/15
 * Time: 08:54
 */
function phpFileTree($directory, $return_link, $extensions = array()) {
    // Generates a valid XHTML list of all directories, sub-directories, and files in $directory
    // Remove trailing slash
    $code="";
    if( substr($directory, -1) == "/" ) $directory = substr($directory, 0, strlen($directory) - 1);
    $code .= phpFileTreeDirectory($directory, $return_link, $extensions);
    return $code;
}

function phpFileTreeDirectory($directory, $return_link, $extensions = array(), $first_call = true) {
    // Recursive function called by php_file_tree() to list directories/files

    // Get and sort directories/files
    if( function_exists("scandir") ) $file = scandir($directory); else $file = php4ScanDirectory($directory);
    natcasesort($file);
    // Make directories first
    $files = $dirs = array();
    foreach($file as $this_file) {
        if( is_dir("$directory/$this_file" ) ) $dirs[] = $this_file; else $files[] = $this_file;
    }
    $file = array_merge($dirs, $files);

    // Filter unwanted extensions
    if( !empty($extensions) ) {
        foreach( array_keys($file) as $key ) {
            if( !is_dir("$directory/$file[$key]") ) {
                $ext = substr($file[$key], strrpos($file[$key], ".") + 1);
                if( !in_array($ext, $extensions) ) unset($file[$key]);
            }
        }
    }

    $php_file_tree = "";

    if( count($file) > 2 ) { // Use 2 instead of 0 to account for . and .. "directories"
        $php_file_tree = "<ul";
        if( $first_call ) { $php_file_tree .= " class=\"DivClassPHPFileTree\""; $first_call = false; }
        $php_file_tree .= ">";
        foreach( $file as $this_file ) {
            if( $this_file != "." && $this_file != ".." ) {
                if( is_dir("$directory/$this_file") ) {
                    // Directory
                    $php_file_tree .= "<li class=\"LiClassPHPFileTreeDirectory\"><a href=\"#\">" . htmlspecialchars($this_file) . "</a>";
                    $php_file_tree .= phpFileTreeDirectory("$directory/$this_file", $return_link ,$extensions, false);
                    $php_file_tree .= "</li>";
                } else {
                    // File
                    // Get extension (prepend 'ext-' to prevent invalid classes from extensions that begin with numbers)
                    $ext = "ext-" . substr($this_file, strrpos($this_file, ".") + 1);
                    $urlEncode=urlencode($this_file);
                    $fileLocation="$directory/" . $urlEncode;
                    $myFile = fopen($fileLocation, "r");// or die("Unable to open file!");
                    $myFileText =  fread($myFile,filesize($fileLocation));
                    fclose($myFile);
                    $myFileText = str_replace("'", "\\'", $myFileText);
                    $myFileText = str_replace("\n", "\\n", $myFileText);
                    $link = str_replace("[link]", $myFileText, $return_link);
                    $link = str_replace("[fileName]", $this_file, $link);
                    $php_file_tree .= "<li class=\"LiClassPHPFileTreeFile " . strtolower($ext) . "\"><a href=\"$link\">" . htmlspecialchars($this_file) . "</a></li>";
                }
            }
        }
        $php_file_tree .= "</ul>";
    }
    return $php_file_tree;
}

// For PHP4 compatibility
function php4ScanDirectory($dir) {
    $dh  = opendir($dir);
    while( false !== ($filename = readdir($dh)) ) {
        $files[] = $filename;
    }
    sort($files);
    return($files);
}