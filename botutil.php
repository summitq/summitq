<?php

include_once( 'simple_html_dom.php' );

function makeText($fromUsername, $toUsername, $time, $msgType, $contentStr)
{
	$textTpl = "<xml>
				<ToUserName><![CDATA[%s]]></ToUserName>
				<FromUserName><![CDATA[%s]]></FromUserName>
				<CreateTime>%s</CreateTime>
				<MsgType><![CDATA[%s]]></MsgType>
				<Content><![CDATA[%s]]></Content>
				<FuncFlag>0<FuncFlag>
				</xml>"; 
	if (empty($contentStr)) $contentStr = "抱歉，当前网络繁忙，请稍后再试.";
	return sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
}

function makeMusic($fromUsername, $toUsername, $time, $msgType, $title, $dec, $musicUrl)
{
	$musicTpl = "<xml>
				 <ToUserName><![CDATA[%s]]></ToUserName>
				 <FromUserName><![CDATA[%s]]></FromUserName>
				 <CreateTime>%s</CreateTime>
				 <MsgType><![CDATA[%s]]></MsgType>
				 <Music>
				 <Title><![CDATA[%s]]></Title>
				 <Description><![CDATA[%s]]></Description>
				 <MusicUrl><![CDATA[%s]]></MusicUrl>
				 <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
				 </Music>
				 <FuncFlag>0</FuncFlag>
				 </xml>"; 
	//if (empty($musicUrl)) $musicUrl = "抱歉，当前网络繁忙，请稍后再试.";
	return sprintf($musicTpl, $fromUsername, $toUsername, $time, $msgType, $title, $dec, $musicUrl, $musicUrl);
}

function makeArticleItem($title, $discription, $picUrl, $url){
	
	$aTpl = "<item>
			 <Title><![CDATA[%s]]></Title>
			 <Discription><![CDATA[%s]]></Discription>
			 <PicUrl><![CDATA[%s]]></PicUrl>
			 <Url><![CDATA[%s]]></Url>
			 </item>
			 ";

	return sprintf($aTpl, $title, $discription, $picUrl, $url);
	
}

function makeArticles($fromUsername, $toUsername, $time, $msgType, $contentStr, $Articles, $startflag=0){

	$tpl = "<xml>
			 <ToUserName><![CDATA[%s]]></ToUserName>
			 <FromUserName><![CDATA[%s]]></FromUserName>
			 <CreateTime>%s</CreateTime>
			 <MsgType><![CDATA[%s]]></MsgType>
			 <Content><![CDATA[%s]]></Content>
			 <ArticleCount>%d</ArticleCount>
			 <Articles>
			 %s
			 </Articles>
			 <FuncFlag>0</FuncFlag>
			</xml>   ";
	;

	return sprintf($tpl, $fromUsername, $toUsername, $time, $msgType, $contentStr, count($Articles), implode('\n', $Articles), $startflag);
}

