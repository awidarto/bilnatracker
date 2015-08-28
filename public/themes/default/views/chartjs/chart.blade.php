<canvas id="{{ $chartid }}" class="responsive-chart" height="300" width="600"></canvas>

<script type="text/javascript">
    $(document).ready(function(){

        var ctx = $("#{{ $chartid }}").get(0).getContext("2d");

        var options = {
            <?php
                $opt = array();
                foreach($options as $k=>$v){
                    if(is_bool($v)){
                        $v = ($v)?'true':'false';
                        $opt[] = $k.':'.$v;
                    }elseif(is_string($v)){
                        $opt[] = $k.':\''.$v.'\'';
                    }elseif(is_numeric($v)){
                        $opt[] = $k.':'.$v;
                    }
                }
                print implode(',', $opt);
            ?>
        };


        var {{ $chartid }}_data = {
                                labels: [
                                    <?php
                                        $opt = array();
                                        foreach($label as $v){
                                            if(is_bool($v)){
                                                $opt[] = ($v)?'true':'false';
                                            }elseif(is_string($v)){
                                                $opt[] = '\''.$v.'\'';
                                            }elseif(is_numeric($v)){
                                                $opt[] = $v;
                                            }
                                        }
                                        print implode(',', $opt);
                                    ?>
                                ],
                                datasets: [
                                    <?php

                                        $dr = array();
                                        foreach($data as $d){
                                            $dt = array();
                                            foreach($d as $k=>$v){
                                                if($k == 'data'){
                                                    $dt[] = 'data : ['.implode(',',$v).']';
                                                }else{
                                                    if(is_bool($v)){
                                                        $dt[] = $k.':'.($v)?'true':'false';
                                                    }elseif(is_string($v)){
                                                        $dt[] = $k.':\''.$v.'\'';
                                                    }elseif(is_numeric($v)){
                                                        $dt[] = $k.':'.$v;
                                                    }
                                                }

                                            }
                                            $dr[] = '{'.implode(',',$dt).'}';
                                        }
                                        $dr = implode(',', $dr);
                                        print($dr);
                                    ?>
                                ]
                            };

        var {{ $chartid }}_Chart = new Chart(ctx).{{ ucwords($type) }}({{ $chartid }}_data, options);
    });


</script>
