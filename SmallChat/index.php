<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
	<title>С�ƹ�����ϵͳ</title>	
<meta name="keywords" content="(���õ�С�Ƽ�API)" 	/>
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
<!-- �������� -->
<h4 class="cr">��ã�����С�ƹ�����ϵͳ��������</h4>
<div class="br">
<table class="table table-hover" id="table">
  
</table>
</div>
<!-- �������� -->
<div class="">
	
		<input type="text" class="form-control" id="mesaid" value="" placeholder="��������Ϣ��" 	/>
		<button class="btn btn-default" onclick="send()">����</button>
		<button class="btn btn-danger" onclick="clear()">����</button>
	
</div>
    <footer>
        <br />
        <p class="cr">���ߣ�ben</p>
        <p>Email��benhuang1024@163.com</p>
    </footer>
</div>

<script type="text/javascript">
	function send(){
		var msg = $("#mesaid").val();
		if(msg != null){
            if(msg.indexOf("����") >= 0  ) {
                 $("table:last-child").append('<tr class="danger tar" ><td ><span class="cr">��:</span>'+msg+'</td></tr>');
                 $("#mesaid").val('');
                 $("table:last-child").append('<tr class="success" ><td ><span class="cr">С�ƹ�:</span>ŶŶ</td></tr>');
            }else if( msg.indexOf("����") >= 0  ){
                  $("table:last-child").append('<tr class="danger tar" ><td ><span class="cr">��:</span>'+msg+'</td></tr>');
                 $("#mesaid").val('');
                var xhgsay;
                var xhgsays = new Array();
				xhgsays[0] = '��';
                xhgsays[1] = '��';
                xhgsays[2] = '�õ�';
                xhgsays[3] = 'ôô��';
                xhgsays[4] = '���Ǵ�˧��';
                xhgsays[5] = '����˧�����';
                xhgsays[6] = '�õ�';
                xhgsays[7] = 'ôô��';
                xhgsays[8] = '���Ǵ�˧��';
                xhgsays[9] = '����˧�����';
                xhgsays[10] = '��';
                xhgsay = xhgsays[Math.ceil(Math.random()*10)];
                 $("table:last-child").append('<tr class="success" ><td ><span class="cr">С�ƹ�:</span>'+xhgsay+'</td></tr>');
            
            }else{            
                $("table:last-child").append('<tr class="danger tar" ><td ><span class="cr">��:</span>'+msg+'</td></tr>');
                $("#mesaid").val();
                $.ajax({type:'GET',async:false,url:"http://xiaohuangguaChat.sinaapp.com/chat.php",data:{parm:msg},success:function(msg){$("table:last-child").append('<tr class="success" ><td ><span class="cr">С�ƹ�:</span>'+msg+'</td></tr>');},error:function(){}
                });		
            }
		}else{
			alert('��������Ϣ��');
		}
		
	}
	function clear(){
		$("#mesaid").val('');
	}
</script>
</body>
    <script type="text/javascript" src="http://tajs.qq.com/stats?sId=38504063" charset="UTF-8"></script>
</html>