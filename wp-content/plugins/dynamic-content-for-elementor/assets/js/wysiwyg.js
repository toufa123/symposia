"use strict";

function WysiwygFieldHandler($scope) {
	tinymce.init({
		selector: '.elementor-field-type-dce_wysiwyg textarea',
		menubar: false,
		branding: false,
		plugins: "lists, link, paste",
	});
};

jQuery(window).on('elementor/frontend/init', function() {
	elementorFrontend.hooks.addAction('frontend/element_ready/form.default', WysiwygFieldHandler);
});