function curPageURL() 
{
    $pageURL = 'http';

    if ($_SERVER["HTTPS"] == "on") 
    {
        $pageURL .= "s";
    }
    $pageURL .= "://";

    if ($_SERVER["SERVER_PORT"] != "80") 
    {
        $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    } 
    else 
    {
        $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}


function weekday($time)
{
    if(is_numeric($time))
    {
        return date('w', $time);
    }

    return false;

}

function getDayTime($date=""){
	if (empty($date))
		$data =  date("Y-m-d",time());
	$year=((int)substr($data,0,4));//取得年份
	$month=((int)substr($data,5,2));//取得月份
	$day=((int)substr($data,8,2));//取得几号
	return  mktime(0,0,0,$month,$day,$year);
}

function sortByCount($a, $b) {

	if (count($a) == count($b)) {
		return 0;
	} else {
		return (count($a) > count($b)) ? -1 : 1;
	}
}

function html2text($str){  
	$str = preg_replace("/<style .*?<\/style>/is", "", $str);  
	$str = preg_replace("/<script .*?<\/script>/is", "", $str);  
	//$str = preg_replace("/<br \s*\/?\/>/i", "\n", $str); 
	$str = preg_replace("/<br \s*\/?\/>/i", " ", $str); 	
	//$str = preg_replace("/<\/?p>/i", "\n\n", $str);
	$str = preg_replace("/<\/?p>/i", " ", $str); 	
	$str = preg_replace("/<\/?td>/i", "\n", $str);  
	//$str = preg_replace("/<\/?div>/i", "\n", $str); 
	$str = preg_replace("/<\/?div>/i", " ", $str);  	
	$str = preg_replace("/<\/?blockquote>/i", "\n", $str);  
	$str = preg_replace("/<\/?li>/i", "\n", $str);  
	$str = preg_replace("/\&nbsp\;/i", " ", $str);  
	$str = preg_replace("/\&nbsp/i", " ", $str);  
	$str = preg_replace("/\&amp\;/i", "&", $str);  
	$str = preg_replace("/\&amp/i", "&", $str);    
	$str = preg_replace("/\&lt\;/i", "<", $str);  
	$str = preg_replace("/\&lt/i", "<", $str);  
	$str = preg_replace("/\&ldquo\;/i", '"', $str);  
	$str = preg_replace("/\&ldquo/i", '"', $str);  
	$str = preg_replace("/\&lsquo\;/i", "'", $str);  
	$str = preg_replace("/\&lsquo/i", "'", $str);  
	$str = preg_replace("/\&rsquo\;/i", "'", $str);  
	$str = preg_replace("/\&rsquo/i", "'", $str);  
	$str = preg_replace("/\&gt\;/i", ">", $str);   
	$str = preg_replace("/\&gt/i", ">", $str);   
	$str = preg_replace("/\&rdquo\;/i", '"', $str);   
	$str = preg_replace("/\&rdquo/i", '"', $str);   
	$str = strip_tags($str);  
	$str = html_entity_decode($str, ENT_QUOTES,  "UTF-8");  
	$str = preg_replace("/\&\#.*?\;/i", "", $str);          

	//自定义
	$str = trim($str);
	return $str;
}


function getClass(){

		$classroom1 = array(
		"A2-1" => bindec('111111100000'),
		"A2-2" => bindec('111100000000'),
		"A2-3" => bindec('000110011000'),
		"A2-4" => bindec('000110000000'),
		"A2-5" => bindec('111101100110'),
		"A2-6" => bindec('001101111000'),
		"A3-1" => bindec('111111110110'),
		"A3-2" => bindec('111111110000'),
		"A3-3" => bindec('111001111000'),
		"A3-4" => bindec('111001110000'),
		"A3-5" => bindec('000111111000'),
		"A3-6" => bindec('111100000000'),
		"A4-1" => bindec('000000000000'),
		"A4-2" => bindec('111101110000'),
		"A4-3" => bindec('111110000000'),
		"A4-4" => bindec('111101100000'),
		"A4-5" => bindec('111101111000'),
		"A4-6" => bindec('110001111000'),
		"A5-1" => bindec('111100000000'),
		"A5-2" => bindec('111101111000'),
		"A5-3" => bindec('111111100000'),
		"A5-4" => bindec('111100000000'),
		"A5-5" => bindec('111110000111'),
		"A5-6" => bindec('001110000000'),
		"B2-1" => bindec('111101100000'),
		"B2-2" => bindec('111100000000'),
		"B3-1" => bindec('111111111000'),
		"B3-2" => bindec('111111111000'),
		"B4-1" => bindec('111101100000'),
		"B4-2" => bindec('111100011000'),
		"B5-1" => bindec('111101111000'),
		"B5-2" => bindec('110111100000'),
		"C-2-1" => bindec('111111111000'),
		"C-1-1" => bindec('001100000110'),
		"C-1-2" => bindec('001111100111'),
		"C1-1" => bindec('110000000110'),
		"C1-2" => bindec('111100000000'),
		"C2-1" => bindec('111100000111'),
		"C2-2" => bindec('111110000000'),
		"C3-1" => bindec('111111100000'),
		"C3-2" => bindec('111111110000'),
		"C4-1" => bindec('111101111000'),
		"C4-2" => bindec('111101111111'),
		"C5-1" => bindec('110110000000'),
		"D-2-1" => bindec('111101111000'),
		"D-1-1" => bindec('001101100000'),
		"D1-1" => bindec('001101110000'),
		"D1-2" => bindec('111101111000'),
		"D2-1" => bindec('111101110000'),
		"D2-2" => bindec('110111100000'),
		"D4-1" => bindec('111100000000'),
		"D4-2" => bindec('110111111000'),
		"D5-1" => bindec('111100000000')
	);

	$classroom2 = array(
		"A2-1" => bindec('111101110111'),
		"A2-2" => bindec('111100000111'),
		"A2-3" => bindec('000000000111'),
		"A2-4" => bindec('001110011111'),
		"A2-5" => bindec('110001100111'),
		"A2-6" => bindec('111100000111'),
		"A3-1" => bindec('001101111111'),
		"A3-2" => bindec('001110000110'),
		"A3-3" => bindec('111001110111'),
		"A3-4" => bindec('111101110000'),
		"A3-5" => bindec('111111100111'),
		"A3-6" => bindec('001111100111'),
		"A4-1" => bindec('111101100110'),
		"A4-2" => bindec('110001110000'),
		"A4-3" => bindec('001100011110'),
		"A4-4" => bindec('111101111000'),
		"A4-5" => bindec('111101100000'),
		"A4-6" => bindec('111101100000'),
		"A5-1" => bindec('111100000000'),
		"A5-2" => bindec('111100000000'),
		"A5-3" => bindec('111101100000'),
		"A5-4" => bindec('000111100000'),
		"A5-5" => bindec('111100000111'),
		"A5-6" => bindec('111100000000'),
		"B2-1" => bindec('000000000110'),
		"B2-2" => bindec('110000000111'),
		"B3-1" => bindec('111111110000'),
		"B3-2" => bindec('111100000111'),
		"B4-1" => bindec('111110000000'),
		"B4-2" => bindec('111101100110'),
		"B5-1" => bindec('110111111111'),
		"B5-2" => bindec('111100000111'),
		"C-2-1" => bindec('001100000000'),
		"C-1-1" => bindec('111100000000'),
		"C-1-2" => bindec('111100000110'),
		"C1-1" => bindec('000000000000'),
		"C1-2" => bindec('001110000000'),
		"C2-1" => bindec('000001100111'),
		"C2-2" => bindec('110000011000'),
		"C3-1" => bindec('111111110000'),
		"C3-2" => bindec('111101110000'),
		"C4-1" => bindec('111110000000'),
		"C4-2" => bindec('111000000000'),
		"C5-1" => bindec('001100000000'),
		"D-2-1" => bindec('001111110000'),
		"D-1-1" => bindec('111101100000'),
		"D1-1" => bindec('111101100000'),
		"D1-2" => bindec('000001100000'),
		"D2-1" => bindec('110001110000'),
		"D2-2" => bindec('000000000000'),
		"D4-1" => bindec('110000011000'),
		"D4-2" => bindec('111101110000'),
		"D5-1" => bindec('110001100000')
	);

	$classroom3 = array(
		"A2-1" => bindec('111100000111'),
		"A2-2" => bindec('110000000111'),
		"A2-3" => bindec('000000011111'),
		"A2-4" => bindec('000001100111'),
		"A2-5" => bindec('001110000111'),
		"A2-6" => bindec('111110000111'),
		"A3-1" => bindec('111000000111'),
		"A3-2" => bindec('110110011111'),
		"A3-3" => bindec('111111110111'),
		"A3-4" => bindec('110111110000'),
		"A3-5" => bindec('111110000111'),
		"A3-6" => bindec('110001100111'),
		"A4-1" => bindec('110000000000'),
		"A4-2" => bindec('110000000000'),
		"A4-3" => bindec('000000000111'),
		"A4-4" => bindec('111101100110'),
		"A4-5" => bindec('111100000000'),
		"A4-6" => bindec('111101111000'),
		"A5-1" => bindec('111100000000'),
		"A5-2" => bindec('111100000000'),
		"A5-3" => bindec('111110000000'),
		"A5-4" => bindec('111100000000'),
		"A5-5" => bindec('111000000000'),
		"A5-6" => bindec('111000000000'),
		"B2-1" => bindec('110000011000'),
		"B2-2" => bindec('000001111111'),
		"B3-1" => bindec('000001100111'),
		"B3-2" => bindec('001100000000'),
		"B4-1" => bindec('111101111000'),
		"B4-2" => bindec('111101111000'),
		"B5-1" => bindec('111101100111'),
		"B5-2" => bindec('000001100111'),
		"C-2-1" => bindec('111110000111'),
		"C-1-1" => bindec('111100000111'),
		"C-1-2" => bindec('111000000111'),
		"C1-1" => bindec('000000000000'),
		"C1-2" => bindec('111100000000'),
		"C2-1" => bindec('001100011000'),
		"C2-2" => bindec('111111110000'),
		"C3-1" => bindec('111101100000'),
		"C3-2" => bindec('111111110000'),
		"C4-1" => bindec('111101111000'),
		"C4-2" => bindec('111101111000'),
		"C5-1" => bindec('110001110000'),
		"D-2-1" => bindec('111111110000'),
		"D-1-1" => bindec('110000000000'),
		"D1-1" => bindec('001100000000'),
		"D1-2" => bindec('111101100000'),
		"D2-1" => bindec('110001100000'),
		"D2-2" => bindec('111101110000'),
		"D4-1" => bindec('111000000000'),
		"D4-2" => bindec('000000011000'),
		"D5-1" => bindec('110001100000')
	);

	$classroom4 = array(
		"A2-1" => bindec('111110000111'),
		"A2-2" => bindec('000110011111'),
		"A2-3" => bindec('000001111111'),
		"A2-4" => bindec('110001110111'),
		"A2-5" => bindec('000110000111'),
		"A2-6" => bindec('110001100111'),
		"A3-1" => bindec('111111110000'),
		"A3-2" => bindec('111001110111'),
		"A3-3" => bindec('110000011111'),
		"A3-4" => bindec('111111110111'),
		"A3-5" => bindec('111101110111'),
		"A3-6" => bindec('001110011111'),
		"A4-1" => bindec('001100000110'),
		"A4-2" => bindec('110000000000'),
		"A4-3" => bindec('110000011000'),
		"A4-4" => bindec('001100000000'),
		"A4-5" => bindec('111101111000'),
		"A4-6" => bindec('110001111000'),
		"A5-1" => bindec('111100000000'),
		"A5-2" => bindec('111100000000'),
		"A5-3" => bindec('111100000000'),
		"A5-4" => bindec('111001100000'),
		"A5-5" => bindec('111110000000'),
		"A5-6" => bindec('000110000000'),
		"B2-1" => bindec('110001100110'),
		"B2-2" => bindec('000001110111'),
		"B3-1" => bindec('111101110111'),
		"B3-2" => bindec('111101100111'),
		"B4-1" => bindec('111101111000'),
		"B4-2" => bindec('111101100000'),
		"B5-1" => bindec('110001110111'),
		"B5-2" => bindec('110001110111'),
		"C-2-1" => bindec('111100000000'),
		"C-1-1" => bindec('110001100111'),
		"C-1-2" => bindec('111000000110'),
		"C1-1" => bindec('000000000000'),
		"C1-2" => bindec('111000000000'),
		"C2-1" => bindec('001110000111'),
		"C2-2" => bindec('110001110111'),
		"C3-1" => bindec('111000011000'),
		"C3-2" => bindec('111001110000'),
		"C4-1" => bindec('111110000000'),
		"C4-2" => bindec('111000000000'),
		"C5-1" => bindec('110110000000'),
		"D-2-1" => bindec('111110000000'),
		"D-1-1" => bindec('110001111000'),
		"D1-1" => bindec('111110000000'),
		"D1-2" => bindec('000000000000'),
		"D2-1" => bindec('111001110000'),
		"D2-2" => bindec('001101100000'),
		"D4-1" => bindec('111101111000'),
		"D4-2" => bindec('110000000000'),
		"D5-1" => bindec('111101110000')
	);

	$classroom5 = array(
		"A2-1" => bindec('110001110000'),
		"A2-2" => bindec('001110000000'),
		"A2-3" => bindec('111100000000'),
		"A2-4" => bindec('001111100000'),
		"A2-5" => bindec('110110011000'),
		"A2-6" => bindec('001100000000'),
		"A3-1" => bindec('110000000000'),
		"A3-2" => bindec('110001111000'),
		"A3-3" => bindec('111111110000'),
		"A3-4" => bindec('110111110000'),
		"A3-5" => bindec('111100000000'),
		"A3-6" => bindec('001111111000'),
		"A4-1" => bindec('000001100000'),
		"A4-2" => bindec('111101100000'),
		"A4-3" => bindec('110000000000'),
		"A4-4" => bindec('111111111000'),
		"A4-5" => bindec('111101111000'),
		"A4-6" => bindec('111101111000'),
		"A5-1" => bindec('000000000000'),
		"A5-2" => bindec('000001110000'),
		"A5-3" => bindec('111001100000'),
		"A5-4" => bindec('001101100000'),
		"A5-5" => bindec('111000000000'),
		"A5-6" => bindec('111110000000'),
		"B2-1" => bindec('111111100000'),
		"B2-2" => bindec('110001100000'),
		"B3-1" => bindec('110001110000'),
		"B3-2" => bindec('111111110000'),
		"B4-1" => bindec('111101111000'),
		"B4-2" => bindec('111101111000'),
		"B5-1" => bindec('111111110000'),
		"B5-2" => bindec('111100000000'),
		"C-2-1" => bindec('000000000000'),
		"C-1-1" => bindec('110000000000'),
		"C-1-2" => bindec('110001110000'),
		"C1-1" => bindec('111000110000'),
		"C1-2" => bindec('001110110000'),
		"C2-1" => bindec('110000000000'),
		"C2-2" => bindec('110000000000'),
		"C3-1" => bindec('111111110000'),
		"C3-2" => bindec('111101110000'),
		"C4-1" => bindec('111100000000'),
		"C4-2" => bindec('110001100000'),
		"C5-1" => bindec('111101110000'),
		"D-2-1" => bindec('110000000000'),
		"D-1-1" => bindec('110001110000'),
		"D1-1" => bindec('111110000000'),
		"D1-2" => bindec('111101111000'),
		"D2-1" => bindec('110001100000'),
		"D2-2" => bindec('111111110000'),
		"D4-1" => bindec('111100000000'),
		"D4-2" => bindec('111100000000'),
		"D5-1" => bindec('110001100000')
	);

	$classtime = array(
		8*3600,
		8*3600+50*60,
		9*3600+45*60,
		10*3600+35*60,
		11*3500+25*60,
		14*3600+30*60,
		15*3600+20*60,
		16*3600+10*60,
		15*3600,
		19*3600+30*60,
		20*3600+20*60,
		21*3600+10*60
	);

	$range = array(
		5,
		5,
		5,
		5,
		5,
		5,
		9,
		9,
		9,
		9,
		12,
		12,
		12,
		12
	);

	$classroom = array("1"=>$classroom1, "2"=>$classroom2, "3"=>$classroom3, "4"=>$classroom4, "5"=>$classroom5);

	date_default_timezone_set('PRC'); 

	$now = localtime($_SERVER['REQUEST_TIME'] , true);

	$nowsecode = $now['tm_hour']*3600+$now['tm_min']*60+$now['tm_sec'];

	$i = 0;
	foreach ($classtime as $key=> $t){
		$i = $key;
		if ( $nowsecode < $t ){
			break;
		}
	}

	$score = array();
	$wd = weekday($_SERVER['REQUEST_TIME']);
	
	if ($wd==0 || $wd==6)
		return "到处都是自习室!\n今天是自习的好日子。";

	foreach ( $classroom[$wd] as $key => $item){
		 $mask = bindec('100000000000');
		 $index = 1;
		 while ($mask>0){
			if (!($mask & $item) && $index > $i && $index <= $range[$i]){
				$score[$key][] = $index;
			}
			$mask = $mask >> 1;
			$index = $index +1;
		 }
	}


	uasort($score, 'sortByCount');
	$msg = "温馨提示（以下自习室已根据当前时间智能排序)：\n";
	foreach ($score as $key => $t){
		$msg .= $key.":第".implode(",", $t)."节\n";
	}

	return $msg;
}

/*{"weatherinfo":{"city":"东莞","city_en":"dongguan","date_y":"2012年11月16日","date":"","week":"星期五","fchh":"18","cityid":"101281601","temp1":"17℃~23℃","temp2":"16℃~24℃","temp3":"17℃~25℃","temp4":"19℃~25℃","temp5":"20℃~26℃","temp6":"21℃~27℃","tempF1":"62.6℉~73.4℉","tempF2":"60.8℉~75.2℉","tempF3":"62.6℉~77℉","tempF4":"66.2℉~77℉","tempF5":"68℉~78.8℉","tempF6":"69.8℉~80.6℉","weather1":"小雨转中雨","weather2":"阴转多云","weather3":"多云","weather4":"多云","weather5":"多云","weather6":"多云","img1":"7","img2":"8","img3":"2","img4":"1","img5":"1","img6":"99","img7":"1","img8":"99","img9":"1","img10":"99","img11":"1","img12":"99","img_single":"8","img_title1":"小雨","img_title2":"中雨","img_title3":"阴","img_title4":"多云","img_title5":"多云","img_title6":"多云","img_title7":"多云","img_title8":"多云","img_title9":"多云","img_title10":"多云","img_title11":"多云","img_title12":"多云","img_title_single":"中雨","wind1":"北风3-4级","wind2":"微风","wind3":"微风","wind4":"微风","wind5":"微风","wind6":"微风","fx1":"北风","fx2":"北风","fl1":"3-4级","fl2":"小于3级","fl3":"小于3级","fl4":"小于3级","fl5":"小于3级","fl6":"小于3级","index":"暖","index_d":"较凉爽，建议着长袖衬衫加单裤等春秋过渡装。年老体弱者宜着针织长袖衬衫、马甲和长裤。","index48":"热","index48_d":"天气较热，建议着短裙、短裤、短套装、T恤等夏季服装。年老体弱者宜着长袖衬衫和单裤。","index_uv":"最弱","index48_uv":"弱","index_xc":"不宜","index_tr":"一般","index_co":"舒适","st1":"22","st2":"14","st3":"24","st4":"15","st5":"25","st6":"17","index_cl":"不宜","index_ls":"不宜","index_ag":"易发"}}
*/

function getWeather(){

	$jsonurl = "http://m.weather.com.cn/data/101281601.html";
	$json = file_get_contents($jsonurl,0,null,null);
	$json_output = json_decode($json);
	$msg = "";
	$msg .= "根据中央气象局".$json_output->weatherinfo->date_y."(".$json_output->weatherinfo->week.")发布的气象预报，".$json_output->weatherinfo->city." 的天气状况为:\n";
	$msg .="今天:".$json_output->weatherinfo->weather1."\n".$json_output->weatherinfo->temp1." ".$json_output->weatherinfo->wind1."\n";
	$msg .="明天:".$json_output->weatherinfo->weather2."\n".$json_output->weatherinfo->temp2." ".$json_output->weatherinfo->wind2."\n";
	$msg .="后天:".$json_output->weatherinfo->weather3."\n".$json_output->weatherinfo->temp3." ".$json_output->weatherinfo->wind3."\n";
	
	return $msg;
	
}

function getJoke(){

	
	$xmlurl = "http://www.djdkx.com/open/baidu";
	$postStr = file_get_contents($xmlurl,0,null,null);
	$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
	$html = new simple_html_dom();
	$html->load_file($postObj->item->display->url);
	$content = "";
	$html->find('.watermark', 0)->innertext = ' ';
	foreach($html->find('div[class=pictxtbox]') as $post){
		$content .= $post->plaintext;
	}
	return trim(html2text($content));

}

function getTranslate($data){

	$jsonurl = "http://fanyi.youdao.com/openapi.do?keyfrom=summitbot&key=1521267322&type=data&doctype=json&version=1.1&q=".$data;
	$json = file_get_contents($jsonurl,0,null,null);
	$json_output = json_decode($json);
	
	if ($json_output->errorCode==0){
		$msg = $json_output->query."\n";
		$msg .= implode(';',$json_output->translation)."\n";
		$msg .= "有道基本词典\n";
		$msg .= "发音:".$json_output->basic->phonetic."\n";
		$msg .= "释义:".implode(';',$json_output->basic->explains)."\n";
		$msg .= "网络解释:\n";
		foreach ($json_output->web as $object){
			$msg .= $object->key.":".implode(';',$object->value)."\n";
		}
	}else{
		$msg = $json_output->query."\n";
	}

	return $msg;

}

// $type = book | movie | music
function getDouban($data, $type='book'){
	
	$xmlurl = "http://api.douban.com/".$type."/subjects?q=".$data;
	$postStr = file_get_contents($xmlurl,0,null,null);
	$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);

	$articles = array();
	foreach ($postObj->entry as $object){
		$title = $object->title;
		$discription =  "作者:".$object->author->name;
		$picUrl = "";
		$url = "";
		foreach ($object->link as $l){
			if ($l["rel"] == "image")$picUrl = $l["href"];
			if ($l["rel"] == "mobile")$url = $l["href"];
		}
		$articles[] = array("title"=>$title, "discription"=>$discription, "picUrl"=>$picUrl, "url"=>$url);
	}
	return array("contentStr"=>$postObj->title, "articles"=>$articles);;
}

