<?php 
	/*
     * 更新listen数据表数据
	 */
header("Content-Type:text/html;charset=GBK");
//header("Cache-Control:no-cache");

include_once( 'config.php' );

class collect{

	private $targetUrl = "http://www.putclub.com/html/listening/digest/list_102_1.html";
	private $dataTable = "test_listen";

    function fn_collectList(){
	
        $con = self::fn_get_contents($this->targetUrl);

        //匹配文章列表所有内容
        $preg = "#<div id=\"news_sort\">(.*)<div class=\"dede_pages\">#iUs";
        preg_match($preg,$con,$arr);
        $content = $arr[1];

        //二次匹配 获取每条内容的时间 链接 标题
        $preg_li = "#<li><span>(.*)</span><a href=\"(.*)\" target=\"_blank\">(.*)</a></li>#iUs";
        preg_match_all($preg_li,$content,$arr_li);

        //最新一页要更新数据的条数
        $urlIndex = 0;
        $orgUrl = "http://www.putclub.com";
        $LastSqlRecord = self::fn_LastSqlRecord();
        foreach($arr_li[2] as $id=>$v){
            $absoluteUrl = $orgUrl.$v;
            if($absoluteUrl == $LastSqlRecord[1]){
                break;
            }
            $urlIndex++;
        }

        //判断文章列表是否有更新内容
        $updateUrlArr = array_slice($arr_li[2], 0, $urlIndex);
        if(!empty($updateUrlArr)){
            rsort($updateUrlArr,SORT_NUMERIC);
            //取出要更新的url 并填充数据库
            foreach($updateUrlArr as $id=>$v){
                $absoluteUrl = $orgUrl.$v;
                self::fn_collectContent($absoluteUrl);
            }
        }
    }

    function fn_collectContent($absoluteUrl){
        $contentCon = self::fn_get_contents($absoluteUrl);
        $pre_arr = array (
            "mp3_preg"  => "#<div class=\"download_mp3\"><a href=\"(.*)\">(.*)</a>#iUs",
            "org_preg"  => "#<div id=\"original\"><p style=\"text-align: center;\">(.*)</p><p>(.*)<div id=\"translation\" class=\"translation\">#iUs",
            "trn_preg"  => "#<div id=\"translation\" class=\"translation\"><p style=\"text-align: center;\">(.*)</p>(.*)<div class=\"clear\">#iUs"
        );
        //获取mp3 url
        $mp3_result = self::fn_preg_match($pre_arr['mp3_preg'],$contentCon);
        $mp3_url = $mp3_result[1];

        //获取英文标题和内容
        $org_result = self::fn_preg_match($pre_arr['org_preg'],$contentCon);
        $orgTitle = $org_result[1];
        $pattern = "#<center>(.*)</center>#iUs";
        $org_content = preg_replace($pattern,"",$org_result[2]);

        //翻译标题和内容 并去掉内容最后一些提供者信息
        $trn_result = self::fn_preg_match($pre_arr['trn_preg'],$contentCon);
        $trn_title = $trn_result[1];
        $offset = strrpos($trn_result[2],"<br />",-1);
        $trn_content = substr_replace($trn_result[2],"",$offset);
        $trn_content = $trn_content;

        //更新数据表
        if($absoluteUrl && $mp3_url && $orgTitle && $org_content && $trn_title && $trn_content){
            self::fn_insertDB($absoluteUrl,$mp3_url,$orgTitle,$org_content,$trn_title,$trn_content);
        }
    }

    function fn_get_contents($url){
        return file_get_contents($url);
    }

    function fn_LastSqlRecord(){
        $sql = "select * from ".$this->dataTable." order by id DESC limit 1";
        $query = mysql_query($sql);
        $row = mysql_fetch_array($query);
        return $row;
    }

    function fn_insertDB($WebUrl,$MusicUrl,$OrgTitle,$OrgContent,$TrnTitle,$TrnContent){
		$nowTime = time();
        echo $sql = "INSERT INTO `".$this->dataTable."` (`id` ,`WebUrl`,`MusicUrl` ,`OrgTitle`,`OrgContent`,`TrnTitle`,`TrnContent`,`PublicDate`)
        VALUES (NULL ,'$WebUrl','$MusicUrl','$OrgTitle ','$$OrgContent','$TrnTitle','$TrnContent',$nowTime);";
        mysql_query($sql);
		//file_put_contents("./mylog.log",$TrnTitle."//---updateTime---//".date('Y-m-d')."\r\n",FILE_APPEND);
    }

    function fn_preg_match($preg,$con){
        preg_match($preg,$con,$arr);
        return $arr;
    }

}

function updateListen(){

	$now =  time();
	$collect = new collect($targetUrl);
	$LastSqlRecord = $collect->fn_LastSqlRecord();

	if(($now - $LastSqlRecord['PublicDate'] - 24*3600) > 0){
		//$collect->fn_collectList($targetUrl);
	}
	$collect->fn_collectList($targetUrl);
	
	$msg = "更新成功";
	return $msg;
}

updateListen();







