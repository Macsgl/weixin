<?php
// 本类由系统自动生成，仅供测试用途
class IndexAction extends Action {
    public function index(){
	/*$this->show('<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} body{ background: #fff; font-family: "微软雅黑"; color: #333;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.8em; font-size: 36px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p>欢迎使用 <b>ThinkPHP</b>！</p></div><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script>','utf-8');*/
	$nonce = $_GET['nonce'];
        $token = 'yoman';
        $timestamp = $_GET['timestamp'];
        $echostr = $_GET['echostr'];
        $signature = $_GET['signature'];
        $array = array($nonce,$timestamp,$token);
        sort($array);
        $str = sha1(implode($array));
        if($str == $signature && $echostr){
        	echo $echostr;
        	exit;
        }else{
        	$this->reponseMsg();
        }
    }
    public function reponseMsg(){
    	$postArr = $GLOBALS['HTTP_RAW_POST_DATA'];
    	$postObj = simplexml_load_string($postArr);
    	if(strtolower($postObj->MsgType) == 'event'){
    		if(strtolower($postObj->Event) == 'subscribe'){
    			/*$toUser = $postObj->FromUserName;
    			$fromUser = $postObj->ToUserName;
    			$time = time();
    			$msgType = 'text';
    			$content = '欢迎关注涨姿势合集';
    			$template = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							</xml>";
				$info = sprintf($template,$toUser,$fromUser,$time,$msgType,$content);
                echo $info;*/
               // $content = '欢迎关注涨姿势合集';
                $indexModel = new IndexModel();
                $AccessToken = $indexModel->getWxAccessToken();
            	$userName = $indexModel->getUserName($AccessToken,$postObj);
            	//$content = 'jk'.$AccessToken;
                $content = '欢迎'.$userName.'关注接口测试公众号';//'./*.$userName.*/'
                $indexModel -> responseEvent($postObj,$content);
    		}
    		else if($postObj->Event == 'CLICK'){
    			if($postObj->EventKey == 'ID'){
    				$indexModel = new IndexModel();
                	$content = '你的OpenID为：'.$postObj->FromUserName;
                	$indexModel -> responseEvent($postObj,$content);
            	}
    		}
    	}
        else if(strtolower($postObj->MsgType) == 'text'){
            $IndexModel = new IndexModel();
            $AccessToken = $IndexModel->getWxAccessToken();
            $userName = $IndexModel->getUserName($AccessToken,$postObj);
            /*if(strtolower($postObj->Content) == 'name'){
            	$AccessToken = $IndexModel->getWxAccessToken();
            	$userName = $IndexModel->getUserName($AccessToken,$postObj);
            	$content = $userName;
            }else{*/
        		switch (trim(strtolower($postObj->Content))) {
        			case 'test':
        				$content = '开心';
        				break;
        			case 'openid':
        				$content = '你的OpenID为：'.$postObj->FromUserName;
        				break;
        			case 'acc':
        				$content = $AccessToken;
        				break;
        			case 'name':
        				$content = '你的昵称是：'.$userName;
        				break;
        			default:
        				$content = '没有查询结果';
        				break;
        		}
        	//}
        	
            
            $IndexModel->responseMsg($postObj,$content);
        }
    }
   /* public function http_curl(){
    	$ch = curl_init();
    	$url = 'http://www.baidu.com';
    	curl_setopt($ch,CURLOPT_URL,$url);
    	curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
    	$output = curl_exec($ch);
    	curl_close($ch);
    	var_dump($output);
    }*/
    /*public function getWxAccessToken(){
        $appid = 'wx185051cb84623783';
        $appSecret = 'ad16bbccb7b7429c4cee139fb11df6f7';
        $conn = mysql_connect(C('db_host'),C('db_user'),C('db_user_passwd'));
        mysql_select_db('test');
        mysql_query('set names utf8');
        $result = mysql_query("SELECT * FROM access_token");
        while($row = mysql_fetch_array($result))
        {
            $accessToken = $row['value'];
            $time = $row['time'];
        }
        if(time()<$time){
            $accessToken = '获取了原来的accesstoken';
        }else{
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$appSecret;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $res = curl_exec($ch);
            curl_close($ch);
            if(curl_errno($ch)){
                var_dump(curl_errno($ch));
            }
            $arr = json_decode($res, true);
            $accessToken = $arr['access_token'];
            $time = time()+7200;
            $query = mysql_query("UPDATE access_token SET time='$time',value='$accessToken' WHERE id=1;");
            if(!$query){
                $accessToken = mysql_error();
            }
        }
        mysql_close();
        echo $accessToken;
       // var_dump($arr);
    }*/
   /* public function getWxAccessToken(){
        $appid = 'wx240ecfce2a2fefa1';
        $appSecret = 'd620406e36f7062a7796b12c2912801f ';
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$appSecret;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $res = curl_exec($ch);
        curl_close($ch);
        if(curl_errno($ch)){
            var_dump(curl_errno($ch));
        }
        $arr = json_decode($res, true);
        echo $arr['access_token'];
       // var_dump($arr);
    }*/
    /*public function getWxServerIp(){
    	$sccessToken = '4d0Vg_gdnua-olkB9JhZJPYOwld71g7gZLuccFmvPTQynWEs4Sn2dufxtr6EiNBMzfr8-iwOFQpai2hmBDR4M39Hu3JTaEu3BK4xswh6XFzqjhYy4l1yVz9DtM2KQ9VHOOCgAIAGGU';
    	$url = 'https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token='.$sccessToken;
    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL, $url);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    	$res = curl_exec($ch);
    	curl_close($ch);
    	if(curl_errno($ch)){
            var_dump(curl_errno($ch));
        }
        $arr = json_decode($res,true);
        echo "<pre>";
        var_dump($arr);
        echo "</pre>";
    }*/
    /*public function getUserName(){
        $access_token = 'N1I4JRCA0GFUni3PvjrcFfOQcLLB6fxI-OSZnPpJIpCH1jmxv0vckfA4wH2CD_i9ZG_cEyX5Ia7jULpxlzCVNFbcVS6N2HsH5GKcQikZrD-7zHdNCr4I-2QbW3RJzvrnCIOdAJAJPV';
        $openid = 'oUzJIw3OoB1ijZ6yRw2YSyImXS3w';
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $res = curl_exec($ch);
        curl_close($ch);
        if(curl_errno($ch)){
            var_dump(curl_errno($ch));
        }
        $arr = json_decode($res, true);
        var_dump($arr);
        echo $arr['nickname'];
    }*/
}