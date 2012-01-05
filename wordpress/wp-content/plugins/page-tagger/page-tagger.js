/*
Page Tagger wordpress plugin
Copyright (C) 2009-2012 Ramesh Nair

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

/*
Plugin Name: Page Tagger
Plugin URI: http://www.hiddentao.com/code/wordpress-page-tagger-plugin/
Description: Enables tagging for pages. PHP 5 required.
Version: 0.3.7
Author: Ramesh Nair
Author URI: http://www.hiddentao.com/
*/

function array_unique_noempty(a) {
	var out = [];
	jQuery.each( a, function(key, val) {
		val = jQuery.trim(val);
		if ( val && jQuery.inArray(val, out) == -1 )
			out.push(val);
		} );
	return out;
}

function new_tag_remove_tag() {
	var id = jQuery( this ).attr( 'id' ), num = id.split('-check-num-')[1], taxbox = jQuery(this).parents('.tagsdiv'), current_tags = taxbox.find( '.the-tags' ).val().split(','), new_tags = [];
	delete current_tags[num];

	jQuery.each( current_tags, function(key, val) {
		val = jQuery.trim(val);
		if ( val ) {
			new_tags.push(val);
		}
	});

	taxbox.find('.the-tags').val( new_tags.join(',').replace(/\s*,+\s*/, ',').replace(/,+/, ',').replace(/,+\s+,+/, ',').replace(/,+\s*$/, '').replace(/^\s*,+/, '') );

	tag_update_quickclicks(taxbox);
	return false;
}

function tag_update_quickclicks(taxbox) {
	if ( jQuery(taxbox).find('.the-tags').length == 0 )
		return;

	var current_tags = jQuery(taxbox).find('.the-tags').val().split(',');
	jQuery(taxbox).find('.tagchecklist').empty();
	shown = false;

	jQuery.each( current_tags, function( key, val ) {
		var txt, button_id;

		val = jQuery.trim(val);
		if ( !val.match(/^\s+$/) && '' != val ) {
			button_id = jQuery(taxbox).attr('id') + '-check-num-' + key;
 			txt = '<span><a id="' + button_id + '" class="ntdelbutton">X</a>&nbsp;' + val + '</span> ';
 			jQuery(taxbox).find('.tagchecklist').append(txt);
 			jQuery( '#' + button_id ).click( new_tag_remove_tag );
		}
	});
	if ( shown )
		jQuery(taxbox).find('.tagchecklist').prepend('<strong>'+pageTaggerL10n.tagsUsed+'</strong><br />');
}

function tag_flush_to_text(id, a) {
	a = a || false;
	var taxbox, text, tags, newtags;

	taxbox = jQuery('#'+id);
	text = a ? jQuery(a).text() : taxbox.find('input.newtag').val();

	// is the input box empty (i.e. showing the 'Add new tag' tip)?
	if ( taxbox.find('input.newtag').hasClass('form-input-tip') && ! a )
		return false;

	tags = taxbox.find('.the-tags').val();
	newtags = tags ? tags + ',' + text : text;

	// massage
	newtags = newtags.replace(/\s+,+\s*/g, ',').replace(/,+/g, ',').replace(/,+\s+,+/g, ',').replace(/,+\s*$/g, '').replace(/^\s*,+/g, '');
	newtags = array_unique_noempty(newtags.split(',')).join(',');
	taxbox.find('.the-tags').val(newtags);
	tag_update_quickclicks(taxbox);

	if ( ! a )
		taxbox.find('input.newtag').val('').focus();

	return false;
}

function tag_save_on_publish() {
	jQuery('.tagsdiv').each( function(i) {
		if ( !jQuery(this).find('input.newtag').hasClass('form-input-tip') ) {
        	tag_flush_to_text(jQuery(this).parents('.tagsdiv').attr('id'));
		} else {
			// just in case tag_flush_to_text gets called later on anyway
			jQuery(this).find('input.newtag').val(''); 
		}
	} );
}

function tag_press_key( e ) {
	if ( 13 == e.which ) {
		tag_flush_to_text(jQuery(e.target).parents('.tagsdiv').attr('id'));
		return false;
	}
};

function tag_init() {

	jQuery('.ajaxtag').show();
    jQuery('.tagsdiv').each( function(i) {
        tag_update_quickclicks(this);
    } );

    // add the quickadd form
    jQuery('.ajaxtag input.tagadd').click(function(){tag_flush_to_text(jQuery(this).parents('.tagsdiv').attr('id'));});
    jQuery('.ajaxtag input.newtag').focus(function() {
        if ( !this.cleared ) {
            this.cleared = true;
            jQuery(this).val( '' ).removeClass( 'form-input-tip' );
        }
    });

    jQuery('.ajaxtag input.newtag').blur(function() {
        if ( this.value == '' ) {
            this.cleared = false;
            jQuery(this).val( pageTaggerL10n.addTag ).addClass( 'form-input-tip' );
        }
    });

    // auto-save tags on post preview/save/publish
    jQuery('#post-preview, #save-post, #publish').click( tag_save_on_publish );

    // catch the enter key
    jQuery('.ajaxtag input.newtag').keypress( tag_press_key );
}

var tagCloud;
(function($){
	tagCloud = {
		init : function() {
			$('.tagcloud-link').click(function(){
				
				// in WP 3 this call already gets made elsewhere
				if (!$(this).hasClass('wp3'))
					tagCloud.get($(this).attr('id'));
				
				$(this).unbind().click(function(){
					$(this).siblings('.the-tagcloud').toggle();
					return false;
				});
				return false;
			});
		},

		get : function(id) {
			var tax = id.substr(id.indexOf('-')+1);

			$.post(ajaxurl, {'action':'get-tagcloud','tax':tax}, function(r, stat) {
				if ( 0 == r || 'success' != stat )
					r = wpAjax.broken;

				r = $('<p id="tagcloud-'+tax+'" class="the-tagcloud">'+r+'</p>');
				$('a', r).click(function(){
					var id = $(this).parents('p').attr('id');
					tag_flush_to_text(id.substr(id.indexOf('-')+1), this);
					return false;
				});

				$('#'+id).after(r);
			});
		}
	};

	$(document).ready(function(){tagCloud.init();});
})(jQuery);


jQuery(document).ready( function($) {

	// prepare the tag UI
	tag_init();

	// auto-suggest stuff
	$('.newtag').each(function(){
		var tax = $(this).parents('div.tagsdiv').attr('id');
		$(this).suggest( 'admin-ajax.php?action=ajax-tag-search&tax='+tax, { delay: 500, minchars: 2, multiple: true, multipleSep: ", " } );
	});

});

