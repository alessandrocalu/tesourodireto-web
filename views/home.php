            <!-- start: Content -->
            <div id="content" class="span10">
            
            <ul class="breadcrumb">
                <li>
                    <i class="icon-bar-chart"></i>
                    <a href="index.html">Visão Geral</a> 
                </li>
            </ul>

            <div class="row-fluid">                
                <div class="span3 statbox purple" ontablet="span6" ondesktop="span3">
                    <div class="number"><?php echo number_format($total_comprado,0,',','.').'&nbsp;'; ?><i class="icon-money"></i></div>
                    <div class="title">reais</div>
                    <div class="footer">
                        Total Investido(R$)
                    </div>  
                </div>
                <div class="span3 statbox green" ontablet="span6" ondesktop="span3">
                    <div class="number"><?php echo number_format($total_venda-$total_comprado,0,',','.').'&nbsp;'; ?><i class="<?php echo ($total_venda<$total_comprado?'icon-arrow-down':'icon-arrow-up'); ?>"></i></div>
                    <div class="title">reais</div>
                    <div class="footer">
                        Lucro Líquido Total(R$)
                    </div>
                </div>
                <div class="span3 statbox blue noMargin" ontablet="span6" ondesktop="span3">
                    <div class="number"><?php echo ($total_comprado?($total_venda<$total_comprado?'-':'').number_format((($total_venda-$total_comprado)/$total_comprado)*100,2,',','.'):'0,00').'&nbsp;'; ?><i class="<?php echo ($total_venda<$total_comprado?'icon-arrow-down':'icon-arrow-up'); ?>"></i></div>
                    <div class="title">porcento</div>
                    <div class="footer">
                        Rentabilidade Líquida(%)
                    </div>
                </div>
                <div class="span3 statbox yellow" ontablet="span6" ondesktop="span3">
                    <div class="number"><?php echo number_format($total_venda,0,',','.').'&nbsp;'; ?><i class="icon-money"></i></div>
                    <div class="title">reais</div>
                    <div class="footer">
                        Valor Líquido Atual(R$)
                    </div>
                </div>  
                
            </div>

            <div class="row-fluid">

                <div class="span8" onTablet="span8" onDesktop="span8">
                    <div class="row-fluid">
                        <div class="span12" onTablet="span12" onDesktop="span12">
                            <div class="header text-center"><b>Valor Investido</b><br> Total: <?php echo 'R$ '.number_format($total_comprado,2,',','.'); ?>
                                <br>
                                <br>
                            </div>
                            <div id="hover"></div> 
                            <div id="piechart" style="height:250px"></div>
                                <br>
                                <br>
                        </div>    
                    </div>   
                    <div class="row-fluid">    
                        <div class="span12" onTablet="span12" onDesktop="span12">
                            <div class="header text-center"><b>Valor Líquido Atual</b><br> Total: <?php echo 'R$ '.number_format($total_venda,2,',','.'); ?>
                                <br>
                                <br>
                            </div>
                            <div id="hover2"></div>  
                            <div id="piechart2" style="height:250px"></div>
                                <br>
                                <br>
                        </div>    
                    </div>
                    <div class="widget blue">
                        <div class="header text-center">Lucro Liquido Total(R$) nos últimos 30 dias (top 30)</div>

                        <div id="stats-chart2"  style="height:150px" ></div>
                    </div>
                         
                </div>
                
                <div class="span4" onTablet="span4" onDesktop="span4">
                      
                    <div class="widget green">
                        <ul class="unstyled">
                        <?php
                            reset($grafico_carteira_atual);
                            foreach ($grafico_carteira_atual as $atual){
                        ?>

                            <li><h2><?php echo $atual["sigla"];?></h2><br>
                                <p><?php echo $atual["nome"];?>
                                    <br>&nbsp;&raquo;Valor Investido(R$)&nbsp;
                                        <b>
                                            <?php echo number_format($atual["comprado"]*$atual["quantidade"],2,',','.'); ?>
                                        </b>
                                    <br>&nbsp;&raquo;Lucro Líquido(R$)&nbsp;
                                        <b>
                                            <?php echo number_format(($atual["compra"]-$atual["comprado"])*$atual["quantidade"],2,',','.'); ?>
                                        </b> 
                                    <br>&nbsp;&raquo;Rentabilidade Líquida(%)&nbsp;
                                        <b>
                                            <?php echo number_format((($atual["compra"]-$atual["comprado"])/$atual["comprado"])*100,2,',','.'); ?>
                                        </b>    
                                    <br>&nbsp;&raquo;Valor Líquido Atual(R$)&nbsp;
                                        <b>
                                            <?php echo number_format($atual["compra"]*$atual["quantidade"],2,',','.'); ?>
                                        </b>        
                                    </p>
                            </li>

                        <?php
                            }
                        ?>    
                        </ul>
                        
                        <div class="clearfix"></div>

                    </div><!-- End .sparkStats -->
                </div>    
            </div>
            
            
    </div><!--/.fluid-container-->
    
            <!-- end: Content -->
        </div><!--/#content.span10-->
        </div><!--/fluid-row-->
        
    <div class="modal hide fade" id="myModal">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h3>Settings</h3>
        </div>
        <div class="modal-body">
            <p>Here settings can be configured...</p>
        </div>
        <div class="modal-footer">
            <a href="#" class="btn" data-dismiss="modal">Close</a>
            <a href="#" class="btn btn-primary">Save changes</a>
        </div>
    </div>
    
    <div class="common-modal modal fade" id="common-Modal1" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-content">
            <ul class="list-inline item-details">
                <li><a href="http://themifycloud.com">Admin templates</a></li>
                <li><a href="http://themescloud.org">Bootstrap themes</a></li>
            </ul>
        </div>
    </div>
    
    <div class="clearfix"></div>   

