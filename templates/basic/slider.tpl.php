<?php
$slider = $this->sliderName;
?>
<div class="products_slider products_slider_theme" id="<?php echo $this->$slider->ControlId; ?>_slider"> 
<p class="back"><a href="#" title="Back"></a></p> 
<p class="next"><a href="#" title="Next"></a></p> 
<p style="font-size: 14px; font-weight: bold; margin: 8px 0 0 25px; text-shadow: #000 1px 1px 1px;"><?php echo $this->$slider->sliderTitle; ?></p> 
<ul> 
 <?php foreach ($this->$slider->links as $prod) {?>
<li><a href="<?php echo $prod['link']; ?>"><img src="<?php echo $prod['image']; ?>" alt="<?php echo $prod['title2']; ?>" /></a><p><?php echo $prod['title']; ?><br /><?php echo $prod['title2']; ?></p></li> 
<?php } ?>
</ul> 
</div>
