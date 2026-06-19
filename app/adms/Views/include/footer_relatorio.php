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
            <span>Copyright &copy; Itel 2020</span>
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
                <h5 class="modal-title" id="exampleModalLabel">Tens A Certeza Que Queres Sair?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Clica "Sair" Para terminar a Sessão.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="<?php echo URLADM; ?>sair">Sair</a>
            </div>
        </div>
    </div>
</div>

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
<!-- OWL Carousel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<!-- Isotope -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.isotope/3.0.6/isotope.pkgd.min.js"></script>
<!-- AOS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>

<!-- JS Local -->
<script src="<?php echo URLADM; ?>app/adms/assets/js/main.js"></script>
<script src="<?php echo URLADM; ?>app/adms/assets/js/personalizado.js"></script>
<script src="<?php echo URLADM; ?>app/adms/assets/js/form-validation.js"></script>

<!-- jQuery UI -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>
<script>
    if (typeof $.widget !== 'undefined' && typeof $.ui !== 'undefined') {
        $.widget.bridge('uibutton', $.ui.button);
    }
</script>

<!-- OverlayScrollbars -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/overlayscrollbars/1.13.3/js/jquery.overlayScrollbars.min.js"></script>

<!-- AdminLTE dist JS (local) -->
<script src="<?php echo URLADM; ?>app/adms/assets/dist/js/adminlte.js"></script>
<script src="<?php echo URLADM; ?>app/adms/assets/dist/js/demo.js"></script>

<!-- Chart.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>

<!-- Moment.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<!-- Daterange picker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs4.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>

<!-- BS-Stepper -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bs-stepper/1.7.0/js/bs-stepper.min.js"></script>

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
