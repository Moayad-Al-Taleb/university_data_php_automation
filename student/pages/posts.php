<?php
session_start();
?>
<?php include "../includes/header.php";

require '../../connect.php';

$sql = "SELECT * FROM posts ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
?>
<div class="container d-flex flex-column gap-3" style="min-height: calc(100vh - 92px);">
    <?php
    if (mysqli_num_rows($result) > 0) {
    ?>

        <h2 class="title text-center my-5">تصفح المنشورات</h2>
        <div style="display: flex;" class="gap-3 flex-wrap justify-content-between align-items-start my-5">
            <?php

            while ($row = mysqli_fetch_assoc($result)) {

                if (!empty($row['file'])) {
                    $imageURL = "../../admin/uploads/" . $row['file'];
                } else {
                    $imageURL = null;
                }

            ?>
                <div class="card my-2" style="width: 48%;">
                    <?php
                    if ($imageURL != null) {
                    ?>
                        <img class="card-img-top" src="<?php echo $imageURL ?>" style="width: 100%; max-height: 350px;" alt="Card image cap">
                    <?php
                    }
                    ?>
                    <div class="card-body">
                        <p class="card-text"><?php echo $row['post'] ?></p>
                    </div>
                </div>

            <?php
            }

            ?>
        </div>
</div>
<?php
    } else {
?>
    <div class="alert alert-warning my-3">لايوجد اي منشورات مضافة</div>
<?php
    }
    mysqli_close($conn);
?>
</div>
<?php include "../includes/footer.php"; ?>