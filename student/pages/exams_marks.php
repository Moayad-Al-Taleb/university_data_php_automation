<?php
session_start();
include "../includes/header.php";
if (isset($_SESSION['id']) && !isset($_SESSION['type'])) {
    $id = intval($_SESSION['id']);
    require '../../connect.php';

    $sql = "SELECT subjects_students.id, subjects_students.grade1, subjects_students.grade2, subjects_students.school_year, semesters_ss.name AS 'subjects_students_semester_name', subjects.name AS 'subjects_name', years.name AS 'years_name', specializations.name AS 'specializations_name', semesters_s.name AS 'subjects_semester_name' FROM subjects_students, subjects, years, specializations, semesters semesters_ss, semesters semesters_s WHERE subjects_students.subject_id = subjects.id AND subjects.year_id = years.id AND subjects.specialization_id = specializations.id AND subjects_students.semester_id = semesters_ss.id AND subjects.semester_id = semesters_s.id AND subjects_students.student_id = '$id'";
    $result = mysqli_query($conn, $sql);
?>
    <div class="container" style="min-height: calc(100vh - 92px);">
        <?php
        if (mysqli_num_rows($result) > 0) {
        ?>

            <div class="d-flex justify-content-between my-4">
                <h4>عرض جميع علاماتي</h4>
            </div>
            <div class="table-responsive">
                <table class="table table-white">
                    <thead class="table-primary">
                        <th scope="col">#</th>
                        <th scope="col">اسم المادة</th>
                        <th scope="col">الاختصاص</th>
                        <th scope="col">السنة</th>
                        <th scope="col">الفصل</th>
                        <th scope="col">علامة العملي</th>
                        <th scope="col">علامة النظري</th>
                        <th scope="col">العلامة الكاملة</th>
                        <th scope="col">حالة المادة</th>
                        <th scope="col">التقديم في الفصل</th>
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
                            </tr>

                        <?php
                        }

                        ?>

                    </tbody>
                </table>
            </div>
        <?php
        } else {  ?>
            <div class="alert alert-warning my-3">لم يتم اضافة اي علامات</div>
        <?php
        }
        mysqli_close($conn);
        ?>
    </div>
<?php
}

include "../includes/footer.php"; ?>