<?php
if (!defined('R4F5CC')) {
    header("Location: /");
    die("Erro: Página não encontrada!");
}
//var_dump($this->dados);
?>
<!--Begin Page Content -->
<div class = "container-fluid">
        <!-- Content Row -->
        <div class="row">

            <div class="col-xl-12 col-lg-12">

                <!-- Area Chart -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Gráfico de Movimentações Saídas</h6>
                    </div>
                    <div class="card-body">
                        <figure class="highcharts-figure">
                            <div id="container"></div>
                            <p class="highcharts-description">
                            <hr>
                            O Gráfico Seguinte Mostra As Saída de Movimentações do Ano Corrente de <?php echo date("Y");?> em cada Mês Até O Mês Atual
                            </p>
                            </figure>




                        <script type="text/javascript">
                            var chart = Highcharts.chart('container', {
                                labels: {
                                    format: '{value}°C'
                                    
                                },
                                title: {
                                    text: 'Valor Total Das Movimentação Do Ano de <?php echo date("Y");?>'
                                },

                                subtitle: {
                                    text: 'Valor á Kwanza'
                                },

                                xAxis: {
                                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
                                },

                                series: [{
                                        type: 'column',
                                        colorByPoint: true,
                                        data: [<?php  if(isset($this->dados['janeiro'])){echo $this->dados['janeiro'];}else{echo "0";}?>,
                                               <?php  if(isset($this->dados['fevereiro'])){echo $this->dados['fevereiro'];}else{echo "0";}?>,
                                               <?php  if(isset($this->dados['marco'])){echo $this->dados['marco'];}else{echo "0";}?>,
                                               <?php  if(isset($this->dados['abril'])){echo $this->dados['abril'];}else{echo "0";}?>,
                                               <?php  if(isset($this->dados['maio'])){echo $this->dados['maio'];}else{echo "0";}?>,
                                               <?php  if(isset($this->dados['junho'])){echo $this->dados['junho'];}else{echo "0";}?>,
                                               <?php  if(isset($this->dados['julho'])){echo $this->dados['julho'];}else{echo "0";}?>,
                                               <?php  if(isset($this->dados['agosto'])){echo $this->dados['agosto'];}else{echo "0";}?>,
                                               <?php  if(isset($this->dados['setembro'])){echo $this->dados['setembro'];}else{echo "0";}?>,
                                               <?php  if(isset($this->dados['outobro'])){echo $this->dados['outobro'];}else{echo "0";}?>,
                                               <?php  if(isset($this->dados['novembro'])){echo $this->dados['novembro'];}else{echo "0";}?>,
                                               <?php  if(isset($this->dados['dez embro'])){echo $this->dados['dezembro'];}else{echo "0";}?>],
                                        showInLegend: false
                                    }]

                            });


                            $('#plain').click(function () {
                                chart.update({
                                    chart: {
                                        inverted: false,
                                        polar: false
                                    },
                                    subtitle: {
                                        text: 'Plain'
                                    }
                                });
                            });

                            $('#inverted').click(function () {
                                chart.update({
                                    chart: {
                                        inverted: true,
                                        polar: false
                                    },
                                    subtitle: {
                                        text: 'Inverted'
                                    }
                                });
                            });

                            $('#polar').click(function () {
                                chart.update({
                                    chart: {
                                        inverted: false,
                                        polar: true
                                    },
                                    subtitle: {
                                        text: 'Polar'
                                    }
                                });
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
