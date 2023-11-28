<?php
$page_title = "ادارة الحسابات";
include "../includes/header.php";
session_start();

if (isset($_SESSION['id']) and $_SESSION['type'] == 0) {
    //home //add //edit
    $section = isset($_GET['section']) ? $_GET['section'] : "home";

    if ($section == "home") {
        require '../../connect.php';

        $sql = "SELECT * FROM admins WHERE type != 0";
        $result = mysqli_query($conn, $sql);
?>

        <div class="container">
            <div class="d-flex w-100 mt-4 justify-content-between align-items-center">
                <a class="btn btn-primary" href="?section=add">اضافة حساب جديد</a>
            </div>
            <?php
            if (mysqli_num_rows($result) > 0) {
            ?>
                <div class="table-responsive my-3">
                    <table class="table">
                        <thead class="table-primary">
                            <th scope="col">#</th>
                            <th scope="col">اسم المستخدم</th>
                            <th scope="col">نوع الحساب</th>
                            <th scope="col">حالة الحساب</th>
                            <th scope="col">الخيارات</th>
                        </thead>
                        <tbody>
                            <?php

                            while ($row = mysqli_fetch_assoc($result)) {

                            ?>
                                <tr>
                                    <td>#</td>
                                    <td><?php echo $row['user_name'] ?></td>
                                    <td><?php echo ($row['type'] == 1) ? "شؤون طلاب" : "موظف امتحانات"; ?></td>
                                    <td><?php echo ($row['status'] == 0) ? "فعال" : "غير فعال"; ?></td>
                                    <td>
                                        <?php
                                        if ($row['status'] == 0) {
                                        ?>
                                            <a href="?section=inactive&&id=<?php echo $row['id'] ?>" class="btn btn-warning">الغاء التفعيل</a>
                                        <?php
                                        } else {
                                        ?>
                                            <a href="?section=active&&id=<?php echo $row['id'] ?>" class="btn btn-warning">تفعيل</a>
                                        <?php
                                        }
                                        ?>
                                        <a href="?section=edit&&id=<?php echo $row['id'] ?>" class="btn btn-info">تعديل</a>
                                        <a href="?section=destroy&&id=<?php echo $row['id'] ?>" class="btn btn-danger">حذف</a>
                                    </td>
                                </tr>
                            <?php
                            }

                            ?>
                        </tbody>
                    </table>
                </div>
            <?php
            }
            ?>
        </div>
        <?php
    } elseif ($section == "add") {
        if (isset($_POST['send'])) {

            $user_name = $_POST['user_name'];
            $pass = sha1($_POST['pass']);
            $type = $_POST['type'];

            if (!empty($user_name) and !empty($pass) and !empty($type)) {
                require '../../connect.php';

                $sql = "SELECT * FROM admins WHERE user_name = '$user_name'";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) == 1) {
                    echo "The entered name is already in use.";
                } else {
                    $sql = "INSERT INTO admins (user_name, pass, type) VALUES ('$user_name', '$pass', '$type')";

                    if (mysqli_query($conn, $sql)) {
        ?>
                        <div class="container" style="margin: 16px 0;">
                            <div class="alert alert-success" role="alert">
                                تم الاضافة بنجاح
                            </div>
                        </div>
        <?php
                        header("refresh:2;url=accounts_management.php");
                    } else {
                        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                    }
                }

                mysqli_close($conn);
            }
        }
        ?>

        <div class="container">
            <div class="d-flex w-100 mt-4 justify-content-between align-items-center">
                <h4>انشاء حساب جديد</h4>
            </div>
            <form action="?section=add" method="post" style="width: 100%; max-width: 450px;" class="mt-3 d-flex flex-column gap-2">
                <div class="form-group">
                    <label for="user_name">اسم المستخدم:</label>
                    <input type="text" class="form-control" name="user_name" id="user_name" required>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">كلمة المرور:</label>
                    <input type="password" class="form-control" name="pass" id="pass" required>
                </div>
                <div class="form-group">
                    <label for="type">تحديد نوع الحساب</label>
                    <select class="form-control" name="type" id="type" required>
                        <option value="">يرجى الاختيار</option>
                        <option value="1">حساب شؤون طلاب</option>
                        <option value="2">حساب موظف امتحانات</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" name="send">اضافة</button>
            </form>
        </div>

        <?php
    } elseif ($section == "edit") {
        $id = intval($_GET['id']);

        require '../../connect.php';

        $sql = "SELECT * FROM admins WHERE id = '$id'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        mysqli_close($conn);

        if (isset($_POST['send'])) {

            $user_name = $_POST['user_name'];
            $pass = $_POST['pass'];

            if (!empty($user_name) and !empty($pass)) {
                require '../../connect.php';

                $sql = "SELECT * FROM admins WHERE user_name = '$user_name' AND id != '$id'";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) == 1) {
                    echo "The entered name is already in use.";
                } else {
                    $sql = "UPDATE admins SET user_name = '$user_name', pass = '$pass' WHERE id = '$id'";

                    if (mysqli_query($conn, $sql)) {
        ?>
                        <div class="container" style="margin: 16px 0;">
                            <div class="alert alert-success" role="alert">
                                تم التعديل بنجاح
                            </div>
                        </div>
        <?php
                        header("refresh:2;url=accounts_management.php");
                    } else {
                        echo "Error updating record: " . mysqli_error($conn);
                    }
                }

                mysqli_close($conn);
            }
        }
        ?>
        <div class="container">
            <div class="d-flex w-100 mt-4 justify-content-between align-items-center">
                <h4>تعديل بيانات الحساب</h4>
            </div>
            <form action="?section=edit&&id=<?php echo $id ?>" method="post" style="width: 100%; max-width: 450px;" class="mt-3 d-flex flex-column gap-2">
                <div class="form-group">
                    <label for="user_name">اسم المستخدم:</label>
                    <input type="text" class="form-control" name="user_name" id="user_name" required value="<?php echo $row['user_name'] ?>">
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">كلمة المرور:</label>
                    <input type="password" class="form-control" name="pass" id="pass" required>
                </div>
                <button type="submit" class="btn btn-primary" name="send">تعديل</button>
            </form>
        </div>

        <?php
    } elseif ($section == "destroy") {
        $id = intval($_GET['id']);

        require '../../connect.php';

        $sql = "DELETE FROM admins WHERE id = '$id'";

        if (mysqli_query($conn, $sql)) {
        ?>
            <div class="container" style="margin: 16px 0;">
                <div class="alert alert-success" role="alert">
                    تم الحذف بنجاح
                </div>
            </div>
        <?php
            header("refresh:2;url=accounts_management.php");
        } else {
            echo "Error deleting record: " . mysqli_error($conn);
        }

        mysqli_close($conn);
    } elseif ($section == "active") {
        $id = intval($_GET['id']);

        require '../../connect.php';

        $sql = "UPDATE admins SET status = 0 WHERE id = '$id'";

        if (mysqli_query($conn, $sql)) {
        ?>
            <div class="container" style="margin: 16px 0;">
                <div class="alert alert-success" role="alert">
                    تم تعديل الحالة بنجاح
                </div>
            </div>
        <?php
            header("refresh:2;url=accounts_management.php");
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }

        mysqli_close($conn);
    } elseif ($section == "inactive") {
        $id = intval($_GET['id']);

        require '../../connect.php';

        $sql = "UPDATE admins SET status = 1 WHERE id = '$id'";

        if (mysqli_query($conn, $sql)) {
        ?>
            <div class="container" style="margin: 16px 0;">
                <div class="alert alert-success" role="alert">
                    تم تعديل الحالة بنجاح
                </div>
            </div>
    <?php
            header("refresh:2;url=accounts_management.php");
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }

        mysqli_close($conn);
    }
} else {
    ?>
    <div class="container" style="margin: 16px 0;">
        <div class="alert alert-danger" role="alert">
            دخول غير مسموح به
        </div>
    </div>
<?php
}

?>


<?php include "../includes/footer.php" ?>