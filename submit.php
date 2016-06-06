<?php
    require_once ("get_excel.php");

    $link = mysqli_connect('localhost','root','','excel_db');
    if (!$link){
        printf("不能连接到MySQL. 错误代码: %sn", mysqli_connect_error());
        exit;
    }
    $id=$_POST['id'];
    $token = $_POST['token'];
    if(!empty($id)&&!empty($token)){
        if($result = mysqli_query($link, "SELECT * FROM lost_table WHERE id=$id")){
            $obj = mysqli_fetch_array($result);
            $seq = $obj['seq'];
            if($token == $obj['token']){
                $lost_list = explode(",",$obj['lost_column']);
                $column_val = "";
                foreach ($lost_list as $index=>$lost_item){

                    $column_val = $column_val.$_POST["col-$lost_item"];

                    if($index < count($lost_list)-1){
                        $column_val=$column_val.",";
                    }
                }
                //update excel
                write("./excel/form.xlsx",$seq,$obj["lost_column"],$column_val);

                if(mysqli_query($link, "UPDATE lost_table SET lost_content='$column_val' WHERE id='$id'")){
                    echo "UPDATE successfully";
                }else{
                    echo "UPDATE failed";
                }
            }else{
                echo "TOKEN ERROR!\n";
            }
        }
    }
