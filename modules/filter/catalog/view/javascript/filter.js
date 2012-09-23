
function isArray(testObject) {	 
    return testObject && !(testObject.propertyIsEnumerable('length')) && typeof testObject === 'object' && typeof testObject.length === 'number';
}
function sort_changed(value){
    var regexpr = /.*sort=(.+)&order=(ASC|DESC).*/ig;
    var sort  = value.replace(regexpr,'$1');
    var order = value.replace(regexpr,'$2');

    if (sort!=null)
	cur_conf['sort'] = sort;
    if (order!=null)
	cur_conf['order'] = order;
    load_content();
}
function limit_changed(value){
    var regexpr = /.*limit=([0-9]+).*/ig;
    var limit  = value.replace(regexpr,'$1');

    if (limit!=null)
	cur_conf['limit'] = limit;
    load_content();
}

function load_content(data) {
    var sentData = new Object();
    sentData['fcategory_id'] =  cur_conf['fcategory_id'];
    filterdata = activeFilters.find("div.filter-item.use");

    $.each(filterdata,function(index,data){
	dataName = $(data).data('name');
	dataValue = $(data).data('value');
	
	if(dataName == "fattributes"){
	    attrId = $(data).data('id');	    
	    if(typeof(sentData[dataName])=="undefined"){
	   		sentData[dataName] = new Object(); 
		}
	    var attrFound = false;
	    for(var prop in sentData[dataName])
		if(prop == attrId){
		  attrFound = true;
		  break;
	        }
	    if(!attrFound){
	      sentData[dataName][attrId] = new Array();
	    }
	    
	    sentData[dataName][attrId].push(dataValue);
	
	}else
	{
		if(!isArray(sentData[dataName])){
	    		sentData[dataName] = new Array();
		}
	

	    sentData[dataName].push(dataValue);
	}
    });
    if((typeof data != "undefined")&&(typeof data['page']!="undefined"))
	sentData['page'] = data['page'];

    if(typeof cur_conf['sort']!="undefined")
	sentData['sort'] = cur_conf['sort'];
    if(typeof cur_conf['order']!="undefined")
	sentData['order'] = cur_conf['order'];
    if(typeof cur_conf['limit']!="undefined")
	sentData['limit'] = cur_conf['limit'];

    var productFrame = $('div.product-list,div.product-grid');


    $.ajax({
        type :  'POST',
        data : sentData,
        dataType : 'json',
        url  : 'index.php?route=module/filter/getIndex',
        
        error : function(req,stat,err) {
            console.log(req + "\n\n" + stat + "\n\n" + err );
        },      
        
        beforeSend : function() {
            productFrame.html('<img src="/catalog/view/theme/default/image/ajax-loader.gif" />');
            $('.pagination').html('');
        },
        
        success : function(data) {
            json = data ;
	    products = json.products;
	    if( products == undefined || products.length < 1 ) {
                productFrame.html(json.text_empty);
            } else {
                productFrame.html('');
                $.each( products, function( index , data )  {
                    var productDiv = '<div>'
                          +'<div class="right">'
                        +'<div class="cart"><a onclick="addToCart(\''+data.product_id+'\');" class="btn_buy"><span>'+json.button_cart+'</span></a></div>'
                          +'<div class="wishlist"><a onclick="addToWishList(\''+data.product_id+'\');"><span>'+json.button_wishlist+'</span></a></div>'
                          +'<div class="compare"><a onclick="addToCompare(\''+data.product_id+'\');"><span>'+json.button_compare+'</span></a></div>'
                          +'</div>'
                          +'<div class="left">'
                          +'<div class="image">'
                          +'<a href="'+data.href+'">'
                          +'<img src="'+data.thumb+'" title="'+data.name+'"/>'
                          +'</a>'
                          +'</div>'
                          +'<div class="price"><div class="pprice"><span>'+data.price+'</span></div></div>'
                          +'<div class="name"><a href="'+data.href+'">'+data.name+'</div>'
                          +'<div class="description">'+data.description+'</div>'
                          +'</div>'
                          +'</div>';
                    productFrame.append(productDiv);
                });
		$('.pagination').html(json.pagination);
		$('div.pagination').on('click','a',function(e){
		    e.preventDefault();
		    var cur_url = $(this).attr('href');
		    var regexpstr = /.*page=([0-9]+).*/ig;
		    var page  = cur_url.replace(regexpstr,'$1');
		    if (page!=null)
		    {
			var data = new Array();
			data['page']=page;
			load_content(data);
		    }
		});

            }
        }        
    }); 
}

$(document).ready(function(){

   if($('div.product-list,div.product-grid').length > 0)
    {
	$("div#filter-box").show();
    }

    $('div.sort select').attr('onchange','sort_changed(this.value)');
    $('div.limit select').attr('onchange','limit_changed(this.value)');
    $('div.pagination').on('click','a',function(e){
	e.preventDefault();
	var cur_url = $(this).attr('href');
	var regexpstr = /.*page=([0-9]+).*/ig;
	var page  = cur_url.replace(regexpstr,'$1');

	if (page!=null)
	{
	    var data = new Array();
	    data['page']=page;
	    load_content(data);
	}
    });
    activeFilters = $("div.active-filters");
    rootActiveFilters = activeFilters.parent('div#filters');

    mainFilters = $("div.main-filters");
    rootMainFilters = mainFilters.parent('div#filters');
    filterItemsArray = new Array();
    mainFilters.find("div.filter-item").each(function(i){
	$(this).attr("attr-id",i);
	filterItemsArray[i] = new Array();
	filterItemsArray[i][0] = $(this);
    });
    activeFilters.html(mainFilters.html()).find("div.filter-item").each(function(i){
	$(this).hide().addClass("active").parent("div.attr-container").hide();
	filterItemsArray[i][1] = $(this);
});
    activeFilters.children("div").hide();

   
$('div.filter-item').on('click',function(){
    toggleFilterItem($(this));
    load_content();
});

    rootActiveFilters.children('h3').children('img').on('click',function(){
	activeFilters.find('div.filter-item').filter(':visible').each(function(i){
	    toggleFilterItem($(this));
	    load_content();
	});
    });

    function hideParentRecursive(parent){
    if( $(parent).hasClass('filter-block')||($(parent).children("div").filter(":visible").length > 0))
	return 0;
    else
    {
	$(parent).slideUp("fast",function(){
	    hideParentRecursive($(parent).parent('div'));
	});
	
    }
    return 0;
}
    function showParentRecursive(parent){
	if($(parent).hasClass('filter-block'))
	   return 0;
	else
    	    $(parent).slideDown("fast",function(){
		showParentRecursive($(parent).parent('div'));
	    });
    
    return 0;
	  }
function toggleFilterItem($this){
    
    id = $this.attr("attr-id");


    mainItem = filterItemsArray[id][0];


    mainItem.toggle();
    activeItem = filterItemsArray[id][1];


    activeItem.toggle().toggleClass('use');
    if(!activeItem.hasClass("use"))
    {
	showParentRecursive(mainItem.parent('div'));
	hideParentRecursive(activeItem.parent('div'));
    } else{
	showParentRecursive(activeItem.parent('div'));
	hideParentRecursive(mainItem.parent('div'));
    }
    return 0;
}

});
