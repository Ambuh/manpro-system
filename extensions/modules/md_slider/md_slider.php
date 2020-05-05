<?php
function md_slider(){
?>
<div id="wowslider-container1">
	<div class="ws_images"><ul>
<li><img src="<?php echo System::getFolderBackjump(); ?>templates/default/data1/images/slide_1.png" alt="AS1" title="AS1" id="wows1_0"/></li>
<li><img src="<?php echo System::getFolderBackjump(); ?>templates/default/data1/images/slide_2.png" alt="AS2" title="AS2" id="wows1_1"/></li>
<li><img src="<?php echo System::getFolderBackjump(); ?>templates/default/data1/images/slide_3.png" alt="AS3" title="AS3" id="wows1_1"/></li>
</ul></div>
<div class="ws_bullets"><div>
<a href="#" title="AS1"><img src="<?php echo System::getFolderBackjump(); ?>templates/default/data1/tooltips/as1.jpg" alt="AS1"/>1</a>
<a href="#" title="AS2"><img src="<?php echo System::getFolderBackjump(); ?>templates/default/data1/tooltips/as2.jpg" alt="AS2"/>2</a>
<a href="#" title="AS3"><img src="<?php echo System::getFolderBackjump(); ?>templates/default/data1/tooltips/as3.jpg" alt="AS3"/>2</a>
</div></div>

	
	</div>
	<script type="text/javascript" src="<?php echo System::getFolderBackjump(); ?>templates/default/engine1/wowslider.js"></script>
	<script type="text/javascript" src="<?php echo System::getFolderBackjump(); ?>templates/default/engine1/script.js"></script>
	<!-- End WOWSlider.com BODY section -->
<?php
}
?>