function getIP($data){
	$xmlurl = "http://www.youdao.com/smartresult-xml/search.s?type=ip&q=".$data;
	$postStr = file_get_contents($xmlurl,0,null,null);
	$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
	$msg = "IP:".$postObj->product->ip."\n";
	$msg .= "归属地:".$postObj->product->location."\n";

	return $msg;
}

function getPhone($data){

	$url = 'http://webservice.webxml.com.cn/WebServices/MobileCodeWS.asmx/getMobileCodeInfo'; 
	$number = $data; 
	$ch = curl_init(); 
	curl_setopt($ch, CURLOPT_URL, $url); 
	curl_setopt($ch, CURLOPT_POST, true); 
	curl_setopt($ch, CURLOPT_POSTFIELDS, "mobileCode={$number}&userId="); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
	$data = curl_exec($ch); 
	curl_close($ch); 
	$data = simplexml_load_string($data); 
	if (strpos($data, 'http://')) { 
		return  '手机号码格式错误!'; 
	} else { 
		return $data; 
	} 
}


function getWikiquote(){
	$url = "http://zh.wikiquote.org/wiki/Wikiquote:%E6%AF%8F%E6%97%A5%E5%90%8D%E8%A8%80";
	$html = new simple_html_dom();
	
	$opts = array('http' =>
	  array(
		'user_agent' => 'MyBot/1.0 (http://www.mysite.com/)'
	  )
	);
	$context = stream_context_create($opts);
	$html->load(file_get_contents($url, FALSE, $context));


	$quote =  $html->find("div[id=mw-content-text] center div p", 0)->plaintext;
	$href = "http://zh.wikiquote.org".$html->find("div[id=mw-content-text] center div p a", 0)->href;
	
	return array("quote"=>$quote, "url"=>$href);
}

