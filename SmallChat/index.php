<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
	<title>小黄瓜聊天系统</title>	
<meta name="keywords" content="(调用的小黄鸡API)" 	/>
<meta name="author" content="ben" 	/>    
<meta name="email" content="benhuang1024@163.com" 	/>   
<link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.2.0/css/bootstrap.min.css">
<script src="http://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
<script src="http://cdn.bootcss.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<style type="text/css">
.br{border:solid 1px red;min-height: 521px;margin-bottom:20px}
.cr{color:red;}
.btn .btn-default{margin-right:20px}
.table{min-height: 20px}
.table tr{height: 20px}
.table tr td{height: 20px}
.fr{float: right}
.tar{text-align: right}
.dn{display: none}
#mesaid{margin-bottom:20px}
</style>
</head>
<body>
<div class="container">
<!-- 聊天区域 -->
<h4 class="cr">你好，我是小黄瓜聊天系统，萌萌哒</h4>
<div class="br">
<table class="table table-hover" id="table">
  
</table>
</div>
<!-- 输入区域 -->
<div class="">
	
		<input type="text" class="form-control" id="mesaid" value="" placeholder="请输入信息！" 	/>
		<button class="btn btn-default" onclick="send()">发送</button>
		<button class="btn btn-danger" onclick="clear()">重置</button>
	
</div>
    <footer>
        <br />
        <p class="cr">作者：ben</p>
        <p>Email：benhuang1024@163.com</p>
    </footer>
</div>

<script type="text/javascript">
	function send(){
		var msg = $("#mesaid").val();
		if(msg != null){
            if(msg.indexOf("笨笨") >= 0  ) {
                 $("table:last-child").append('<tr class="danger tar" ><td ><span class="cr">你:</span>'+msg+'</td></tr>');
                 $("#mesaid").val('');
                 $("table:last-child").append('<tr class="success" ><td ><span class="cr">小黄瓜:</span>哦哦</td></tr>');
            }else if( msg.indexOf("月月") >= 0  ){
                  $("table:last-child").append('<tr class="danger tar" ><td ><span class="cr">你:</span>'+msg+'</td></tr>');
                 $("#mesaid").val('');
                var xhgsay;
                var xhgsays = new Array();
				xhgsays[0] = '在';
                xhgsays[1] = '恩';
                xhgsays[2] = '好的';
                xhgsays[3] = '么么哒';
                xhgsays[4] = '我是大帅哥';
                xhgsays[5] = '喊本帅哥干嘛';
                xhgsays[6] = '好的';
                xhgsays[7] = '么么哒';
                xhgsays[8] = '我是大帅哥';
                xhgsays[9] = '喊本帅哥干嘛';
                xhgsays[10] = '恩';
                xhgsay = xhgsays[Math.ceil(Math.random()*10)];
                 $("table:last-child").append('<tr class="success" ><td ><span class="cr">小黄瓜:</span>'+xhgsay+'</td></tr>');
            
            }else{            
                $("table:last-child").append('<tr class="danger tar" ><td ><span class="cr">你:</span>'+msg+'</td></tr>');
                $("#mesaid").val();
                $.ajax({type:'GET',async:false,url:"http://xiaohuangguaChat.sinaapp.com/chat.php",data:{parm:msg},success:function(msg){$("table:last-child").append('<tr class="success" ><td ><span class="cr">小黄瓜:</span>'+msg+'</td></tr>');},error:function(){}
                });		
            }
		}else{
			alert('请输入信息！');
		}
		
	}
	function clear(){
		$("#mesaid").val('');
	}
</script>
</body>
    <script type="text/javascript" src="http://tajs.qq.com/stats?sId=38504063" charset="UTF-8"></script>
</html>