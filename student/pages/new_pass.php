<?php
session_start();
include "../includes/header.php";
if (isset($_POST['send'])) {
    $id = $_SESSION['id'];
    $pass = sha1($_POST['pass']);

    if (!empty($pass)) {
        require "../../connect.php";
        $sql = "UPDATE students SET pass = '$pass' WHERE id = '$id'";

        if (mysqli_query($conn, $sql)) {
?>
            <div class="container" style="margin: 16px 0;">
                <div class="alert alert-success" role="alert">
                    تم التعديل بنجاح سيتم تسجيل خروجك
                </div>
            </div>
<?php
            header("refresh:2;url=../logout.php");
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }

        mysqli_close($conn);
    }
}
?>
<div class="container mt-4" style="min-height: 55vh;">
    <form action="" method="post" class="searchForm">
        <div class="form-group mb-2" style="max-width: 400px;">
            <label for="pass">كلمة المرور الجديدة:</label>
            <input type="pass" id="pass" name="pass" class="form-control">
        </div>
        <button type="submit" class="btn btn-secondary" name="send">تغيير</button>
    </form>
</div>
<?php include "../includes/footer.php" ?>