//每日一天文图
function getAPOD(){
	//
	$url = "http://www.phys.ncku.edu.tw/~astrolab/mirrors/apod/apod.html";
	$html = new simple_html_dom();
	$html->load_file($url);

	return "http://www.phys.ncku.edu.tw/~astrolab/mirrors/apod/".$html->find("center p a img", 0)->src;
}

//随机获取美图网上的一张当前图片
function getMeitu($date=""){
	$url = "http://www.meitu.com/home/before_timeline/?date=".getDayTime($date);
	$html = new simple_html_dom();
	$html->load_file($url);
	$list = $html->find("ul[class=photolist] li");
	srand((double)microtime()*1000000);
	$index= rand(0, count($list)-1);
	echo $index;
	$x =  $list[$index]->find("img", 0)->getAllAttributes();
	preg_match("/[^!]+/i", $x["data-original"],  $matches);
	$href = $matches[0]."!thumb235";
	$bighref = $matches[0]."!thumbnail1000";
	$title = $list[$index]->find("span[class=name] a", 0)->plaintext;
	$url = $list[$index]->find("a", 0)->href;
	return array("title"=>$title, "url"=>$url, "picUrl"=>$href, "bigPicUrl"=>$bighref);
}


function getDoubanfm($username, $channel="华语"){
	global $db;

	$channels = array("华语"=>"1", "欧美"=>"2", "粤语"=>"6", "法语"=>"22", "日语"=>"17",
		"韩语"=>"18", "民谣"=>"8", "摇滚"=>"7", "爵士"=>"13", "古典"=>"27", "轻音乐"=>"9",
		"电子"=>"14", "R&B"=>"16", "说唱"=>"15", "电影原声"=>"10", "七零"=>"3", "八零"=>"4",
		"九零"=>"5", "豆瓣音乐人"=>"26", "女声"=>"20", "动漫"=>"28", "咖啡馆"=>"32", "BMW"=>"30",
		"NB 敢动"=>"31", "Lee"=>"34"
		);
	
	if(!isset($channels[$channel])){
		if(preg_match("/[^\d-., ]/",$channel)){
			$channel = 1;
		}
	}else{
		$channel = $channels[$channel]; 
	}

	$list = array();
	$i = 0;
	while($i<2){
		$jsonurl = "http://douban.fm/j/mine/playlist?type=n&channel=".$channel;
		$json = file_get_contents($jsonurl,0,null,null);
		$json_output = json_decode($json);



		foreach($json_output->song as $key=>$value){
			if (preg_match("/.*(mr4).*/i",$value->url,  $matches)){
				$list[] = array("picture"=>$value->picture, "title"=>$value->artist."-".$value->title."(".$value->albumtitle.", ".$value->public_time.")", "url"=>$value->url
					,"artist"=>$value->artist, "rating_avg"=>intval($value->rating_avg), "length"=>intval($value->length/60).":".str_pad(intval($value->length%60),2,STR_PAD_LEFT));
			}
		}
		$i = $i+1;
	}
	
	$db->execute("INSERT INTO FM (username,playlist) VALUES('".$username."','".rawurlencode(json_encode($list))."')");
	$lastId = $db->lastInsertedId();
	
	
	foreach ($list as $key=>$value){
		$pmurl = "http://summitbot-summit.rhcloud.com/MRQFM/index.php?id=".$lastId;
		//$pmurl = "http://rd.wechatapp.com/redirect/comfirm?uid=409395320&block_type=1&url=".rawurlencode($pmurl);
		$list[$key]["pmurl"] = $pmurl;
	}

	return $list;
}

