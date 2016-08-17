<!DOCTYPE html>
<html>
<head>
	<title></title>
	<meta charset="utf-8" />
	<link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.2.0/css/bootstrap.min.css">
</head>
<body>
<h4>竞价排名查询</h4>
<h5 class="text-primary">关键词总数:<?php echo $keyword_con; ?></h5>
<div>
	<span>功能按钮:</span><button id="showThis" class="btn btn-default">显示已有排名</button>
</div>
<br>
<table class="table table-hover">
	<tr>
		<th>ID</th>
		<th>关键词</th>
		<th>推广链接</th>
		<th>排名</th>
		<th>详情</th>
	</tr>
	<?php
	if(!empty($rankVal_arr)){
		foreach($rankVal_arr as $rKey=>$rVal){
			if(1==@$pc){
				if('no' != $rVal['rank']){
					echo '<tr class="success">';
				}else{
					echo '<tr class="active">';
				}
			}else{
				switch ($rVal['rank']){
					case "1":
						echo '<tr class="success">';
						break;
					case "2":
						echo '<tr class="info">';
						break;
					case "3":
						echo '<tr class="warning">';
						break;
					case "4":
						echo '<tr class="warning" >';
						break;
					case "5":
						echo '<tr class="warning" >';
						break;
					case "6":
						echo '<tr class="warning" >';
						break;
					case "7":
						echo '<tr class="warning" >';
						break;
					case "8":
						echo '<tr class="warning" >';
						break;
					case "9":
						echo '<tr class="warning" >';
						break;
					case "10":
						echo '<tr class="warning" >';
						break;
					default:
						if('无' == $rVal['url']){
							echo '<tr class="danger" >';
						}else{
							echo '<tr class="active">';
						}

				}
			}
			$key = $rKey+1;
			echo '<td>'.$key.'</td>';
			echo '<td>'.$rVal['keyword'].'</td>';
			echo '<td>'.$rVal['url'].'</td>';
			echo '<td>'.$rVal['rank'].'</td>';
			echo '<td ><button data-keyword="'.$rVal['keyword'].'" class="btn btn-defalut searchthis">查看</button></td>';
			echo '</tr>';
		}
	}
	?>
</table>


<!-- Modal -->
<d1iv class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close closethis" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">详情</h4>
			</div>
			<div class="modal-body">
				<h4>关键词:<span id="keyword"></span></h4>
				<h5>平均排名:&nbsp;&nbsp;<span id="average"></span></h5>
				<h5>展现最多:&nbsp;&nbsp;<span id="maxUrl"></span></h5>
			</div>
			<div class="modal-footer">
				<button  type="button" class="btn btn-default closethis" data-dismiss="modal">Close</button>
				<!--						<button type="button" class="btn btn-primary">Save changes</button>-->
			</div>
		</div>
	</div>
</d1iv>
<script src= "http://libs.useso.com/js/jquery/2.1.0/jquery.min.js" type ="text/javascript" charset= "utf-8" ></script>
<script src="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script type="text/javascript">
	$(".searchthis").click(function(){
		var keyword = $(this).attr("data-keyword");
		$.ajax({
			type:"post",
			async:false,
			url:"searchThis.php",
			data:{keyword:keyword},
			success:function(val){
				$("#myModal").slideDown('slow');
				$("#myModal").addClass("in");
				var objVal = jQuery.parseJSON(val);
				$("#keyword").text(keyword);
				$("#maxUrl").text(objVal.maxUrl);
				$("#average").text(objVal.average);
			},
			error:function(){
				alert('加载失败');
			}
		});
	});
	$(".closethis").click(function(){
		$("#myModal").slideUp();
	});

	//show this
	$("#showThis").click(function(){
		$(".active").toggle();
		if('显示已有排名' == $("#showThis").html()) {
			$("#showThis").html('显示全部');
		}else{
			$("#showThis").html('显示已有排名');
		}
	});
</script>
</body>
</html>