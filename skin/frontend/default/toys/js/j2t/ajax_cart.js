/**
 * J2T-DESIGN.
 *
 * @category   J2t
 * @package    J2t_Ajaxcheckout
 * @copyright  Copyright (c) 2003-2009 J2T DESIGN. (http://www.j2t-design.com)
 * @license    GPL
 */
/*
var loadingW = 260;
var loadingH = 50;
var confirmW = 260;
var confirmH = 134;
*/
var inCart = false;

if (window.location.toString().search('/product_compare/') != -1){
    var win = window.opener;
}
else{
    var win = window;
}

if (window.location.toString().search('/checkout/cart/') != -1){
    inCart = true;
}


function setLocation(url){
    if(!inCart && (/*(url.search('/add') != -1 ) || (url.search('/remove') != -1 ) ||*/ url.search('checkout/cart/add') != -1) ){
        sendcart(url, 'url');
    }else{
        window.location.href = url;
    }
}


function sendcart(url, type){

    showLoading();
    var confirm_box = jQuery('.j2t_ajax_confirm');
    
    confirm_box.css('width','400px').css('height','278px');
    
    if (type == 'form'){

        url = ($('product_addtocart_form').action).replace('checkout', 'j2tajaxcheckout/index/cart');

        //url = ($('product_addtocart_form').action);
        var myAjax = new Ajax.Request(
            url,
            {
                method: 'post',
                postBody: $('product_addtocart_form').serialize(),
                parameters : Form.serialize("product_addtocart_form"),
                onException: function (xhr, e)
                {                    
                    alert('Exception : ' + e);
                },
                onComplete: function (xhr)
                {
                    
                    $('j2t-temp-div').innerHTML = xhr.responseText;
                    var test = $('j2t-temp-div').down('.testmessage').innerHTML;
                    var return_message = $('j2t-temp-div').down('.j2t_ajax_message').innerHTML;
                    var form = '';
                    form = form + '<fieldset class="highlight"><h6>Заказать через телефон: <span>Можно не заполнять никаких форм, просто оставить телефон и консультант \n\
                   решит все вопросы по оформлению заказа.</span></h6>';
                    form = form + '<ul class="form-list"><li>';
                    form = form + '<label for="nickname_field" class="required"><em>*</em>Ваши ФИО:</label>';
                    form = form + '<div class="input-box">';
                    form = form + '<input type="text" name="nickname" id="nickname_field" class="input-text fio required-entry" value="">';
                    form = form + '</div></li><li>';
                    form = form + '<label for="summary_field" class="required"><em>*</em>Ваш телефон/e-mail:</label>';
                    form = form + '<div class="input-box">';
                    form = form + '<input type="text" name="title" id="summary_field" class="input-text phone-mail required-entry" value="">';
                    form = form + '</div></li></ul>';
                    form = form + '</fieldset>';
                    form = form + '<div class="buttons-set">';
                    form = form + '<button title="" class="button" onClick=sendOrder("'+ url+'")><span><span>Заказать!</span></span></button>';
                    form = form + '</div>';
                    var middle_text = '<div class="j2t-cart-bts">'+$('j2t-temp-div').down('.back-ajax-add').innerHTML+form+'</div>';
                    $('j2t_ajax_confirm').innerHTML = '<div id="j2t_ajax_confirm_wrapper">'+test + '</div>';
                    if (ajax_cart_show_popup){
                        showConfirm();
                    } else {
                        hideJ2tOverlay();
                    }

                    
                    jQuery.ajax({
                        type : 'POST',
                        url : url,
                        data : Form.serialize("product_addtocart_form") + "&add_to_cart=true",
                        success : function(response) {
                            $('j2t-temp-div').innerHTML = response;
                            var link_cart_txt = $('j2t-temp-div').down('.cart_content').innerHTML;

                            $$('.top-link-cart').each(function (el){
                                el.innerHTML = link_cart_txt;
                            });                    
                                
                            var mini_cart_txt = $('j2t-temp-div').down('.cart_side_ajax').innerHTML;

                            $$('.mini-cart').each(function (el){

                                el.replace(mini_cart_txt);                            
                            });

                            $$('.block-cart').each(function (el){
                                el.replace(mini_cart_txt);                            
                            });
                        
                            replaceDelUrls();
                        }
                    });
                    
jQuery('#product-count-input input').val(jQuery('.input-text.qty.validation-passed').val());
                }

            });



    } else if (type == 'url'){

        url = url.replace('checkout', 'j2tajaxcheckout/index/cart');

        var myAjax = new Ajax.Request(
            url,
            {
                method: 'post',
                postBody: '',
                onException: function (xhr, e)
                {
                    console.log(xhr);
                    alert('Exception : ' + e);
                },
                onComplete: function (xhr)
                {
                    $('j2t-temp-div').innerHTML = xhr.responseText;
                    var test = $('j2t-temp-div').down('.testmessage').innerHTML;
                    var return_message = $('j2t-temp-div').down('.j2t_ajax_message').innerHTML;
                    var middle_text = '<div class="j2t-cart-bts">'+$('j2t-temp-div').down('.back-ajax-add').innerHTML+'</div>';

                    var content_ajax = return_message + middle_text;
                    var form = '';
                    form = form + '<fieldset class="highlight"><h6>Заказать через телефон: <span>Можно не заполнять никаких форм, просто оставить телефон и консультант \n\
                   решит все вопросы по оформлению заказа.</span></h6>';
                    form = form + '<ul class="form-list"><li>';
                    form = form + '<label for="nickname_field" class="required"><em>*</em>Ваши ФИО:</label>';
                    form = form + '<div class="input-box">';
                    form = form + '<input type="text" name="nickname" id="nickname_field" class="input-text fio required-entry" value="">';
                    form = form + '</div></li><li>';
                    form = form + '<label for="summary_field" class="required"><em>*</em>Ваш телефон/e-mail:</label>';
                    form = form + '<div class="input-box">';
                    form = form + '<input type="text" name="title" id="summary_field" class="input-text phone-mail required-entry" value="">';
                    form = form + '</div></li></ul>';
                    form = form + '</fieldset>';
                    form = form + '<div class="buttons-set">';
                    form = form + '<button title="" class="button" onClick=sendOrder("'+ url+'")><span><span>Заказать!</span></span></button>';
                    form = form + '</div>';        
                    //$('j2t_ajax_confirm').innerHTML = '<div id="j2t_ajax_confirm_wrapper">'+ content_ajax + form + test + '</div>';
                    $('j2t_ajax_confirm').innerHTML = '<div id="j2t_ajax_confirm_wrapper">'+ test +'</div>';
                    if (ajax_cart_show_popup){
                        showConfirm();
                    } else {
                        hideJ2tOverlay();
                    }
                    jQuery.post(url, {add_to_cart: true}, function(response){
                        $('j2t-temp-div').innerHTML = response;
                        var link_cart_txt = $('j2t-temp-div').down('.cart_content').innerHTML;

                        $$('.top-link-cart').each(function (el){
                            el.innerHTML = link_cart_txt;
                        });

                        var mini_cart_txt = $('j2t-temp-div').down('.cart_side_ajax').innerHTML;

                        $$('.mini-cart').each(function (el){
                            el.replace(mini_cart_txt);                
                        });

                        $$('.block-cart').each(function (el){
                            el.replace(mini_cart_txt);                    
                        });
                        replaceDelUrls();
                        
                    });
                }
            });
    }

}

