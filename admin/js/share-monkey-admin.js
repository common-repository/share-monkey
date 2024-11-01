(function( $ ) {
	'use strict';

    $(function() {

        $("#share_monkey_social_icons_all").sortable({
            group: {
                name: "share_monkey_social_icons_all",
                pull: "clone"
            },
            sort: false
        });
        
        $("#share_monkey_social_icons_selected").sortable({
            group: {
                name: "share_monkey_social_icons_selected",
                put: function(to, from, element) {
                    var dup = false;
                    var currentID = $(element).data("id");
                    for (var i = 0; i < to.el.children.length; i++) {
                        if ($(to.el.children[i]).data("id") == currentID) {
                            dup = true;
                            break;
                        }
                    }
                    return dup ? false : ["share_monkey_social_icons_all"];
                }
            },
            filter: ".share_monkey_remove",
            onFilter: function (evt) {
                var item = evt.item,
                    ctrl = evt.target;
        
                if ($(ctrl).is(".share_monkey_remove")) {  // Click on remove button
                    item.parentNode.removeChild(item); // remove sortable item
                }
            }
        });

        function addClass(el,cls)
        {
            !el.hasClass(cls) && el.addClass(cls)
        }

        function validateForm(){

            var valid = true

           //reomve error classes on init
            $(".share_monkey_settings_field").removeClass (function (index, className) {
                return (className.match (/\bshare_monkey_error\S*/g) || []).join(' '); 
            });

            //validate selected social networks
            var social_icons = $('.share_monkey_social_icons')
            if($('#share_monkey_social_icons_selected li').length==0)
            {
                addClass(social_icons,'share_monkey_error')
                addClass(social_icons,'share_monkey_error_min')
                valid = false
            }

            //validate selected post types
            var show_on_types = $('.share_monkey_settings_field.share_monkey_show_on_types')
            if($('[name="share_monkey_settings[show_on_types][]"]:checked').length==0)
            {
                addClass(show_on_types,'share_monkey_error')
                addClass(show_on_types,'share_monkey_error_min')
                valid = false
            }

            //validate selected icon style
            var icon_style = $('.share_monkey_settings_field.share_monkey_icon_style')
            if($('[name="share_monkey_settings[icon_style]"]:checked').val()!='default')
            {
                var colorPattern = /^\#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/
                var custom_bg_color = $('.share_monkey_settings_field.share_monkey_custom_bg_color')
                if(!colorPattern.test($('[name="share_monkey_settings[custom_bg_color]"]').val()))
                {
                    addClass(custom_bg_color,'share_monkey_error')
                    addClass(custom_bg_color,'share_monkey_error_color')
                    valid = false
                }
            }

            //validate selected areas to show
            var show_on_places = $('.share_monkey_settings_field.share_monkey_show_on_places')
            if($('[name="share_monkey_settings[show_on_places][]"]:checked').length==0)
            {
                addClass(show_on_places,'share_monkey_error')
                addClass(show_on_places,'share_monkey_error_min')
                valid = false
            }

        return valid

        }   


        // handle icon style change
        
        $($('[name="share_monkey_settings[icon_style]"]')).click(function(){

            var selected_icons_list = $('#share_monkey_social_icons_selected')

            if($('[name="share_monkey_settings[icon_style]"]:checked').val()=='default')
            {
                $('.share_monkey_color_field').hide()
                addClass(selected_icons_list,'share_monkey_default')
                selected_icons_list.removeClass('share_monkey_custom')
            }
            else{
                $('.share_monkey_color_field').show()
                addClass(selected_icons_list,'share_monkey_custom')
                selected_icons_list.removeClass('share_monkey_default')
            }
        })


         // handle icon size change
         
         $($('[name="share_monkey_settings[icon_size]"]')).click(function(){
 
            var selected_icons_list = $('#share_monkey_social_icons_selected')

            selected_icons_list.removeClass("share_monkey_small share_monkey_medium share_monkey_large")

           addClass(selected_icons_list,'share_monkey_'+$('[name="share_monkey_settings[icon_size]"]:checked').val())
                
        })


        // Add Color Picker to bg color select and change icon bg color on change event

        $($('[name="share_monkey_settings[custom_bg_color]"]')).wpColorPicker(
            {
                change: function (event, ui) {
                    var color = ui.color.toString()
                    $('.share_monkey_bar_item').css('background-color',color)
                },
            
            }
        );


        // Add Color Picker to text color select and change icon color on change event

        $($('[name="share_monkey_settings[custom_text_color]"]')).wpColorPicker(
            {
                change: function (event, ui) {
                    var color = ui.color.toString()
                    $('.share_monkey_bar_item').css('color',color)
                },
            
            }
        );


        //handle submit button
        $('.share_monkey_submit_btn').click(function(){

            if(!validateForm()) return;

            var clickedBtn = $(this)

            var form = $('#share_monkey_admin_form')
            var admin_wrap = $('.share_monkey_admin_wrapper')

            addClass(admin_wrap ,'share_monkey_loading')

            var selected_items = ''

            $('#share_monkey_social_icons_selected li').each(function(i,e){
                selected_items += '<input type="hidden" name="share_monkey_settings[items][]" value="' + $(e).data('id') + '" />'
            })

            $('#share_monkey_selected_items').html(selected_items)
            
            var data = form.serialize() + '&action=share_monkey_update_settings' + '&nonce=' + share_monkey_ajax.share_monkey_ajax_nonce;
			
			$.post(ajaxurl, data, function(response) {
                admin_wrap.removeClass('share_monkey_loading')
                if(response.success) {
                    $(response.data.message).each(function(i,m){
                        clickedBtn.notify(m,{className:"success",position:"right"})
                    })
                }  else {
                    $(response.data.message).each(function(i,m){
                        clickedBtn.notify(m,{className:"error",position:"right"})
                    })
                }
			});
            
        })
        
        });

})( jQuery );
