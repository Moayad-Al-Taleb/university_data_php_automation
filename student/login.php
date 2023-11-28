<?php
session_start();
$err = "";
if (isset($_POST['send'])) {

    $student_number = $_POST['student_number'];
    $pass = sha1($_POST['pass']);

    if (!empty($student_number) and !empty($pass)) {
        require '../connect.php';

        $sql = "SELECT * FROM students WHERE student_number = '$student_number' AND pass = '$pass'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);

            $_SESSION['id'] = $row['id'];
            $_SESSION['student_number'] = $row['student_number'];
            header("Location: pages/home_page.php");
        } else {
            $err =  "Login Failed.";
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
    <link rel="stylesheet" href="./styles/login.css">
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
            <form action="login.php" method="post">
                <div class="input-field">
                    <label for="student_number"> رقم الاكتتاب:</label>
                    <input type="text" name="student_number" id="student_number" required>
                </div>
                <div class="input-field">
                    <label for="pass"> كلمة المرور:</label>
                    <input type="password" name="pass" id="pass" required>
                </div>
                <input type="submit" value="دخول" name="send" class="submit-btn">
            </form>
        </div>
        <div class="img">
            <img src="../assets/imgs/1.PNG" alt="">
        </div>
    </div>
</body>

</html>