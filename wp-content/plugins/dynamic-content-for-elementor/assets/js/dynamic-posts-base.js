var dceDynamicPostsSkin = '';
var dceDynamicPostsSkinPrefix = '';
var Widget_DCE_Dynamicposts_base_Handler = function($scope, $) {

	dceDynamicPostsSkin = $scope.attr('data-widget_type').split('.')[1];
	if(dceDynamicPostsSkin === 'grid-filters') {
		dceDynamicPostsSkin = 'grid_filters';
	}
    dceDynamicPostsSkinPrefix = dceDynamicPostsSkin + '_';
    var elementSettings = dceGetElementSettings($scope);

    // Run on load
	fitImages();
	if ( typeof elementor !== 'undefined' ) {
		elementor.channels.editor.on( 'dceDynamicPosts:previewImageRatio', (view) => {
			imageRatioOn();
		});
	}

    // HOVER EFFECTS
    var blocks_hoverEffects = $scope.find('.dce-post-block.dce-hover-effects');
    if (blocks_hoverEffects.length) {
		blocks_hoverEffects.each(function(i, el) {
			$(el).on("mouseenter touchstart", function() {
				$(this).find('.dce-hover-effect-content').removeClass('dce-close').addClass('dce-open');
			});
			$(el).on("mouseleave touchend", function() {
				$(this).find('.dce-hover-effect-content').removeClass('dce-open').addClass('dce-close');
			});
		});
    }

    // Linkable Template
    if(
      false === elementorFrontend.isEditMode()
	  && 'yes' === elementSettings.templatemode_linkable
    ){
      $scope.find('.dce-post.dce-post-item[data-post-link]').click(function() {
        window.location.assign( $(this).attr("data-post-link") );
        return false;
      });
    }

	// Fit Images Ratio
	function fitImage($post) {
		var $imageParent = $post.find('.dce-img');
		$image = $imageParent.find('img');
		image = $image[0];

		if (!image) {
			return;
		}

		var imageParentRatio = $imageParent.outerHeight() / $imageParent.outerWidth(),
		imageRatio = image.naturalHeight / image.naturalWidth;
		$imageParent.toggleClass('dce-fit-img', imageRatio < imageParentRatio);
  	}

	function imageRatioOn() {
		$scope.find('.dce-posts-container').toggleClass('dce-is-ratio', true);
		$scope.find('.dce-post-image').each(function() {
			var $post = $(this);
			var $imageParent = $post.find('.dce-img');
			$imageParent.toggleClass('dce-fit-img', true);
		});
	}

	function fitImages() {
		var itemRatio = $scope.find('.dce-post-image figure').first().data('image-ratio');

		if( !itemRatio ) {
			return;
		}
		$scope.find('.dce-posts-container').toggleClass('dce-is-ratio', itemRatio);
		$scope.find('.dce-post-image').each(function() {
			var $post = $(this);
			$image = $post.find('.dce-img img');
			fitImage($post);
			$image.on('load', function() {
				fitImage($post);
			});
		});
	}
};

jQuery(window).on('elementor/frontend/init', function() {
	elementorFrontend.hooks.addAction('frontend/element_ready/widget', Widget_DCE_Dynamicposts_base_Handler);
});

// Re init layout after ajax request on Search&Filter Pro
(function ( $ ) {
	"use strict";
	$(function () {
		$(document).on("sf:ajaxfinish", ".searchandfilter", function( e, data ) {
			if ( elementorFrontend) {
				if ( elementorFrontend.elementsHandler.runReadyTrigger && SF_LDATA.extensions.indexOf('search-filter-elementor') < 0 ) {
					elementorFrontend.elementsHandler.runReadyTrigger(data.targetSelector);
				}
			}
		});
	});
}(jQuery));
