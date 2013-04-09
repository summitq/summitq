<?php
/**
  * wechat php test
  */

include_once("config.php");
include_once('botutil.php');
include_once('user.php');

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
            return  "familyday,欢迎你的加入";//欢迎语
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
			
			$mainContentStr = "你好！欢迎关注“小Q老师”，小Q老师最懂你，他能帮助你找自习室，督促你学习英语，在你不开心的时候为你讲笑话，小Q老师愿成为你大学最亲密的伴侣！请回复以下数字：\n\n•1【自习】——找自习室\n•2【听力】——每日听力\n•3【笑话】——开心一刻\n•4【天气】——出行不愁\n•5【美图】——每日美图\n•6【语录】——维基语录"; 
			$classContentStr = "欢迎使用教室管理系统，请回复以下数字:\n\n•1【自习】——当前自习室\n•#【目录】——返回主目录";
			$listenContentStr = "欢迎进入听力学习，进一步操作，请回复以下数字:\n\n•1【音频】——听力音频\n•2【原文】——听力原文\n•3【译文】——听力译文\n•*【菜单】——返回菜单\n•#【目录】——返回主目录";
			$jokeContentStr = "不开心么，小Q老师在你不开心的时候为你讲笑话，希望你天天有个好心情。请回复以下数字:\n•1【笑话】——幽默笑话\n•#【目录】——返回主目录";
			$weatherContentStr = "查看今日气象预报，请回复以下数字:\n\n•1【天气】——今日天气\n•#【目录】——返回主目录";
			$pictureContentStr = "一张图书写一个世界！发现生活中的美。请回复以下数字:\n\n•1【美图】——精美图片\n•#【目录】——返回主目录";
			$wikiquoteContentStr = "名人名言能改变你的心情！每日维基语录。请回复以下数字:\n\n•1【语录】——维基语录\n•#【目录】——返回主目录";
			
			$user = new User();
			$userQuery = $user->fn_userStatus($fromUsername);
			$userStatus  = mysql_fetch_array($userQuery);
			
			//不存在数据表里面的用户 则增加用户进数据库并设置用户当前状态在主菜单
			if(!$userStatus){
				$userOperate = '{"flag":"main","index":"0","time":"'.$time .'"}';
				$user->fn_insertUserStatus($fromUsername,$userOperate);
			}else{
				$userOperate = $userStatus['Operate'];
			}
			
			//用户当前所在的目录节点
			$userOperate = json_decode($userOperate);
			$userOpFlag = $userOperate->flag;
			
			if(!empty( $keyword ))
			{
				if ($key=='Hello2BizUser'){
					$msgType = "text";
					$contentStr = $this->welcome($toUsername);
					$resultStr = makeText($fromUsername, $toUsername, $time, $msgType, $contentStr); 
				}elseif($userOpFlag == "main"){
					
					if($keyword == "1"){
					
						$userStatus = '{"flag":"自习","index":"0","time":"'.$time .'"}';
						$user->fn_updateUserStatus($fromUsername,$userStatus);
						
						$msgType = "text";
						$contentStr = getClass();
						$resultStr = makeText($fromUsername, $toUsername, $time, $msgType, $contentStr); 
					}elseif($keyword == "2"){
					
						$userStatus = '{"flag":"听力","index":"0","time":"'.$time .'"}';
						$user->fn_updateUserStatus($fromUsername,$userStatus);
						
						$msgType = "text";
						$contentStr = $listenContentStr;
						$resultStr = makeText($fromUsername, $toUsername, $time, $msgType, $contentStr); 
					}elseif($keyword == "3"){
					
						$userStatus = '{"flag":"笑话","index":"0","time":"'.$time .'"}';
						$user->fn_updateUserStatus($fromUsername,$userStatus);
						
						$msgType = "text";
						$contentStr = getJoke();
						$resultStr = makeText($fromUsername, $toUsername, $time, $msgType, $contentStr); 
					}elseif($keyword == "4"){
					
						$userStatus = '{"flag":"天气","index":"0","time":"'.$time .'"}';
						$user->fn_updateUserStatus($fromUsername,$userStatus);
						
						$msgType = "text";
						$contentStr = getWeather();
						$resultStr = makeText($fromUsername, $toUsername, $time, $msgType, $contentStr); 
					}elseif($keyword == "5"){
					
						$userStatus = '{"flag":"美图","index":"0","time":"'.$time .'"}';
						$user->fn_updateUserStatus($fromUsername,$userStatus);
						
						$msgType = "news";
						$contentStr = "每日100张美图";
						$articles = array();
						$article = getMeitu();
						$articles[] = makeArticleItem("每日100张美图", $article["title"], $article["picUrl"], $article["picUrl"]);
						$resultStr = makeArticles($fromUsername, $toUsername, $time, $msgType, $contentStr,$articles); 
						
					}elseif($keyword == "6"){
						$userStatus = '{"flag":"语录","index":"0","time":"'.$time .'"}';
						$user->fn_updateUserStatus($fromUsername,$userStatus);
						
						$msgType = "news";
						$contentStr = "每日维基语录";
						$articles = array();
						$article = getWikiquote();
						
						$articles[] = makeArticleItem("每日维基语录", $article["quote"], getAPOD(), $article["url"]);
						$resultStr = makeArticles($fromUsername, $toUsername, $time, $msgType, $contentStr,$articles); 
						
					}else{
						$userStatus = '{"flag":"main","index":"0","time":"'.$time .'"}';
						$user->fn_updateUserStatus($fromUsername,$userStatus);
											
						$msgType = "text";
						$contentStr = $mainContentStr;
						$resultStr = makeText($fromUsername, $toUsername, $time, $msgType, $contentStr); 
					}
					
				}elseif($userOpFlag == "自习"){
					//返回主目录
					if($keyword == "#"){
					
						$userStatus = '{"flag":"main","index":"0","time":"'.$time .'"}';
						$user->fn_updateUserStatus($fromUsername,$userStatus);
											
						$msgType = "text";
						$contentStr = $mainContentStr;
						$resultStr = makeText($fromUsername, $toUsername, $time, $msgType, $contentStr); 
					}elseif($keyword == "1"){
					
						$msgType = "text";
						$contentStr = getClass();
						$resultStr = makeText($fromUsername, $toUsername, $time, $msgType, $contentStr); 
					}else{
						//当前目录
						$userStatus = '{"flag":"自习","index":"0","time":"'.$time .'"}';
						$user->fn_updateUserStatus($fromUsername,$userStatus);
						
						$msgType = "text";
						$contentStr = $classContentStr;
						$resultStr = makeText($fromUsername, $toUsername, $time, $msgType, $contentStr); 
					}
					
				}elseif($userOpFlag == "听力"){
					//返回主目录
					if($keyword == "#"){
					
						$userStatus = '{"flag":"main","index":"0","time":"'.$time .'"}';
						$user->fn_updateUserStatus($fromUsername,$userStatus);
											
						$msgType = "text";
						$contentStr = $mainContentStr;
						$resultStr = makeText($fromUsername, $toUsername, $time, $msgType, $contentStr); 
					}elseif($keyword == "1"){
					
						$numRows = numOfListen();
						$randId = rand(1,$numRows);
						$userStatus = '{"flag":"听力","index":"'.$randId.'","time":"'.$time .'"}';
						$user->fn_updateUserStatus($fromUsername,$userStatus);
						
						$msgType = "music";
						$listenRes = getListen($randId);
						$resMes = substr(html2text($listenRes->OrgContent),0,200);
						$resultStr = makeMusic($fromUsername, $toUsername, $time, $msgType, $listenRes->OrgTitle, $resMes, $listenRes->MusicUrl); 
					}elseif($keyword == "2"){
					
						$randId = $userOperate->index;
						
						$msgType = "text";
						$listenRes = getListen($randId);
						$resMes = html2text($listenRes->OrgContent);
						$resultStr = makeText($fromUsername, $toUsername, $time, $msgType, $resMes); 
					}elseif($keyword == "3"){
					
						$randId = $userOperate->index;
						
						$msgType = "text";
						$listenRes = getListen($randId);
						$resMes = html2text($listenRes->TrnContent);
						$resultStr = makeText($fromUsername, $toUsername, $time, $msgType, $resMes); 
					}else{

						$userStatus = '{"flag":"听力","index":"0","time":"'.$time .'"}';
						$user->fn_updateUserStatus($fromUsername,$userStatus);
						
						$msgType = "text";
						$contentStr = $listenContentStr;
						$resultStr = makeText($fromUsername, $toUsername, $time, $msgType, $contentStr); 
					}
					
				}elseif($userOpFlag == "笑话"){
					if($keyword == "#"){
					//返回主目录
						$userStatus = '{"flag":"main","index":"0","time":"'.$time .'"}';
						$user->fn_updateUserStatus($fromUsername,$userStatus);
											
						$msgType = "text";
						$contentStr = $mainContentStr;
						$resultStr = makeText($fromUsername, $toUsername, $time, $msgType, $contentStr); 
					}elseif($keyword == "1"){
					
						$msgType = "text";
						$contentStr = getJoke();
						$resultStr = makeText($fromUsername, $toUsername, $time, $msgType, $contentStr); 
					}else{
					
						$userStatus = '{"flag":"笑话","index":"0","time":"'.$time .'"}';
						$user->fn_updateUserStatus($fromUsername,$userStatus);
						
						$msgType = "text";
						$contentStr = $jokeContentStr;
						$resultStr = makeText($fromUsername, $toUsername, $time, $msgType, $contentStr); 
					}
				}elseif($userOpFlag == "天气"){
					//返回主目录
					if($keyword == "#"){
					
						$userStatus = '{"flag":"main","index":"0","time":"'.$time .'"}';
						$user->fn_updateUserStatus($fromUsername,$userStatus);
											
						$msgType = "text";
						$contentStr = $mainContentStr;
						$resultStr = makeText($fromUsername, $toUsername, $time, $msgType, $contentStr); 
					}elseif($keyword == "1"){
					
						$msgType = "text";
						$contentStr = getWeather();
						$resultStr = makeText($fromUsername, $toUsername, $time, $msgType, $contentStr); 
					}else{

						$userStatus = '{"flag":"天气","index":"0","time":"'.$time .'"}';
						$user->fn_updateUserStatus($fromUsername,$userStatus);
						
						$msgType = "text";
						$contentStr = $weatherContentStr;
						$resultStr = makeText($fromUsername, $toUsername, $time, $msgType, $contentStr); 
					}
					
				}elseif($userOpFlag == "美图"){
					//返回主目录
					if($keyword == "#"){
					
						$userStatus = '{"flag":"main","index":"0","time":"'.$time .'"}';
						$user->fn_updateUserStatus($fromUsername,$userStatus);
											
						$msgType = "text";
						$contentStr = $mainContentStr;
						$resultStr = makeText($fromUsername, $toUsername, $time, $msgType, $contentStr); 
					}elseif($keyword == "1"){
					
						$msgType = "news";
						$contentStr = "每日100张美图";
						$articles = array();
						$article = getMeitu();
						$articles[] = makeArticleItem("每日100张美图", $article["title"], $article["picUrl"], $article["picUrl"]);
						$resultStr = makeArticles($fromUsername, $toUsername, $time, $msgType, $contentStr,$articles); 
					}else{

						$userStatus = '{"flag":"美图","index":"0","time":"'.$time .'"}';
						$user->fn_updateUserStatus($fromUsername,$userStatus);
						
						$msgType = "text";
						$contentStr = $pictureContentStr;
						$resultStr = makeText($fromUsername, $toUsername, $time, $msgType, $contentStr); 
					}
					
				}elseif($userOpFlag == "语录"){
					//返回主目录
					if($keyword == "#"){
					
						$userStatus = '{"flag":"main","index":"0","time":"'.$time .'"}';
						$user->fn_updateUserStatus($fromUsername,$userStatus);
											
						$msgType = "text";
						$contentStr = $mainContentStr;
						$resultStr = makeText($fromUsername, $toUsername, $time, $msgType, $contentStr); 
					}elseif($keyword == "1"){
					
						$msgType = "news";
						$contentStr = "每日维基语录";
						$articles = array();
						$article = getWikiquote();
						
						$articles[] = makeArticleItem("每日维基语录", $article["quote"], getAPOD(), $article["url"]);
						$resultStr = makeArticles($fromUsername, $toUsername, $time, $msgType, $contentStr,$articles); 
					}else{

						$userStatus = '{"flag":"语录","index":"0","time":"'.$time .'"}';
						$user->fn_updateUserStatus($fromUsername,$userStatus);
						
						$msgType = "text";
						$contentStr = $wikiquoteContentStr;
						$resultStr = makeText($fromUsername, $toUsername, $time, $msgType, $contentStr); 
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