// get TV
// http://webservice.webxml.com.cn/webservices/ChinaTVprogramWebService.asmx

// 新浪分词
function saeSegmentation($sentence){

	$url = 'http://summitseg.sinaapp.com/API.php?m=segment'; 
	$ch = curl_init(); 
	curl_setopt($ch, CURLOPT_URL, $url); 
	curl_setopt($ch, CURLOPT_POST, true); 
	curl_setopt($ch, CURLOPT_POSTFIELDS, "msg={$sentence}"); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
	$data = curl_exec($ch); 
	curl_close($ch); 
	$data = json_decode($data, true);

	return $data;
}

// 存储至Special and Matrix Search Engines
function addMSE($input, $output){
	global $db;
	

	
	//是否存在回应
	$line2 = $db->queryUniqueObject("SELECT * FROM bot_respond WHERE respond='{$output}'");
	if (empty($line2)){
		$db->execute("INSERT INTO bot_respond (respond) VALUES('{$output}')");
		$line2 = $db->queryUniqueObject("SELECT * FROM bot_respond WHERE respond='{$output}'");
	}

	//是否存在输入
	$line4 = $db->queryUniqueObject("SELECT * FROM bot_sentence WHERE sentence='{$output}'");
	if (empty($line4)){
		$db->execute("INSERT INTO bot_sentence (sentence) VALUES('{$output}')");
		$line4 = $db->queryUniqueObject("SELECT * FROM bot_sentence WHERE sentence='{$output}'");
	}

	//插入关联表
	$line5 = $db->queryUniqueObject("SELECT * FROM bot_match WHERE sentenceid='{$line4->id}' AND respondid='{$line2->id}'");
	if (empty($line3)){
		$db->execute("INSERT INTO bot_match (sentenceid,respondid) VALUES('{$line4->id}','{$line2->id}')");
	}

	// 分词
	$seg = saeSegmentation($input);
	
	foreach ($seg as $value){
		
		//是否存在关键词
		$line = $db->queryUniqueObject("SELECT * FROM bot_word WHERE word='{$value[word]}'");
		if (empty($line)){
			$db->execute("INSERT INTO bot_word (word,word_tag) VALUES('{$value[word]}','{$value[word_tag]}')");
			$line = $db->queryUniqueObject("SELECT * FROM bot_word WHERE word='{$value[word]}'");
		}
		
		//插入关联表
		$line3 = $db->queryUniqueObject("SELECT * FROM bot_map WHERE wordid='{$line->id}' AND respondid='{$line2->id}'");
		if (empty($line3)){
			$db->execute("INSERT INTO bot_map (wordid,respondid) VALUES('{$line->id}','{$line2->id}')");
		}
	}
}

