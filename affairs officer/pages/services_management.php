<?php
$page_title = "ادارة الخدمات";
include "../includes/header.php";
session_start();

if (isset($_SESSION['id']) and isset($_SESSION['type']) and $_SESSION['type'] == 1) {
    if ($_SESSION['status'] == 0) {

        $section = (isset($_GET['section'])) ? $_GET['section'] : "home";

        if ($section == "home") {
            require '../../connect.php';

            $sql = "SELECT * FROM services";
            $result = mysqli_query($conn, $sql);
?>

            <div class="container">
                <div class="d-flex w-100 mt-4 justify-content-between align-items-center">
                    <a class="btn btn-primary" href="?section=add">اضافة خدمة جديدة</a>
                </div>
                <?php
                if (mysqli_num_rows($result) > 0) {
                ?>
                    <div class="table-responsive my-3">
                        <table class="table">
                            <thead class="table-primary">
                                <th scope="col">#</th>
                                <th scope="col">الاسم</th>
                                <th style="width:45%" scope="col">التفاصيل</th>
                                <th scope="col">الحالة</th>
                                <th scope="col">الخيارات</th>
                            </thead>
                            <tbody>
                                <?php

                                while ($row = mysqli_fetch_assoc($result)) {

                                ?>
                                    <tr>
                                        <td>#</td>
                                        <td><?php echo $row['name'] ?></td>
                                        <td style="width:45%"><?php echo $row['description'] ?></td>
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
                } else {
                ?>
                    <div style="margin: 16px 0;">
                        <div class="alert alert-warning" role="alert">
                            لايوجد بيانات لعرضها
                        </div>
                    </div>
                <?php
                }
                mysqli_close($conn);
                ?>

            </div>
            <?php
        } elseif ($section == "add") {
            if (isset($_POST['send'])) {

                $name = $_POST['name'];
                $description = $_POST['description'];
                $employee_id = $_SESSION['id'];

                if (!empty($name) and !empty($description) and !empty($employee_id)) {
                    require '../../connect.php';

                    $sql = "SELECT * FROM services WHERE name = '$name'";
                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result) == 1) {
            ?>
                        <div class="container" style="margin: 16px 0;">
                            <div class="alert alert-danger" role="alert">
                                اسم الخدمة مأخوذ مسبقا
                            </div>
                        </div>
                        <?php
                    } else {
                        $sql = "INSERT INTO services (name, description, employee_id) VALUES ('$name', '$description', '$employee_id')";

                        if (mysqli_query($conn, $sql)) {
                        ?>
                            <div class="container" style="margin: 16px 0;">
                                <div class="alert alert-success" role="alert">
                                    تم الاضافة بنجاح
                                </div>
                            </div>
            <?php
                            header("refresh:2;url=services_management.php");
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
                    <h4>اضافة خدمة جديدة</h4>
                </div>
                <form action="?section=add" method="post" style="width: 100%; max-width: 450px;" class="mt-3 d-flex flex-column gap-2">
                    <div class="form-group">
                        <label for="name">الاسم:</label>
                        <input type="text" class="form-control" name="name" id="name" required>
                    </div>
                    <div class="form-group">
                        <label for="description">تفاصيل الخدمة</label>
                        <textarea class="form-control" name="description" id="description" required rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" name="send">اضافة</button>
                </form>
            </div>

            <?php
        } elseif ($section == "edit") {
            $id = intval(($_GET['id']));

            require '../../connect.php';

            $sql = "SELECT * FROM services WHERE id = '$id'";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            mysqli_close($conn);

            if (isset($_POST['send'])) {

                $name = $_POST['name'];
                $description = $_POST['description'];
                $employee_id = $_SESSION['id'];

                if (!empty($name) and !empty($description) and !empty($employee_id)) {
                    require '../../connect.php';

                    $sql = "SELECT * FROM services WHERE name = '$name' AND id != '$id'";
                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result) == 1) {
            ?>
                        <div class="container" style="margin: 16px 0;">
                            <div class="alert alert-danger" role="alert">
                                اسم الخدمة مأخوذ مسبقا
                            </div>
                        </div>
                        <?php
                    } else {
                        $sql = "UPDATE services SET name = '$name', description = '$description', employee_id = '$employee_id' WHERE id = '$id'";

                        if (mysqli_query($conn, $sql)) {
                        ?>
                            <div class="container" style="margin: 16px 0;">
                                <div class="alert alert-success" role="alert">
                                    تم التعديل بنجاح
                                </div>
                            </div>
            <?php
                            header("refresh:2;url=services_management.php");
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
                    <h4>اضافة خدمة جديدة</h4>
                </div>
                <form action="?section=edit&&id=<?php echo $id ?>" method="post" style="width: 100%; max-width: 450px;" class="mt-3 d-flex flex-column gap-2">
                    <div class="form-group">
                        <label for="name">الاسم:</label>
                        <input type="text" class="form-control" name="name" id="name" required value="<?php echo $row['name'] ?>">
                    </div>
                    <div class="form-group">
                        <label for="description">تفاصيل الخدمة</label>
                        <textarea class="form-control" name="description" id="description" required rows="3"><?php echo $row['description'] ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" name="send">تعديل</button>
                </form>
            </div>

            <?php
        } elseif ($section == "destroy") {
            $id = intval($_GET['id']);

            require '../../connect.php';

            $sql = "DELETE FROM services WHERE id = '$id'";

            if (mysqli_query($conn, $sql)) {
            ?>
                <div class="container" style="margin: 16px 0;">
                    <div class="alert alert-success" role="alert">
                        تم الحذف بنجاح
                    </div>
                </div>
            <?php
                header("refresh:2;url=services_management.php");
            } else {
                echo "Error deleting record: " . mysqli_error($conn);
            }

            mysqli_close($conn);
        } elseif ($section == "active") {
            $id = intval($_GET['id']);

            require '../../connect.php';

            $sql = "UPDATE services SET status = 0 WHERE id = '$id'";

            if (mysqli_query($conn, $sql)) {
            ?>
                <div class="container" style="margin: 16px 0;">
                    <div class="alert alert-success" role="alert">
                        تم تعديل الحالة بنجاح
                    </div>
                </div>
            <?php
                header("refresh:2;url=services_management.php");
            } else {
                echo "Error updating record: " . mysqli_error($conn);
            }

            mysqli_close($conn);
        } elseif ($section == "inactive") {
            $id = intval($_GET['id']);

            require '../../connect.php';

            $sql = "UPDATE services SET status = 1 WHERE id = '$id'";

            if (mysqli_query($conn, $sql)) {
            ?>
                <div class="container" style="margin: 16px 0;">
                    <div class="alert alert-success" role="alert">
                        تم تعديل الحالة بنجاح
                    </div>
                </div>
        <?php
                header("refresh:2;url=services_management.php");
            } else {
                echo "Error updating record: " . mysqli_error($conn);
            }

            mysqli_close($conn);
        }
    } else {
        ?>
        <div class="container" style="margin: 16px 0;">
            <div class="alert alert-Warning" role="alert">
                عذرا ولكن حسابك مجمد
            </div>
        </div>
    <?php
    }
} else {
    ?>
    <div class="container" style="margin: 16px 0;">
        <div class="alert alert-warning" role="alert">
            دخول غير مسموح به
        </div>
    </div>
<?php
}
?>


<?php include "../includes/footer.php" ?>