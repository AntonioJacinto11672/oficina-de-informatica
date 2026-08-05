<?php
if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}
?>
</div>
<!-- End of Main Content -->

<!-- Footer -->
<footer class="sticky-footer bg-white">
    <div class="container my-auto">
        <div class="copyright text-center my-auto">
            <span>Copyright &copy; ULA 2026</span>
        </div>
    </div>
</footer>
<!-- End of Footer -->

</div>
<!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Terminar sessão?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Clique em "Sair" para terminar a sessão.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                <a class="btn btn-primary" href="<?php echo URLADM; ?>sair">Sair</a>
            </div>
        </div>
    </div>
</div>

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
<!-- OWL Carousel (local) -->
<script src="<?php echo URLADM; ?>app/adms/assets/vendor/owlcarousel/js/owl.carousel.min.js"></script>
<!-- Isotope (local) -->
<script src="<?php echo URLADM; ?>app/adms/assets/vendor/isotope/isotope.pkgd.min.js"></script>
<!-- AOS (local) -->
<script src="<?php echo URLADM; ?>app/adms/assets/vendor/aos/aos.js"></script>

<!-- JS Local -->
<script src="<?php echo URLADM; ?>app/adms/assets/js/main.js"></script>
<script src="<?php echo URLADM; ?>app/adms/assets/js/personalizado.js"></script>
<script src="<?php echo URLADM; ?>app/adms/assets/js/form-validation.js"></script>

<!-- jQuery UI (local) -->
<script src="<?php echo URLADM; ?>app/adms/assets/vendor/jquery-ui/jquery-ui.min.js"></script>
<script>
    if (typeof $.widget !== 'undefined' && typeof $.ui !== 'undefined') {
        $.widget.bridge('uibutton', $.ui.button);
    }
</script>

<!-- OverlayScrollbars (local) -->
<script src="<?php echo URLADM; ?>app/adms/assets/vendor/overlayscrollbars/js/jquery.overlayScrollbars.min.js"></script>

<!-- AdminLTE dist JS (local) -->
<script src="<?php echo URLADM; ?>app/adms/assets/dist/js/adminlte.js"></script>
<script src="<?php echo URLADM; ?>app/adms/assets/dist/js/demo.js"></script>

<!-- Chart.js (local) -->
<script src="<?php echo URLADM; ?>app/adms/assets/plugins/chart.js/Chart.min.js"></script>

<!-- Moment.js (local) -->
<script src="<?php echo URLADM; ?>app/adms/assets/vendor/moment/moment.min.js"></script>
<!-- Daterange picker (local) -->
<script src="<?php echo URLADM; ?>app/adms/assets/vendor/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 (local) -->
<script src="<?php echo URLADM; ?>app/adms/assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote (local) -->
<script src="<?php echo URLADM; ?>app/adms/assets/vendor/summernote/summernote-bs4.min.js"></script>

<!-- DataTables (local) -->
<script src="<?php echo URLADM; ?>app/adms/assets/vendor/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?php echo URLADM; ?>app/adms/assets/vendor/datatables/js/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo URLADM; ?>app/adms/assets/vendor/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php echo URLADM; ?>app/adms/assets/vendor/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?php echo URLADM; ?>app/adms/assets/vendor/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?php echo URLADM; ?>app/adms/assets/vendor/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="<?php echo URLADM; ?>app/adms/assets/vendor/jszip/jszip.min.js"></script>
<script src="<?php echo URLADM; ?>app/adms/assets/vendor/pdfmake/pdfmake.min.js"></script>
<script src="<?php echo URLADM; ?>app/adms/assets/vendor/pdfmake/vfs_fonts.js"></script>
<script src="<?php echo URLADM; ?>app/adms/assets/vendor/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="<?php echo URLADM; ?>app/adms/assets/vendor/datatables-buttons/js/buttons.print.min.js"></script>
<script src="<?php echo URLADM; ?>app/adms/assets/vendor/datatables-buttons/js/buttons.colVis.min.js"></script>

<!-- BS-Stepper (local) -->
<script src="<?php echo URLADM; ?>app/adms/assets/plugins/bs-stepper/js/bs-stepper.min.js"></script>

<!-- Page specific scripts -->
<script>
    $(function () {
        if ($.fn.DataTable && $('#example1').length) {
            $("#example1").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        }
        if ($.fn.DataTable && $('#example2').length) {
            $('#example2').DataTable({
                "paging": true, "lengthChange": false, "searching": false,
                "ordering": true, "info": true, "autoWidth": false, "responsive": true,
            });
        }
    });
</script>

<!-- W3 Slide -->
<script>
    var slideIndex = 1;
    if (document.getElementsByClassName("mySlides").length > 0) {
        showDivs(slideIndex);
    }

    function plusDivs(n) { showDivs(slideIndex += n); }
    function currentDiv(n) { showDivs(slideIndex = n); }

    function showDivs(n) {
        var i;
        var x = document.getElementsByClassName("mySlides");
        var dots = document.getElementsByClassName("demo");
        if (n > x.length) { slideIndex = 1; }
        if (n < 1) { slideIndex = x.length; }
        for (i = 0; i < x.length; i++) { x[i].style.display = "none"; }
        for (i = 0; i < dots.length; i++) { dots[i].classList.remove("w3-blue"); }
        if (x[slideIndex - 1]) { x[slideIndex - 1].style.display = "block"; }
        if (dots[slideIndex - 1]) { dots[slideIndex - 1].classList.add("w3-blue"); }
    }
</script>

<script>
    $(function () {
        if ($('#compose-textarea').length && $.fn.summernote) {
            $('#compose-textarea').summernote();
        }
    });
</script>

</body>
</html>
