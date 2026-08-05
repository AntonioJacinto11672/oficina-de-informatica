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

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#">
    <i class="fas fa-angle-up"></i>
</a>

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
<!-- Counter-Up (local) -->
<script src="<?php echo URLADM; ?>app/adms/assets/vendor/counterup/jquery.counterup.min.js"></script>
<!-- OWL Carousel (local) -->
<script src="<?php echo URLADM; ?>app/adms/assets/vendor/owlcarousel/js/owl.carousel.min.js"></script>
<!-- Isotope (local) -->
<script src="<?php echo URLADM; ?>app/adms/assets/vendor/isotope/isotope.pkgd.min.js"></script>
<!-- AOS (local) -->
<script src="<?php echo URLADM; ?>app/adms/assets/vendor/aos/aos.js"></script>

<!-- JS Local -->
<script src="<?php echo URLADM; ?>app/adms/assets/js/main.js"></script>
<script src="<?php echo URLADM; ?>app/adms/assets/js/form-validation.js"></script>
<script src="<?php echo URLADM; ?>app/adms/assets/js/sb-admin-2.min.js"></script>
<script src="<?php echo URLADM; ?>app/adms/assets/js/personalizado.js"></script>

<!-- DataTables (local) -->
<script src="<?php echo URLADM; ?>app/adms/assets/vendor/datatables/js/jquery.dataTables.min.js"></script>
<script src="<?php echo URLADM; ?>app/adms/assets/vendor/datatables/js/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo URLADM; ?>app/adms/assets/js/datatables-demo.js"></script>

<!-- Chart.js (local) -->
<script src="<?php echo URLADM; ?>app/adms/assets/plugins/chart.js/Chart.min.js"></script>
<script src="<?php echo URLADM; ?>app/adms/assets/js/demo/chart-area-demo.js"></script>
<script src="<?php echo URLADM; ?>app/adms/assets/js/demo/chart-pie-demo.js"></script>
<script src="<?php echo URLADM; ?>app/adms/assets/js/demo/chart-bar-demo.js"></script>

<!-- Highcharts (local) -->
<script src="<?php echo URLADM; ?>app/adms/assets/vendor/highcharts/highcharts.js"></script>
<script src="<?php echo URLADM; ?>app/adms/assets/vendor/highcharts/modules/exporting.js"></script>
<script src="<?php echo URLADM; ?>app/adms/assets/vendor/highcharts/modules/export-data.js"></script>
<script src="<?php echo URLADM; ?>app/adms/assets/vendor/highcharts/modules/accessibility.js"></script>
<script src="<?php echo URLADM; ?>app/adms/assets/vendor/highcharts/modules/data.js"></script>
<script src="<?php echo URLADM; ?>app/adms/assets/vendor/highcharts/modules/drilldown.js"></script>

</body>
</html>
