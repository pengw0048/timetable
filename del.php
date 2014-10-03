<?php
header("Content-Type: text/html;charset=utf-8");
function safesql($string){
  $string=mysql_real_escape_string(addslashes($string));
  return $string;
}
$mysql_server_name='127.0.0.1';
$mysql_username='root';
$mysql_password='';
$mysql_database='timetable';
$tid=safesql(isset($_GET['tid'])?$_GET['tid']:(isset($_POST['tid'])?$_POST['tid']:''));
$myname=safesql(isset($_POST['myname'])?$_POST['myname']:'');
$pass=safesql(isset($_POST['pass'])?$_POST['pass']:'');
if($tid!=''&&ctype_alnum($tid)){
  $conn=mysql_connect($mysql_server_name,$mysql_username,$mysql_password);
  mysql_query("SET NAMES UTF8",$conn);
  mysql_select_db($mysql_database,$conn);
  $strsql="SELECT * FROM tableinfo WHERE tid='$tid'";
  $result=mysql_query($strsql,$conn);
  if($result==false||mysql_num_rows($result)!=1){
    header('location: index.htm');
    mysql_close($conn);
    exit;
  }
  mysql_close($conn);
}
if($myname==''){
?>
<html>
  <head>
    <!--
	  这只是一个网页而已，你们千万不要拿它干不好的事情啊！
	  如果你萌玩SQL注入什么的，见鬼，我会用靴子狠狠地踢你们的屁股。
	  我发誓，我一定会这样做的！
	-->
    <title>
	  真的要抛弃我吗？
	</title>
    <meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  </head>
  <body>
    <form action="del.php?tid=<?php echo $tid;?>" method="post">
	  <p>
	    还记得填写时输入的信息吗？
	  </p>
      <p>名字：<input type="text" name="myname" id="myname"/></p>
	  <p>密码：<input type="password" name="pass" id="pass"></p>
	  <br/>
	  <input type="submit" value="残忍地抛弃"/>
	</form>
  </body>
</html>
<?php
  }else{
    $conn=mysql_connect($mysql_server_name,$mysql_username,$mysql_password);
    mysql_query("SET NAMES UTF8",$conn);
    mysql_select_db($mysql_database,$conn);
    $strsql="SELECT * FROM personinfo WHERE tid='$tid' AND name='$myname' AND pass='$pass'";
    $result=mysql_query($strsql,$conn);
    if($result==false||$result==false||mysql_num_rows($result)!=1){
      echo "找不到你的记录啊，再想想...";
      mysql_close($conn);
      exit;
    }
	$strsql="DELETE FROM personinfo WHERE tid='$tid' AND myname='$myname' AND pass='$pass'";
    mysql_query($strsql,$conn);
    mysql_close($conn);
?>
<html><body><a href="share.php?tid=<?php echo $tid;?>">删完啦，快回去吧</a></body></html>
<?php
  }
?>