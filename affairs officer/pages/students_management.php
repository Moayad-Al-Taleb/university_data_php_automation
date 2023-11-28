<?php
$page_title = "ادارة ذاتية الطلاب";
include "../includes/header.php";
session_start();

if (isset($_SESSION['id']) and isset($_SESSION['type']) and $_SESSION['type'] == 1) {
    if ($_SESSION['status'] == 0) {
        $section = isset($_GET['section']) ? $_GET['section'] : "home";

        if ($section == "home") {
            require '../../connect.php';

            $sql = "SELECT students.*, specializations.name FROM students, specializations WHERE students.specialization_id = specializations.id";
            $result = mysqli_query($conn, $sql);
?>

            <div class="container">
                <div class="d-flex w-100 mt-4 justify-content-between align-items-center">
                    <a class="btn btn-primary" href="?section=add">اضافة ذاتية طالب </a>
                </div>
                <?php
                if (mysqli_num_rows($result) > 0) {
                ?>
                    <div class="table-responsive my-3">
                        <table class="table">
                            <thead class="table-primary">
                                <th scope="col">#</th>
                                <th scope="col">الاسم</th>
                                <th scope="col">الكنية</th>
                                <th scope="col">اسم الاب</th>
                                <th scope="col">اسم الام</th>
                                <th scope="col">الجنسية</th>
                                <th scope="col">الاختصاص</th>
                                <th scope="col">رقم الاكتتاب</th>
                                <th scope="col">الخيارات</th>
                            </thead>
                            <tbody>
                                <?php

                                while ($row = mysqli_fetch_assoc($result)) {

                                ?>
                                    <tr>
                                        <td>#</td>
                                        <td><?php echo $row['first_name'] ?></td>
                                        <td><?php echo $row['last_name'] ?></td>
                                        <td><?php echo $row['father_name'] ?></td>
                                        <td><?php echo $row['mother_name'] ?></td>
                                        <td><?php echo $row['nationality'] ?></td>
                                        <td><?php echo $row['name'] ?></td>
                                        <td><?php echo $row['student_number'] ?></td>
                                        <td>
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

                $first_name = $_POST['first_name'];
                $last_name = $_POST['last_name'];
                $father_name = $_POST['father_name'];
                $mother_name = $_POST['mother_name'];
                $nationality = $_POST['nationality'];
                $specialization_id = $_POST['specialization_id'];
                $student_number = $_POST['student_number'];
                $pass = sha1($student_number . "_123pass");
                $employee_id = $_SESSION['id'];

                if (
                    !empty($first_name) and !empty($last_name) and !empty($father_name) and !empty($mother_name) and !empty($nationality) and !empty($specialization_id) and !empty($student_number) and !empty($employee_id)
                ) {
                    require '../../connect.php';

                    $sql = "SELECT * FROM students WHERE student_number = '$student_number'";
                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result) == 1) {
            ?>
                        <div class="container" style="margin: 16px 0;">
                            <div class="alert alert-danger" role="alert">
                                رقم الاكتتاب مأخوذ مسبقا
                            </div>
                        </div>
                        <?php
                    } else {
                        $sql = "INSERT INTO students (first_name, last_name, father_name, mother_name, nationality, specialization_id, student_number, pass, employee_id) VALUES ('$first_name', '$last_name', '$father_name', '$mother_name', '$nationality', '$specialization_id', '$student_number', '$pass', '$employee_id')";

                        if (mysqli_query($conn, $sql)) {

                        ?>
                            <div class="container" style="margin: 16px 0;">
                                <div class="alert alert-success" role="alert">
                                    تم الاضافة بنجاح
                                </div>
                            </div>
            <?php
                            header("refresh:2;url=students_management.php");
                        } else {
                            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                        }
                    }

                    mysqli_close($conn);
                }
            }

            ?>

            <div class="container">
                <div class="d-flex w-100 mt-4 flex-column">
                    <h4>اضافة ذاتية طالب جديدة</h4>
                    <small class="text-muted">يرجى ادخال جميع البيانات</small>
                </div>
                <form action="?section=add" method="post" class="mt-3 d-flex flex-column gap-2">
                    <div class="inputs-container">
                        <div class="form-group">
                            <label for="first_name">الاسم:</label>
                            <input type="text" class="form-control" name="first_name" id="first_name" required>
                        </div>
                        <div class="form-group">
                            <label for="last_name">الكنية:</label>
                            <input type="text" class="form-control" name="last_name" id="last_name" required>
                        </div>
                    </div>
                    <div class="inputs-container">
                        <div class="form-group">
                            <label for="father_name">اسم الاب:</label>
                            <input type="text" class="form-control" name="father_name" id="father_name" required>
                        </div>
                        <div class="form-group">
                            <label for="mother_name">اسم الام:</label>
                            <input type="text" class="form-control" name="mother_name" id="mother_name" required>
                        </div>
                    </div>
                    <div class="inputs-container">
                        <div class="form-group">
                            <label for="nationality">الجنسية:</label>
                            <input type="text" class="form-control" name="nationality" id="nationality" required>
                        </div>
                        <div class="form-group">
                            <label for="student_number">رقم الاكتتاب:</label>
                            <input type="number" class="form-control" name="student_number" id="student_number" required>
                        </div>
                    </div>
                    <?php
                    require '../../connect.php';
                    $sql = "SELECT * FROM specializations";
                    $result = mysqli_query($conn, $sql);
                    ?>
                    <div class="form-group">
                        <label for="specialization_id">تحديد الاختصاص:</label>
                        <select class="form-control" name="specialization_id" id="specialization_id" required>
                            <option value="">-</option>
                            <?php
                            while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                                <option value="<?php echo $row['id'] ?>"><?php echo $row['name'] ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit" style="width: 320px;" class="btn btn-primary" name="send">اضافة</button>
                </form>
            </div>

            <?php
        } elseif ($section == "edit") {
            $id = intval(($_GET['id']));

            require '../../connect.php';

            $sql = "SELECT * FROM students WHERE id = '$id'";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            mysqli_close($conn);

            if (isset($_POST['send'])) {

                $first_name = $_POST['first_name'];
                $last_name = $_POST['last_name'];
                $father_name = $_POST['father_name'];
                $mother_name = $_POST['mother_name'];
                $nationality = $_POST['nationality'];
                $student_number = $_POST['student_number'];
                $employee_id = $_SESSION['id'];

                if (!empty($first_name) and !empty($last_name) and !empty($father_name) and !empty($mother_name) and !empty($nationality) and !empty($student_number) and !empty($employee_id)) {
                    require '../../connect.php';

                    $sql = "SELECT * FROM students WHERE student_number = '$student_number' AND id != '$id'";
                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result) == 1) {
            ?>
                        <div class="container" style="margin: 16px 0;">
                            <div class="alert alert-danger" role="alert">
                                رقم الاكتتاب مأخوذ مسبقا
                            </div>
                        </div>
                        <?php
                    } else {
                        $sql = "UPDATE students SET first_name = '$first_name', last_name = '$last_name', father_name = '$father_name', mother_name = '$mother_name', nationality = '$nationality', student_number = '$student_number', employee_id = '$employee_id' WHERE id = '$id'";

                        if (mysqli_query($conn, $sql)) {
                        ?>
                            <div class="container" style="margin: 16px 0;">
                                <div class="alert alert-success" role="alert">
                                    تم التعديل بنجاح
                                </div>
                            </div>
            <?php
                            header("refresh:2;url=students_management.php");
                        } else {
                            echo "Error updating record: " . mysqli_error($conn);
                        }
                    }

                    mysqli_close($conn);
                }
            }
            ?>
            <div class="container">
                <div class="d-flex w-100 mt-4 flex-column">
                    <h4>تعديل ذاتية الطالب</h4>
                    <small class="text-muted">يرجى ادخال جميع البيانات</small>
                </div>
                <form action="?section=edit&&id=<?php echo $id ?>" method="post" class="mt-3 d-flex flex-column gap-2">
                    <div class="inputs-container">
                        <div class="form-group">
                            <label for="first_name">الاسم:</label>
                            <input type="text" class="form-control" name="first_name" id="first_name" required value="<?php echo $row['first_name'] ?>">
                        </div>
                        <div class="form-group">
                            <label for="last_name">الكنية:</label>
                            <input type="text" class="form-control" name="last_name" id="last_name" required value="<?php echo $row['last_name'] ?>">
                        </div>
                    </div>
                    <div class="inputs-container">
                        <div class="form-group">
                            <label for="father_name">اسم الاب:</label>
                            <input type="text" class="form-control" name="father_name" id="father_name" required value="<?php echo $row['father_name'] ?>">
                        </div>
                        <div class="form-group">
                            <label for="mother_name">اسم الام:</label>
                            <input type="text" class="form-control" name="mother_name" id="mother_name" required value="<?php echo $row['mother_name'] ?>">
                        </div>
                    </div>
                    <div class="inputs-container">
                        <div class="form-group">
                            <label for="nationality">الجنسية:</label>
                            <input type="text" class="form-control" name="nationality" id="nationality" required value="<?php echo $row['nationality'] ?>">
                        </div>
                        <div class="form-group">
                            <label for="student_number">رقم الاكتتاب:</label>
                            <input type="number" class="form-control" name="student_number" id="student_number" required value="<?php echo $row['student_number'] ?>">
                        </div>
                    </div>
                    <?php
                    require '../../connect.php';
                    $sql = "SELECT * FROM specializations";
                    $result = mysqli_query($conn, $sql);
                    ?>
                    <button type="submit" style="width: 320px;" class="btn btn-primary" name="send">تعديل</button>
                </form>
            </div>

            <?php
        } elseif ($section == "destroy") {
            $id = intval($_GET['id']);

            require '../../connect.php';

            $sql = "DELETE FROM students WHERE id = '$id'";

            if (mysqli_query($conn, $sql)) {
            ?>
                <div class="container" style="margin: 16px 0;">
                    <div class="alert alert-success" role="alert">
                        تم الحذف بنجاح
                    </div>
                </div>
        <?php
                header("refresh:2;url=students_management.php");
            } else {
                echo "Error deleting record: " . mysqli_error($conn);
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