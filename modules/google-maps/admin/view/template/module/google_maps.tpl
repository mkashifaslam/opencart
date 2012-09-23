<?php echo $header; ?>
<script src="view/javascript/jquery/cnplugins/jquery.predefinedinput-1.0.1.js" type="text/javascript"></script>
<script type="text/javascript"><!--
	$(document).ready(function() {
		$('.gmaps_mts').iAlphaNumeric({allow:'_-',disallow:'.',comma:true});
		$('.gmaps_mapalias').iAlphaNumeric({allow:'_-',disallow:'.'});
	});

//--></script>
<style type="text/css">
.in_tbl { width:100%; }
.in_tbl td {
	border:0;
	border-bottom:1px #f0f0f0 dotted;
	padding:3px 0 3px 0 !important;
}
</style>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
	  <table cellpadding="0" cellspacing="0" style="width:100%;">
		<tr>
			<td class="left"></td>
			<td style="text-align:right" valign="middle"><span class="help" style="font-size:80%"><?php echo $gmaps_version; ?></span></td>
		</tr>
	  </table><br />
	  
	  <table id="module" class="list">
        <thead>
          <tr>
		    <td class="left"><?php echo $entry_settigns; ?></td>
		    <td class="left"><?php echo $entry_theme_box; ?></td>
			<td class="left"><?php echo $entry_options; ?></td>
            <td></td>
          </tr>
        </thead>
        <?php $module_row = 0; ?>
        <?php foreach ($modules as $module) { ?>
        <tbody id="module-row<?php echo $module_row; ?>">
          <tr>
			<td>
				<table cellpadding="2" cellspacing="0" class="in_tbl">
					<tr>
						<td><?php echo $entry_mts; ?></td>
						<td><input class="gmaps_mts" name="google_maps_module[<?php echo $module_row; ?>][mts]" value="<?php echo $module['mts']; ?>" type="text" /></td>
					</tr>
					<tr>
						<td><?php echo $entry_widthheight; ?></td>
						<td><input name="google_maps_module[<?php echo $module_row; ?>][width]" style="width:50px" value="<?php echo $module['width']; ?>" type="text" /> x <input name="google_maps_module[<?php echo $module_row; ?>][height]" style="width:50px" value="<?php echo $module['height']; ?>" type="text" /></td>
					</tr>
					<tr>
						<td><?php echo $entry_zoom; ?></td>
						<td><select name="google_maps_module[<?php echo $module_row; ?>][zoom]">
							<option value="20" <?php if ($module['zoom'] == '20') { ?>selected="selected"<?php }?>>20</option>
							<option value="19" <?php if ($module['zoom'] == '19') { ?>selected="selected"<?php }?>>19</option>
							<option value="18" <?php if ($module['zoom'] == '18') { ?>selected="selected"<?php }?>>18</option>
							<option value="17" <?php if ($module['zoom'] == '17') { ?>selected="selected"<?php }?>>17</option>
							<option value="16" <?php if ($module['zoom'] == '16') { ?>selected="selected"<?php }?>>16</option>
							<option value="15" <?php if ($module['zoom'] == '15') { ?>selected="selected"<?php }?>>15</option>
							<option value="14" <?php if ($module['zoom'] == '14') { ?>selected="selected"<?php }?>>14</option>
							<option value="13" <?php if ($module['zoom'] == '13') { ?>selected="selected"<?php }?>>13</option>
							<option value="12" <?php if ($module['zoom'] == '12') { ?>selected="selected"<?php }?>>12</option>
							<option value="11" <?php if ($module['zoom'] == '11') { ?>selected="selected"<?php }?>>11</option>
							<option value="10" <?php if ($module['zoom'] == '10') { ?>selected="selected"<?php }?>>10</option>
							<option value="9" <?php if ($module['zoom'] == '9') { ?>selected="selected"<?php }?>>09</option>
							<option value="8" <?php if ($module['zoom'] == '8') { ?>selected="selected"<?php }?>>08</option>
							<option value="7" <?php if ($module['zoom'] == '7') { ?>selected="selected"<?php }?>>07</option>
							<option value="6" <?php if ($module['zoom'] == '6') { ?>selected="selected"<?php }?>>06</option>
							<option value="5" <?php if ($module['zoom'] == '5') { ?>selected="selected"<?php }?>>05</option>
							<option value="4" <?php if ($module['zoom'] == '4') { ?>selected="selected"<?php }?>>04</option>
							<option value="3" <?php if ($module['zoom'] == '3') { ?>selected="selected"<?php }?>>03</option>
							<option value="2" <?php if ($module['zoom'] == '2') { ?>selected="selected"<?php }?>>02</option>
							<option value="1" <?php if ($module['zoom'] == '1') { ?>selected="selected"<?php }?>>01</option>
						</select></td>
					</tr>
					<tr>
						<td><?php echo $entry_maptype; ?></td>
						<td><select name="google_maps_module[<?php echo $module_row; ?>][maptype]">
								<option value="ROADMAP" <?php if ($module['maptype'] == 'ROADMAP') { ?>selected="selected"<?php } ?>>ROADMAP</option>
								<option value="SATELLITE" <?php if ($module['maptype'] == 'SATELLITE') { ?>selected="selected"<?php } ?>>SATELLITE</option>
								<option value="HYBRID" <?php if ($module['maptype'] == 'HYBRID') { ?>selected="selected"<?php } ?>>HYBRID</option>
								<option value="TERRAIN" <?php if ($module['maptype'] == 'TERRAIN') { ?>selected="selected"<?php } ?>>TERRAIN</option>
						</select></td>
					</tr>
				</table>
			</td>
			<td>
				<table cellpadding="2" cellspacing="0" class="in_tbl">
					<tr>
						<td><?php echo $entry_theme_show_box; ?></td>
						<td><select name="google_maps_module[<?php echo $module_row; ?>][showbox]">
							<option value="1" <?php if ($module['showbox'] == '1') { ?>selected="selected"<?php }?>><?php echo $text_yes; ?></option>
							<option value="0" <?php if ($module['showbox'] == '0') { ?>selected="selected"<?php }?>><?php echo $text_no; ?></option>
						</select></td>
					</tr>
					<tr>
						<td><?php echo $entry_theme_box_title; ?></td>
						<td nowrap="nowrap">
							<?php foreach ($languages as $language) { ?>
							<input name="google_maps_module[<?php echo $module_row; ?>][boxtitle][<?php echo $language['language_id']; ?>]" value="<?php echo $module['boxtitle'][$language['language_id']]; ?>" type="text" /><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
							<?php } ?>
						</td>
					</tr>
				</table>
			</td>
			<td>
				<table cellpadding="2" cellspacing="0" class="in_tbl">
					<tr>
						<td><?php echo $entry_layout; ?></td>
						<td class="left"><select name="google_maps_module[<?php echo $module_row; ?>][layout_id]">
							<?php foreach ($layouts as $layout) { ?>
							<?php if ($layout['layout_id'] == $module['layout_id']) { ?>
							<option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
							<?php } else { ?>
							<option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
							<?php } ?>
							<?php } ?>
						</select></td>
					</tr>
					<tr>
						<td><?php echo $entry_position; ?></td>
						<td class="left"><select name="google_maps_module[<?php echo $module_row; ?>][position]">
							<?php if ($module['position'] == 'content_top') { ?>
							<option value="content_top" selected="selected"><?php echo $text_content_top; ?></option>
							<?php } else { ?>
							<option value="content_top"><?php echo $text_content_top; ?></option>
							<?php } ?>  
							<?php if ($module['position'] == 'content_bottom') { ?>
							<option value="content_bottom" selected="selected"><?php echo $text_content_bottom; ?></option>
							<?php } else { ?>
							<option value="content_bottom"><?php echo $text_content_bottom; ?></option>
							<?php } ?>  
							<?php if ($module['position'] == 'column_left') { ?>
							<option value="column_left" selected="selected"><?php echo $text_column_left; ?></option>
							<?php } else { ?>
							<option value="column_left"><?php echo $text_column_left; ?></option>
							<?php } ?>
							<?php if ($module['position'] == 'column_right') { ?>
							<option value="column_right" selected="selected"><?php echo $text_column_right; ?></option>
							<?php } else { ?>
							<option value="column_right"><?php echo $text_column_right; ?></option>
							<?php } ?>
						</select></td>
					</tr>
					<tr>
						<td><?php echo $entry_status; ?></td>
						<td class="left"><select name="google_maps_module[<?php echo $module_row; ?>][status]">
							<?php if ($module['status']) { ?>
							<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
							<option value="0"><?php echo $text_disabled; ?></option>
							<?php } else { ?>
							<option value="1"><?php echo $text_enabled; ?></option>
							<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
							<?php } ?>
						</select></td>
					</tr>
					<tr>
						<td><?php echo $entry_sort_order; ?></td>
						<td class="left"><input type="text" name="google_maps_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" /></td>
					</tr>
				</table>
			</td>
            <td class="left"><a onclick="$('#module-row<?php echo $module_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
          </tr>
        </tbody>
        <?php $module_row++; ?>
        <?php } ?>
        <tfoot>
          <tr>
            <td colspan="3"></td>
            <td class="left"><a onclick="addModule();" class="button"><?php echo $button_add_module; ?></a></td>
          </tr>
        </tfoot>
      </table>
	  <br /><br />
		<table class="list" style="width:100%">
			<thead>
				<tr>
					<td class="left">Google Maps</td>
				</tr>
			</thead>
		</table>
	  <div class="vtabs">
          <?php $map_row = 1; ?>
          <?php foreach ($gmaps as $gmap) { ?>
          <a href="#tab-module-<?php echo $map_row; ?>" id="module-<?php echo $map_row; ?>"><?php echo isset($gmap['mapalias']) ? strlen($gmap['mapalias'])>0 ? $gmap['mapalias'] : 'Map ' . $map_row : 'Map ' . $map_row; ?>&nbsp;<img src="view/image/delete.png" alt="" onclick="if ( confirm('<?php echo $confirm_mapid; ?> (<?php echo $gmap['latlong']; ?>) ?') ) { $('.vtabs a:first').trigger('click'); $('#module-<?php echo $map_row; ?>').remove(); $('#tab-module-<?php echo $map_row; ?>').remove(); return false; }" /></a>
          <?php $map_row++; ?>
          <?php } ?>
          <span id="module-map-add"><?php echo $button_addmap; ?>&nbsp;<img src="view/image/add.png" alt="" onclick="addGMap();" /></span> </div>
        
		<?php $map_row = 1; ?>
        <?php foreach ($gmaps as $gmap) { ?>
        <div id="tab-module-<?php echo $map_row; ?>" class="vtabs-content">
          <div id="language-<?php echo $map_row; ?>" class="htabs">
            <?php foreach ($languages as $language) { ?>
            <a href="#tab-language-<?php echo $map_row; ?>-<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
            <?php } ?>
          </div>
			<table class="form">
				<tr>
					<td valign="bottom"><span class="required">*</span> <?php echo $entry_mapid; ?></td>
					<td>
						<a target="_blank" href="http://maps.google.com/maps/api/staticmap?center=(<?php echo $gmap['latlong']; ?>)&amp;zoom=16&amp;markers=(<?php echo $gmap['latlong']; ?>)&amp;size=500x500&amp;maptype=roadmap&amp;sensor=true"><img id="gmap_preview_img<?php echo $map_row; ?>" src="http://maps.google.com/maps/api/staticmap?center=(<?php echo $gmap['latlong']; ?>)&amp;zoom=13&amp;markers=(<?php echo $gmap['latlong']; ?>)&amp;size=100x100&amp;maptype=roadmap&amp;sensor=true"></a>
						<br /><input class="gmaps_mapalias" name="google_maps_module_map[<?php echo $map_row; ?>][mapalias]" value="<?php echo isset($gmap['mapalias']) ? $gmap['mapalias'] : ''; ?>" type="text" />
					</td>
				</tr>
				<tr>
					<td><?php echo $entry_latlong; ?></td>
					<td><input name="google_maps_module_map[<?php echo $map_row; ?>][latlong]" value="<?php echo $gmap['latlong']; ?>" type="text" /></td>
				</tr>
			</table>

          <?php foreach ($languages as $language) { ?>
			<div id="tab-language-<?php echo $map_row; ?>-<?php echo $language['language_id']; ?>">
				<div id="oneline-<?php echo $map_row; ?>" class="htabs">
				<a href="#tab-oneline-<?php echo $map_row; ?>-<?php echo $language['language_id']; ?>-1">Text Editor</a>
				<a href="#tab-oneline-<?php echo $map_row; ?>-<?php echo $language['language_id']; ?>-2">One Line HTML</a>
				</div>
				<div id="tab-oneline-<?php echo $map_row; ?>-<?php echo $language['language_id']; ?>-1">
					<table class="form">
						<tr>
							<td><?php echo $entry_ballon_text; ?></td>
							<td><textarea name="google_maps_module_map[<?php echo $map_row; ?>][maptext][<?php echo $language['language_id']; ?>]" id="maptext-<?php echo $map_row; ?>-<?php echo $language['language_id']; ?>"><?php echo $gmap['maptext'][$language['language_id']]; ?></textarea></td>
						</tr>
					</table>
				</div>
				<div id="tab-oneline-<?php echo $map_row; ?>-<?php echo $language['language_id']; ?>-2">
					<table class="form">
						<tr>
							<td><?php echo $entry_ballon_text; ?></td>
							<td><input style="width:350px;" name="google_maps_module_map[<?php echo $map_row; ?>][onelinetext][<?php echo $language['language_id']; ?>]" value="<?php echo $gmap['onelinetext'][$language['language_id']]; ?>" type="text" /></td>
						</tr>
					</table>
				</div>
			</div>
          <?php } ?>
        </div>
        <?php $map_row++; ?>
        <?php } ?>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script> 
