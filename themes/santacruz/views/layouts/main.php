<!DOCTYPE html>
<html lang="<?= Yii::app()->language ?>">
    
    <?php
    /**
     * Created by JetBrains PhpStorm.
     * User: lightspeed
     * Date: 9/21/13
     * Time: 12:40 PM
     * To change this template use File | Settings | File Templates.
     */
    ?>

    <!-- Head -->
    <?=$this->renderPartial("/site/_head",null,true,false) ?>

	<?php echo $this->sharingHeader; ?>

    <!-- Header -->
    <?=$this->renderPartial("/site/_header",null,true,false) ?>

    <!-- Nav Bar -->

    <?=$this->renderPartial("/site/_navbar",null,true,false) ?>

    <div class="container">
        <?= $content ?>
    </div>

    </div>
    <!-- Footer -->
    <?=$this->renderPartial("/site/_footer",null,true,false) ?>

	<?php echo $this->sharingFooter; ?>

</html>

