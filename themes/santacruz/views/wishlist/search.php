<?php $this->layout='//layouts/column1'; ?>

<div id="wishlistdisplay">
    <div class="modal-header">
        <h2><?= Yii::t('global','Wish List Search'); ?></h2>
    </div>

    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'wishlistsearch',
        'enableClientValidation'=>true,
        'focus'=>array($model,'email'),
        'htmlOptions'=>array('role'=>'form'),
    ));
    ?>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-5">
                <p class="help-block"><?php echo Yii::t('global','Search for a wish list by email address:'); ?></p>

                <div class="form-group">
                    <?php echo $form->textField($model,'email',array('class'=>'form-control')); ?>
                    <?php echo $form->error($model,'email',array('class'=>'')); ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <?php echo CHtml::submitButton(Yii::t('global','Search'), array('id'=>'btnSubmit','class'=>'btn btn-block btn-primary'));  ?>
                </div>
            </div>
        </div>

    <?php $this->endWidget(); ?>

    <div class="clearfix"></div>

        <?php if ($objWishlists): ?>

    <div class="row">
	    <div class="col-sm-6">
           <h3><?= Yii::t('wishlist','Select a wish list name to view.'); ?></h3>
        </div>
    </div>


	<?php foreach ($objWishlists as $objWishlist): ?>
	    <div class="row">
            <div id="wishlist-table" class="table-responsive col-sm-8">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th><?= Yii::t('global','Name'); ?></th>
                            <th><?= Yii::t('global','Contains'); ?></th>
                            <th><?= Yii::t('global','Description'); ?></th>
                        </tr>
                    </thead>

                    <tbody>
                        <td>
                            <?php echo CHtml::link($objWishlist->registry_name,
                                Yii::app()->createUrl('wishlist/view',array('code'=>$objWishlist->gift_code)));
                            ?>
                        </td>
                        <td>
                            <?= Yii::t('global','{items} item|{items} items',array(count($objWishlist->wishlistItems),
                                '{items}'=>count($objWishlist->wishlistItems))); ?>
                        </td>
                        <td>
                            <?= $objWishlist->registry_description ?>
                        </td>
                    </tbody>
                </table>
	        </div>

	    </div>
	<?php endforeach; ?>

    <?php endif ?>

    </div>
</div>