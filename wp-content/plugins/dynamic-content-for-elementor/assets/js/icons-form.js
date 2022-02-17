"use strict";
var iconsForm = ($scope, $) => {
	let form = $scope[0];
	var allInput = form.querySelectorAll(".elementor-form-fields-wrapper input");
	var allTextarea = form.querySelectorAll(".elementor-form-fields-wrapper textarea");
	var allSelect = form.querySelectorAll(".elementor-form-fields-wrapper select");
	var allLabels = form.querySelectorAll(".elementor-form-fields-wrapper .elementor-field-label");
	let icon;
	let inputHeight = jQuery('.elementor-form-fields-wrapper input').outerHeight();
	let labelHeight = jQuery('.elementor-field-label').outerHeight();
	let fontSize;
	let color;
	let margin;
	let elementSettings = dceGetElementSettings($scope);
	let fieldIconSize = elementSettings.field_icon_size.size;
	let fieldIconSizeDefined = Boolean( fieldIconSize );
	let labelIconSize = elementSettings.label_icon_size.size;
	let labelIconSizeDefined = Boolean( labelIconSize );

	// Input
	allInput.forEach(function(field) {
		icon = jQuery(field).attr('dce-icon-render');
		if(icon) {
			let wrapper = jQuery('<div class="dce-field-input-wrapper"></div>');
			jQuery(field).wrap( wrapper ).parent().prepend(icon);
			color = jQuery(field).css('color');
			inputHeight = jQuery(field).outerHeight();

			jQuery( '.dce-field-input-wrapper svg').addClass('input-icons');
			jQuery( '.elementor-field-label svg').addClass('label-icons');
			if( ! fieldIconSizeDefined ) {
				jQuery(field).css('padding-left', inputHeight + 'px' );
				jQuery( '.dce-field-input-wrapper i.input-icons' ).css('font-size', inputHeight - 20 + 'px');
				jQuery( '.dce-field-input-wrapper svg.input-icons' ).css('height', inputHeight - 20 + 'px');
				jQuery( '.dce-field-input-wrapper svg.input-icons' ).css('width', inputHeight - 20 + 'px');
				jQuery( '.dce-field-input-wrapper i.input-icons, .dce-field-input-wrapper svg.input-icons' ).css('top', '10px');
				jQuery( '.dce-field-input-wrapper i.input-icons, .dce-field-input-wrapper svg.input-icons' ).css('left', '10px');
			} else {
				margin = parseInt( ( inputHeight - fieldIconSize ) / 2 );
				jQuery(field).css('padding-left', fieldIconSize + 20 + 'px' );
				jQuery( '.dce-field-input-wrapper i.input-icons' ).css('top', margin + 'px');
				jQuery( '.dce-field-input-wrapper i.input-icons' ).css('left', margin + 'px');
			}
		}
	});

	// Textarea
	allTextarea.forEach(function(textarea) {
		icon = jQuery(textarea).attr('dce-icon-render');
		if(icon) {
			let wrapper = jQuery('<div class="dce-field-input-wrapper"></div>');
			jQuery(textarea).wrap( wrapper ).parent().prepend(icon);
			color = jQuery(textarea).css('color');
			jQuery(textarea).css('padding-left', inputHeight + 'px' );
			jQuery( '.dce-field-input-wrapper svg').addClass('input-icons');
			jQuery( '.elementor-field-label svg').addClass('label-icons');
			if( ! fieldIconSizeDefined ) {
				jQuery( '.dce-field-input-wrapper i.input-icons' ).css('font-size', inputHeight - 20 + 'px');
				jQuery( '.dce-field-input-wrapper svg.input-icons' ).css('height', inputHeight - 20 + 'px');
				jQuery( '.dce-field-input-wrapper svg.input-icons' ).css('width', inputHeight - 20 + 'px');
				jQuery( '.dce-field-input-wrapper i.input-icons' ).css('top', '10px');
				jQuery( '.dce-field-input-wrapper i.input-icons' ).css('left', '10px');
			} else {
				margin = parseInt( ( inputHeight - fieldIconSize ) / 2 );
				jQuery(field).css('padding-left', fieldIconSize + 20 + 'px' );
				jQuery( '.dce-field-input-wrapper i.input-icons' ).css('top', margin + 'px');
				jQuery( '.dce-field-input-wrapper i.input-icons' ).css('left', margin + 'px');
			}
		}
	});

	// Select
	allSelect.forEach(function(select) {
		icon = jQuery(select).attr('dce-icon-render');
		if(icon) {
			let wrapper = jQuery('<div class="dce-field-input-wrapper"></div>');
			jQuery(select).wrap( wrapper ).parent().prepend(icon);
			color = jQuery(select).css('color');
			jQuery(select).css('padding-left', inputHeight + 'px' );
			jQuery( '.dce-field-input-wrapper svg').addClass('input-icons');
			jQuery( '.elementor-field-label svg').addClass('label-icons');
			if( ! fieldIconSizeDefined ) {
				jQuery( '.dce-field-input-wrapper i.input-icons' ).css('font-size', inputHeight - 20 + 'px');
				jQuery( '.dce-field-input-wrapper svg.input-icons' ).css('height', inputHeight - 20 + 'px');
				jQuery( '.dce-field-input-wrapper svg.input-icons' ).css('width', inputHeight - 20 + 'px');
				jQuery( '.dce-field-input-wrapper i.input-icons' ).css('top', '10px');
				jQuery( '.dce-field-input-wrapper i.input-icons' ).css('left', '10px');
			} else {
				margin = parseInt( ( inputHeight - fieldIconSize ) / 2 );
				jQuery(field).css('padding-left', fieldIconSize + 20 + 'px' );
				jQuery( '.dce-field-input-wrapper i.input-icons' ).css('top', margin + 'px');
				jQuery( '.dce-field-input-wrapper i.input-icons' ).css('left', margin + 'px');
			}
		}
	});

	// Labels
	allLabels.forEach(function(label) {
		icon = jQuery(label).attr('dce-icon-render');
		if(icon) {
			$(icon).prependTo( label );

			if( ! labelIconSizeDefined ) {
				jQuery( '.elementor-field-label svg' ).css('height', labelHeight + 'px');
				jQuery( '.elementor-field-label svg' ).css('width', labelHeight + 'px');
				jQuery( '.elementor-field-label svg' ).css('margin-right', labelHeight / 4 + 'px');
			} else {
				jQuery( '.elementor-field-label svg' ).css('margin-right', labelIconSize / 4 + 'px');
			}
		}
	});
}

jQuery(window).on('elementor/frontend/init', function() {
	elementorFrontend.hooks.addAction('frontend/element_ready/form.default', iconsForm);
});
