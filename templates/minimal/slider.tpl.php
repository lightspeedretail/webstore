<?php
$slider = $this->sliderName;
?>
<div class="eleven columns alpha products_slider products_slider_theme" id="<?php echo $this->$slider->ControlId; ?>_slider">
	<p class="back"><a href="#" title="Back"></a></p>

	<p class="next"><a href="#" title="Next"></a></p>

	<p><span class="label"><?php echo $this->$slider->sliderTitle; ?></span></p>
	<ul>
		<?php foreach ($this->$slider->links as $prod): ?>
		<li><a href="<?php echo $prod['link']; ?>"><img src="<?php echo $prod['image']; ?>"
		                                                alt="<?php echo $prod['title']; ?>"/></a>
			<p><?php echo $prod['title']; ?></p></li>
		<?php endforeach; ?>
	</ul>
</div>
