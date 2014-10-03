<?php
header("Content-Type: text/html;charset=utf-8");
function safesql($string){
  $string=mysql_real_escape_string(addslashes($string));
  return $string;
}
function GetIP(){ 
  if(getenv('HTTP_CLIENT_IP')&&strcasecmp(getenv('HTTP_CLIENT_IP'),'unknown'))
    $ip=getenv('HTTP_CLIENT_IP');
  else if(getenv('HTTP_X_FORWARDED_FOR')&&strcasecmp(getenv('HTTP_X_FORWARDED_FOR'),'unknown'))
    $ip=getenv('HTTP_X_FORWARDED_FOR');
  else if(getenv('REMOTE_ADDR')&&strcasecmp(getenv('REMOTE_ADDR'),'unknown'))
    $ip=getenv('REMOTE_ADDR');
  else if(isset($_SERVER['REMOTE_ADDR'])&&$_SERVER['REMOTE_ADDR']&&strcasecmp($_SERVER['REMOTE_ADDR'],'unknown'))
    $ip=$_SERVER['REMOTE_ADDR'];
  else
    $ip='unknown';
  return($ip);
}
$mysql_server_name='127.0.0.1';
$mysql_username='root';
$mysql_password='';
$mysql_database='timetable';
$tid=safesql(isset($_GET['tid'])?$_GET['tid']:(isset($_POST['tid'])?$_POST['tid']:''));
$content=safesql(isset($_POST['content'])?$_POST['content']:'');
$myname=safesql(isset($_POST['myname'])?$_POST['myname']:'');
$pass=safesql(isset($_POST['pass'])?$_POST['pass']:'');
if($tid!=''&&ctype_alnum($tid)){
  $conn=mysql_connect($mysql_server_name,$mysql_username,$mysql_password);
  mysql_query("SET NAMES UTF8",$conn);
  mysql_select_db($mysql_database,$conn);
  $strsql="SELECT * FROM tableinfo WHERE tid='$tid'";
  $result=mysql_query($strsql,$conn);
  if($result==false||mysql_num_rows($result)!=1){
    echo "<html><body><img src=\"error.png\"/></body></html>";
    mysql_close($conn);
    exit;
  }
  $info=mysql_fetch_array($result);
  mysql_close($conn);
}else{
  echo "<html><body><img src=\"error.png\"/></body></html>";
  exit;
}
if($content!=''){
  $conn=mysql_connect($mysql_server_name,$mysql_username,$mysql_password);
  mysql_query("SET NAMES UTF8",$conn);
  mysql_select_db($mysql_database,$conn);
  $strsql="INSERT INTO personinfo (`name`, `tid`, `content`, `pass`, `ip`) VALUES ('$myname', '$tid', '$content', '$pass', '".GetIP()."');";
  mysql_query($strsql,$conn);
  header('location: show.php?tid='.$tid.'');
  mysql_close($conn);
  exit;
}
?>
<html>
  <head>
    <!--
	  这只是一个网页而已，你们千万不要拿它干不好的事情啊！
	  如果你萌玩SQL注入什么的，见鬼，我会用靴子狠狠地踢你们的屁股。
	  我发誓，我一定会这样做的！
	-->
    <title>
	  没想好这个网页叫什么...
	</title>
	<meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<script type="text/javascript">
	  state="<?php echo $info['content']; ?>";
	  swidth=window.screen.width;
	  sheight=window.screen.height;
	  ssize=(swidth-40)/6;
	  if(ssize>100)ssize=100;
	  cTable=-1;
	  cCell=-1;
	  sState=new Array();
	  function handleMove(event){
	    //var x=event.targetTouches[0].offsetX;
		//var y=event.targetTouches[0].offsetY;
		var box = (event.currentTarget).getBoundingClientRect(), 
		offsetX = event.targetTouches[0].clientX - box.left, 
		offsetY = event.targetTouches[0].clientY - box.top; 
		var table=parseInt(event.currentTarget.id.split("table")[1]);
		var x=offsetX;
		var y=offsetY;
		var row=parseInt(y/(ssize/1.5+2))-1;
		var col=parseInt(x/(ssize+2))-1;
		if(row<0||col<0||col>=sarray[table][0])return;
		var cid=row*sarray[table][0]+col;
		var td=document.getElementById("table"+table+"td"+cid);
		if(td==null||cTable==table&&cCell==cid)return;
		td.style.backgroundColor=(sState[table][cid]==0?"#f00":"#0f0");
		sState[table][cid]=1-sState[table][cid];
		cTable=table;
		cCell=cid;
	  }
	  function drawTable(){
	    clear();
		var canvas=document.getElementById("canvas");
		for(var i=0;i<sarray.length;i++){
		  sState[i]=new Array();
		  var table=document.createElement("table");
		  table.border="1px";
		  table.style.fontSize="smaller";
		  table.style.tableLayout="fixed";
		  table.style.wordBreak="break-all";
		  table.nowrap="nowrap";
		  table.addEventListener("touchmove",function(event){handleMove(event);},false);
		  table.addEventListener("touchstart",function(event){event.preventDefault();},false);
		  table.addEventListener("touchend",function(event){cTable=-1;cCell=-1;},false);
		  table.addEventListener("mousemove",function(event){handleMove(event);},false);
		  table.addEventListener("mousedown",function(event){event.preventDefault();},false);
		  table.addEventListener("mouseup",function(event){cTable=-1;cCell=-1;},false);
		  table.id="table"+i;
		  var tr=document.createElement("tr");
		  for(var j=-1;j<sarray[i][0];j++){
		    var td=document.createElement("td");
			td.style.height=ssize/1.5;
			td.style.width=ssize;
			td.height=ssize/1.5;
			td.width=ssize;
			if(j>=0)td.innerText=sarray[i][j+1];
			else td.innerText="";
			tr.appendChild(td);
		  }
		  table.appendChild(tr);
		  var date1=new Date(sarray[i][5],sarray[i][6]-1,sarray[i][7]);
		  var date2=new Date(sarray[i][8],sarray[i][9]-1,sarray[i][10]);
		  var dd=parseInt(Math.ceil((date2-date1)/1000/60/60/24));
		  for(var k=0;k<=dd;k++){
		    var tr=document.createElement("tr");
			var daten=new Date((date1/1000+86400*k)*1000);
			for(var j=-1;j<sarray[i][0];j++){
		      var td=document.createElement("td");
			  td.style.height=ssize/1.5;
			  td.style.width=ssize;
		      td.height=ssize/1.5;
			  td.width=ssize;
			  if(j>=0){
			    td.innerText="";
				td.id="table"+i+"td"+(k*sarray[i][0]+j);
				sState[i][k*sarray[i][0]+j]=0;
				td.style.backgroundColor="#0F0";
			  }else td.innerText=(daten.getMonth()+1)+"-"+daten.getDate();;
			  tr.appendChild(td);
		    }
		    table.appendChild(tr);
		  }
		  canvas.appendChild(table);
		}
	  }
	  function clear(){
	    document.getElementById("canvas").innerHTML="";
	  }
	  function init(){
	    var tarray=state.split("~#*");
		sarray=new Array();
		for(var i=0;i<tarray.length;i++)
		  sarray[i]=tarray[i].split("~@*");
	    var wind=document.getElementById("wind");
		wind.style.height=sheight*0.9;
		wind.style.width=swidth*0.9;
		//wind.style.marginLeft=-swidth*0.45;
		//wind.style.marginTop=-sheight*0.45;
		drawTable();
	  }
	  function doSubmit(){
	    if(document.getElementById("myname").value==""){
		  alert("骗人！");
		  return;
		}
	    var tsa=new Array();
		for(var i=0;i<sState.length;i++)
		  tsa[i]=sState[i].join("~@*");
		var ts=tsa.join("~#*");
		document.getElementById("content").value=ts;
	    hform.submit();
	  }
	</script>
	<style>
      .wind_old{
	    position:absolute;
        left:50%;
		top:50%;
        height:300px;
		width:200px;
        margin-left:-150px;
        margin-top:-100px;
        border:1PX solid #F00;
		background-color:#FFFFFF;
      }
      .wind{
        height:90%;
		width:90%;
        border:1PX solid #F00;
		background-color:#FFFFFF;
      }
	  body html{
	    width:100%;
	  }
    </style>
  </head>
  <body onload="init()">
	<div class="wind" id="wind" name="wind">
	  <div style="width:95%;border:1px solid #F00">
	    <p style="font-size:smaller"><?php echo $info['desc']?></p>
	  </div>
	  <p><a onclick="javascript:document.getElementById('wind').style.display='none';" href="#">填写我有空的时间</a></p>
	  <p><a href="del.php?tid=<?php echo $tid;?>">删除以前填写的内容</a></p>
	  <p><a href="show.php?tid=<?php echo $tid;?>">看看大家的空余时间</a></p>
	</div>
	<form action="share.php" method="post" id="hform" name="hform">
	  <input type="hidden" id="tid" name="tid" value="<?php echo $tid; ?>"/>
	  <input type="hidden" id="content" name="content" value=""/>
      <p>我的名字：<input type="text" name="myname" id="myname"/></p>
	  <p>设置一个密码：<input type="password" name="pass" id="pass"></p>
	  <button type="button" onclick="doSubmit();">时间什么的全都选好了</button>
	</form>
	<div id="canvas" name="canvas">
	  <!--在这里添加内容...-->
	</div>
  </body>
</html>
