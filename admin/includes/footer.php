 </div>
 </div>

 <script>
     //  1
     const closeIcon = document.getElementById("side-close-icon");
     const showIcon = document.getElementById("side-show-icon");

     closeIcon.onclick = () => {
         document.querySelector(".sidebar").classList.add("active");
     };

     showIcon.onclick = () => {
         document.querySelector(".sidebar").classList.remove("active");
     };

     // 2
     const url = location.href.split("?");

     const sideLinks = document.querySelectorAll(".nav-link");

     sideLinks.forEach((link) => {
         if (url.includes(link.href)) {
             link.classList.add("active");
         }
     })
 </script>

 <script src="../../assets/bootstrap/bootstrap.bundle.min.js"></script>
 <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
 <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
 </body>

 </html>