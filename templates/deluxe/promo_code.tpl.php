<div class="left margin clear">
<dl>
<dt><label for="Promo Code" class="city"><?php _xt("Enter a Promotional Code here to receive a discount") ?></label></dt>
<dd>
    <div style='float:left; padding-right:1em;'>
        <?php $this->txtPromoCode->RenderWithError() ?>
    </div>
    <div style='float:left; clear:right;'>
        <?php $this->btnPromoVerify->Render() ?>
    </div>
</dd>
<dd><?php $this->lblPromoErr->Render() ?></dd>
</dl>
</div>
<br style="clear: both;"/>
