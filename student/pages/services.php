<?php
session_start();
include "../includes/header.php";
if (isset($_SESSION['id']) && !isset($_SESSION['type'])) {
    $section = isset($_GET['section']) ? $_GET['section'] : "home";
    if ($section == "home") {
        $id = $_SESSION['id'];
        require '../../connect.php';

        $sql = "SELECT services_students.*, services.name AS 'service_name', services.description AS 'services_description' FROM services_students, services WHERE services_students.service_id = services.id AND services_students.student_id = '$id' ORDER BY services_students.service_status ASC";
        $result = mysqli_query($conn, $sql);

?>

        <div class="container" style="min-height: calc(100vh - 92px);">
            <div class="d-flex justify-content-between my-4">
                <h4>جميع الطلبات المقدمة</h4>
                <a href="?section=add" class="btn btn-primary">طلب جديد</a>
            </div>
            <?php
            if (mysqli_num_rows($result) > 0) {
            ?>
                <div class="table-responsive">
                    <table class="table table-white">
                        <thead class="table-primary">
                            <th>#</th>
                            <th>اسم الخدمة</th>
                            <th>تفاصيل الخدمة</th>
                            <th>حالة الخدمة</th>
                        </thead>
                        <tbody>

                            <?php

                            while ($row = mysqli_fetch_assoc($result)) {

                            ?>
                                <tr>
                                    <td>#</td>
                                    <td><?php echo $row['service_name'] ?></td>
                                    <td><?php echo $row['services_description'] ?></td>
                                    <td>
                                        <?php

                                        if ($row['service_status'] == 1) {
                                            echo "الانتظار";
                                        } elseif ($row['service_status'] == 2) {
                                            echo "قيد المعالجة";
                                        } elseif ($row['service_status'] == 3) {
                                            echo "مرفوض";
                                        } elseif ($row['service_status'] == 4) {
                                            echo "للاستلام";
                                        } elseif ($row['service_status'] == 5) {
                                            // echo "Send to the student";
                                            $imageURL = "../../admin/uploads/" . $row['file'];
                                        ?>
                                            <a href="<?php echo $imageURL; ?>">تحميل</a>

                                        <?php
                                        }

                                        ?>
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
                <div class="alert alert-warning">
                    لايوجد بيانات لعرضها
                </div>
            <?php
            }
            mysqli_close($conn);
            ?>
        </div>
        <?php
    } elseif ($section == "add") {
        if (isset($_POST['send'])) {

            $student_id = $_SESSION['id'];
            $service_id = $_POST['service_id'];

            if (!empty($student_id) and !empty($service_id)) {
                require '../../connect.php';

                $sql = "INSERT INTO services_students (student_id, service_id) VALUES ('$student_id', '$service_id')";

                if (mysqli_query($conn, $sql)) {
        ?>
                    <div class="container my-3">
                        <div class="alert alert-success">
                            تم الاصافة بنجاح
                        </div>
                    </div>
        <?php
                    header("refresh:2;url=services.php");
                } else {
                    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                }

                mysqli_close($conn);
            }
        }
        ?>
        <div class="container" style="min-height: 55.3vh;">
            <div class="d-flex justify-content-between my-4">
                <h4>تقديم طلب خدمة جديدة</h4>
            </div>
            <?php

            require '../../connect.php';
            $sql = "SELECT * FROM services WHERE services.status = 0";
            $result = mysqli_query($conn, $sql);

            ?>
            <form action="?section=add" method="post" id="searchBySer" class="searchForm">
                <div class="form-group mb-2">
                    <label for="service_id">تحديد اسم الخدمة:</label>
                    <select class="form-control" name="service_id" id="service_id" required class="form-control">
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
                <button type="submit" class="btn btn-secondary" name="send">تقديم طلب</button>
            </form>
        </div>
<?php
    }
}
include "../includes/footer.php"; ?>