//get base url
url = base_url;

function ajaxCompare(url,id){
	url = url.replace("catalog/product_compare/add","ajax/index/compare");
	url += 'isAjax/1/';	
    jQuery(".loader-overlay").addClass("visible");
	jQuery.ajax( {
		url : url,
		dataType : 'json',
		success : function(data) {			
            jQuery(".loader-overlay").removeClass("visible");
			if(data.status == 'ERROR'){
                swal("Oops...", data.message, "error");
			}else{
                swal(data.status, data.message, "success");
                updateBlocks(data.update_blocks); 
			}
		}
	});
}

function ajaxRemoveCompare(url,id){
    swal({   
        title: removecompare_title,   
        text: removecompare_text,   
        type: "warning",   
        showCancelButton: true,   
        confirmButtonColor: "#DD6B55",   
        confirmButtonText: removecompare_confirmButtonText,   
        cancelButtonText: removecompare_cancelButtonText,   
        closeOnConfirm: false,   
        closeOnCancel: false 
    }, 
    function(isConfirm){
        if (isConfirm){
            url = url.replace("catalog/product_compare/remove","ajax/index/removecompare");
            url += 'isAjax/1/'; 
            jQuery(".loader-overlay").addClass("visible");
            jQuery.ajax( {
                url : url,
                dataType : 'json',
                success : function(data) {          
                    jQuery(".loader-overlay").removeClass("visible");
                    if(data.status == 'ERROR'){
                        swal("Oops...", data.message, "error");
                    }else{
                        swal(data.status, data.message, "success");
                        updateBlocks(data.update_blocks); 
                    }
                }
            });
        } else {     
            swal("Cancelled", removecompare_cancelledtext, "error");   
        }    
    });
}

function ajaxWishlist(url,id){
	url = url.replace("wishlist/index/add","ajax/index/addwish");
	url += 'isAjax/1/';	
    jQuery(".loader-overlay").addClass("visible");	
	jQuery.ajax( {
		url : url,
		dataType : 'json',
		success : function(data) {			
            jQuery(".loader-overlay").removeClass("visible");
			if(data.status == 'ERROR'){				
                swal("Oops...", data.message, "error");
                updateBlocks(data.update_blocks);

			}else{				
                swal(data.status, data.message, "success");
				updateBlocks(data.update_blocks);
			}			
		}
	});
}

function ajaxUpdateCart(item){    
    jQuery(".loader-overlay").addClass("visible");    
    url += "/ajax/index/updatecart/isAjax/1/"; 
    var item_id = "#span-"+item;    
    qty = jQuery(item_id).val();
    item = item;    
    jQuery.ajax( {
        url : url,
        dataType : 'json',
        data: { qty: qty, item: item },
        success : function(data) {                    
            jQuery(".loader-overlay").removeClass("visible");
            if (data.status == 'ERROR') {
                swal("Oops...", data.message, "error");
            } else {
                swal(data.status, data.message, "success");
                updateBlocks(data.update_blocks);                 
            } 
        }
    }); 
}

function ajaxClearCart() {
    swal({   
        title: ajaxClearCart_title,   
        text: ajaxClearCart_text,   
        type: "warning",   
        showCancelButton: true,   
        confirmButtonColor: "#DD6B55",   
        confirmButtonText: ajaxClearCart_confirmButtonText,   
        cancelButtonText: ajaxClearCart_cancelButtonText,   
        closeOnConfirm: false,   
        closeOnCancel: false 
    },

    function(isConfirm){
        if (isConfirm){
            jQuery(".loader-overlay").addClass("visible");
            jQuery('button.confirm').attr('disabled','disabled');
            jQuery('button.confirm').css('background-color', '#ccc');
            jQuery('button.confirm').css('cursor', 'wait');
            jQuery('button.confirm').html('Please Wait...');
            url += "/ajax/index/clearcart/isAjax/1/"; 
            jQuery.ajax( {
                url : url,
                dataType : 'json',
                success : function(data) {
                    jQuery('button.confirm').removeAttr('disabled');
                    jQuery(".loader-overlay").removeClass("visible");
                    jQuery('button.confirm').css('cursor', 'pointer');
                    if (data.status == 'ERROR') {
                        swal("Oops...", data.message, "error");
                    } else {
                        swal(data.status, data.message, "success");
                        updateBlocks(data.update_blocks);                 
                    } 
                }
            });
        } else {     
            swal("Cancelled", ajaxClearCart_cancelledtext, "error");   
        }    
    });
}

function ajaxCoupon() {
    jQuery(".loader-overlay").addClass("visible");   
    url += "/ajax/index/coupon/isAjax/1/"; 
    var coupon_element = "#coupon_code";    
    coupon = jQuery(coupon_element).val();
    if(!coupon){
        jQuery(".loader-overlay").removeClass("visible");
        swal("Oops...", ajaxCoupon_emptytext, "error");
    } else {
        jQuery.ajax( {
            url : url,
            dataType : 'json',
            data : { coupon:coupon },
            success : function(data) {
                jQuery(".loader-overlay").removeClass("visible");
                if (data.status == 'ERROR') {
                    swal("Oops...", data.message, "error");
                } else {
                    swal(data.status, data.message, "success");
                    updateBlocks(data.update_blocks);                 
                } 
            }
        });
    }
 
}

