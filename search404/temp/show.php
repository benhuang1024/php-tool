<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
    <link rel="stylesheet" href="http://cdn.bootcss.com/bootstrap/3.2.0/css/bootstrap.min.css">
</head>
<body>
<table>
    <tr>
        <th>链接</th>
        <th>错误</th>
    </tr>
    <?php
    foreach($start_all as $key=>$val) {
        ?>
        <tr>
            <td><?php echo $key;?></td>
            <td>
                <table>
                    <tr></tr>
                    <?php
                    if(!empty($val)){foreach($val as $k=>$v){
                        ?>
                        <tr><td>
                                <?php
                                echo $v['url'];
                                ?>
                            </td><td>
                                <?php
                                echo $v['http_code'];
                                ?>
                            </td></tr>
                    <?php }} ?>
                </table>
            </td>
        </tr>
        <?php
    }
    ?>
</table>
</body>
</html>