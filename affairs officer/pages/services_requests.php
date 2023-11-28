<?php
$page_title = "ادارة الطلبات";
include "../includes/header.php";
session_start();

if (isset($_SESSION['id']) and isset($_SESSION['type']) and $_SESSION['type'] == 1) {
    if ($_SESSION['status'] == 0) {
        $section = isset($_GET['section']) ? $_GET['section'] : "home";

        if ($section == "home") {
?>
            <div class="container">
                <div class="d-flex w-100 mt-4 justify-content-between align-items-center">
                    <div>
                        <span>البحث عبر</span>
                        <button id="btnSearchByStu" class="btn btn-primary">اسم الطالب</button>
                        <button id="btnSearchBySer" class="btn btn-secondary">اسم الخدمة</button>
                    </div>
                </div>
                <?php

                require '../../connect.php';
                $sql = "SELECT * FROM students";
                $result = mysqli_query($conn, $sql);
                ?>
                <form action="?section=students" method="post" id="searchByStu" class="searchForm">
                    <div class="form-group">
                        <label for="id">تحديد اسم الطالب:</label>
                        <select name="id" id="id" class="form-control">
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
                <?php

                require '../../connect.php';
                $sql = "SELECT * FROM services";
                $result = mysqli_query($conn, $sql);

                ?>
                <form action="?section=services" method="post" id="searchBySer" class="searchForm">
                    <div class="form-group">
                        <label for="id">تحديد اسم الخدمة:</label>
                        <select name="id" id="id" class="form-control">
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
                    <button type="submit" class="btn btn-secondary" name="search">بحث</button>
                </form>
                <?php

                require '../../connect.php';
                $sql = "SELECT services_students.*, services.name AS 'service_name', students.first_name, students.last_name, students.student_number FROM services_students, services, students WHERE services_students.service_id = services.id AND services_students.student_id = students.id ORDER BY services_students.service_status ASC";
                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) > 0) {

                ?>
                    <div class="table-responsive my-3">
                        <table class="table">
                            <thead class="table-primary">
                                <th scope="col">#</th>
                                <th scope="col">اسم الخدمة</th>
                                <th scope="col">اسم الطالب</th>
                                <th scope="col">رقم الاكتتاب</th>
                                <th scope="col">الحالة</th>
                                <th scope="col">الخيارات</th>
                            </thead>
                            <tbody>
                                <?php

                                while ($row = mysqli_fetch_assoc($result)) {

                                ?>
                                    <tr>
                                        <td>#</td>
                                        <td><?php echo $row['service_name'] ?></td>
                                        <td><?php echo $row['first_name'] . " " . $row['last_name'] ?></td>
                                        <td><?php echo $row['student_number'] ?></td>
                                        <td>
                                            <?php
                                            if ($row['service_status'] == 1) {
                                                echo "الانتظار";
                                            } elseif ($row['service_status'] == 2) {
                                                echo "قيد المعالجة";
                                            } elseif ($row['service_status'] == 3) {
                                                echo "مرفوض";
                                            } elseif ($row['service_status'] == 4) {
                                                echo "للإستلام";
                                            } elseif ($row['service_status'] == 5) {
                                                echo "تم الارسال";
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            if ($row['service_status'] != 5) {
                                            ?>
                                                <a href="?section=edit&&id=<?php echo $row['id'] ?>" class="btn btn-info">تعديل</a>
                                            <?php
                                            } else {
                                                echo "تم معالجة الطلب";
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
            <script>
                const searchByStu = document.getElementById("searchByStu");
                const searchBySer = document.getElementById("searchBySer");
                const btnSearchByStu = document.getElementById("btnSearchByStu");
                const btnSearchBySer = document.getElementById("btnSearchBySer");
                btnSearchBySer.onclick = () => {
                    searchBySer.classList.add("active");
                    searchByStu.classList.remove("active")
                }
                btnSearchByStu.onclick = () => {
                    searchByStu.classList.add("active");
                    searchBySer.classList.remove("active")
                }
            </script>
            <?php
        } elseif ($section == "edit") {
            $id = intval($_GET['id']);

            if (isset($_POST['send'])) {

                $service_status = $_POST['service_status'];
                $file = "";
                if (!empty($_FILES['file']['name'])) {
                    $targetDir = "../../admin/uploads/";
                    $fileName = basename($_FILES["file"]["name"]);
                    $targetFilePath = $targetDir . $fileName;
                    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

                    $allowTypes = array('jpg', 'png', 'jpeg', 'gif', 'pdf');
                    if (in_array($fileType, $allowTypes)) {
                        if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)) {
                            $file = $fileName;
                        } else {
            ?>
                            <div class="container" style="margin: 16px 0;">
                                <div class="alert alert-danger" role="alert">
                                    عذرا حدث خطأ في اضافة الصورة
                                </div>
                            </div>
                        <?php
                        }
                    } else {
                        ?>
                        <div class="container" style="margin: 16px 0;">
                            <div class="alert alert-danger" role="alert">
                                JPG, JPEG, PNG, GIF, & PDF عذرا فقط هذه اللاحقات المسموح بها
                            </div>
                        </div>
                    <?php
                    }
                }
                $employee_id = $_SESSION['id'];

                if (!empty($service_status) and !empty($employee_id) and !empty($file)) {
                    require '../../connect.php';

                    $sql = "UPDATE services_students SET service_status = '$service_status', employee_id = '$employee_id', file = '$file'  WHERE id = '$id'";

                    if (mysqli_query($conn, $sql)) {
                    ?>
                        <div class="container" style="margin: 16px 0;">
                            <div class="alert alert-success" role="alert">
                                تم تعديل الحالة بنجاح
                            </div>
                        </div>
                    <?php
                        header("refresh:2;url=services_requests.php");
                    } else {
                        echo "Error updating record: " . mysqli_error($conn);
                    }

                    mysqli_close($conn);
                }
                if (!empty($service_status) and !empty($employee_id) and empty($file)) {
                    require '../../connect.php';

                    $sql = "UPDATE services_students SET service_status = '$service_status', employee_id = '$employee_id' WHERE id = '$id'";

                    if (mysqli_query($conn, $sql)) {
                    ?>
                        <div class="container" style="margin: 16px 0;">
                            <div class="alert alert-success" role="alert">
                                تم تعديل الحالة بنجاح
                            </div>
                        </div>
            <?php
                        header("refresh:2;url=services_requests.php");
                    } else {
                        echo "Error updating record: " . mysqli_error($conn);
                    }

                    mysqli_close($conn);
                }
            }
            ?>
            <div class="container">
                <div class="d-flex w-100 mt-4 justify-content-between align-items-center">
                    <h4>تغيير حالة الطلب</h4>
                </div>
                <form action="?section=edit&&id=<?php echo $id ?>" method="post" enctype="multipart/form-data" style="width: 100%; max-width: 450px;" class="mt-3 d-flex flex-column gap-2">
                    <div class="form-group">
                        <label for="service_status">تحديث حالة الطلب:</label>
                        <select name="service_status" id="service_status" required class="form-control">
                            <option value="">-</option>
                            <option value="1">انتظار</option>
                            <option value="2">قيد المعالجة</option>
                            <option value="3">مرفوض</option>
                            <option value="4">استلام</option>
                            <option value="5">ارسال الى الطالب</option>
                        </select>
                    </div>
                    <div class="form-group requestFileInput">
                        <label for="file">ارفاق ملف الخدمة:</label>
                        <input type="file" class="form-control-file" name="file" id="file">
                    </div>
                    <button type="submit" class="btn btn-primary" name="send">تعديل</button>
                </form>
            </div>


            <script>
                const requestStateChange = document.getElementById("service_status");
                requestStateChange.onchange = () => {
                    console.log(requestStateChange.value)
                    if (requestStateChange.value == "5") {
                        document.querySelector(".requestFileInput").style.display = "block";
                        document.getElementById("file").setAttribute("required", "");
                    } else {
                        document.querySelector(".requestFileInput").style.display = "none";
                        document.getElementById("file").removeAttribute("required");
                    }
                }
            </script>

            <?php
        } elseif ($section == "students") {
            $id = intval($_POST['id']);

            require '../../connect.php';

            $sql = "SELECT services_students.*, services.name AS 'service_name', students.first_name, students.last_name, students.student_number FROM services_students, services, students WHERE services_students.service_id = services.id AND services_students.student_id = students.id AND services_students.student_id = '$id' ORDER BY services_students.service_status ASC";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {

            ?>
                <div class="container">
                    <div class="table-responsive my-3">
                        <table class="table">
                            <thead class="table-primary">
                                <th scope="col">#</th>
                                <th scope="col">اسم الخدمة</th>
                                <th scope="col">اسم الطالب</th>
                                <th scope="col">رقم الاكتتاب</th>
                                <th scope="col">الحالة</th>
                                <th scope="col">الخيارات</th>
                            </thead>
                            <tbody>
                                <?php

                                while ($row = mysqli_fetch_assoc($result)) {

                                ?>
                                    <tr>
                                        <td>#</td>
                                        <td><?php echo $row['service_name'] ?></td>
                                        <td><?php echo $row['first_name'] . " " . $row['last_name'] ?></td>
                                        <td><?php echo $row['student_number'] ?></td>
                                        <td>
                                            <?php
                                            if ($row['service_status'] == 1) {
                                                echo "الانتظار";
                                            } elseif ($row['service_status'] == 2) {
                                                echo "قيد المعالجة";
                                            } elseif ($row['service_status'] == 3) {
                                                echo "مرفوض";
                                            } elseif ($row['service_status'] == 4) {
                                                echo "للإستلام";
                                            } elseif ($row['service_status'] == 5) {
                                                echo "تم الارسال";
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            if ($row['service_status'] != 5) {
                                            ?>
                                                <a href="?section=edit&&id=<?php echo $row['id'] ?>" class="btn btn-info">تعديل</a>
                                            <?php
                                            } else {
                                                echo "تم معالجة الطلب";
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
        } elseif ($section == "services") {
            $id = intval($_POST['id']);

            require '../../connect.php';

            $sql = "SELECT services_students.*, services.name AS 'service_name', students.first_name, students.last_name, students.student_number FROM services_students, services, students WHERE services_students.service_id = services.id AND services_students.student_id = students.id AND services_students.service_id = '$id' ORDER BY services_students.service_status ASC";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {

            ?>
                <div class="container">
                    <div class="table-responsive my-3">
                        <table class="table">
                            <thead class="table-primary">
                                <th scope="col">#</th>
                                <th scope="col">اسم الخدمة</th>
                                <th scope="col">اسم الطالب</th>
                                <th scope="col">رقم الاكتتاب</th>
                                <th scope="col">الحالة</th>
                                <th scope="col">الخيارات</th>
                            </thead>
                            <tbody>
                                <?php

                                while ($row = mysqli_fetch_assoc($result)) {

                                ?>
                                    <tr>
                                        <td>#</td>
                                        <td><?php echo $row['service_name'] ?></td>
                                        <td><?php echo $row['first_name'] . " " . $row['last_name'] ?></td>
                                        <td><?php echo $row['student_number'] ?></td>
                                        <td>
                                            <?php
                                            if ($row['service_status'] == 1) {
                                                echo "الانتظار";
                                            } elseif ($row['service_status'] == 2) {
                                                echo "قيد المعالجة";
                                            } elseif ($row['service_status'] == 3) {
                                                echo "مرفوض";
                                            } elseif ($row['service_status'] == 4) {
                                                echo "للإستلام";
                                            } elseif ($row['service_status'] == 5) {
                                                echo "تم الارسال";
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            if ($row['service_status'] != 5) {
                                            ?>
                                                <a href="?section=edit&&id=<?php echo $row['id'] ?>" class="btn btn-info">تعديل</a>
                                            <?php
                                            } else {
                                                echo "تم معالجة الطلب";
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
        }
    } else {
        ?>
        <div class="container" style="margin: 16px 0;">
            <div class="alert alert-warning" role="alert">
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