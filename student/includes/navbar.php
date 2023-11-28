<nav style="background-color: #091010;" class=" py-3 gap-2 d-flex flex-wrap justify-content-between align-items-center px-4 text-white">
  <div class="d-flex gap-2 align-items-center">
    <img src="../../assets/imgs/logo.png" width="60" height="60" alt="">
    <h5>أتمتة البيانات - المعهد الفني للحاسوب</h5>
  </div>
  <nav class="stu-navbar d-flex gap-3">
    <a href="home_page.php" class="stu-nav-link text-decoration-none">الصفحة الرئيسية</a>
    <a href="posts.php" class="stu-nav-link text-decoration-none ">المنشورات</a>
    <?php
    if (isset($_SESSION['id'])) {
    ?>
      <a href="services.php" class="stu-nav-link text-decoration-none ">طلب الخدمات</a>
      <a href="exams_marks.php" class="stu-nav-link text-decoration-none ">عرض العلامات</a>
    <?php
    }
    ?>
  </nav>
  <?php
if (isset($_SESSION['id']) && !isset($_SESSION['type'])) {
  ?>
    <div>
      <a href="../logout.php" class="text-white">تسجيل الخروج</a>
      <a href="new_pass.php" class="text-white">تعديل كلمة المرور</a>
    </div>
  <?php
  } else { ?>
    <a href="../logout.php" class="text-white">تسجيل الدخول</a>
  <?php
  }
  ?>
</nav>
<script>
  const url = location.href.split("?");

  const sideLinks = document.querySelectorAll(".stu-nav-link");

  sideLinks.forEach((link) => {
    if (url.includes(link.href)) {
      link.classList.add("active");
    }
  })
</script>