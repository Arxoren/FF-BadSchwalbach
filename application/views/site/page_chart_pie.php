<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="row chart_module">
    
    <?php foreach($chartdata as $chart) { ?>

        <div class="double_pie">
            <h3><?php echo $chart['chart_name']; ?></h3>
            <canvas id="chart_<?php echo $chart['chartID']; ?>" width="400" height="400"></canvas>
         
            <script>
                var ctx = document.getElementById("chart_<?php echo $chart['chartID']; ?>");
                var myChart = new Chart(ctx, {
                    type: '<?php echo $chart["chart_type"]; ?>',
                    animation: {
                        animateScale:true
                    },
                    data: {
                        labels: [
                            <?php 
                                foreach($chart["chart_labels"] as $labels) {
                                    echo '"'.$labels.'",';
                                }
                            ?>
                        ],
                        datasets: [{
                            label: 'Einsätze',
                            data: [
                                <?php 
                                    foreach($chart["chart_data"] as $data) {
                                        echo ''.$data.',';
                                    }
                                ?>
                            ],
                            backgroundColor: [
                               <?php 
                                foreach($chart["chart_datacolor"] as $colors) {
                                    echo '"'.$colors.'",';
                                }
                                ?>
                            ],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        legend: {
                            display: false,
                            position: 'bottom',
                        }
                    }
                });
            </script>
            <ul>
                <?php 

                    $i=0;

                    foreach($chart["chart_labels"] as $labels) {
                        echo '<li><span style="background-color: '.$chart["chart_datacolor"][$i].'"></span>'.$labels.'</li>';
                        $i++;
                    }
                ?> 
            </ul> 
        </div>
    <?php } ?>

    <hr class="clear" />
</div> 