function redirectToOnepage() {
    window.location="http://dachniki-club.ru/onepagecheckout/";
}

function sendOrder(url, maximum, id) {
    maximum = parseInt(maximum);
    qty = jQuery('#count_cart').val();
    qty = parseInt(qty);
    if(qty>maximum) {
        alert("Запрашиваемое количество для этого товара недоступно. Максимальное количество - "+maximum);
    } else {
        var fio = jQuery('.fio').val();
        var phone = jQuery('.phone-mail').val();
        can_continue = true;
        if(fio=='') {
            jQuery('.fio').addClass('validation-failed');       
            can_continue = false;
        } else {
            jQuery('.fio').removeClass('validation-failed');
        }
        if(phone=='') {
            can_continue = false;
            jQuery('.phone-mail').addClass('validation-failed');
        } else {
            jQuery('.phone-mail').removeClass('validation-failed');
        }
        if(can_continue==true) {
            jQuery.ajax({
                url     : '/sendorder?fio='+fio+'&phone='+phone+'&url='+url+'&qty='+qty+'&id='+id,
                type    : 'POST',
                dataType: 'html',
                timeout : 9000,
                error   : function() {
                    alert('Произошла ошибка.');
                },
                success : function(html) {               
                    jQuery('#j2t_ajax_confirm').css('height','17px');                
                    jQuery('#j2t_ajax_confirm_wrapper').html('<div id="j2t_ajax_confirm_wrapper" style="padding-top:38px;"><h4>Ваш заказ успешно отправлен!</h4></div>');       
                jQuery('#j2t_ajax_confirm_wrapper').parent().css('height','100px');
                
                



                    setTimeout(hideJ2tOverlay, 10); 


                //setTimeout(hideJ2tOverlay(), 5000)

                }//success
            });//ajax
        } 
    }
}

