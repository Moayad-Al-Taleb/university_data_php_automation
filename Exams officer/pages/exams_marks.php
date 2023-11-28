<?php
$page_title = "ادارة علامات الامتحانات";
include "../includes/header.php";
session_start();
if (isset($_SESSION['id']) and $_SESSION['type'] == 2) {
    if ($_SESSION['status'] == 0) {
        $section = isset($_GET['section']) ? $_GET['section'] : "home";

        if ($section == "home") {

            require '../../connect.php';
            $sql = "SELECT * FROM students";
            $result = mysqli_query($conn, $sql);
?>

            <div class="container">
                <form action="?section=students" method="post" id="searchByStu" class="search-form mt-3">
                    <div class="form-group">
                        <label for="exampleInputEmail1">يرجى تحديد اسم الطالب:</label>
                        <select name="id" id="id" required class="form-control">
                            <option value="">-</option>
                            <?php
                            while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                                <option value="<?php echo $row['id'] ?>"><?php echo $row['first_name'] . ' ' . $row['last_name'] . ' - ' . $row['student_number']  ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary" name="search">بحث</button>
                </form>

            </div>

            <?php
        } elseif ($section == "students") {
            $id = intval($_POST['id']);

            require '../../connect.php';

            $sql = "SELECT students.id, students.first_name, students.last_name, students.student_number FROM students WHERE students.id = '$id'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
            ?>
                <div class="container">
                    <div class="table-responsive my-3">
                        <table class="table">
                            <thead class="table-primary">
                                <th scope="col">#</th>
                                <th scope="col">اسم الطالب</th>
                                <th scope="col">رقم الاكتتاب</th>
                                <th scope="col">الخيارات</th>
                            </thead>
                            <tbody>
                                <?php

                                while ($row = mysqli_fetch_assoc($result)) {

                                ?>
                                    <tr>
                                        <td>#</td>
                                        <td><?php echo $row['first_name'] . " " . $row['last_name'] ?></td>
                                        <td><?php echo $row['student_number'] ?></td>
                                        <td>
                                            <a href="?section=show&&id=<?php echo $row['id'] ?>" class="btn btn-info">عرض</a>
                                        </td>
                                    </tr>
                                <?php
                                }

                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php
            } else {
            ?>
                <div class="container" style="margin: 16px 0;">
                    <div class="alert alert-warning" role="alert">
                        لايوجد بيانات لعرضها
                    </div>
                </div>
                <?php
            }
            mysqli_close($conn);
        } elseif ($section == "show") {
            $id = intval($_GET['id']);

            if (isset($_POST['send'])) {
                $subject_id = $_POST['subject_id'];
                $student_id  = $id;
                $grade1 = $_POST['grade1'];
                $grade2 = $_POST['grade2'];
                $semester_id = $_POST['semester_id'];
                $employee_id  = $_SESSION['id'];

                if (!empty($subject_id) and !empty($student_id) and !empty($grade1) and empty($grade2) and !empty($semester_id) and !empty($employee_id)) {
                    if (($grade1 <= 40 && $grade1 >= 0)) {
                        require '../../connect.php';

                        $sql = "INSERT INTO subjects_students (subject_id, student_id, grade1, semester_id, employee_id) VALUES ('$subject_id', '$student_id', '$grade1', '$semester_id', '$employee_id')";

                        if (mysqli_query($conn, $sql)) {
                ?>
                            <div class="container" style="margin: 16px 0;">
                                <div class="alert alert-success" role="alert">
                                    تم الاضافة بنجاح
                                </div>
                            </div>
                        <?php
                            header("refresh:2;url=exams_marks.php?section=show&&id=" . $id);
                        } else {
                            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                        }

                        mysqli_close($conn);
                    } else {
                        ?>
                        <div class="container" style="margin: 16px 0;">
                            <div class="alert alert-danger" role="alert">
                                خطأ في ادخال البيانات
                                <b>يجب ان لاتتجاوز علامة العملي 40 وان لاتقل عن 0</b>
                            </div>
                        </div>
                        <?php
                    }
                } elseif (!empty($subject_id) and !empty($student_id) and !empty($grade1) and !empty($grade2) and !empty($semester_id) and !empty($employee_id)) {
                    if ($grade1 <= 40 && $grade1 >= 0) {
                        if (($grade2 <= 60 && $grade2 >= 0)) {
                            require '../../connect.php';

                            $sql = "INSERT INTO subjects_students (subject_id, student_id, grade1, grade2, semester_id, employee_id) VALUES ('$subject_id', '$student_id', '$grade1', '$grade2', '$semester_id', '$employee_id')";

                            if (mysqli_query($conn, $sql)) {
                        ?>
                                <div class="container" style="margin: 16px 0;">
                                    <div class="alert alert-success" role="alert">
                                        تم الاضافة بنجاح
                                    </div>
                                </div>
                            <?php
                                header("refresh:2;url=exams_marks.php?section=show&&id=" . $id);
                            } else {
                                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                            }

                            mysqli_close($conn);
                        } else {
                            ?>
                            <div class="container" style="margin: 16px 0;">
                                <div class="alert alert-danger" role="alert">
                                    خطأ في ادخال البيانات <br>
                                    <b>يجب ان لاتتجاوز علامة النظري 60 وان لاتقل عن 0</b>
                                </div>
                            </div>
                        <?php
                        }
                    } else {
                        ?>
                        <div class="container" style="margin: 16px 0;">
                            <div class="alert alert-danger" role="alert">
                                خطأ في ادخال البيانات <br>
                                <b>يجب ان لاتتجاوز علامة العملي 40 وان لاتقل عن 0</b> <br>
                            </div>
                        </div>
            <?php
                    }
                }
            }
            ?>

            <div class="container">
                <h4 class="mt-4">اضافة علامة مادة الى الطالب</h4>
                <form action="?section=show&&id=<?php echo $id ?>" method="post" id="searchByStu" class="d-flex flex-column gap-2 w-50 mt-2 mb-4">
                    <?php

                    require '../../connect.php';
                    $sql = "SELECT subjects.id, subjects.name FROM subjects WHERE subjects.id NOT IN ( SELECT subjects_students.subject_id FROM subjects_students WHERE subjects_students.student_id = '$id' ) ORDER BY subjects.id ASC";
                    $result = mysqli_query($conn, $sql);

                    ?>
                    <div class="form-group">
                        <label for="subject_id">تحديد المادة التي تريد اضافتها:</label>
                        <select name="subject_id" id="subject_id " required class="form-control">
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
                    <div class="form-group">
                        <label for="grade1">ادخال علامة العملي:</label>
                        <input type="number" name="grade1" id="grade1" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="grade2">ادخال علامة النظري (ان وجدت):</label>
                        <input type="number" name="grade2" id="grade2" class="form-control">
                    </div>
                    <?php
                    require '../../connect.php';
                    $sql = "SELECT semesters.id, semesters.name FROM semesters";
                    $result = mysqli_query($conn, $sql);
                    ?>
                    <div class="form-group">
                        <label for="semester_id">تحديد الفصل الدراسي:</label>
                        <select name="semester_id" id="semester_id" required class="form-control">
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
                    <button type="submit" class="btn btn-primary" name="send">اضافة</button>
                </form>
                <?php

                require '../../connect.php';

                $sql = "SELECT subjects_students.id, subjects_students.grade1, subjects_students.grade2, subjects_students.school_year, semesters_ss.name AS 'subjects_students_semester_name', subjects.name AS 'subjects_name', years.name AS 'years_name', specializations.name AS 'specializations_name', semesters_s.name AS 'subjects_semester_name' FROM subjects_students, subjects, years, specializations, semesters semesters_ss, semesters semesters_s WHERE subjects_students.subject_id = subjects.id AND subjects.year_id = years.id AND subjects.specialization_id = specializations.id AND subjects_students.semester_id = semesters_ss.id AND subjects.semester_id = semesters_s.id AND subjects_students.student_id = '$id'";
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {
                ?>
                    <div class="table-responsive my-3">
                        <table class="table">
                            <thead class="table-primary">
                                <th scope="col">#</th>
                                <th scope="col">اسم المادة</th>
                                <th scope="col">الاختصاص</th>
                                <th scope="col">السنة</th>
                                <th scope="col">الفصل</th>
                                <th scope="col">تاريخ الاضافة</th>
                                <th scope="col">علامة العملي</th>
                                <th scope="col">علامة النظري</th>
                                <th scope="col">العلامة الكاملة</th>
                                <th scope="col">حالة المادة</th>
                                <th scope="col">التقديم في الفصل</th>
                                <th scope="col">الخيارات</th>
                            </thead>
                            <tbody>
                                <?php

                                while ($row = mysqli_fetch_assoc($result)) {

                                ?>

                                    <tr>
                                        <td>#</td>
                                        <td><?php echo $row['subjects_name'] ?></td>
                                        <td><?php echo $row['specializations_name'] ?></td>
                                        <td><?php echo $row['years_name'] ?></td>
                                        <td><?php echo $row['subjects_semester_name'] ?></td>
                                        <td><?php echo $row['school_year'] ?></td>
                                        <td><?php echo $row['grade1'] ?></td>
                                        <td><?php echo $row['grade2'] ?></td>
                                        <td><?php echo ($row['grade1'] + $row['grade2']) ?></td>
                                        <td>
                                            <?php
                                            if (($row['grade1'] + $row['grade2']) >= 60) {
                                                echo "ناجح";
                                            } else {
                                                echo "راسب";
                                            }
                                            ?>
                                        </td>
                                        <td><?php echo $row['subjects_students_semester_name'] ?></td>
                                        <td>
                                            <a href="?section=edit&&id=<?php echo $id; ?>&&id2=<?php echo $row['id'] ?>" class="btn btn-warning">تعديل</a>
                                            <a href="?section=destroy&&id=<?php echo $id; ?>&&id2=<?php echo $row['id'] ?>" class="btn btn-danger mt-1">حذف</a>
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
                    <div class="container" style="margin: 16px 0;">
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
        } elseif ($section == "edit") {
            $id = intval($_GET['id']);
            $id2 = intval($_GET['id2']);

            require '../../connect.php';

            $sql = "SELECT * FROM subjects_students WHERE id = '$id2'";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            mysqli_close($conn);

            if (isset($_POST['send'])) {
                $grade1 = $_POST['grade1'];
                $grade2 = $_POST['grade2'];
                $semester_id = $_POST['semester_id'];
                $employee_id  = $_SESSION['id'];

                if (!empty($grade1) and ($grade2 != null) and !empty($semester_id) and !empty($employee_id)) {
                    if (($grade1 <= 40 && $grade1 >= 0)) {
                        if ($grade2 <= 60 && $grade2 >= 0) {
                            require '../../connect.php';

                            $sql = "UPDATE subjects_students SET grade1 = '$grade1', grade2 = '$grade2', semester_id = '$semester_id', employee_id = '$employee_id' WHERE id = '$id2'";

                            if (mysqli_query($conn, $sql)) {
            ?>
                                <div class="container" style="margin: 16px 0;">
                                    <div class="alert alert-success" role="alert">
                                        تم التعديل بنجاح
                                    </div>
                                </div>
                            <?php
                                header("refresh:2;url=exams_marks.php?section=show&&id=" . $id);
                            } else {
                                echo "Error updating record: " . mysqli_error($conn);
                            }

                            mysqli_close($conn);
                        } else {
                            ?>
                            <div class="container" style="margin: 16px 0;">
                                <div class="alert alert-danger" role="alert">
                                    خطأ في ادخال البيانات <br>
                                    <b>يجب ان لاتتجاوز علامة النظري 60 وان لاتقل عن 0</b> <br>
                                </div>
                            </div>
                        <?php

                        }
                    } else {
                        ?>
                        <div class="container" style="margin: 16px 0;">
                            <div class="alert alert-danger" role="alert">
                                خطأ في ادخال البيانات <br>
                                <b>يجب ان لاتتجاوز علامة العملي 40 وان لاتقل عن 0</b> <br>
                            </div>
                        </div>
            <?php
                    }
                }
            }
            ?>
            <div class="container">
                <h4 class="mt-4">تعديل علامة مادة </h4>
                <small>في حال كنت تريد ازالة علامة النظري يرجى وضعها بقيمة 0</small>
                <form action="?section=edit&&id=<?php echo $id ?>&&id2=<?php echo $id2 ?>" method="post" id="searchByStu" class="d-flex flex-column gap-2 w-50 mt-2 mb-4">
                    <div class="form-group">
                        <label for="grade1">ادخال علامة العملي:</label>
                        <input type="number" name="grade1" id="grade1" required value="<?php echo $row['grade1'] ?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="grade2">ادخال علامة النظري (ان وجدت):</label>
                        <input type="number" name="grade2" id="grade2" value="<?php echo $row['grade2'] ?>" class="form-control">
                    </div>
                    <?php
                    require '../../connect.php';
                    $sql = "SELECT semesters.id, semesters.name FROM semesters WHERE id != '4'";
                    $result = mysqli_query($conn, $sql);
                    ?>
                    <div class="form-group">
                        <label for="semester_id">تحديد الفصل الدراسي:</label>
                        <select name="semester_id" id="semester_id" required class="form-control">
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
                    <button type="submit" class="btn btn-primary" name="send">تعديل</button>
                </form>
            </div>
            <?php
        } elseif ($section == "destroy") {
            $id = intval($_GET['id']);
            $id2 = intval($_GET['id2']);

            require '../../connect.php';

            $sql = "DELETE FROM subjects_students WHERE id = '$id2'";

            if (mysqli_query($conn, $sql)) {
            ?>
                <div class="container" style="margin: 16px 0;">
                    <div class="alert alert-success" role="alert">
                        تم الحذف بنجاح
                    </div>
                </div>
        <?php
                header("refresh:2;url=exams_marks.php?section=show&&id=" . $id);
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
        <div class="alert alert-Warning" role="alert">
            دخول غير مسموح به
        </div>
    </div>
<?php
}

?>


<?php include "../includes/footer.php" ?>