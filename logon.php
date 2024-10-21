<?php
if(isset($_POST["account"]))
{
    require_once("dbtools.inc.php");

    //取得使用者登入的帳號與密碼
    $login_user=$_POST["account"];
    $login_password=$_POST["password"];

    //建立資料連結
    $link=create_connection();
    //驗證帳號與密碼是否正確
    $sql="SELECT `account`,`name` FROM user WHERE account='$login_user' 
        AND password='$login_password'";
    $result=executed_sql($link,"album",$sql);

    //若沒找到資料，表示帳號與密碼錯誤
    if(mysqli_num_rows($result)==0)
    {
        mysqli_free_result($result);
        mysqli_close($link);

        //顯示訊息要求使用者輸入正確的帳號與密碼
        echo "<script type='text/javascript'>alert('帳號密碼錯誤，請查明後再登入')</script>";
    }
    else
    {
        //將使用者資料儲存在_Session
        session_start();
        $row=mysqli_fetch_object($result);
        $_SESSION["login_user"]=$row->account;
        $_SESSION["login_name"]=$row->name;

        mysqli_free_result($result);

        mysqli_close($link);

        header("location:index.php");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>電子相簿</title>
</head>
<body>
    <p align="center"><img src="Title.jpg"></p>
    <form action="logon.php" name="myForm" method="post">
        <table align="center">
            <tr>
                <td>
                    帳號:
                </td>
                <td>
                    <input type="text" name="account" size="15">
                </td>
            </tr>
            <tr>
                <td>
                    密碼:
                </td>
                <td>
                    <input type="password" name="password" size="15">
                </td>
            </tr>
            <tr>
                <td align="center" colspan="2">
                    <input type="submit" value="登入">
                    <input type="reset" value="重填">
                </td>
            </tr>
        </table>
    </form>   
</body>
</html>