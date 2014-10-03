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
$tid=isset($_POST['tid'])?$_POST['tid']:'';
$content=isset($_POST['content'])?$_POST['content']:'';
if($content!=''&&$tid!=''&&ctype_alnum($tid)){
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
  $strsql="UPDATE tableinfo SET content='".safesql($content)."' WHERE tid='$tid';";
  mysql_query($strsql,$conn);
  mysql_close($conn);
  echo "设置完毕，现在可以把下面地址分享给朋友们了！<br/>http://167.88.114.53/tt/share.php?tid=$tid";
  exit;
}
header('location: index.htm');
exit;
?>