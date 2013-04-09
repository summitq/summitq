<?php
/**
  * wechat php test
  */

include_once("config.php");
include_once( 'botutil.php' );


//define your token
define("TOKEN", "summitbot");
$wechatObj = new wechatCallbackapiTest();
//$wechatObj->valid();
$wechatObj->responseMsg();

class wechatCallbackapiTest
{
	public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->checkSignature()){
        	echo $echoStr;
        	exit;
        }
    }

	public function welcome($toUsername) {
        if($toUsername=="gh_51b7466306d9"){//微信原始id
            return      "familyday,欢迎你的加入";//欢迎语
        }
    }

    public function responseMsg()
    {
		//get post data, May be due to the different environments
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
		
      	//extract post data
		if (!empty($postStr)){
                
              	$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                $fromUsername = $postObj->FromUserName;
                $toUsername = $postObj->ToUserName;
                $keyword = trim($postObj->Content);
                $time = time();
                
				
				if(!empty( $keyword ))
                {
					if ($key=='Hello2BizUser'){
						$msgType = "text";
						$contentStr = $this->welcome($toUsername);
						$resultStr = makeText($fromUsername, $toUsername, $time, $msgType, $contentStr); 
					}elseif($keyword == "自习"){
						
						$msgType = "text";
						$contentStr = getClass();
						$resultStr = makeText($fromUsername, $toUsername, $time, $msgType, $contentStr); 
					}elseif($keyword == "听力"){
						
						$msgType = "music";
						$listenRes = getListen(0);
						$resMes = "查看原文回复:听力原文@".$listenRes->id." 查看翻译回复:听力翻译@".$listenRes->id;
						$resultStr = makeMusic($fromUsername, $toUsername, $time, $msgType, $listenRes->OrgTitle, $resMes, $listenRes->MusicUrl); 
					}elseif(preg_match("/听力原文@(.*)/i", $keyword, $matches)){
					
						$msgType = "text";
						$listenRes = getListen($matches[1]);
						
						if(!empty($listenRes->OrgContent)){
							$resMes = html2text($listenRes->OrgContent);
						}else{
							$resMes = "你查看的听力原文不存在哦(⊙o⊙)！请查看是否输入有误";
						}
						$resultStr = makeText($fromUsername, $toUsername, $time, $msgType, $resMes); 	
					}elseif(preg_match("/听力翻译@(.*)/i", $keyword, $matches)){
					
						$msgType = "text";
						$listenRes = getListen($matches[1]);
						
						if(!empty($listenRes->TrnContent)){
							$resMes = html2text($listenRes->TrnContent);
						}else{
							$resMes = "你查看的听力翻译不存在哦(⊙o⊙)！请查看是否输入有误";
						}
						$resultStr = makeText($fromUsername, $toUsername, $time, $msgType, $resMes); 
					}elseif($keyword == "天气"){
						
						$msgType = "text";
						$contentStr = getWeather();
						$resultStr = makeText($fromUsername, $toUsername, $time, $msgType, $contentStr); 
					}elseif($keyword == "笑话"){
						
						$msgType = "text";
						$contentStr = getJoke();
						$resultStr = makeText($fromUsername, $toUsername, $time, $msgType, $contentStr); 
					}elseif($keyword == "美图"){
						$msgType = "news";
						$contentStr = "每日100张美图";
						$articles = array();
						$article = getMeitu();
						$articles[] = makeArticleItem("每日100张美图", $article["title"], $article["picUrl"], $article["picUrl"]);
						$resultStr = makeArticles($fromUsername, $toUsername, $time, $msgType, $contentStr,$articles); 
					}elseif($keyword == "语录"){
						$msgType = "news";
						$contentStr = "每日维基语录";
						$articles = array();
						$article = getWikiquote();
						
						$articles[] = makeArticleItem("每日维基语录", $article["quote"], getAPOD(), $article["url"]);
						$resultStr = makeArticles($fromUsername, $toUsername, $time, $msgType, $contentStr,$articles); 

					}elseif($keyword == "末日"){
						$msgType = "text";
						$contentStr = "世界未末日";
						$resultStr = makeText($fromUsername, $toUsername, $time, $msgType, $contentStr);
					}elseif (preg_match("/翻译@([a-zA-Z0-9 ,.\n\t]+)/i", $keyword, $matches)){
						
						$msgType = "text";
						$contentStr = getTranslate($matches[1]);
						$resultStr = makeText($fromUsername, $toUsername, $time, $msgType, $contentStr); 
					}elseif(preg_match("/书@(.*)/i", $keyword, $matches)){
						
						$msgType = "news";
						$r = getDouban($matches[1], 'book');
						$contentStr = $r["contentStr"];
						$articles = array();
						foreach ($r["articles"] as $article)
						{
							$articles[] = makeArticleItem($article["title"], $article["discription"], $article["picUrl"], $article["url"]);
						}
						$resultStr = makeArticles($fromUsername, $toUsername, $time, $msgType, $contentStr,$articles); 
					}elseif(preg_match("/电影@(.*)/i", $keyword, $matches)){
						
						$msgType = "news";
						$r = getDouban($matches[1], 'movie');
						$contentStr = $r["contentStr"];
						$articles = array();
						foreach ($r["articles"] as $article)
						{
							$articles[] = makeArticleItem($article["title"], $article["discription"], $article["picUrl"], $article["url"]);
						}
						$resultStr = makeArticles($fromUsername, $toUsername, $time, $msgType, $contentStr,$articles); 
					}elseif(preg_match("/音乐@(.*)/i", $keyword, $matches)){
						
						$msgType = "news";
						$r = getDouban($matches[1], 'music');
						$contentStr = $r["contentStr"];
						$articles = array();
						foreach ($r["articles"] as $article)
						{
							$articles[] = makeArticleItem($article["title"], $article["discription"], $article["picUrl"], $article["bigPicUrl"]);
						}
						$resultStr = makeArticles($fromUsername, $toUsername, $time, $msgType, $contentStr,$articles); 
					}elseif(preg_match("/ip@([0-9.]+)/i", $keyword, $matches)){
						
						$msgType = "text";
						$contentStr = getIP($matches[1]);
						$resultStr = makeText($fromUsername, $toUsername, $time, $msgType, $contentStr); 
					}elseif(preg_match("/电话@([0-9]+)/i", $keyword, $matches)){
						
						$msgType = "text";
						$contentStr = getPhone($matches[1]);
						$resultStr = makeText($fromUsername, $toUsername, $time, $msgType, $contentStr); 
					}elseif(preg_match("/fm@(.*)/i", $keyword, $matches)){
						$msgType = "news";
						$r = getDoubanfm($fromUsername,$matches[1]);
						$contentStr = "豆瓣FM";
						$articles = array();
						foreach ($r as $article)
						{
							$articles[] = makeArticleItem($article["title"], $article["title"], $article["picture"], $article["pmurl"].$fromUsername);
						}
						$resultStr = makeArticles($fromUsername, $toUsername, $time, $msgType, $contentStr,$articles);
						
					}elseif(preg_match("/训练@(.*)@(.*)/i", $keyword, $matches)){
						addMSE($matches[1], $matches[2]);
						$msgType = "text";
						$contentStr = "主人我已学会.若有人向我说<".$matches[1].">我就答:<".$matches[2].">";
						$resultStr = makeText($fromUsername, $toUsername, $time, $msgType, $contentStr);
					}elseif(preg_match("/回答@(.*)/i", $keyword, $matches)){
						$msgType = "text";
						$contentStr =  searchMSE($matches[1]);
						$resultStr = makeText($fromUsername, $toUsername, $time, $msgType, $contentStr);
					}elseif(preg_match("/注册@(.*)/i", $keyword, $matches)){
						$msgType = "text";
						addUser($matches[1], $fromUsername);
						$resultStr = makeText($fromUsername, $toUsername, $time, $msgType, $toUsername);
					}else{
						srand((double)microtime()*1000000);
						$index= rand(0, 2);
						if ($index==0){
							$msgType = "text";
							$contentStr = $postObj->Content;
							//文本
							$resultStr = makeText($fromUsername, $toUsername, $time, $msgType, $contentStr);
						}elseif($index==1){
							$msgType = "news";
							$contentStr = "每日维基语录";
							$articles = array();
							$article = getWikiquote();
							
							$articles[] = makeArticleItem($postObj->Content, $article["quote"], getAPOD(), $article["url"]);
							$resultStr = makeArticles($fromUsername, $toUsername, $time, $msgType, $contentStr,$articles); 
						}else{
							$msgType = "news";
							$contentStr = "每日100张美图";
							$articles = array();
							$article = getMeitu();
							$articles[] = makeArticleItem($postObj->Content, $article["title"], $article["picUrl"], $article["bigPicUrl"]);
							$resultStr = makeArticles($fromUsername, $toUsername, $time, $msgType, $contentStr,$articles); 
						}
						
					}
					
                	echo $resultStr;
                }else{
                	echo "Input something...";
                }

        }else {
        	echo "";
        	exit;
        }
    }
		
	private function checkSignature()
	{
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];	
        		
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
}


$db->close();

?>