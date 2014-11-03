    var $k = jQuery.noConflict();   
    $k(document).ready(function(){ $k('#features').jshowoff(); }); 
        function changeCity(id) {
            var url = '/geoip/index/changecity?id=' + id;
            var myAjax = new Ajax.Request(
                    url,
                    {
                        method: 'post',
                        postBody: '',
                        onException: function(xhr, e)
                        {
                            alert('Exception : ' + e);
                        },
                        onComplete: function(xhr)
                        {

                            if (xhr.responseText == 'ok') {
                                setLocation(jQuery('#location').val());
                            } else {
                                alert('К сожалению, мы не смогли определить ваш город.');
                            }
                        }

                    });
        }

        function getcity() {

            showLoadingCity();
            var url = '/geoip/index/getcities';
            // url = url.replace('checkout', 'j2tajaxcheckout/index/cart');

            var myAjax = new Ajax.Request(
                    url,
                    {
                        method: 'post',
                        postBody: '',
                        onException: function(xhr, e)
                        {


                            alert('Exception : ' + e);
                        },
                        onComplete: function(xhr)
                        {
                            jQuery('#j2t-temp-div').html(xhr.responseText);
                            var form = '';
                            jQuery('#j2t_ajax_confirm').html("<div id='j2t_ajax_confirm_wrapper'></div>");
                            jQuery('#j2t_ajax_confirm_wrapper').html(xhr.responseText + form);
                            replaceDelUrls();
                            if (ajax_cart_show_popup) {
                                showConfirmCity();
                            } else {
                                hideJ2tOverlay();
                            }
                        }

                    });



        }
        function showLoadingCity() {
            showJ2tOverlay();
            var progress_box = $('j2t_ajax_progress');
            progress_box.show();
            progress_box.style.width = '500px';
            progress_box.style.height = '500px';


            $('j2t_ajax_progress').innerHTML = $('j2t-loading-data').innerHTML;
            progress_box.style.position = 'absolute';

            j2tCenterWindowCity(progress_box);
        }
        function j2tCenterWindowCity(element) {
            if ($(element) != null) {

                // retrieve required dimensions
                var el = $(element);
                var elDims = el.getDimensions();
                var browserName = navigator.appName;
                if (browserName === "Microsoft Internet Explorer") {

                    if (document.documentElement.clientWidth == 0) {
                        //IE8 Quirks
                        //alert('In Quirks Mode!');
                        var y = (document.viewport.getScrollOffsets().top + (document.body.clientHeight - elDims.height) / 2);
                        var x = (document.viewport.getScrollOffsets().left + (document.body.clientWidth - elDims.width) / 2);
                    }
                    else {
                        var y = (document.viewport.getScrollOffsets().top + (document.documentElement.clientHeight - elDims.height) / 2);
                        var x = (document.viewport.getScrollOffsets().left + (document.documentElement.clientWidth - elDims.width) / 2);
                    }
                }
                else {
                    // calculate the center of the page using the browser andelement dimensions
                    var y = Math.round(document.viewport.getScrollOffsets().top + ((window.innerHeight - $(element).getHeight())) / 2);
                    var x = Math.round(document.viewport.getScrollOffsets().left + ((window.innerWidth - $(element).getWidth())) / 2);
                }
                // set the style of the element so it is centered
                var styles = {
                    position: 'absolute',
                    top: y + 'px',
                    left: x + 'px'
                };
                el.setStyle(styles);




            }
        }
        function showConfirmCity() {
            showJ2tOverlay();
            $('j2t_ajax_progress').hide();
            var confirm_box = $('j2t_ajax_confirm');
            confirm_box.show();
            confirm_box.style.width = '600px';
            confirm_box.style.height = '400px';
            //j2t_ajax_confirm_wrapper
            if ($('j2t_ajax_confirm_wrapper') && $('j2t-upsell-product-table')) {
                //alert($('j2t_ajax_confirm_wrapper').getHeight());
                confirm_box.style.height = $('j2t_ajax_confirm_wrapper').getHeight() + 'px';
                decorateTable('j2t-upsell-product-table');
            }

            $('j2t_ajax_confirm_wrapper').replace('<div id="j2t_ajax_confirm_wrapper">' + $('j2t_ajax_confirm_wrapper').innerHTML);

            confirm_box.style.position = 'absolute';
            j2tCenterWindow(confirm_box);
        }

        function showConfirmCall() {
            showJ2tOverlay();
            $('j2t_ajax_progress').hide();
            var confirm_box = $('j2t_ajax_confirm');
            confirm_box.show();
            confirm_box.style.width = '500px';
            confirm_box.style.height = '400px';
            //j2t_ajax_confirm_wrapper
            if ($('j2t_ajax_confirm_wrapper') && $('j2t-upsell-product-table')) {
                //alert($('j2t_ajax_confirm_wrapper').getHeight());
                confirm_box.style.height = $('j2t_ajax_confirm_wrapper').getHeight() + 'px';
                decorateTable('j2t-upsell-product-table');
            }

            $('j2t_ajax_confirm_wrapper').replace('<div id="j2t_ajax_confirm_wrapper">' + $('j2t_ajax_confirm_wrapper').innerHTML);

            confirm_box.style.position = 'absolute';
            j2tCenterWindow(confirm_box);
        }

        jQuery.fn.ac = function(o) {

            var o = jQuery.extend({
                url: '/geoip/index/getcountries',
                onClose: function(suggest) {
                    setTimeout(function() {
                        suggest.slideUp('fast');
                    }, 100);
                },
                dataSend: function(input) {
                    return 'suggest_name=' + input.attr('name') + '&query=ac&word=' + word;
                },
                wordClick: function(input, link) {
                    input.val(link.attr('href')).focus();
                }
            }, o);

            return jQuery(o).each(function() {

                var onClose = o.onClose;
                var input = jQuery('#one');
                input.after('<div class="auto-suggest"></div>');

                var suggest = input.next();
                suggest.width(input.width() + 6);

                input.keydown(function(e) {
                    if (e.keyCode == 38 || e.keyCode == 40) {
                        var tag = suggest.children('a.selected'),
                                new_tag = suggest.children('a:first');
                        if (tag.length) {
                            if (e.keyCode == 38) {
                                if (suggest.children('a:first.selected').length) {
                                    new_tag = suggest.children('a:last');
                                } else {
                                    new_tag = tag.prev('a');
                                }
                            } else {
                                if (!suggest.children('a:last.selected').length)
                                    new_tag = tag.next('a');
                            }
                            tag.removeClass('selected');
                        }
                        new_tag.addClass('selected');
                        input.val(new_tag.attr('href'));
                        return;
                    }
                    if (e.keyCode == 13 || e.keyCode == 27) {
                        onClose(suggest);
                        return;
                    }
                }).keyup(function(e) {
                    if (e.keyCode == 38 || e.keyCode == 40 || e.keyCode == 13 || e.keyCode == 27)
                        return;
                    word = input.val();
                    if (word) {
                        jQuery.post(o.url, o.dataSend(input), function(data) {
                            if (data.length > 0) {
                                suggest.html(data).show().children('a').click(function() {
                                    o.wordClick(input, jQuery(this));
                                    return false;
                                });
                            } else {
                                onClose(suggest);
                            }
                        });
                    } else {

                        setTimeout(function() {
                            suggest.slideUp('fast');
                        }, 100);
                    }
                });
            });
        }

        function keyup(e) {

            suggest = jQuery('.auto-suggest');
            input = jQuery('#one');
            var url = '/geoip/index/getcountries';

            if (e == 38 || e == 40 || e == 13 || e == 27)
                return;
            word = input.val();
            var params = 'suggest_name=asdf&query=ac&word=' + word;
            if (word) {
                jQuery.post(url, params, function(data) {

                    if (data.length > 0) {
                        suggest.html(data).show().children('span').click(function() {
                            o.wordClick(input, jQuery(this));
                            return false;
                        });
                    } else {
                        setTimeout(function() {
                            suggest.slideUp('fast');
                        }, 100);
                    }
                });
            } else {
                setTimeout(function() {
                    suggest.slideUp('fast');
                }, 100);
            }

        }

        function getLocation() {
            code = jQuery('#city-code').val();
            changeCity(code);
        }

        function getCall() {
            showLoadingCity();
            var form = "<div class='send-call-wrapper'><div class='close-window' onclick='hideJ2tOverlay()'>&nbsp;</div><br><h1>Заказ услуги обратный звонок</h1><span class='send-call-header-text'>Если Вы не можете нам дозвониться - сообщите нам свою контактную информацию и мы Вам перезвоним, в течение 1 часа или в другое, указанное Вами время.</span><br><br>    <span class='send-call-your-data'>Ваши данные:</span><br><table border=0>       <tr>          <td class='first-col'>               Имя           </td>           <td class='second-col'>               <input type='text' id='call-name' class='send-call-input input-text'></input>           </td>      </tr>      <tr>           <td  class='first-col'>               Телефон           </td>           <td  class='second-col'>               <input type='text' class='send-call-input input-text' id='call-phone'></input>           </td>       </tr>       <tr>           <td  class='first-col'>               О чем Вы хотели поговорить?           </td>          <td  class='second-col'>               <textarea  class='send-call-input' id='call-text'></textarea>           </td>       </tr> </table></span><div class='buttons-set' style='clear:both;border-top:0px !important;text-align:center;width:100%'><button title='' class='css3buttongreen green-button' style='margin:0 auto; float: none !important;' onclick='sendCall()'><span><span>ПЕРЕЗВОНИТЕ МНЕ</span></span></button>                    </div></div>";
            jQuery('#j2t_ajax_confirm').html('<div id="j2t_ajax_confirm_wrapper">' + form + '</div>');
            replaceDelUrls();
            if (ajax_cart_show_popup) {
                showConfirmCall();
            } else {
                hideJ2tOverlay();
            }
        }


            var data = "";
    var active = false;
    jQuery(function(jQuery){

        if(jQuery('body').find('#resultLoading').attr('id') != 'resultLoading'){
        jQuery('.main').append('<div id="resultLoading" style="display:none"><div><img src="http://dachniki-club.ru/media/magehouse/slider/default/ajax-loader.gif"><div>Fetching Results... Please wait...</div></div><div class="bg"></div></div>');}
        var height = jQuery('.main').outerHeight();
        var width = jQuery('.main').outerWidth();
        jQuery('.ui-slider-handle').css('cursor','pointer');
        
        jQuery('#resultLoading').css({
            'width':width,
            'height':'100%',
            'position':'absolute',
            'z-index':'10000000',
            'top':'0',
            'left':'0'
        }); 
        jQuery('#resultLoading .bg').css({
            'background':'#000000',
            'opacity':'0.7',
            'width':'100%',
            'height':'100%',
            'position':'absolute',
            'top':'0'
        });
        jQuery('#resultLoading>div:first').css({
            'width': '100%',
            'text-align': 'center',
            'position': 'absolute',
            'left': '0',
            'margin-top': '22em',
            'font-size':'16px',
            'z-index':'10',
            'color':'#ffffff'
            
        });
        
                
        
                
        
    });

 

function callback(){
        
}
    var ajax_cart_show_popup = 1;
            
    var loadingW = 290;
    var loadingH = 50;
    var confirmW = 500;
    var confirmH = 378;