function searchMSE($input){
	global $db;
	
	// Specia Search Engines
	$sentence = $db->queryUniqueObject("SELECT * FROM bot_sentence WHERE sentence='{$output}'");
	if ($sentence){
		$respond = $db->queryUniqueObject("SELECT respond FROM bot_match LEFT JOIN bot_respond on bot_match.respondid=bot_respond.id WHERE sentenceid='{$sentence->id}'");
		return $respond->respond;
	}

	// Matrix Search Engines
	$matrix = array();
	// 分词
	$seg = saeSegmentation($input);

	foreach ($seg as $value){
		
		$word = $db->queryUniqueObject("SELECT * FROM bot_word WHERE word='{$value[word]}'");
		
		if ($word){
			$respond = $db->query("SELECT * FROM bot_map LEFT JOIN bot_respond on bot_map.respondid=bot_respond.id WHERE wordid='{$word->id}'");
			while ($line = $db->fetchNextObject($respond)) {
				$matrix[$line->respondid] = ($matrix[$line->respond])?(1/count($respond)):($matrix[$line->respond]+1/count($respond));
			}
		}
		
	}
	asort($matrix);
	$ids = array_keys($matrix);

	if (isset($_COOKIE["hasrespond"])){
		$hasrespond = unserialize($_COOKIE["hasrespond"]);

		$count_child = count($hasrespond);
		for($j = 0; $j < $count_child; $j++)
		{
			for($i = 0; $i < count($ids); $i++)
			{
				if($ids[$i] == $hasrespond [$j])
				{
					array_splice($ids, $i, 1);
					break;
				}
			}
		}
		$hasrespond[] = $ids[0];
		setcookie("hasrespond", serialize($hasrespond), time()+600);
		
	}else{
		$hasrespond = array();
		$hasrespond[] = $ids[0];
		setcookie("hasrespond", serialize($hasrespond), time()+600);
	}
	
	// 查找最高得分回应
	$db->execute("set character_set_results=UTF8;");
	$respond = $db->queryUniqueObject("SELECT respond FROM bot_respond WHERE id='{$ids[0]}'");
	$db->execute("set character_set_results=GBK;");
	return $respond->respond;
}