<script type="text/javascript"><!--
<?php $map_row = 1; ?>
<?php foreach ($gmaps as $gmap) { ?>
<?php foreach ($languages as $language) { ?>
CKEDITOR.replace('maptext-<?php echo $map_row; ?>-<?php echo $language['language_id']; ?>', {
	toolbar :
	[
		{ name: 'document', items : [ 'Source' ] },
		{ name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
		{ name: 'basicstyles', items : [ 'Bold','Italic','Strike' ] },
		{ name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock' ] },
		{ name: 'styles', items : [ 'Styles','Format','Font','FontSize' ] },
		{ name: 'colors', items : [ 'TextColor','BGColor' ] }
	],
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
});
<?php } ?>
<?php $map_row++; ?>
<?php } ?>
//--></script> 
<script type="text/javascript"><!--
var map_row = <?php echo $map_row; ?>;

function addGMap() {	
	html  = '<div id="tab-module-' + map_row + '" class="vtabs-content">';
	html += '  <div id="language-' + map_row + '" class="htabs">';
    <?php foreach ($languages as $language) { ?>
    html += '    <a href="#tab-language-'+ map_row + '-<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>';
    <?php } ?>
	html += '  </div>';
	html += '			<table class="form">';
	html += '				<tr>';
	html += '					<td valign="bottom"><span class="required">*</span> <?php echo $entry_mapid; ?></td>';
	html += '					<td>';
	html += '						<img id="gmap_preview_img' + map_row + '" src="<?php echo $no_image; ?>">';
	html += '						<br /><input class="gmaps_mapalias" name="google_maps_module_map[' + map_row + '][mapalias]" value="" type="text" />';
	html += '					</td>';
	html += '				</tr>';
	html += '				<tr>';
	html += '					<td><?php echo $entry_latlong; ?></td>';
	html += '					<td><input name="google_maps_module_map[' + map_row + '][latlong]" value="" type="text" /></td>';
	html += '				</tr>';
	html += '			</table>';
	
    html += '			     <div id="oneline-'+ map_row + '" class="htabs">';
    html += '			       <a href="#tab-oneline-'+ map_row + '-1">Text Editor</a>';
    html += '			       <a href="#tab-oneline-'+ map_row + '-2">One Line HTML</a>';
    html += '			     </div>';
	<?php foreach ($languages as $language) { ?>
	html += '    <div id="tab-language-'+ map_row + '-<?php echo $language['language_id']; ?>">';
	html += '	<div id="tab-oneline-'+ map_row + '-1">';
	html += '      <table class="form">';
	html += '        <tr>';
	html += '          <td><?php echo $entry_ballon_text; ?></td>';
	html += '          <td><textarea name="google_maps_module_map[' + map_row + '][maptext][<?php echo $language['language_id']; ?>]" id="maptext-' + map_row + '-<?php echo $language['language_id']; ?>"></textarea></td>';
	html += '        </tr>';
	html += '      </table>';
	html += '			</div>';
	html += '			<div id="tab-oneline-'+ map_row + '-2">';
	html += '				<table class="form">';
	html += '					<tr>';
	html += '						<td><?php echo $entry_ballon_text; ?></td>';
	html += '						<td><input style="width:350px;" name="google_maps_module_map['+ map_row + '][onelinetext][<?php echo $language['language_id']; ?>]" value="" type="text" /></td>';
	html += '					</tr>';
	html += '				</table>';
	html += '			</div>';
	html += '    </div>';
	<?php } ?>

	html += '</div>';
	
	$('#form').append(html);
	
	<?php foreach ($languages as $language) { ?>
	CKEDITOR.replace('maptext-' + map_row + '-<?php echo $language['language_id']; ?>', {
		toolbar :
		[
			{ name: 'document', items : [ 'Source' ] },
			{ name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
			{ name: 'basicstyles', items : [ 'Bold','Italic','Strike' ] },
			{ name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock' ] },
			{ name: 'styles', items : [ 'Styles','Format','Font','FontSize' ] },
			{ name: 'colors', items : [ 'TextColor','BGColor' ] }
		],
		filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
	});  
	<?php } ?>
	
	$('#language-' + map_row + ' a').tabs();
	$('#oneline-' + map_row + ' a').tabs();
	
	$('#module-map-add').before('<a href="#tab-module-' + map_row + '" id="module-' + map_row + '">Map ' + map_row + '&nbsp;<img src="view/image/delete.png" alt="" onclick="$(\'.vtabs a:first\').trigger(\'click\'); $(\'#module-' + map_row + '\').remove(); $(\'#tab-module-' + map_row + '\').remove(); return false;" /></a>');
	
	$('.vtabs a').tabs();
	
	$('#module-' + map_row).trigger('click');
		
	$('.gmaps_mapalias').iAlphaNumeric({allow:'_-',disallow:'.'});
	
	map_row++;
}

var module_row = <?php echo $module_row; ?>;

function addModule() {	
	html  = '<tbody id="module-row' + module_row + '">';
	html += '  <tr>';
	html += '		<td>';
	html += '			<table cellpadding="2" cellspacing="0" class="in_tbl">';
	html += '				<tr>';
	html += '					<td><?php echo $entry_mts; ?></td>';
	html += '					<td><input class="gmaps_mts" name="google_maps_module[' + module_row + '][mts]" value="" type="text" /></td>';
	html += '				</tr>';
	html += '				<tr>';
	html += '					<td><?php echo $entry_widthheight; ?></td>';
	html += '					<td><input name="google_maps_module[' + module_row + '][width]" style="width:50px" value="" type="text" /> x <input name="google_maps_module[' + module_row + '][height]" style="width:50px" value="" type="text" /></td>';
	html += '				</tr>';
	html += '				<tr>';
	html += '					<td><?php echo $entry_zoom; ?></td>';
	html += '  	   				<td><select name="google_maps_module[' + module_row + '][zoom]">';
	html += '  						<option value="20">20</option>';
	html += '  						<option value="19">19</option>';
	html += '  						<option value="18">18</option>';
	html += '  						<option value="17">17</option>';
	html += '  						<option value="16">16</option>';
	html += '  						<option value="15">15</option>';
	html += '  						<option value="14">14</option>';
	html += '  						<option value="13">13</option>';
	html += '  						<option value="12">12</option>';
	html += '  						<option value="11">11</option>';
	html += '  						<option value="10">10</option>';
	html += '  						<option value="9">09</option>';
	html += '  						<option value="8">08</option>';
	html += '  						<option value="7">07</option>';
	html += '  						<option value="6">06</option>';
	html += '  						<option value="5">05</option>';
	html += '  						<option value="4">04</option>';
	html += '  						<option value="3">03</option>';
	html += '  						<option value="2">02</option>';
	html += '  						<option value="1">01</option>';
	html += '  					</select></td>';
	html += '				</tr>';
	html += '				<tr>';
	html += '					<td><?php echo $entry_maptype; ?></td>';
	html += '					<td><select name="google_maps_module['+ module_row + '][maptype]">';
	html += '							<option selected="selected" value="ROADMAP">ROADMAP</option>';
	html += '							<option value="SATELLITE">SATELLITE</option>';
	html += '							<option value="HYBRID">HYBRID</option>';
	html += '							<option value="TERRAIN">TERRAIN</option>';
	html += '					</select></td>';
	html += '				</tr>';
	html += '			</table>';
	html += '		</td>';
	html += '		<td>';
	html += '			<table cellpadding="2" cellspacing="0" class="in_tbl">';
	html += '				<tr>';
	html += '					<td><?php echo $entry_theme_show_box; ?></td>';
	html += '					<td><select name="google_maps_module[' + module_row + '][showbox]">';
	html += '						<option value="1"><?php echo $text_yes; ?></option>';
	html += '						<option value="0"><?php echo $text_no; ?></option>';
	html += '					</select></td>';
	html += '				</tr>';
	html += '				<tr>';
	html += '					<td><?php echo $entry_theme_box_title; ?></td>';
	html += '					<td nowrap="nowrap">';
	<?php foreach ($languages as $language) { ?>
	html += '						<input name="google_maps_module[' + module_row + '][boxtitle][<?php echo $language['language_id']; ?>]" type="text" /><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />';
	<?php } ?>
	html += '					</td>';
	html += '				</tr>';
	html += '			</table>';
	html += '		</td>';
	html += '		<td>';
	html += '			<table cellpadding="2" cellspacing="0" class="in_tbl">';
	html += '				<tr>';
	html += '					<td><?php echo $entry_layout; ?></td>';
	html += '					<td class="left"><select name="google_maps_module[' + module_row + '][layout_id]">';
	<?php foreach ($layouts as $layout) { ?>
	html += '      					<option value="<?php echo $layout['layout_id']; ?>"><?php echo addslashes($layout['name']); ?></option>';
	<?php } ?>
	html += '					</select></td>';
	html += '				</tr>';
	html += '				<tr>';
	html += '					<td><?php echo $entry_position; ?></td>';
	html += '				    <td class="left"><select name="google_maps_module[' + module_row + '][position]">';
	html += '  					    <option value="content_top"><?php echo $text_content_top; ?></option>';
	html += '  					    <option value="content_bottom"><?php echo $text_content_bottom; ?></option>';
	html += '   				    <option value="column_left"><?php echo $text_column_left; ?></option>';
	html += '  					    <option value="column_right"><?php echo $text_column_right; ?></option>';
	html += '    				</select></td>';
	html += '				</tr>';
	html += '				<tr>';
	html += '					<td><?php echo $entry_status; ?></td>';
	html += '    				<td class="left"><select name="google_maps_module[' + module_row + '][status]">';
    html += '     					<option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
    html += '     					<option value="0"><?php echo $text_disabled; ?></option>';
    html += '    				</select></td>';
	html += '				</tr>';
	html += '				<tr>';
	html += '					<td><?php echo $entry_sort_order; ?></td>';
	html += '    				<td class="right"><input type="text" name="google_maps_module[' + module_row + '][sort_order]" value="" size="3" /></td>';
	html += '				</tr>';
	html += '			</table>';
	html += '		</td>';
	html += '		<td class="left"><a onclick="$(\'#module-row' + module_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
	html += '	</tr>';
	html += '</tbody>';
	
	$('#module tfoot').before(html);
	$('.gmaps_mts').iAlphaNumeric({allow:'_-',disallow:'.',comma:true});

	module_row++;
}


//--></script> 
<script type="text/javascript"><!--
$('.vtabs a').tabs();
//--></script> 
<script type="text/javascript"><!--
<?php $map_row = 1; ?>
<?php foreach ($gmaps as $gmap) { ?>
$('#language-<?php echo $map_row; ?> a').tabs();
$('#oneline-<?php echo $map_row; ?> a').tabs();
<?php $map_row++; ?>
<?php } ?> 
//--></script> 
<?php echo $footer; ?>