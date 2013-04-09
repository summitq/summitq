<?php 

include_once("config.php");
echo "Hello world!"; 
//异步发信息更新听力数据库
function updateListen(){
    $fp = fsockopen($hostname,$port,$errno,$errstr,5);
    if(!$fp){
       echo "$errstr($errno)<br />\n";
    }
    fputs($fp,"GET /index.php?param=1\r\n");
    fclose($fp);
}

//updateListen()；

echo $hostname."< br />".$port;

?>