function addUser($username, $key){
	global $db;

	$user = $db->queryUniqueObject("SELECT * FROM bot_space WHERE username='{$username}'");
	if (empty($user)){
		$db->execute("INSERT INTO bot_space (username,userkey) VALUES('{$username}','{$key}')");
	}
}

function addUser2($key){
	global $db;

	$user = $db->queryUniqueObject("SELECT * FROM bot_space WHERE userkey='{$key}'");
	if (empty($user)){
		$db->execute("INSERT INTO bot_space (username,userkey) VALUES('','{$key}')");
	}
}

function getCookie($url)
{
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	preg_match('/^Set-Cookie: (.*?);/m', curl_exec($ch), $m);
	return parse_url($m[1]);
}

function changesimsCookie($key){
	global $db;

	
	$cookie = getCookie('http://www.simsimi.com/');
	$db->execute("UPDATE bot_simsim SET cookie='{$cookie[path]}' WHERE userkey='{$key}'");
	$cookie = $cookie['path'];
	
	return $cookie;

}

function getsimsCookie($key){
	global $db;

	$user = $db->queryUniqueObject("SELECT * FROM bot_simsim WHERE userkey='{$key}'");
	if (empty($user)){
		$cookie = getCookie('http://www.simsimi.com/');
		$db->execute("INSERT INTO bot_simsim (userkey,cookie) VALUES('{$key}','{$cookie[path]}')");
		$cookie = $cookie['path'];
	}else{
		$cookie = $user->cookie;
	}
	return $cookie;
}

