<?php
//包含一个文件上传类中的上传类
include "./lib/fileupload.php";


$up = new fileupload;
$up -> set("path", "./excel/");
$up -> set("maxsize", 2000000);
$up -> set("allowtype", array("jpg","xlsx"));
$up -> set("israndname", false);

//使用对象中的upload方法， 就可以上传文件， 方法需要传一个上传表单的名子 pic, 如果成功返回true, 失败返回false
if($up -> upload("pic")) {
    echo '<pre>';
    //获取上传后文件名子
    var_dump($up->getFileName());
    echo '</pre>';

} else {
    echo '<pre>';
    //获取上传失败以后的错误提示
    var_dump($up->getErrorMsg());
    echo '</pre>';
}
?>

<!--开始处理excel-->
<?php
    require_once ("./get_excel.php");
    $base_url = "http://localhost/excel_manager/form/form.php";
    //读取excel
    $sheetdata = readFromExcel("./excel/form.xlsx");
//    var_dump($sheetdata);
    foreach ($sheetdata as $data_index => $data_item) {
        $lost_column = "";
        $has_empty = false;
        $mail = $data_item['E'];//可改
        foreach ($data_item as $attr_index => $attr_item) {
            if (is_null($attr_item)) {
//                echo "空 ";
                $lost_column = $lost_column.$attr_index.",";
                $has_empty = true;
            } else {
//                echo $attr_item . " ";
            }

        }
        if($has_empty){
            $lost_column = substr($lost_column,0,-1);
            //存入数据库

            $obj = insertRecord($data_index,$lost_column,'',getRandChar(7));
            if(!is_null($obj)){
                if(!is_null($mail)){
                    $id = $obj['id'];
                    $token = $obj['token'];
                    $tmpURL = $base_url."?lost_column=$lost_column&id=$id&token=$token";
                    echo $mail.",".$tmpURL;
                    sendMail($mail,"表单还没写好呀","<a href='http://www.baidu.com'>teset</a>Please fill your information at $tmpURL");
                    addSentTimes($id);
                }else{
                    var_dump($data_item);
                    echo "</br>";
                    echo "此人没有输入邮箱\n";
                }
            }


        }

//        echo "<br>";
    }
?>