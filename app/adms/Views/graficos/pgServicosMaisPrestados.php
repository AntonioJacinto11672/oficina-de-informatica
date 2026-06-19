<?php
if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}
//var_dump($this->dados);
/* for ($index1 = 0; $index1 < count($this->dados['nome']); $index1++) {
  echo $this->dados['nome'][$index1];
  } */
?>
<!--Begin Page Content -->
<div class = "container-fluid">


    <!-- Page Heading -->
    
    <!-- Content Row -->
    <div class="row">

        <div class="col-xl-12 col-lg-12">

            <!-- Area Chart -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Grafico de Serviços Mais prestados</h6>
                </div>
                <div class="card-body">

                    <figure class="highcharts-figure">
                        <div id="container"></div>
                        <p class="highcharts-description">
                            <?php
                            if (isset($_SESSION['msg_gerafico1']) AND !isset($_SESSION['msg_gerafico2'])) {
                                echo $_SESSION['msg_gerafico1'];
                            } elseif (!isset ($_SESSION['msg_gerafico1']) AND isset($_SESSION['msg_gerafico2'])) {
                                echo $_SESSION['msg_gerafico2'];
                            } elseif (isset($_SESSION['msg_gerafico1']) AND isset($_SESSION['msg_gerafico2'])) {
                                echo $_SESSION['msg_gerafico1'] . " " . $_SESSION['msg_gerafico2'];
                            }
                            ?>
                        </p>
                    </figure>



                    <script type="text/javascript">
                        // Radialize the colors
                        Highcharts.setOptions({
                            colors: Highcharts.map(Highcharts.getOptions().colors, function (color) {
                                return {
                                    radialGradient: {
                                        cx: 0.5,
                                        cy: 0.3,
                                        r: 0.7
                                    },
                                    stops: [
                                        [0, color],
                                        [1, Highcharts.color(color).brighten(-0.3).get('rgb')] // darken
                                    ]
                                };
                            })
                        });

                        // Build the chart
                        Highcharts.chart('container', {
                            chart: {
                                plotBackgroundColor: null,
                                plotBorderWidth: null,
                                plotShadow: false,
                                type: 'pie'
                            },
                            title: {
                                text: 'Os Tipo de Serviços Mais Prestados Na Oficina, de <?php echo date("Y"); ?>'
                            },
                            tooltip: {
                                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                            },
                            accessibility: {
                                point: {
                                    valueSuffix: '%'
                                }
                            },
                            plotOptions: {
                                pie: {
                                    allowPointSelect: true,
                                    cursor: 'pointer',
                                    dataLabels: {
                                        enabled: true,
                                        format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                                        connectorColor: 'silver'
                                    }
                                }
                            },
                            series: [{
                                    name: 'Quantidade',
                                    data: [
<?php
for ($index = 0; $index < count($this->dados['nome']); $index++) {
    echo " {name: '" . $this->dados['nome'][$index] . "', y: " . $this->dados['valor'][$index] . "},";
}
?>

                                    ]
                                }]
                        });
                    </script>
                </div>
            </div>

            <!-- Bar Chart -->


        </div>

        <!-- Donut Chart -->

    </div>
</div>
<!-- /.container-fluid -->
