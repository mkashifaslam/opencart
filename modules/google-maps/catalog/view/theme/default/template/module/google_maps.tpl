<?php 
	if ($gmap_width == 'auto') {
		$gmapwidth = 'auto';
	} else if (is_numeric($gmap_width)) {
		$gmapwidth = $gmap_width . 'px';
	} else {
		$gmapwidth = '425px';
	}
	
	if ($gmap_height == 'auto') {
		$gmapheight = 'auto';
	} else if (is_numeric($gmap_height)) {
		$gmapheight = $gmap_height . 'px';
	} else {
		$gmapheight = '350px';
	}
?>
<style type="text/css">
#gmap_div<?php echo $module_map; ?>{
	width:<?php echo $gmapwidth; ?>;
	height:<?php echo $gmapheight; ?>;
	border:6px solid #F4F4F4;
	margin:0;
	padding:0;
}
</style>
<?php if ($gmap_showbox) {?>
<div class="box">
  <div class="box-heading"><?php echo $gmap_boxtitle; ?></div>
  <div class="box-content">
	<div id="gmap_div<?php echo $module_map; ?>">&nbsp;</div>
  </div>
</div>
<?php } else {?>
<div id="gmap_div<?php echo $module_map; ?>" style="margin-bottom:10px;">&nbsp;</div>
<?php }?>
<script src="http://maps.google.com/maps/api/js?sensor=true" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
	//------- Google Maps ---------//
	// Creating a LatLng object containing the coordinate for the center of the map
	var latlng<?php echo $module_map; ?> = new google.maps.LatLng(<?php echo $gmap_flatlong; ?>);
	 
	// Creating an object literal containing the properties we want to pass to the map  
	var options<?php echo $module_map; ?> = {  
		zoom: <?php echo $gmap_zoom; ?>, // This number can be set to define the initial zoom level of the map
		center: latlng<?php echo $module_map; ?>,
		mapTypeId: google.maps.MapTypeId.<?php echo $gmap_maptype; ?> // This value can be set to define the map type ROADMAP/SATELLITE/HYBRID/TERRAIN
	};  
	// Calling the constructor, thereby initializing the map  
	var map<?php echo $module_map; ?> = new google.maps.Map(document.getElementById('gmap_div<?php echo $module_map; ?>'), options<?php echo $module_map; ?>);  
	
	// Define Marker properties
	var image<?php echo $module_map; ?> = new google.maps.MarkerImage(
		//Image file name
		'catalog/view/theme/<?php echo $this->config->get('config_template'); ?>/image/gmaps_marker.png',
		// This marker is 129 pixels wide by 42 pixels tall.
		new google.maps.Size(129, 42),
		// The origin for this image is 0,0.
		new google.maps.Point(0,0),
		// The anchor for this image is the base of the flagpole at 18,42.
		new google.maps.Point(18, 42)
	);
	<?php 
	$aa = 0;
	foreach ($gmaps as $gmap) {
		$aa += 1;
	?>
	// Add Marker
	var marker<?php echo $module_map; ?><?php echo $aa; ?> = new google.maps.Marker({
		position: new google.maps.LatLng(<?php echo $gmap['latlong']; ?>), 
		map: map<?php echo $module_map; ?>,
		icon: image<?php echo $module_map; ?> // This path is the custom pin to be shown. Remove this line and the proceeding comma to use default pin
	});	
	
	// Add listener for a click on the pin
	google.maps.event.addListener(marker<?php echo $module_map; ?><?php echo $aa; ?>, 'click', function() {  
		infowindow<?php echo $module_map; ?><?php echo $aa; ?>.open(map<?php echo $module_map; ?>, marker<?php echo $module_map; ?><?php echo $aa; ?>); 
	});
	// Add listener for a click on the map
	google.maps.event.addListener(map<?php echo $module_map; ?>, 'click', function() {  
		infowindow<?php echo $module_map; ?><?php echo $aa; ?>.close(); 
	});	
	// Add information window
	var infowindow<?php echo $module_map; ?><?php echo $aa; ?> = new google.maps.InfoWindow({  
		content:  '<div><?php echo strlen($gmap['onelinetext'])>0 ? $gmap['onelinetext'] : $gmap['maptext']; ?></div>'
	}); 
	<?php } ?>
});
</script>


