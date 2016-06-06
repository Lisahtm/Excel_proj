<?php
/**
 * Created by PhpStorm.
 * User: Lisa
 * Date: 2016/6/5
 * Time: 18:03
 */
?>
<form action="upload.php" method="post" enctype="multipart/form-data" >
    name: <input type="text" name="username" value="" /><br>
    <input type="hidden" name="MAX_FILE_SIZE" value="10485760000" />
    up pic: <input type="file" name="pic[]" value=""><br>

    <input type="submit" value="开始处理" /><br>

</form>
