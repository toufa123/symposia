(function ($) {
  var WidgetElementsPopupHandler = function ($scope, $) {

      var dce_popup_settings = dceGetElementSettings($scope);
      var id_scope = $scope.attr('data-id');
      var id_popup = $scope.find('.dce-modal').attr('id');
      var is_animate = false;

      function dce_show_modal(id_modal) {
          var id_modal_scope = id_modal.split('-');
          id_modal_scope.pop();
          id_modal_scope = id_modal_scope.join('-');

          var open_delay = 0;
          if (dce_popup_settings.open_delay.size) {
              open_delay = dce_popup_settings.open_delay.size;
          }
          if(!is_animate)
          setTimeout(function () {
              //aggiungo al body la classe aperto
              if (!elementorFrontend.isEditMode()) {
                  is_animate = true;
                  $('body').removeClass('modal-close-' + id_modal).removeClass('modal-close-' + id_modal_scope);
                  $('body').addClass('modal-open-' + id_modal).addClass('modal-open-' + id_modal_scope).addClass('dce-modal-open');
                  $('html').addClass('dce-modal-open');

                  $('#' + id_modal + ' .modal-dialog').one('webkitAnimationEnd oanimationend msAnimationEnd animationend', function (el) {
                     is_animate = false;
                  });
              }
              if (dce_popup_settings.wrapper_maincontent) {
                  $(dce_popup_settings.wrapper_maincontent).addClass('dce-push').addClass('animated').parent().addClass('perspective');
              }
              $('#' + id_modal).show();
              $('#' + id_modal + '-background').show().removeClass('fadeOut').addClass('fadeIn').one('webkitAnimationEnd oanimationend msAnimationEnd animationend', function (el) {
                 is_animate = false;
              });

          }, open_delay);
      }

      function dce_hide_modal(id_modal) {
          // set cookie
          var id_modal_scope = id_modal.split('-');
          id_modal_scope.pop();
          id_modal_scope = id_modal_scope.join('-');

          if (!dce_popup_settings.always_visible) {
        	dce_setCookie(id_modal, 1, dce_popup_settings.cookie_lifetime);
          }
          var settings_close_delay = 0;
          if (dce_popup_settings.close_delay) {
            settings_close_delay = dce_popup_settings.close_delay;
          }

		  $('.elementor-video').each(function(){
			this.contentWindow.postMessage('{"event":"command","func":"pauseVideo","args":""}', '*')
		  });

		  $('.elementor-video-iframe').each(function(){
			this.contentWindow.postMessage('{"method":"pause"}', '*')
		  });

          // Remove from body the "open" class
          $('body').removeClass('modal-open-' + id_modal).removeClass('modal-open-' + id_modal_scope);
          $('body').addClass('modal-close-' + id_modal).addClass('modal-close-' + id_modal_scope);
          $('#' + id_modal + '-background').hide();

          $('#' + id_modal + ' .modal-dialog').one('webkitAnimationEnd oanimationend msAnimationEnd animationend', function (el) {
              $('#' + id_modal).hide();
              setTimeout(function () {
                  if (!elementorFrontend.isEditMode()) {
                      $('body').removeClass('modal-close-' + id_modal).removeClass('modal-close-' + id_modal_scope).removeClass('dce-modal-open');
                      $('html').removeClass('dce-modal-open');
                      $(el.currentTarget).off('webkitAnimationEnd oanimationend msAnimationEnd animationend');
                  }
                  if (dce_popup_settings.wrapper_maincontent)
                      $(dce_popup_settings.wrapper_maincontent).removeClass('dce-push').removeClass('animated').parent().removeClass('perspective');
              }, 300);
          });

          setTimeout(function () {
              $('#' + id_modal + '-background').removeClass('fadeIn').addClass('fadeOut');
          }, settings_close_delay);
      }


      var modal = $scope.find('.dce-popup-container-' + id_scope);

      function push_actions() {
          if (!elementorFrontend.isEditMode()) {
              $(modal).prependTo("body");
          }

      }
      push_actions();

      // ON LOAD
      $('.dce-popup-container-'+id_scope+'.dce-popup-onload').each(function () {
          var id_modal = $(this).find('.dce-modal').attr('id');

          // read cookie
          var cookie_popup = dce_getCookie(id_modal);
          if (dce_popup_settings.always_visible) {
              cookie_popup = false;
          }
          if (!cookie_popup) {
              dce_show_modal(id_modal);
          }
      });

      // BUTTON
      $scope.on('click', '.dce-button-open-modal, .dce-button-next-modal', function () {
          var id_modal = $(this).data('target');
          dce_show_modal(id_modal);
      });

      // SCROLL
      if ($('.dce-popup-container-'+id_scope+'.dce-popup-scroll').length) {
          $(window).on('scroll', function () {
              $('.dce-popup-scroll').each(function () {
                  if ($(window).scrollTop() > dce_popup_settings.scroll_display_displacement) {
                      $(this).removeClass('dce-popup-scroll');
                      var id_modal = $(this).find('.dce-modal').attr('id');
                      dce_show_modal(id_modal);
                  }
              });
          });
      }

      $(window).on('scroll', function () {
          $('.modal-hide-on-scroll:visible').each(function () {
              $(this).removeClass('modal-hide-on-scroll');
              dce_hide_modal($(this).attr('id'));
          });
      });

      $(document).on('keyup', function (evt) {
          if (evt.keyCode == 27) {
              $('.modal-hide-esc:visible').each(function () {
                  dce_hide_modal($(this).attr('id'));
              });
          }
      });

      // Closing with X
      $(document).on('click', '#'+id_popup+'.dce-modal .dce-modal-close, .dce-modal .dce-button-close-modal, .dce-modal .dce-button-next-modal', function () {
          dce_hide_modal(id_popup);
      });
      // Closing when click background
      $(document).on('click', '#'+id_popup+'-background.dce-modal-background-layer-close', function () {
          dce_hide_modal(id_popup);
      });
    };

    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/dyncontel-popup.default', WidgetElementsPopupHandler);
    });
})(jQuery);