<script>

function float2moeda(num){  

    x = 0;   
    
    if(num < 0){
        num = Math.abs(num);
        x = 1;
    }
    
    if(isNaN(num)) num = "0";
    
    num = Math.floor((num*100+0.5)/100).toString();
    
    for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
    
    num = num.substring(0,num.length-(4*i+3))+'.'
         +num.substring(num.length-(4*i+3));
    ret = num;
    
    if (x == 1) ret = ' - ' + ret;
    
    return ret; 
}


/* ---------- Charts ---------- */
gerar_graficos = true;
function charts_home() {
    
    /* ---------- Chart with points ---------- */
    if($("#stats-chart2").length)
    {   

        <?php
            reset($grafico_carteira_cotacoes);
            $contador = 0;
            foreach ($grafico_carteira_cotacoes as $linha){
        ?>
                var linha_<?php  echo $contador; ?> = [

                <?php
                    $dados = $linha["dados"];
                    reset($dados);
                    $i = 0;
                    foreach ($dados as $dado){
                        if ($i > 0){
                            echo ",";
                        } 
                        echo '['.($i+1).','.$dado["value"].']';
                        $i++;
                    }
                ?>

                ];

                var labels = [];

                <?php
                    $dados = $linha["dados"];
                    reset($dados);
                    $i = 0;
                    foreach ($dados as $dado){
                        echo "labels[".($i+1)."] = '".$dado["label"]."';";
                        $i++;
                    }
                ?>

        <?php
                $contador++;
            }
        ?>
        
        var plot = $.plot($("#stats-chart2"),
               [ 
        <?php
                reset($grafico_carteira_cotacoes);
                $contador = 0;
                foreach ($grafico_carteira_cotacoes as $linha){
                    if($contador > 0){
                        echo ",";
                    }
        ?>            
                 { data: linha_<?php echo $contador;  ?>, 
                   label: "<?php echo  $linha["sigla"]; ?>", 
                   lines: { show: true, 
                            fill: false,
                            lineWidth: 2 
                          },
                   shadowSize: 0    
                  }   
        <?php
                    $contador++;
                }
        ?>    
                ], {
                   
                   grid: { hoverable: true, 
                           clickable: true, 
                           tickColor: "rgba(255,255,255,0.05)",
                           borderWidth: 0
                         },
                 legend: {
                            show: false
                        },  
                colors:  ["#FA5833", "#2FABE9", "#FABB3D", "#78CD51"],
                xaxis: {ticks:15, tickFormatter: "string", show: false, color: "rgba(255,255,255,0.8)" },
                yaxis: {ticks:5, tickDecimals: 0, color: "rgba(255,255,255,0.8)" },
                });
        
        
                

        function showTooltip(x, y, contents) {
            $('<div id="tooltip">' + contents + '</div>').css( {
                position: 'absolute',
                display: 'none',
                top: y + 5,
                left: x + 5,
                border: '1px solid #fdd',
                padding: '2px',
                'background-color': '#dfeffc',
                opacity: 0.80
            }).appendTo("body").fadeIn(200);
        }

        var previousPoint = null;
        $("#stats-chart2").bind("plothover", function (event, pos, item) {
            $("#x").text(pos.x.toFixed(2));
            $("#y").text(pos.y.toFixed(2));

                if (item) {
                    if (previousPoint != item.dataIndex) {
                        previousPoint = item.dataIndex;

                        $("#tooltip").remove();
                        var x = item.datapoint[0].toFixed(0),
                            y = item.datapoint[1].toFixed(2);

                        showTooltip(item.pageX, item.pageY,
                                    item.series.label + " de " + labels[x] + " = " + y);
                    }
                }
                else {
                    $("#tooltip").remove();
                    previousPoint = null;
                }
        });
    
    }
    
       

    /* ---------- Pie chart ---------- */
    var data = [
    <?php
    reset($grafico_carteira_montante);
    $contador = 0;
    foreach ($grafico_carteira_montante as $fatiaM){
        if($contador > 0){
            echo ",";
        }
        echo '{ label: "'.$fatiaM["sigla"].': R$ '.number_format($fatiaM["montante"],2,',','.').'",  data: '.$fatiaM["montante"].'}';
        $contador++;    
    }    
    ?>    
    ];
    
    if($("#piechart").length)
    {
        $.plot($("#piechart"), data,
        {
            series: {
                    pie: {
                            show: true
                    }
            },
            grid: {
                    hoverable: true,
                    clickable: true
            },
            legend: {
                show: true
            },
            colors: ["#FA5833", "#2FABE9", "#FABB3D", "#78CD51"]
        });
        
        function pieHover(event, pos, obj)
        {
            if (!obj)
                    return;
            valor = float2moeda(Math.round(obj.series.data[0][1]));

            $("#hover").html('<span style="font-weight: bold; color: '+obj.series.color+'">'+valor+' reais</span>');
        }
        $("#piechart").bind("plothover", pieHover);
    }


    /* ---------- Pie chart 2 ---------- */
    var data2 = [
    <?php
    reset($grafico_carteira_montante_atual);
    $contador = 0;
    foreach ($grafico_carteira_montante_atual as $fatiaM){
        if($contador > 0){
            echo ",";
        }
        echo '{ label: "'.$fatiaM["sigla"].': R$ '.number_format($fatiaM["montante"],2,',','.').'",  data: '.$fatiaM["montante"].'}';
        $contador++;    
    }    
    ?>    
    ];
    
    if($("#piechart2").length)
    {
        $.plot($("#piechart2"), data2,
        {
            series: {
                    pie: {
                            show: true
                    }
            },
            grid: {
                    hoverable: true,
                    clickable: true
            },
            legend: {
                show: true
            },
            colors: ["#FA5833", "#2FABE9", "#FABB3D", "#78CD51"]
        });
        
        function pieHover(event, pos, obj)
        {
            if (!obj)
                    return;
            valor = float2moeda(Math.round(obj.series.data[0][1]));

            $("#hover2").html('<span style="font-weight: bold; color: '+obj.series.color+'">'+valor+' reais</span>');
        }
        $("#piechart2").bind("plothover", pieHover);
    }
}    

</script>