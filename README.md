# weixin
微信公众平台测试代码<br/>
16/8/5<br/>
发送test返回“开心”，发送acc返回access_token，发送openid返回openid，发送name返回用户昵称，用户关注后发送欢迎消息带用户昵称，发送其他的返回没有查询结果<br/>
access_token保存在数据库中，数据库连接写在配置文件中，用thinkphp的c方法调用<br/>
