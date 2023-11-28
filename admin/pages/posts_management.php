<?php
$page_title = "ادارة المنشورات";
include "../includes/header.php";
session_start();
if (isset($_SESSION['id']) and $_SESSION['type'] == 0) {

    $section = isset($_GET['section']) ? $_GET['section'] : "home";

    if ($section == "home") {
        require '../../connect.php';

        $sql = "SELECT * FROM posts";
        $result = mysqli_query($conn, $sql);
?>

        <div class="container">
            <div class="d-flex w-100 mt-4 justify-content-between align-items-center">
                <a class="btn btn-primary" href="?section=add">اضافة منشور جديد</a>
            </div>
            <?php
            if (mysqli_num_rows($result) > 0) {

            ?>
                <div class="d-flex justify-content-center align-items-start gap-3 flex-wrap flex-column flex-md-row my-3">
                    <?php

                    while ($row = mysqli_fetch_assoc($result)) {
                        if (!empty($row['file'])) {
                            $imageURL = "../uploads/" . $row['file'];
                        } else {
                            $imageURL = null;
                        }
                    ?>
                        <div class="card hover-card" style="width: 500px; max-width:100%;">
                            <?php
                            if ($imageURL != null) {
                            ?>
                                <img style="width: 100%; max-height: 350px; object-fit: cover; object-position: top;" class="card-img-top" src="<?php echo $imageURL; ?>" alt="Card image cap">
                            <?php
                            }
                            ?>
                            <div class="card-body">
                                <p class="card-text"><?php echo $row['post'] ?></p>
                            </div>
                            <div class="absolute-card-btns">
                                <a href="?section=edit&&id=<?php echo $row['id'] ?>" class="btn btn-warning">تعديل</a>
                                <a href="?section=destroy&&id=<?php echo $row['id'] ?>" class="btn btn-danger">حذف</a>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            <?php
            }
            ?>
        </div>
        <?php
    } elseif ($section == "add") {
        if (isset($_POST['send'])) {

            $post = $_POST['post'];
            $file = "";
            if (!empty($_FILES['file']['name'])) {
                $targetDir = "../uploads/";
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
                                عذرا هناك خطأاثناء الاضافة
                            </div>
                        </div>
                    <?php
                    }
                } else {
                    ?>
                    <div class="container" style="margin: 16px 0;">
                        <div class="alert alert-danger" role="alert">
                            JPG, JPEG, PNG, GIF, & PDF عذرا فقط هذه اللاحقات الصالحة
                        </div>
                    </div>
                <?php
                }
            }
            $employee_id = $_SESSION['id'];

            if (!empty($post) and !empty($file) and !empty($employee_id)) {
                require '../../connect.php';

                $sql = "INSERT INTO posts(post, file, employee_id) VALUES ('$post', '$file', '$employee_id')";

                if (mysqli_query($conn, $sql)) {
                ?>
                    <div class="container" style="margin: 16px 0;">
                        <div class="alert alert-success" role="alert">
                            تم الاضافة بنجاح
                        </div>
                    </div>
                <?php
                    header("refresh:2;url=posts_management.php");
                } else {
                    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                }

                mysqli_close($conn);
            } elseif (!empty($post) and empty($file) and !empty($employee_id)) {
                require '../../connect.php';

                $sql = "INSERT INTO posts(post, employee_id) VALUES ('$post', '$employee_id')";

                if (mysqli_query($conn, $sql)) {
                ?>
                    <div class="container" style="margin: 16px 0;">
                        <div class="alert alert-success" role="alert">
                            تم الاضافة بنجاح
                        </div>
                    </div>
        <?php
                    header("refresh:2;url=posts_management.php");
                } else {
                    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                }

                mysqli_close($conn);
            }
        }

        ?>

        <div class="container">
            <div class="d-flex w-100 mt-4 justify-content-between align-items-center">
                <h4>انشاء منشور جديد</h4>
            </div>
            <form action="?section=add" method="post" enctype="multipart/form-data" style="width: 100%; max-width: 450px;" class="mt-3 d-flex flex-column gap-2">
                <div class="form-group">
                    <label for="post">محتوى المنشور</label>
                    <textarea name="post" id="post" class="form-control" id="exampleFormControlTextarea1" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label for="file">ارفاق صورة</label>
                    <input type="file" name="file" id="file" class="form-control-file" id="exampleFormControlFile1">
                </div>
                <button type="submit" class="btn btn-primary" name="send">اضافة</button>
            </form>
        </div>

        <?php
    } elseif ($section == "edit") {
        $id = intval($_GET['id']);

        require '../../connect.php';

        $sql = "SELECT * FROM posts WHERE id = '$id'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        mysqli_close($conn);
        $imageURL = "../uploads/" . $row['file'];

        if (isset($_POST['send'])) {

            $post = $_POST['post'];
            $file = "";
            if (!empty($_FILES['file']['name'])) {
                $targetDir = "../uploads/";
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
                                عذرا هناك خطأاثناء الاضافة
                            </div>
                        </div>
                    <?php
                    }
                } else {
                    ?>
                    <div class="container" style="margin: 16px 0;">
                        <div class="alert alert-danger" role="alert">
                            JPG, JPEG, PNG, GIF, & PDF عذرا فقط هذه اللاحقات الصالحة
                        </div>
                    </div>
                <?php
                }
            }
            $employee_id = $_SESSION['id'];

            if (!empty($post) and !empty($file) and !empty($employee_id)) {
                require '../../connect.php';

                $sql = "UPDATE posts SET post = '$post', file = '$file', employee_id = '$employee_id' WHERE id = '$id'";

                if (mysqli_query($conn, $sql)) {
                ?>
                    <div class="container" style="margin: 16px 0;">
                        <div class="alert alert-success" role="alert">
                            تم التعديل بنجاح
                        </div>
                    </div>
                    <?php

                    if (file_exists('../uploads/' . $row['file'])) {
                        if (!empty($row['file'])) {
                            unlink('../uploads/' . $row['file']);
                        }
                    }

                    header("refresh:2;url=posts_management.php");
                } else {
                    echo "Error updating record: " . mysqli_error($conn);
                }

                mysqli_close($conn);
            } elseif (!empty($post) and empty($file) and !empty($employee_id)) {
                require '../../connect.php';

                $sql = "UPDATE posts SET post = '$post', employee_id = '$employee_id' WHERE id = '$id'";

                if (mysqli_query($conn, $sql)) {
                    ?>
                    <div class="container" style="margin: 16px 0;">
                        <div class="alert alert-success" role="alert">
                            تم التعديل بنجاح
                        </div>
                    </div>
        <?php
                    header("refresh:2;url=posts_management.php");
                } else {
                    echo "Error updating record: " . mysqli_error($conn);
                }

                mysqli_close($conn);
            }
        }
        ?>
        <div class="container">
            <div class="d-flex w-100 mt-4 justify-content-between align-items-center">
                <h4>انشاء منشور جديد</h4>
            </div>
            <form action="?section=edit&&id=<?php echo $id ?>" method="post" enctype="multipart/form-data" style="width: 100%; max-width: 450px;" class="mt-3 d-flex flex-column gap-2">
                <div class="form-group">
                    <label for="post">محتوى المنشور</label>
                    <textarea name="post" id="post" class="form-control" id="exampleFormControlTextarea1" rows="3" required> <?php echo $row['post'] ?></textarea>
                </div>
                <div class="form-group">
                    <label for="file">ارفاق صورة</label>
                    <input type="file" name="file" id="file" class="form-control-file" id="exampleFormControlFile1">
                </div>
                <button type="submit" class="btn btn-primary" name="send">تعديل</button>
            </form>
        </div>

        <?php
    } elseif ($section == "destroy") {
        $id = intval($_GET['id']);

        require '../../connect.php';
        $sql = "SELECT * FROM posts WHERE id = '$id'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        mysqli_close($conn);

        require '../../connect.php';

        $sql = "DELETE FROM posts WHERE id = '$id'";

        if (mysqli_query($conn, $sql)) {
        ?>
            <div class="container" style="margin: 16px 0;">
                <div class="alert alert-success" role="alert">
                    تم الحذف بنجاح
                </div>
            </div>
    <?php

            if (!empty($row['file'])) {
                if (file_exists('../uploads/' . $row['file'])) {
                    unlink('../uploads/' . $row['file']);
                }
            }

            header("refresh:2;url=posts_management.php");
        } else {
            echo "Error deleting record: " . mysqli_error($conn);
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