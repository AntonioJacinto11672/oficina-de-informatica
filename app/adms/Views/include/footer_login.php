<?php
if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}
?>
<footer id="footer" data-aos="fade-up" data-aos-easing="ease-in-out" data-aos-duration="500">

    <!-- W3 Slide de form e outros-->
    <script>
        var slideIndex = 1;
        showDivs(slideIndex);

        function plusDivs(n) {
            showDivs(slideIndex += n);
        }

        function currentDiv(n) {
            showDivs(slideIndex = n);
        }

        function showDivs(n) {
            var i;
            var x = document.getElementsByClassName("mySlides");
            var dots = document.getElementsByClassName("demo");
            if (n > x.length) { slideIndex = 1; }
            if (n < 1) { slideIndex = x.length; }
            for (i = 0; i < x.length; i++) {
                x[i].style.display = "none";
            }
            for (i = 0; i < dots.length; i++) {
                dots[i].classList.remove("w3-blue");
            }
            x[slideIndex - 1].style.display = "block";
            dots[slideIndex - 1].classList.add("w3-blue");
        }
    </script>

    <!-- jQuery (obrigatório primeiro, local) -->
    <script src="<?php echo URLADM; ?>app/adms/assets/vendor/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 Bundle (inclui Popper.js, local) -->
    <script src="<?php echo URLADM; ?>app/adms/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery Easing (local) -->
    <script src="<?php echo URLADM; ?>app/adms/assets/vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- jQuery Sticky (local) -->
    <script src="<?php echo URLADM; ?>app/adms/assets/vendor/jquery-sticky/jquery.sticky.min.js"></script>
    <!-- Waypoints (local) -->
    <script src="<?php echo URLADM; ?>app/adms/assets/vendor/waypoints/jquery.waypoints.min.js"></script>
    <!-- Counter-Up (local) -->
    <script src="<?php echo URLADM; ?>app/adms/assets/vendor/counterup/jquery.counterup.min.js"></script>
    <!-- OWL Carousel (local) -->
    <script src="<?php echo URLADM; ?>app/adms/assets/vendor/owlcarousel/js/owl.carousel.min.js"></script>
    <!-- Isotope (local) -->
    <script src="<?php echo URLADM; ?>app/adms/assets/vendor/isotope/isotope.pkgd.min.js"></script>
    <!-- AOS (local) -->
    <script src="<?php echo URLADM; ?>app/adms/assets/vendor/aos/aos.js"></script>

    <!-- JS Local -->
    <script src="<?php echo URLADM; ?>app/adms/assets/js/personalizado.js"></script>
    <script src="<?php echo URLADM; ?>app/adms/assets/js/form-validation.js"></script>
    <script src="<?php echo URLADM; ?>app/adms/assets/js/sb-admin-2.min.js"></script>
    <script src="<?php echo URLADM; ?>app/adms/assets/js/main.js"></script>
</body>
</html>