function replaceDelUrls(){
    //if (!inCart){
    $$('a').each(function(el){
        if(el.href.search('checkout/cart/delete') != -1 && el.href.search('javascript:cartdelete') == -1){
            el.href = 'javascript:cartdelete(\'' + el.href +'\')';
        }
    });
//}
}

function replaceAddUrls(){
    $$('a').each(function(link){
        if(link.href.search('checkout/cart/add') != -1){
            link.href = 'javascript:setLocation(\''+link.href+'\'); void(0);';
        }
    });
}

function cartdelete(url){ 
    
    showLoading();
    url = url.replace('checkout', 'j2tajaxcheckout/index/cartdelete');
    var myAjax = new Ajax.Request(
        url,
        {
            method: 'post',
            postBody: '',
            onException: function (xhr, e)
            {
                alert('Exception : ' + e);
            },
            onComplete: function (xhr)
            {
                console.log(xhr);
                console.log(xhr.responseText);
                $('j2t-temp-div').innerHTML = xhr.responseText;
                //$('j2t-temp-div').insert(xhr.responseText);

                var cart_content = $('j2t-temp-div').down('.cart_content').innerHTML;

                //alert(cart_content);

                $$('.top-link-cart').each(function (el){
                    el.innerHTML = cart_content;
                });

            

                var process_reload_cart = false;
                var full_cart_content = $('j2t-temp-div').down('.j2t_full_cart_content').innerHTML;
                $$('.cart').each(function (el){
                    el.replace(full_cart_content);
                    process_reload_cart = true;
                });

                if (!process_reload_cart){
                    $$('.checkout-cart-index .col-main').each(function (el){
                        el.replace(full_cart_content);
                    //new Effect.Opacity(el, { from: 0, to: 1, duration: 1.5 });
                    });
                }

jQuery('.checkout-types li').html('<button type=\"button\" title=\"Оформить заказ\" class=\"checkout-button-cart\" onclick=\"redirectToOnepage();\"><span><span>Оформить заказ</span></span></button>');


                var cart_side = '';
                if ($('j2t-temp-div').down('.cart_side_ajax')){
                    cart_side = $('j2t-temp-div').down('.cart_side_ajax').innerHTML;
                }

            
                $$('.mini-cart').each(function (el){
                    el.replace(cart_side);
                //new Effect.Opacity(el, { from: 0, to: 1, duration: 1.5 });
                });
                $$('.block-cart').each(function (el){
                    el.replace(cart_side);
                //new Effect.Opacity(el, { from: 0, to: 1, duration: 1.5 });
                });

            

                replaceDelUrls();
                 jQuery('.button.btn-proceed-checkout.btn-checkout').attr('onclick','window.location="/onepagecheckout"');

                //$('j2t_ajax_progress').hide();
                hideJ2tOverlay();
            }

        });


}

function showJ2tOverlay(){
    new Effect.Appear($('j2t-overlay'), {
        duration: 0.2,  
        to: 0.2
    });
}

function hideJ2tOverlay(){
    $('j2t-overlay').hide();
    $('j2t_ajax_progress').hide();
    $('j2t_ajax_confirm').hide();
}


