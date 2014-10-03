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
if($info["pass"]==$pass){
?>
<html>
  <head>
    <!--
	  这只是一个网页而已，你们千万不要拿它干不好的事情啊！
	  如果你萌玩SQL注入什么的，见鬼，我会用靴子狠狠地踢你们的屁股。
	  我发誓，我一定会这样做的！
	-->
    <title>
	  看看小伙伴们的回答
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
	  sdd=new Array();
	  function drawTable(){
	    clear();
		var canvas=document.getElementById("canvas");
		for(var i=0;i<sarray.length;i++){
		  sState[i]=new Array();
		  var table=document.createElement("table");
		  table.border="1px";
		  table.style.fontSize="smaller";
		  table.id="table"+i;
		  table.style.textAlign="center";
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
		  sdd[i]=dd;
		  for(var k=0;k<=dd;k++){
		    var tr=document.createElement("tr");
			var daten=new Date((date1/1000+86400*k)*1000);
			for(var j=-1;j<sarray[i][0];j++){
		      var td=document.createElement("td");
			  td.style.height=ssize/1.5;
			  td.style.width=ssize;
		      td.height=ssize/1.5;
			  td.width=ssize;
			  td.style.fontSize="larger";
			  if(j>=0){
			    td.innerText="";
				td.id="table"+i+"td"+(k*sarray[i][0]+j);
				sState[i][k*sarray[i][0]+j]=0;
			  }else td.innerText=(daten.getMonth()+1)+"-"+daten.getDate();
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
		var tpeople=new Array();
<?php
  $conn=mysql_connect($mysql_server_name,$mysql_username,$mysql_password);
  mysql_query("SET NAMES UTF8",$conn);
  mysql_select_db($mysql_database,$conn);
  $strsql="SELECT * FROM personinfo WHERE tid='$tid'";
  $result=mysql_query($strsql,$conn);
  while($info=mysql_fetch_array($result))
    echo "tpeople[tpeople.length]=['$info[0]','$info[2]'];\r\n";
  mysql_close($conn);
?>
        pname=new Array();
		pinfo=new Array();
		for(var i=0;i<tpeople.length;i++){
		  pname[i]=tpeople[i][0];
		  var tarr=tpeople[i][1].split("~#*");
		  pinfo[i]=new Array();
		  for(var j=0;j<tarr.length;j++)
		    pinfo[i][j]=tarr[j].split("~@*");
		}
		for(var i=0;i<pname.length;i++){
		  var p=document.createElement("p");
		  var cb=document.createElement("input");
		  cb.type="checkbox";
		  cb.checked=true;
		  cb.addEventListener("click",updateTable,false);
		  cb.id="cb"+i;
		  p.appendChild(cb);
		  cb=document.createElement("input");
		  cb.type="checkbox";
		  cb.checked=false;
		  cb.addEventListener("click",updateTable,false);
		  cb.id="tb"+i;
		  p.appendChild(cb);
		  var div=document.createElement("div");
		  div.style.fontSize="smaller";
		  div.style.float="left";
		  div.innerText=pname[i]+":选中|特别关注";
		  p.appendChild(div);
		  document.getElementById("people").appendChild(p);
		}
		drawTable();
		updateTable();
	  }
	  function updateTable(event){
	    var cb=new Array();
	    var tb=new Array();
		for(var i=0;i<pname.length;i++)
		  cb[i]=document.getElementById("cb"+i);
		for(var i=0;i<pname.length;i++)
		  tb[i]=document.getElementById("tb"+i);
		for(var i=0;i<sarray.length;i++){
		  for(var j=0;j<sarray[i][0]*(sdd[i]+1);j++){
		    var count=0;
			var allok=true;
			var text="";
			for(var k=0;k<pname.length;k++){
			  if(cb[k].checked&&pinfo[k][i][j]=="0"){
			    count++;
				text=text+pname[k]+" ";
			  }
			  if(tb[k].checked&&pinfo[k][i][j]!="0")
			    allok=false;
		    }
			document.getElementById("table"+i+"td"+j).innerHTML="<a href='#' onclick='showWindow(\""+text+"\")'>"+count+"</a>";
			document.getElementById("table"+i+"td"+j).style.backgroundColor=(allok?"#99ff99":"ff9999");
		  }
		}
	  }
	  function showWindow(str){
	    document.getElementById("plist").innerText=str;
		document.getElementById('wind').style.display='';
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
	  <div id="plist" name="plist" style="font-size:smaller;">
	  </div>
	  <button type="button" onclick="document.getElementById('wind').style.display='none';">关闭</button>
	</div>
    <div id="people" name="people">
	</div>
	<div id="canvas" name="canvas">
	  <!--在这里添加内容...-->
	</div>
	<p style="font-size:smaller">表格内显示：该时间段，勾选的小伙伴中可行的人数。<br/>表格背景色：“特别关注”的小伙伴是否到齐。</p>
  </body>
</html>
<?php
  }else{
?>
<html>
  <head>
    <!--
	  这只是一个网页而已，你们千万不要拿它干不好的事情啊！
	  如果你萌玩SQL注入什么的，见鬼，我会用靴子狠狠地踢你们的屁股。
	  我发誓，我一定会这样做的！
	-->
    <title>
	  啊哟，密码...
	</title>
    <meta name="viewport" content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  </head>
  <body>
    <form action="show.php?tid=<?php echo $tid;?>" method="post">
	  <p>
	    <?php echo ($pass==''?'看看小伙伴们的时间？<br/>但是，主人设置了密码呀':'再试一次吧');?>
	  </p>
	  <p>密码：<input type="password" name="pass" id="pass"></p>
	  <br/>
	  <input type="submit" value="快让我看！"/>
	</form>
  </body>
</html>
<?php
  }
?>
