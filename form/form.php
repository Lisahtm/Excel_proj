<?php
    header("Last-Modified:".gmdate("D,d M Y H:i:s")."GMT");
    header("Cache-control:no-cache,must-revalidate");
?>
<!DOCTYPE html>
<html>
<head>
    <title>LISA</title>
    <link rel="stylesheet" type="text/css" href="./css/bootstrap.css">
    <script type="text/javascript" src="./js/jquery.min.js"></script>
    <script type="text/javascript" src="./js/bootstrap.min.js"></script>
    <script type="text/javascript" src="./js/bootstrap3-validation.js"></script>
    <script type="text/javascript" src="./js/jquery.cityselect.js"></script>
    <script type="text/javascript" src="./js/school.min.js"></script>
</head>
<body>
<h1 class="text-center">表单</h1>
<form class="form-horizontal" role="form" id="J-form" action="../submit.php" method="POST">
    <div id="J-generate_wrapper"></div>

    <div id="province_manager" class="form-group">
        <label class="col-sm-2 control-label">地区</label>
        <div class="col-sm-2">
            <select class="prov form-control"></select>
        </div>
        <div class="col-sm-2">
            <select class="city form-control" disabled="disabled"></select>
        </div>
        <div class="col-sm-2">
            <select class="school form-control" id="J-school" ></select>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-default">提交</button>
        </div>
    </div>
</form>
    <script src="./js/main.js"></script>
</body>
</html>






