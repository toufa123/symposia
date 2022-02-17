( function( $ ) {
	var isReveal = false;
	var WidgetElements_RevealHandler = function( $scope, $ ) {
		var elementSettings = dceGetElementSettings( $scope );
		var rev1;
		var revealAction = function(){
			rev1 = new RevealFx(revealistance, {
				revealSettings : {
					bgcolor: elementSettings.reveal_bgcolor,
					direction: elementSettings.reveal_direction,
					duration: Number(elementSettings.reveal_speed.size)*100,
					delay: Number(elementSettings.reveal_delay.size)*100,
					onCover: function(contentEl, revealerEl) {
						contentEl.style.opacity = 1;
					}
				}

			});

		};
		var runReveal = function(){
			rev1.reveal();
		};
		if(elementSettings.enabled_reveal){

			var revealId = '#reveal-'+$scope.data('id');
			var revealistance = document.querySelector(revealId);
      if (!jQuery(revealId).hasClass('block-revealer')) {
          revealAction();
      }

			var waypointOptions = {
				offset: '100%',
				triggerOnce: true
			};
			elementorFrontend.waypoint($(revealistance), runReveal, waypointOptions);

		}
	};

	// Make sure you run this code under Elementor..
	$( window ).on( 'elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/global', WidgetElements_RevealHandler );
	} );
} )( jQuery );