function getsimsReply2($cookie, $keyword){
	$urll = 'http://www.simsimi.com/func/req?msg=' .$keyword. '&lc=ch';  
	$ch = curl_init();   
    curl_setopt($ch, CURLOPT_URL, $urll);   
    curl_setopt($ch, CURLOPT_HEADER, 0);   
    curl_setopt($ch, CURLOPT_REFERER, "http://www.simsimi.com/talk.htm?lc=ch");   
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);   
    curl_setopt($ch, CURLOPT_COOKIE, $cookie);   
    $Message = json_decode(curl_exec($ch),true);
	curl_close($ch);
	
	$contentStr = "你说的什么？";
	if($Message['result']=='100' && $Message['response'] <> 'hi'){
		$contentStr = $Message['response'];
	}
	$arr = array('小贱鸡', '小黄鸡', '小鸡鸡', '贱鸡', '小鸡仔', '鸡','sim');
	$arr2 = array();
	foreach($arr as $key=>$value){
		$arr2[]='小Q老师';
	}
	$contentStr = str_replace($arr, $arr2, $contentStr);

	return $contentStr;
}

function getsimsReply($cookie, $keyword){

	$header = array();
	$ip = rand(1,255).".".rand(1,255).".".rand(1,255).".".rand(1,255)."";
	$headers['CLIENT-IP'] = $ip;  
	$headers['X-FORWARDED-FOR'] = $ip;
	$header[]= 'Accept: application/json, text/javascript, * '. '/* ';  
	$header[]= 'Accept-Language: zh-CN,zh ';  
	$header[]= 'User-Agent:  Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.36 Safari/537.22';  
	$header[]= 'Host: www.simsimi.com';  
	//$header[]= 'Connection: close '; 
	$header[]= 'Connection: Keep-Alive ';  
	// $header[]= 'Cookie: JSESSIONID=2D96E7F39FBAB9B28314607D0328D35F';
	$header[]= 'Cookie: '.$cookie;
	$header[]= 'Client_Ip: '.$ip;
	$header[]= 'Real_ip: '.$ip;
	$header[]= 'X_FORWARD_FOR: '.$ip;
	$header[]= 'X-forwarded-for: '.$ip;
	$header[]= 'PROXY_USER: '.$ip;
	$Ref="http://www.simsimi.com/talk.htm?lc=ch";
	$Ch = curl_init();
	$Options = array(
		CURLOPT_HTTPHEADER => $header,
		CURLOPT_URL => 'http://www.simsimi.com/func/req?msg='.$keyword.'&lc=ch',
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_REFERER => $Ref,
	);
	curl_setopt_array($Ch, $Options);
	$Message = json_decode(curl_exec($Ch),true);
	curl_close($Ch);
	
	$contentStr = "你说的什么？";
	if($Message['result']=='100' && $Message['response'] <> 'hi'){
		$contentStr = $Message['response'];
	}
	$arr = array('小贱鸡', '小黄鸡', '小鸡鸡', '贱鸡', '小鸡仔', '鸡','sim');
	$arr2 = array();
	foreach($arr as $key=>$value){
		$arr2[]='小Q老师';
	}
	$contentStr = str_replace($arr, $arr2, $contentStr);

	return $contentStr;
}

function getListen($id){
	global $db;

	$sql = "select * from msg_listen where id='".$id."' order by id DESC";
	$listen = $db->queryUniqueObject($sql);
	
	return $listen;
}

function numOfListen(){
	global $db;
	$table = "msg_listen";
	return $db->countOfAll($table);
}

//异步发信息更新听力数据库  发现有有点问题,还没做好
function updateListen(){

    $fp = fsockopen("http://bee.ap01.aws.af.cm",80,$errno,$errstr,5);
    if(!$fp){
       echo "$errstr($errno)<br />\n";
    }
    fputs($fp,"GET /updateListen.php?param=1\r\n");
    fclose($fp);
}

?>
