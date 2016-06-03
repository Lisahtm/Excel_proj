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
    $inputFileName = './' . $name . '.xlsx';

    $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);

    $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);

    //var_dump($sheetData);
    return $sheetData;
}

function writeToExcel($name,$row,$column,$data){
    $inputFileName = './' . $name . '.xlsx';

    $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
    $objPHPExcel->getActiveSheet()
                ->setCellValue($row.$column, $data);

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('./'.$name.'.xlsx');
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

    $mail->CharSet = "GB2312";   // 这里指定字符集！
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

writeToExcel("hello","C",1,"lisa@C1");
//sendMail("383015311@qq.com", "lisa title", "lisa content");
$sheetdata = readFromExcel("hello");
foreach ($sheetdata as $data_index => $data_item) {
    foreach ($data_item as $attr_index => $attr_item) {
        if (is_null($attr_item)) {
            echo "空 ";
        } else {
            echo $attr_item . " ";
        }

    }
    echo "<br>";
}


?>