function j2tCenterWindow(element) {
    if($(element) != null) {

        // retrieve required dimensions
        var el = $(element);
        var elDims = el.getDimensions();
        var browserName=navigator.appName;
        if(browserName==="Microsoft Internet Explorer") {

            if(document.documentElement.clientWidth==0) {
                //IE8 Quirks
                //alert('In Quirks Mode!');
                var y=(document.viewport.getScrollOffsets().top + (document.body.clientHeight - elDims.height) / 2);
                var x=(document.viewport.getScrollOffsets().left + (document.body.clientWidth - elDims.width) / 2);
            }
            else {
                var y=(document.viewport.getScrollOffsets().top + (document.documentElement.clientHeight - elDims.height) / 2);
                var x=(document.viewport.getScrollOffsets().left + (document.documentElement.clientWidth - elDims.width) / 2);
            }
        }
        else {
            var y = Math.round(document.viewport.getScrollOffsets().top + ((window.innerHeight - $(element).getHeight()))/2);
            var x = Math.round(document.viewport.getScrollOffsets().left + ((window.innerWidth - $(element).getWidth()))/2);
        }
        var styles = {
            position: 'absolute',
            top: y + 'px',
            left : x + 'px'
        };
        el.setStyle(styles);




    }
}
  

function maskInput(price) {

    var key_code = window.event.keyCode;
    var oElement = window.event.srcElement;
    if (!window.event.shiftKey && !window.event.ctrlKey && !window.event.altKey) {
        if ((key_code > 47 && key_code < 58) ||
            (key_code > 95 && key_code < 106)) {

            if (key_code > 95)
                key_code -= (95-47);
            oElement.value = oElement.value;
        } else if(key_code == 8) {
            oElement.value = oElement.value;
        } else if(key_code != 9) {
            event.returnValue = false;
        }
    }
}

function changeHtml(price, maximum) {    
    var value = parseInt(document.getElementById('count_cart').value, 10);
    if(value>maximum) {
        value=maximum;     
    }
    if(isNaN(value)) {
        value='1';     
    }
    jQuery('.checkout_form #count_cart').val(value); 
    jQuery("#count-product-href").html(value+" "+declination("товаров", "товар", "товара", value));
    total = value*parseInt(price);
    jQuery('#on-summ').html(number_format(total, 0, ' ', ' ') +' руб.');
}

function number_format( number, decimals, dec_point, thousands_sep ) {  // Format a number with grouped thousands

    var i, j, kw, kd, km;
    if( isNaN(decimals = Math.abs(decimals)) ){
        decimals = 2;
    }
    if( dec_point == undefined ){
        dec_point = ",";
    }
    if( thousands_sep == undefined ){
        thousands_sep = ".";
    }
    i = parseInt(number = (+number || 0).toFixed(decimals)) + "";
    if( (j = i.length) > 3 ){
        j = j % 3;
    } else{
        j = 0;
    }
    km = (j ? i.substr(0, j) + thousands_sep : "");
    kw = i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands_sep);

    kd = (decimals ? dec_point + Math.abs(number - i).toFixed(decimals).replace(/-/, 0).slice(2) : "");
    return km + kw + kd;
}

function declination(a, b, c, s) {  
    var words = [a, b, c]; 
    var index = s % 100;  
    if (index >=11 && index <= 14) {
        index = 0;
    } 
    else {
        index = (index %= 10) < 5 ? (index > 2 ? 2 : index): 0;
    } 
    return(words[index]); 
}


function increment(maximum, price) {
    maximum = parseInt(maximum);
    var value = parseInt(document.getElementById('count_cart').value, 10);
    value = isNaN(value) ? 1 : value;
    value++;
    if(value>maximum) {
        value=maximum;
    }        
    jQuery('.checkout_form #count_cart').val(value);    
    jQuery("#count-product-href").html(value+" "+declination("товаров", "товар", "товара", value));
    total = value*parseInt(price);
    jQuery('#on-summ').html(number_format(total, 0, ' ', ' ') +' руб.');
    
}
function decrement(price) {
    price = parseInt(price);
    var value = parseInt(document.getElementById('count_cart').value, 10);
    value = isNaN(value) ? 1 : value;
    value--;
    if(value=='0') value=1;    
    jQuery('.checkout_form #count_cart').val(value);    
    jQuery("#count-product-href").html(value+" "+declination("товаров", "товар", "товара", value));
    total = value*parseInt(price);
    jQuery('#on-summ').html(number_format(total, 0, ' ', ' ') +' руб.');
}