function ajaxCancelCoupon() {
    jQuery(".loader-overlay").addClass("visible");   
    url += "/ajax/index/coupon/isAjax/1/";     
    cancel_coupon = "remove";
    jQuery.ajax( {
        url : url,
        dataType : 'json',
        data : { cancel_coupon:cancel_coupon },
        success : function(data) {
            jQuery(".loader-overlay").removeClass("visible");
            if (data.status == 'ERROR') {
                swal("Oops...", data.message, "error");
            } else {
                swal(data.status, data.message, "success");
                updateBlocks(data.update_blocks);                 
            } 
        }
    });
}

function ajaxDeleteItem(url,id){              
    swal({   
        title: ajaxDeleteItem_title,   
        text: ajaxDeleteItem_text,   
        type: "warning",   
        showCancelButton: true,   
        confirmButtonColor: "#DD6B55",   
        confirmButtonText: ajaxDeleteItem_confirmButtonText,   
        cancelButtonText: ajaxDeleteItem_cancelButtonText,   
        closeOnConfirm: false,   
        closeOnCancel: false 
    }, 
    function(isConfirm){
        if (isConfirm){
            url += url.replace("checkout/cart/delete","ajax/index/delete");
            url += 'isAjax/1/'; 

            jQuery(".loader-overlay").addClass("visible");
            jQuery('button.confirm').attr('disabled','disabled');
            jQuery('button.confirm').css('background-color', '#ccc');
            jQuery('button.confirm').css('cursor', 'wait');
            jQuery('button.confirm').html('Please Wait...'); 

            jQuery.ajax( {
                url : url,
                dataType : 'json',
                success : function(data) {
                    jQuery(".loader-overlay").removeClass("visible");
                    jQuery('button.confirm').removeAttr('disabled');
                    jQuery('button.confirm').css('cursor', 'pointer');

                    if(data.status == 'ERROR'){
                        swal("Oops...", data.message, "error");
                    }else{
                        swal(data.status, data.message, "success");
                        updateBlocks(data.update_blocks);
                    } 
                }
            });    
        }else{     
            swal("Cancelled", ajaxDeleteItem_cancelledtext, "error");   
        }    
    }); 
}

function updateBlocks(blocks) {
    var _this = this;
    if(blocks) {
        try{
            blocks.each(function(block){
                if(block.key) {
                    var dom_selector = block.key;
                    if($$(dom_selector)) {
                        $$(dom_selector).each(function(e){
                            $(e).update(block.value);
                        });
                    }
                }
            });                
        } catch(e) {
            console.log(e);
        }
    }
}

function showOptions(id){
    url += "/ajax/index/options/"+id;
    product_id = id;
    jQuery(".loader-overlay").addClass("visible");

    jQuery.ajax( {
        url : url,
        dataType : 'json',
        data : { product_id:product_id },
        success : function(data) {
            jQuery(".loader-overlay").removeClass("visible");
            jQuery('.product-view-data').html(data.product_options_block);
            jQuery('.product-view-data').show();
            jQuery('.cd-popup').addClass('is-visible');

            jQuery(".lazy-load-big").unveil(200, function() {
                jQuery(this).load(function() {
                    this.style.opacity = 1;
                });
            });
        }
    });
}
    
function setAjaxData(data,iframe){
    if(data.status == 'ERROR'){
        swal("Oops...", data.message, "error");
    }else{
        jQuery('.cd-popup').removeClass('is-visible');
        jQuery('.product-view-data').empty();
        swal(data.status, data.message, "success");
        updateBlocks(data.update_blocks);
    }
}

function setLocationAjax(url,id){
    url += 'isAjax/1';
    url = url.replace("checkout/cart","ajax/index");
    jQuery(".loader-overlay").addClass("visible");        
    try {
        jQuery.ajax( {
            url : url,
            dataType : 'json',
            success : function(data) {
                jQuery(".loader-overlay").removeClass("visible");
                setAjaxData(data,false);  
                swal(data.status, data.message, "success");        
            }
        });
    } catch (e) {
    }
}

jQuery(document).ready(function(){
    if (window.matchMedia('(max-width: 768px)').matches) {
        jQuery(".footer-links h3").click(function(){            
            jQuery(".footer-links ul").slideUp();
            jQuery(".footer-links h3").removeClass('act', 300, "easeOutBounce");
            if(!jQuery(this).next().is(":visible")){
                jQuery(this).addClass('act', 300, "easeOutBounce");
                jQuery(this).next().slideDown();
            }          
        })
    }

    
});



