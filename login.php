<?php
$err_msg = "";
function check_account($status, $type)
{
    $err_msg = "";
    switch ($type) {
        case 0:
            header("Location: admin/pages/accounts_management.php");
            break;

        case 1:
            if ($status == 0) {
                header("Location: affairs officer/pages/students_management.php");
            } else {
                $err_msg =  "عذرا ولكن تم تجميد الحساب.";
            }
            break;

        case 2:
            if ($status == 0) {
                header("Location: Exams officer/pages/exams_marks.php");
            } else {
                $err_msg = "عذرا ولكن تم تجميد الحساب.";
            }
            break;
    }
    return $err_msg;
}

session_start();
$err = "";

if (isset($_POST['send'])) {

    $user_name = $_POST['user_name'];
    $pass = sha1($_POST['pass']);

    if (!empty($user_name) and !empty($pass)) {
        require 'connect.php';

        $sql = "SELECT * FROM admins WHERE user_name = '$user_name' AND pass = '$pass'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);

            $_SESSION['id'] = $row['id'];
            $_SESSION['user_name'] = $row['user_name'];
            $_SESSION['status'] = $row['status'];
            $_SESSION['type'] = $row['type'];
            $err = check_account($_SESSION['status'], $_SESSION['type']);
        } else {
            $err_msg = "يرجى التحقق من البيانات.";
        }

        mysqli_close($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./login.css">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Almarai:wght@300;400;700;800&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">
</head>

<body dir="rtl">
    <div class="container">
        <div class="form">
            <h3>تسجيل الدخول</h3>
            <?php echo $err ?>
            <?php echo $err_msg ?>
            <form action="login.php" method="post">
                <div class="input-field">
                    <label for="user_name">اسم المستخدم</label>
                    <input type="text" name="user_name" id="user_name" required>
                </div>

                <div class="input-field">
                    <label for="pass">كلمة المرور</label>
                    <input type="password" name="pass" id="pass" required>
                </div>

                <input type="submit" value="تسجيل" name="send" class="submit-btn">
            </form>
        </div>
        <div class="img">
            <img src="./assets/imgs/1.PNG" alt="">
        </div>
    </div>
</body>

</html>