function redirect() {
    window.location = '/';
}

function changeQty(id, maximum) {    

    qty = jQuery('#count_cart').val();
    qty = parseInt(qty);
    maximum = parseInt(maximum);
    if(qty>maximum) {
        alert("Запрашиваемое количество для этого товара недоступно. Максимальное количество - "+maximum);
    } else {
        jQuery('.checkout_form').submit();
    }
}

function showLoading(){
    showJ2tOverlay();
    var progress_box = $('j2t_ajax_progress');
    progress_box.show();
    progress_box.style.width = loadingW + 'px';
    progress_box.style.height = loadingH + 'px';
    $('j2t_ajax_progress').innerHTML = $('j2t-loading-data').innerHTML;
    progress_box.style.position = 'absolute';
    j2tCenterWindow(progress_box);
}

function sendCall() {
    name = jQuery('#call-name').val();
    phone = jQuery('#call-phone').val();
    text = jQuery('#call-text').val();
    can_continue = true;
    if(name=='') {
        jQuery('#call-name').addClass('validation-failed');
        can_continue = false;
    } else {
        jQuery('#call-name').removeClass('validation-failed');
    }
    if(phone=='') {
        jQuery('#call-phone').addClass('validation-failed');
        can_continue = false;
    } else {
        jQuery('#call-phone').removeClass('validation-failed');
    }
    if(can_continue===true) {
        jQuery.ajax({
            url     : '/sendcall?name='+name+'&phone='+phone+'&text='+text,
            type    : 'POST',
            dataType: 'html',
            timeout : 9000,
            error   : function() {
                alert('Произошла ошибка.');
            },
            success : function(html) {          
                jQuery('#j2t_ajax_confirm').css('height','17px');                
                jQuery('#j2t_ajax_confirm_wrapper').html('<div id="j2t_ajax_confirm_wrapper" style="padding-top:38px"><h4>Спасибо за заявку! Мы обязательно Вам перезвоним.</h4></div>');               
                jQuery('#j2t_ajax_confirm_wrapper').parent().css('height','100px');
                setTimeout(hideJ2tOverlay, 10); 
            }
        });
    }
}

function showConfirm(){
    showJ2tOverlay();
    $('j2t_ajax_progress').hide();
    var confirm_box = $('j2t_ajax_confirm');
    confirm_box.show();
    confirm_box.style.width = confirmW + 'px';
    confirm_box.style.height = confirmH + 'px';
    if ($('j2t_ajax_confirm_wrapper') && $('j2t-upsell-product-table')){
        confirm_box.style.height = $('j2t_ajax_confirm_wrapper').getHeight() + 'px';
        decorateTable('j2t-upsell-product-table');
    }

    $('j2t_ajax_confirm_wrapper').replace('<div id="j2t_ajax_confirm_wrapper">'+$('j2t_ajax_confirm_wrapper').innerHTML);

    confirm_box.style.position = 'absolute';
    j2tCenterWindow(confirm_box);
}
    document.observe("dom:loaded", function() {
    replaceDelUrls();
    replaceAddUrls();
    Event.observe($('j2t-overlay'), 'click', hideJ2tOverlay);

    var cartInt = setInterval(function(){
        if (typeof productAddToCartForm  != 'undefined'){
            if ($('j2t-overlay')){
                Event.observe($('j2t-overlay'), 'click', hideJ2tOverlay);
            }
            productAddToCartForm.submit = function(url){
                if(this.validator && this.validator.validate()){
                    sendcart('', 'form');
                    clearInterval(cartInt);
                }

                return false;
            }
        } else {
            clearInterval(cartInt);
        }
    },100);
});

