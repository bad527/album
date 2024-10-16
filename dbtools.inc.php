<?php
function create_connection(){
    $link=mysqli_connect("localhost","root","","album")or die("無法連線".mysqli_connect_error());
    mysqli_query($link,"SET NAMES 'utf8'");
    return $link;
}
function executed_sql($link,$database,$sql){
    mysqli_select_db($link,$database)or die("無法連結資料庫".mysqli_error($link));
    $result=mysqli_query($link,$sql);
    return $result;
}
?>