<div class="box" id="filter-box" style="display:none">
  <div class="filter-block">
    <script type="text/javascript">

    var cur_conf = new Object();
    cur_conf['fcategory_id'] = <?php echo $fcategory_id;?>;
    
    </script>
    <div style="display:none" id="filters">
      <h3>Активные фильтры<img style="float:right;margin:5px;cursor:pointer" src="/catalog/view/theme/CoolOne/image/close.gif"/></h3>
      <div class="active-filters"></div>
    </div>
    <div id="filters">
      <h3><?php echo $heading_title; ?></h3>
      <div class="main-filters">
	<?php if($categories){?>
	<div>
	  <h4>Категория&nbsp;&nbsp;</h4>
	  <?php foreach ($categories as $category) { ?>
	  <div class="filter-item" data-name="categories" data-value="<?php echo $category['category_id']; ?>">
	    <div style="display: inline;" ><?php echo $category['name'].'('.$category['total'].')'; ?></div>&nbsp;&nbsp;
	  </div>
	  <?php } ?>
        </div>
        <?php }?>
	<div>
	  <h4>Цена&nbsp;&nbsp;</h4>
	  <?php foreach ($fprice as $pricc) { ?>
	  <div class="filter-item" data-name="fprice" data-value="<?php echo $pricc['fprice_id']; ?>">
	    <div style="display: inline;" ><?php echo $pricc['name']; ?></div>&nbsp;&nbsp;
	  </div>
	  <?php } ?>
	</div>
	<div>
	  <h4>Производители&nbsp;&nbsp;</h4>
	  <?php foreach ($manufacturers as $manufacturer) { ?>
	  <div data-name="manufacturers" data-value="<?php echo $manufacturer['manufacturer_id']; ?>" class="filter-item">
	    <div style="display: inline;" ><?php echo $manufacturer['name'].'('.$manufacturer['total'].')'; ?></div>&nbsp;&nbsp;
	  </div>
	  <?php } ?>
	</div>
	<div>
	  <?php if ($fattributes) { ?>
	   <?php foreach ($fattributes as $fattribute_group) { ?>
	   <h4><?php echo $fattribute_group['name'];?></h4>
	     <?php foreach($fattribute_group['attribute'] as $fattr) { ?>
	       <?php if($fattr['type_id']!=0){ ?>
	         <div class="attr-container" style="color: #999;padding-left:10px;"">
		  <?php echo $fattr['name']; ?>
		  <?php foreach($fattr['values'] as $value) { ?>
		    <div data-id="<?php echo $fattr['attribute_id'];?>" data-name="fattributes" class="filter-item" data-value="<?php echo $value['value'];?>" data-type="<?php echo $fattr['type_id'];?>">
		      <div style="display:inline"  >
		        <?php echo $value['name']; ?>
		      </div>
		    </div>
		  <?php }?>
	         </div>
	       <?php } else { ?>
	         <div data-name="fattributes" class="filter-item" data-id="<?php echo $fattr['attribute_id'];?>" data-value="1" data-type="0">
		   <div style="display:inline"  >
		     <?php echo $fattr['name']; ?>
		   </div>
		 </div>
	       <?php } ?>
	     <?php } ?>
	   <?php } ?>
	  <?php } ?>
	</div>
      </div>
    </div>
  </div>
</div>


