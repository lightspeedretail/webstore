<?php
$slider = $this->sliderName;
?>
<div class="eleven columns alpha omega products_slider products_slider_theme" id="<?php echo $this->$slider->ControlId; ?>_slider">

	<div class="one column alpha"><p class=" back highzindex"><a href="#" title="Back"></a></p></div>
	<div class="eight columns"><span class="label"><?php echo $this->$slider->sliderTitle; ?></span></div>
	<div class="one column omega leftpadding"><p class="highzindex next"><a href="#" title="Next"></a></p></div>
	<br clear="both">

		<ul>
		<?php foreach ($this->$slider->links as $prod): ?>
		<li><a href="<?php echo $prod['link']; ?>"><img src="<?php echo $prod['image']; ?>"
		                                                alt="<?php echo $prod['title']; ?>"/></a>
			<p><?php echo $prod['title']; ?></p></li>
		<?php endforeach; ?>
	</ul>
</div>
