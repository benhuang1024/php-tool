<!DOCTYPE HTML>
<html dir="ltr" lang="zh-CN">
<head>
<meta charset='utf8' />
<title>用户登录</title>
<link href="./css/main.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="login">
	<div class="menus">
		<div class="public"></div>
	</div>
	<div class="box png">
		<form action="./benhuang.php" method="post">
			<input type="hidden" name="action" value="login" />
			<div class="header">
				<span class="alt">用户登录</span>
			</div>
			<ul>
				<li><label for='user'>用户名</label><input id="user" name="user" type="text" class="text" value="" /></li>
				<li><label for='pass'>密    码</label><input id="pass" name="pass" type="password" class="text" value="" /></li>
				
				<li class="submits">
					<input class="submit" type="submit" value="登录" />
					<input class="submit" type="reset" value="重置"  />
				<li/>
			</ul>
		</form>
	</div>
	<div class="air-balloon ab-1 png"></div>
	<div class="air-balloon ab-2 png"></div>
	<div class="footer"></div>
</div>
<script type="text/javascript" src="./js/jQuery.js"></script>
<script type="text/javascript" src="./js/fun.base.js"></script>
<script type="text/javascript" src="./js/login.js"></script>
</body>
</html>