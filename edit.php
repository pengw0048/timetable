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
$desc=safesql(isset($_POST['desc'])?safesql($_POST['desc']):'');
$pass=safesql(isset($_POST['pass'])?safesql($_POST['pass']):'');
$create=isset($_POST['create'])?$_POST['create']:'';
$tid=isset($_GET['tid'])?$_GET['tid']:'';
if($create=='yes'){
  $tid=uniqid();
  $conn=mysql_connect($mysql_server_name,$mysql_username,$mysql_password);
  mysql_query("SET NAMES UTF8",$conn);
  mysql_select_db($mysql_database,$conn);
  $strsql="INSERT INTO tableinfo (`desc`, `pass`, `tid`, `ip`) VALUES ('$desc', '$pass', '$tid', '".GetIP()."');";
  mysql_query($strsql,$conn);
  header('location: edit.php?tid='.$tid.'');
  mysql_close($conn);
  exit;
}
if($tid!=''&&ctype_alnum($tid)){
  $conn=mysql_connect($mysql_server_name,$mysql_username,$mysql_password);
  mysql_query("SET NAMES UTF8",$conn);
  mysql_select_db($mysql_database,$conn);
  $strsql="SELECT * FROM tableinfo WHERE tid='$tid'";
  $result=mysql_query($strsql,$conn);
  if($result==false||mysql_num_rows($result)!=1){
    mysql_close($conn);
    header('location: index.htm');
    exit;
  }
  mysql_close($conn);
  //继续网页内容
}else{
  header('location: index.htm');
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
	  编辑时间表
	</title>
	<meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<script type="text/javascript">
	  state="<?php echo mysql_fetch_array($result)['content']; ?>";
	  swidth=window.screen.width;
	  sheight=window.screen.height;
	  ssize=(swidth-40)/8;
	  if(ssize>100)ssize=100;
	  function drawTable(){
	    clear();
		var canvas=document.getElementById("canvas");
		for(var i=0;i<sarray.length;i++){
		  var dlink=document.createElement("p");
		  dlink.innerHTML="<a href=\"javascript:doDel("+i+")\">删除下面的表格</a>"
		  canvas.appendChild(dlink);
		  var table=document.createElement("table");
		  table.border="1px";
		  table.style.fontSize="smaller";
		  var tr=document.createElement("tr");
		  for(var j=-1;j<sarray[i][0];j++){
		    var td=document.createElement("td");
			td.style.height=ssize/2;
			td.style.width=ssize;
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
			  td.style.height=ssize;
			  td.style.width=ssize;
			  if(j>=0)td.innerText="";
			  else td.innerText=daten.toLocaleDateString();
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
		for(var i=0;i<tarray[i].length;i++)
		  sarray[i]=tarray.split("~@*");
	    var wind=document.getElementById("wind");
		wind.style.height=sheight*0.9;
		wind.style.width=swidth*0.9;
		//wind.style.marginLeft=-swidth*0.45;
		//wind.style.marginTop=-sheight*0.45;
		updateCol(1);
	  }
	  function updateCol(num){
	    for(var i=1;i<=4;i++)
		  document.getElementById("col"+i).style.display=(i>num?"none":"");
		ncol=num;
		document.getElementById("col"+num).checked=true;
	  }
	  function doAdd(){
	    var pos=sarray.length;
		var coltext=new Array();
		for(var i=1;i<=4;i++){
		  coltext[i-1]=document.getElementById("text"+i).value;
		  if(i<=ncol&&coltext[i-1]==""){
		    alert("标题不能为空");
			return;
		  }
		}
		var date1=new Date(document.getElementById("year1").value,document.getElementById("month1").value-1,document.getElementById("day1").value);
		var date2=new Date(document.getElementById("year2").value,document.getElementById("month2").value-1,document.getElementById("day2").value);
		if(isNaN(date1)||isNaN(date2)){
		  alert("日期无效");
		  return;
		}
		var dd=parseInt(Math.ceil((date2-date1)/1000/60/60/24));
		if(dd<0||dd>20){
		  alert("日期范围不合适吧");
		  return;
		}
		sarray[pos]=[ncol,coltext[0],coltext[1],coltext[2],coltext[3],document.getElementById("year1").value,document.getElementById("month1").value,document.getElementById("day1").value,document.getElementById("year2").value,document.getElementById("month2").value,document.getElementById("day2").value];
	    document.getElementById('wind').style.display='none';
		for(var i=1;i<=4;i++)
		  document.getElementById("text"+i).value="";
		document.getElementById("year1").value="";
	    document.getElementById("year2").value="";
		document.getElementById("month1").value="";
		document.getElementById("month2").value="";
		document.getElementById("day1").value="";
		document.getElementById("day2").value="";
		drawTable();
	  }
	  function doDel(num){
	    var tarray=Array();
		for(var i=0;i<num;i++)
		  tarray[i]=sarray[i];
		for(var i=num+1;i<sarray.length;i++)
		  tarray[i-1]=sarray[i];
		sarray=tarray;
		drawTable();
	  }
	  function doSubmit(){
	    if(sarray.length==0){
		  alert("干什么呢");
		  return;
		}
		var tsa=Array();
		for(var i=0;i<sarray.length;i++)
		  tsa[i]=sarray[i].join("~@*");
		var ts=tsa.join("~#*");
		document.getElementById("content").value=ts;
		document.getElementById("hform").submit();
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
	<div class="wind" id="wind" name="wind" style="display:none;">
	  <p>每天有几个时间段？</p>
	  <input type="radio" name="cols" onclick="updateCol(1)" checked/>1&nbsp;
	  <input type="radio" name="cols" onclick="updateCol(2)"/>2&nbsp;
	  <input type="radio" name="cols" onclick="updateCol(3)"/>3&nbsp;
	  <input type="radio" name="cols" onclick="updateCol(4)"/>4<br/>
	  <div id="col1" name="col1">标题1：<input type="text" id="text1" name="text1"/></div>
	  <div id="col2" name="col2">标题2：<input type="text" id="text2" name="text2"/></div>
	  <div id="col3" name="col3">标题3：<input type="text" id="text3" name="text3"/></div>
	  <div id="col4" name="col4">标题4：<input type="text" id="text4" name="text4"/></div>
	  <p>从这天开始...</p>
	  <input type="text" id="year1" name="year1" size="4" maxLength="4"/>年
	  <input type="text" id="month1" name="month1" size="2" maxLength="2"/>月
	  <input type="text" id="day1" name="day1" size="2" maxLength="2"/>日
	  <p>...到这天结束</p>
	  <input type="text" id="year2" name="year2" size="4" maxLength="4"/>年
	  <input type="text" id="month2" name="month2" size="2" maxLength="2"/>月
	  <input type="text" id="day2" name="day2" size="2" maxLength="2"/>日<br/>
	  <button type="button" onclick="doAdd()">添加试试</button>
	  <button type="button" onclick="document.getElementById('wind').style.display='none';">关闭</button>
	</div>
    <button type="button" id="add" name="add" onclick="updateCol(1);document.getElementById('wind').style.display='';">添加一个表格</button>
	<button type="button" id="thatsit" name="thatsit" onclick="doSubmit();">就是它了！</button>
	<br/>
	<div id="canvas" name="canvas">
	  <!--在这里添加内容...-->
	</div>
	<form action="editok.php" method="post" id="hform" name="hform">
	  <input type="hidden" id="tid" name="tid" value="<?php echo $tid; ?>"/>
	  <input type="hidden" id="content" name="content" value=""/>
	</form>
  </body>
</html>
