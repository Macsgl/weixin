<?php
class IndexModel{
	public function responseMsg($postObj,$content){
                $toUser = $postObj->FromUserName;
                $fromUser = $postObj->ToUserName;
                $time = time();
                $msgType = 'text';
                $template = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[%s]]></MsgType>
                            <Content><![CDATA[%s]]></Content>
                            </xml>";
                $info = sprintf($template,$toUser,$fromUser,$time,$msgType,$content);
                echo $info;
	}
	public function responseEvent($postObj,$content){
		$toUser = $postObj->FromUserName;
        $fromUser = $postObj->ToUserName;
        $time = time();
        $msgType = 'text';
        $template = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[%s]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                    </xml>";
        $info = sprintf($template,$toUser,$fromUser,$time,$msgType,$content);
        echo $info;
	}
    public function getMySql(){
        $conn = mysql_connect(C('db_host'),C('db_user'),C('db_user_passwd'));
        mysql_select_db(C('db_name'));
        mysql_query('set names utf8');
        $result = mysql_query("SELECT * FROM access_token");
        while($row = mysql_fetch_array($result))
        {
            $accessToken = $row['value'];
            $time = $row['time'];
        }
        mysql_close();
        return $accessToken;
    }
    public function getWxAccessToken(){
        $appid = 'wx185051cb84623783';
        $appSecret = 'ad16bbccb7b7429c4cee139fb11df6f7';
        $conn = mysql_connect(C('db_host'),C('db_user'),C('db_user_passwd'));
        mysql_select_db(C('db_name'));
        mysql_query('set names utf8');
        $result = mysql_query("SELECT * FROM access_token");
        while($row = mysql_fetch_array($result))
        {
            $accessToken = $row['value'];
            $time = $row['time'];
        }
        if(time()<$time){
            ;
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
            $query = mysql_query("UPDATE access_token SET time='$time',value='$accessToken' WHERE id=1");
            if(!$query){
                $accessToken = mysql_error();
            }
        }
        mysql_close();
        return $accessToken;
       // var_dump($arr);
    }
    public function getUserName($access_token,$postObj){
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token.'&openid='.$postObj->FromUserName;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $res = curl_exec($ch);
        curl_close($ch);
        if(curl_errno($ch)){
            var_dump(curl_errno($ch));
        }
        $arr = json_decode($res, true);
        return $arr['nickname'];
    }

}



?>