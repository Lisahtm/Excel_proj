<?php

error_reporting(E_ALL);
set_time_limit(0);

date_default_timezone_set('Asia/Shanghai');
?>


<?php

require("lib/PHPMailerAutoload.php");
/** PHPExcel_IOFactory */
include 'lib/PHPExcel/IOFactory.php';


function readFromExcel($name)
{
    $inputFileName =  $name;

    $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);

    $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);

    //var_dump($sheetData);
    return $sheetData;
}

function writeByIndex($name,$row,$column,$data){
    $inputFileName = './' . $name . '.xlsx';

    $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
    $objPHPExcel->getActiveSheet()
                ->setCellValue($row.$column, $data);

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('./'.$name.'.xlsx');
}
//拆分字符串后，写入
function write($name,$seq,$lost_column,$lost_content){
    $column_list = explode(",",$lost_column);
    $content_list = explode(",",$lost_content);
    foreach ($column_list as $index=>$column_index){
        writeByIndex($name,$column_index,$seq,$content_list[$index]);
    }
}

function sendMail($tomail, $title, $content)
{
    $mail = new PHPMailer();
    $mail->IsSMTP();                  // send via SMTP
    $mail->Host = "smtp.163.com";   // SMTP servers
    $mail->SMTPAuth = true;           // turn on SMTP authentication
    $mail->Username = "hangtianmenglisa";     // SMTP username  注意：普通邮件认证不需要加 @域名
    $mail->Password = "lzlugggohnbxqgmn"; // SMTP password
    $mail->From = "hangtianmenglisa@163.com";      // 发件人邮箱
    $mail->FromName =  "Lisa";  // 发件人

    $mail->CharSet = "UTF-8";   // 这里指定字符集！
    $mail->Encoding = "base64";
    $mail->AddAddress($tomail,"name");  // 收件人邮箱和姓名
    $mail->IsHTML(true);  // send as HTML
    // 邮件主题
    $mail->Subject = $title;
    // 邮件内容
    $mail->Body = "<html><body>".$content."</body></html>";
    $mail->AltBody ="text/html";
    if(!$mail->Send())
    {
        echo "邮件发送有误 <p>";
        echo "邮件错误信息: " . $mail->ErrorInfo;
        exit;
    }
    else {
        echo " 邮件成功发送给",$tomail,"<br />";
    }

}

//表单
function updateInfo(){

}
function getRandChar($length){
    $str = null;
    $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
    $max = strlen($strPol)-1;

    for($i=0;$i<$length;$i++){
        $str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
    }

    return $str;
}
// search from database
function searchFromDB(){

    $link = mysqli_connect('localhost','root','','excel_db');
    if (!$link){
        printf("不能连接到MySQL. 错误代码: %sn", mysqli_connect_error());
        exit;
    }

    if ($result = mysqli_query($link, 'SELECT id, lost_content FROM lost_table LIMIT 5')){

        while( $row = mysqli_fetch_assoc($result) ){
            echo $row['id'].$row['lost_content'].'<br>';
        }
        mysqli_free_result($result);//注销结果集。释放内存
    }
    mysqli_close($link); //关闭连接

}
//返回一条完整记录
function insertRecord($seq,$lost_column,$lost_content,$token){
    $link = mysqli_connect('localhost','root','','excel_db');
    if (!$link){
        printf("不能连接到MySQL. 错误代码: %sn", mysqli_connect_error());
        exit;
    }
    if(mysqli_query($link, "INSERT into lost_table(seq,lost_column,lost_content,token) values('$seq','$lost_column','$lost_content','$token')")){
        $newId = mysqli_insert_id($link);
        echo "id=",$newId,"插入记录成功<br />";
        if($obj = mysqli_query($link,"SELECT * FROM lost_table where id=$newId")){
            $result = mysqli_fetch_array($obj);
        }else{
            echo "查询新插入数据失败\n";
            $result = null;
        }
        mysqli_close($link);
        return $result;

    }else{
        printf("插入新记录失败！\n");

    }
    mysqli_close($link); //关闭连接
    return null;
}
//邮件发送次数增加
function addSentTimes($id){
    $link = mysqli_connect('localhost','root','','excel_db');
    if (!$link){
        printf("不能连接到MySQL. 错误代码: %sn", mysqli_connect_error());
        exit;
    }
    if(mysqli_query($link, "UPDATE lost_table SET send_times=send_times+1 WHERE id='$id'")){
        echo "times add successfully"."</br>";
    }else{
        echo "times add failed"."</br>";
    }
    mysqli_close($link); //关闭连接
}
//用户已提交信息
function hasUpdated($id){
    $link = mysqli_connect('localhost','root','','excel_db');
    if (!$link){
        printf("不能连接到MySQL. 错误代码: %sn", mysqli_connect_error());
        exit;
    }
    if(mysqli_query($link, "UPDATE lost_table SET is_update=1 WHERE id='$id'")){
        echo "update add successfully"."</br>";
    }else{
        echo "update add failed"."</br>";
    }
    mysqli_close($link); //关闭连接
}
//返回的结果是否和原来的token匹配
function isTokenMatched($id,$token){
    $link = mysqli_connect('localhost','root','','excel_db');
    if (!$link){
        printf("不能连接到MySQL. 错误代码: %sn", mysqli_connect_error());
        exit;
    }

    if($result = mysqli_query($link, "SELECT token FROM lost_table WHERE id='$id'")){
        $token_search = mysqli_fetch_array($result)['token'];
        if($token_search == $token){
            return true;
        }else{
            return false;
        }

    }else{
        return false;
    }


    mysqli_close($link); //关闭连接
}

//write("hello",2,"A,C,D","HiA,hiC,hiD");
//insertRecord(3,"3,5","aligado,gozayi",getRandChar(7));
//isTokenMatched(2,"efe");
//searchFromDB();
//writeToExcel("hello","C",1,"lisa@C1");
//sendMail("hangtianmenglisa@gmail.com", "快点修改", "这只是测试");
//$sheetdata = readFromExcel("hello");
//var_dump($sheetdata);
//foreach ($sheetdata as $data_index => $data_item) {
//    foreach ($data_item as $attr_index => $attr_item) {
//        if (is_null($attr_item)) {
//            echo "空 ";
//        } else {
//            echo $attr_index.$attr_item . " ";
//        }
//
//    }
//    echo "<br>";
//}


?>
