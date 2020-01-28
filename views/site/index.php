<?php

/* @var $this yii\web\View */
/* @var $loyalty_points int */
/* @var $money float */

$this->title = 'Test task';
?>
<div class="site-index">

    <div class="row">
        <div class="col-md-12">
            <div class="col-md-3 col-md-offset-6">
                <p>Money: <?=$money?></p>
            </div>

            <div class="col-md-3">
                <p>Loyalty points: <?=$loyalty_points?></p>
            </div>
        </div>
    </div>

    <div class="jumbotron">
        <h1>Congratulations!</h1>

        <p class="lead">You can get a prize.</p>

        <p><a id="btn-prize" class="btn btn-lg btn-success" >Click on me and get a prize</a></p>
    </div>
</div>


<?php
$script = <<< JS
    $(document).ready(function() {   
       $('#btn-prize').on('click', function(){
           GetPrize();
       });
    });
   
JS;
$this->registerJs($script, \yii\web\View::POS_END);
?>