function getlogin(){      
    var form = jQuery('#onepagecheckout_loginbox').html();
    jQuery('#j2t_ajax_confirm').html('<div id="j2t_ajax_confirm_wrapper">' + form +'</div>');
    replaceDelUrls();
    if (ajax_cart_show_popup){
        showConfirmLogin();
    } else {
        hideJ2tOverlay();
    }


    

}
function sendLogin() {

    url = '/onepagecheckout/index/login/';
    email = jQuery('#j2t_ajax_confirm_wrapper #email').val();
    pass = jQuery('#j2t_ajax_confirm_wrapper #pass').val();
    can_continue = true;
    if(email=='') {
        can_continue = false;
        jQuery('#j2t_ajax_confirm_wrapper #email').css('border', '1px dashed red');
    } else { 
        jQuery('#j2t_ajax_confirm_wrapper #email').css('border','1px solid lightgrey');
    }
    if(pass=='') {
        can_continue = false;
        jQuery('#j2t_ajax_confirm_wrapper #pass').css('border', '1px dashed red');
    } else { 
        jQuery('#j2t_ajax_confirm_wrapper #pass').css('border','1px solid lightgrey');
    }
    if(can_continue==true) {
        jQuery.ajax({
            url     : url+'?email='+email+'&pass='+pass,
            type    : 'POST',
            dataType: 'html',
            timeout : 9000,
            error   : function() {
                alert('Произошла ошибка.');
            },
            success : function(html) {  
response = html.evalJSON();      
if(response.success===false) {alert('Неверный адрес электронной почти или пароль!'); jQuery('#j2t_ajax_confirm_wrapper #pass').val('');
} else {document.location = response.redirect;}

          /*      jQuery('#j2t_ajax_confirm').css('height','17px');                
                jQuery('#j2t_ajax_confirm_wrapper').html('<div id="j2t_ajax_confirm_wrapper">Ваш заказ успешно отправлен!</div>');               
                setTimeout(hideJ2tOverlay, 4000); */
            }
        });
    }




}

function showConfirmLogin(){
    showJ2tOverlay();
    $('j2t_ajax_progress').hide();
    var confirm_box = $('j2t_ajax_confirm');
    confirm_box.show();
    confirm_box.style.width = '400px';
    confirm_box.style.height = '278px';
    //j2t_ajax_confirm_wrapper
    if ($('j2t_ajax_confirm_wrapper') && $('j2t-upsell-product-table')){
        //alert($('j2t_ajax_confirm_wrapper').getHeight());
        confirm_box.style.height = $('j2t_ajax_confirm_wrapper').getHeight() + 'px';
        decorateTable('j2t-upsell-product-table');
    }
    $('j2t_ajax_confirm_wrapper').replace('<div id="j2t_ajax_confirm_wrapper">'+$('j2t_ajax_confirm_wrapper').innerHTML);

    confirm_box.style.position = 'absolute';
    j2tCenterWindow(confirm_box);
}

function showForgot() {

    // showLoadingLogin();
    var form = jQuery('#onepagecheckout_forgotbox').html();
    jQuery('#j2t_ajax_confirm').html('<div id="j2t_ajax_confirm_wrapper">' + form +'</div>');
    replaceDelUrls();
    if (ajax_cart_show_popup){
        showConfirmLogin();
    } else {
        hideJ2tOverlay();
    }}

function getforgot() {
    url = '/onepagecheckout/index/forgotpassword/';
    email = jQuery('#j2t_ajax_confirm_wrapper #email_address').val();
    can_continue = true;
    if(email=='') {
        can_continue = false;
        jQuery('#j2t_ajax_confirm_wrapper #email_address').css('border', '1px dashed red');
    } else { 
        jQuery('#j2t_ajax_confirm_wrapper #email_address').css('border','1px solid lightgrey');
    }
    if(can_continue==true) {
        jQuery.ajax({
            url     : url+'?email='+email,
            type    : 'POST',
            dataType: 'html',
            timeout : 9000,
            error   : function() {
                alert('Произошла ошибка.');
            },
            success : function(html) {  
                response = html.evalJSON();    
                if(response.success===false) {
                    alert('Введенный email отсутствует!');                
                } else {
                   jQuery('#j2t_ajax_confirm').css('height','17px');                
                    jQuery('#j2t_ajax_confirm_wrapper').html(
                            '<div id="j2t_ajax_confirm_wrapper">Новый пароль был отправлен на ваш почтовый ящик - '+email+'.</div>');               
                    setTimeout(hideJ2tOverlay, 10); 
                }
            }
        });
    }


}
