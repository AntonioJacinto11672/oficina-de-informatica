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

    <!-- jQuery (obrigatório primeiro) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <!-- Bootstrap 4 Bundle (inclui Popper.js) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery Easing -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
    <!-- jQuery Sticky -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.sticky/1.0.4/jquery.sticky.min.js"></script>
    <!-- Waypoints -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/waypoints/4.0.1/jquery.waypoints.min.js"></script>
    <!-- Counter-Up -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Counter-Up/1.0.0/jquery.counterup.min.js"></script>
    <!-- OWL Carousel -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <!-- Isotope -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.isotope/3.0.6/isotope.pkgd.min.js"></script>
    <!-- AOS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>

    <!-- JS Local -->
    <script src="<?php echo URLADM; ?>app/adms/assets/js/personalizado.js"></script>
    <script src="<?php echo URLADM; ?>app/adms/assets/js/form-validation.js"></script>
    <script src="<?php echo URLADM; ?>app/adms/assets/js/sb-admin-2.min.js"></script>
    <script src="<?php echo URLADM; ?>app/adms/assets/js/main.js"></script>
</body>
</html>
