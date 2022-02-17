var gtf3d = null;
var Widget_DCE_Dynamicposts_gridtofullscreen3d_Handler = function($scope, $) {

  var elementSettings = dceGetElementSettings($scope);
  var gridtofullscreen3d = $scope.find('.dce-posts-container.dce-skin-gridtofullscreen3d');
  var gridtofullscreen3d_wrapper = $scope.find('.dce-posts-wrapper.dce-gridtofullscreen3d-wrapper');
  var gridtofullscreen3d_target = '.dce-post-image';
  const panel_position = elementSettings.gridtofullscreen3d_gridtofullscreen3d_panel_position;
  var deviceMode = $('body').attr('data-elementor-device-mode');

  if (panel_position == 'left' || panel_position == 'right') {
    const panel_width_desktop = elementSettings.gridtofullscreen3d_gridtofullscreen3d_panel_width.size;
    const panel_width_tablet = elementSettings.gridtofullscreen3d_gridtofullscreen3d_panel_width_tablet.size;
    const panel_width_mobile = elementSettings.gridtofullscreen3d_gridtofullscreen3d_panel_width_mobile.size;
    var panel_width = panel_width_desktop;
    if (deviceMode == 'tablet' && panel_width_tablet) {
      panel_width = panel_width_tablet;
    } else if (deviceMode == 'mobile' && panel_width_mobile) {
      panel_width = panel_width_mobile;
    }
    var title_width = 100 - panel_width;
  } else {
    // Panel position top or bottom
    const panel_height_desktop = elementSettings.gridtofullscreen3d_gridtofullscreen3d_panel_height.size;
    const panel_height_tablet = elementSettings.gridtofullscreen3d_gridtofullscreen3d_panel_height_tablet.size;
    const panel_height_mobile = elementSettings.gridtofullscreen3d_gridtofullscreen3d_panel_height_mobile.size;
    var panel_height = panel_height_desktop;
    if (deviceMode == 'tablet' && panel_height_tablet) {
      panel_height = panel_height_tablet;
    } else if (deviceMode == 'mobile' && panel_height_mobile) {
      panel_height = panel_height_mobile;
    }
    var title_height = 100 - panel_height;
  }

  function createEffect(options) {
    const transitionEffect = new GridToFullscreenEffect(
      document.getElementById("app"),
      gridtofullscreen3d_wrapper[0],
      gridtofullscreen3d_target,
      Object.assign({
          scrollContainer: window,
          onToFullscreenStart: ({
            index
          }) => {},
          onToFullscreenFinish: ({
            index
          }) => {},
          onToGridStart: ({
            index
          }) => {},
          onToGridFinish: ({
            index,
            lastIndex
          }) => {}
        },
        options
      )
    );

    return transitionEffect;
  }

  let currentIndex;
  const itemsWrapper = gridtofullscreen3d_wrapper[0];
  const thumbs = [...itemsWrapper.querySelectorAll("img.grid__item-img:not(.grid__item-img--large)")];
  const fullviewItems = [...document.querySelectorAll(".fullview__item")];
  const backToGridCtrl = document.querySelector(".fullview__close");
  const transitionEffectDuration = elementSettings[dceDynamicPostsSkinPrefix + 'gridtofullscreen3d_duration']['size'] || 1.8;
  const activationType = elementSettings[dceDynamicPostsSkinPrefix + 'gridtofullscreen3d_activations'] || 'corners';

  const effect1 = {
    activation: {
      type: "closestCorner"
    },
    timing: {
      duration: transitionEffectDuration
    },
    transformation: {
      type: "flipX"
    },
    flipBeizerControls: {
      c0: {
        x: 0.4,
        y: -0.8
      },
      c1: {
        x: 0.5,
        y: 0.9
      }
    },
    onToFullscreenStart: ({
      index
    }) => {
      currentIndex = index;
      thumbs[currentIndex].style.opacity = 0;

      toggleFullview();
    },
    onToGridFinish: ({
      index,
      lastIndex
    }) => {
      thumbs[lastIndex].style.opacity = 1;
      fullviewItems[currentIndex].classList.remove("fullview__item--current");
    },
    easings: {
      toFullscreen: Quint.easeOut,
      toGrid: Quint.easeOut
    }
  };
  const effect2 = {
    activation: {
      type: "sinX"
    },
    flipX: false,
    timing: {
      type: "sections",
      sections: 4,
      duration: transitionEffectDuration
    },
    onToFullscreenStart: ({
      index
    }) => {
      currentIndex = index;
      thumbs[currentIndex].style.opacity = 0;
      toggleFullview();
    },
    onToGridFinish: ({
      index,
      lastIndex
    }) => {
      thumbs[lastIndex].style.opacity = 1;
      fullviewItems[currentIndex].classList.remove("fullview__item--current");
    },
    easings: {
      toFullscreen: Cubic.easeInOut,
      toGrid: Cubic.easeInOut
    }
  };
  const effect3 = {
    activation: {
      type: "top"
    },
    timing: {
      type: "sections",
      sections: 20,
      duration: transitionEffectDuration
    },
    onToFullscreenStart: ({
      index
    }) => {
      currentIndex = index;
      thumbs[currentIndex].style.opacity = 0;
      toggleFullview();
    },
    onToGridFinish: ({
      index,
      lastIndex
    }) => {
      thumbs[lastIndex].style.opacity = 1;
      fullviewItems[currentIndex].classList.remove("fullview__item--current");
    },
    easings: {
      toFullscreen: Quint.easeInOut,
      toGrid: Quint.easeInOut
    }
  };
  const effect4 = {
    activation: {
      type: "mouse"
    },
    timing: {
      duration: transitionEffectDuration
    },
    transformation: {
      type: "simplex",
      props: {
        seed: "8000",
        frequencyX: 0.2,
        frequencyY: 0.2,
        amplitudeX: 0.3,
        amplitudeY: 0.3
      }
    },
    onToFullscreenStart: ({
      index
    }) => {
      currentIndex = index;
      thumbs[currentIndex].style.opacity = 0;
      transitionEffect.uniforms.uSeed.value = index * 10;
      toggleFullview();
    },
    onToGridFinish: ({
      index,
      lastIndex
    }) => {
      thumbs[lastIndex].style.opacity = 1;
      fullviewItems[currentIndex].classList.remove("fullview__item--current");
    },
    seed: 800,
    easings: {
      toFullscreen: Power1.easeOut,
      toGrid: Power1.easeInOut
    }
  };

  const effect5 = {
    activation: {
      type: "bottom"
    },
    timing: {
      duration: transitionEffectDuration
    },
    transformation: {
      type: "wavy",
      props: {
        seed: "8000",
        frequency: 1,
        amplitude: 0.6
      }
    },
    onToFullscreenStart: ({
      index
    }) => {
      currentIndex = index;
      thumbs[currentIndex].style.opacity = 0;
      transitionEffect.uniforms.uSeed.value = index * 10;
      toggleFullview();
    },
    onToGridFinish: ({
      index,
      lastIndex
    }) => {
      thumbs[lastIndex].style.opacity = 1;
      fullviewItems[currentIndex].classList.remove("fullview__item--current");
    },
    seed: 800,
    easings: {
      toFullscreen: Quint.easeOut,
      toGrid: Power3.easeOut
    }
  };
  const effect6 = {
    timing: {
      type: "sections",
      sections: 1,
      duration: transitionEffectDuration
    },
    activation: {
      type: "mouse"
    },
    transformation: {
      type: "wavy",
      props: {
        seed: "8000",
        frequency: 0.1,
        amplitude: 1
      }
    },
    onToFullscreenStart: ({
      index
    }) => {
      currentIndex = index;
      thumbs[currentIndex].style.opacity = 0;
      transitionEffect.uniforms.uSeed.value = index * 10;
      toggleFullview();
    },
    onToGridFinish: ({
      index,
      lastIndex
    }) => {
      thumbs[lastIndex].style.opacity = 1;
      fullviewItems[currentIndex].classList.remove("fullview__item--current");
    },
    seed: 800,
    easings: {
      toFullscreen: Cubic.easeOut,
      toGrid: Power3.easeInOut
    }
  };
  const custom_effect = {
    timing: {
      type: "sections",
      sections: 1,
      duration: transitionEffectDuration
    },
    activation: {
      type: activationType
    },
    transformation: {
      type: "wavy",
      props: {
        seed: "8000",
        frequency: 0.1,
        amplitude: 1
      }
    },
    onToFullscreenStart: ({
      index
    }) => {
      currentIndex = index;
      thumbs[currentIndex].style.opacity = 0;
      transitionEffect.uniforms.uSeed.value = index * 10;
      toggleFullview();
    },
    onToGridFinish: ({
      index,
      lastIndex
    }) => {
      thumbs[lastIndex].style.opacity = 1;
      fullviewItems[currentIndex].classList.remove("fullview__item--current");
    },
    seed: 800,
    easings: {
      toFullscreen: Cubic.easeOut,
      toGrid: Power3.easeInOut
    }
  };
  eval('var settings_effect = ' + elementSettings[dceDynamicPostsSkinPrefix + 'gridtofullscreen3d_effects'] + ';');
  const transitionEffect = createEffect(settings_effect);
  transitionEffect.init();

  const toggleFullview = () => {
    if (transitionEffect.isFullscreen) {
      TweenLite.to(fullviewItems[currentIndex].querySelector(".fullview__item-title"), 0.2, {
        ease: Quad.easeOut,
        opacity: 0,
        x: "5%"
      });
      if (elementSettings[dceDynamicPostsSkinPrefix + 'gridtofullscreen3d_template']) {
        TweenLite.to(fullviewItems[currentIndex].querySelector(".fullview__item-box"), 0.6, {
          ease: Expo.easeInOut,
          onComplete: function() {
            transitionEffect.toGrid();
            $('body').removeClass('dce-fullview-open');
          },
          width: "0%",
        });
      } else {
        transitionEffect.toGrid();
        $('body').removeClass('dce-fullview-open');
      }
      TweenLite.to(backToGridCtrl, 0.5, {
        ease: Quad.easeOut,
        opacity: 0,
        scale: 0
      });
    } else {
      fullviewItems[currentIndex].classList.add("fullview__item--current");
      $('body').addClass('dce-fullview-open');
      TweenLite.to(fullviewItems[currentIndex].querySelector(".fullview__item-title"), 0.8, {
        ease: Expo.easeOut,
        startAt: {
          x: "5%"
        },
        opacity: 1,
        x: "0%",
        delay: transitionEffectDuration * 0.6
      });
      if (elementSettings[dceDynamicPostsSkinPrefix + 'gridtofullscreen3d_template']) {
        if (panel_position == 'left' || panel_position == 'right') {
          TweenLite.to(fullviewItems[currentIndex].querySelector(".fullview__item-box"), 0.8, {
            ease: Expo.easeInOut,
            width: panel_width + "%",
            delay: transitionEffectDuration * 0.9,
          });
          TweenLite.to(fullviewItems[currentIndex].querySelector(".fullview__item-title"), 0, {
            ease: Expo.easeInOut,
            width: title_width + "%",
          });
        } else {
          TweenLite.to(fullviewItems[currentIndex].querySelector(".fullview__item-box"), 0.8, {
            ease: Expo.easeInOut,
            height: panel_height + "%",
            delay: transitionEffectDuration * 0.9,
          });
          TweenLite.to(fullviewItems[currentIndex].querySelector(".fullview__item-title"), 0, {
            ease: Expo.easeInOut,
            height: title_height + "%",
          });
        }
      }
      TweenLite.to(backToGridCtrl, 0.8, {
        ease: Expo.easeOut,
        startAt: {
          scale: 0
        },
        opacity: 1,
        scale: 1,
        delay: transitionEffectDuration * 0.5
      });
    }
  };

  backToGridCtrl.addEventListener("click", () => {
    if (transitionEffect.isAnimating) {
      return;
    }
    toggleFullview();
    return false;
  });
  document.addEventListener("keyup", (e) => {
    if (transitionEffect.isAnimating) {
      return;
    }
    if (e.keyCode == 27) { // escape key maps to keycode `27`
      toggleFullview();
    }

  });

  if (gridtofullscreen3d.length) {
    // Preload all the images in the page
    var allImages = $scope.find('.dce-post-block .dce-post-image img');
    imagesLoaded(allImages, instance => {
      //https://www.techrepublic.com/article/preloading-and-the-javascript-image-object/

      // Make Images sets for creating the textures.
      let images = [];
      for (var i = 0, imageSet = {}; i < instance.elements.length; i++) {
        let image = {
          element: instance.elements[i],
          image: instance.images[i].isLoaded ? instance.images[i].img : null
        };
        if (i % 2 === 0) {
          imageSet = {};
          imageSet.small = image;
        }

        if (i % 2 === 1) {
          imageSet.large = image;
          images.push(imageSet);
        }
      }
      transitionEffect.createTextures(images);
    });
  }

  Widget_DCE_Dynamicposts_grid_Handler($scope, $);
};

jQuery(window).on('elementor/frontend/init', function() {
  elementorFrontend.hooks.addAction('frontend/element_ready/dce-dynamicposts-v2.gridtofullscreen3d', Widget_DCE_Dynamicposts_gridtofullscreen3d_Handler);
  elementorFrontend.hooks.addAction('frontend/element_ready/dce-woo-products-cart.gridtofullscreen3d', Widget_DCE_Dynamicposts_gridtofullscreen3d_Handler);
  elementorFrontend.hooks.addAction('frontend/element_ready/dce-dynamic-woo-products.gridtofullscreen3d', Widget_DCE_Dynamicposts_gridtofullscreen3d_Handler);
  elementorFrontend.hooks.addAction('frontend/element_ready/dce-dynamic-show-favorites.gridtofullscreen3d', Widget_DCE_Dynamicposts_gridtofullscreen3d_Handler);
  elementorFrontend.hooks.addAction('frontend/element_ready/dce-my-posts.gridtofullscreen3d', Widget_DCE_Dynamicposts_gridtofullscreen3d_Handler);
  elementorFrontend.hooks.addAction('frontend/element_ready/dce-sticky-posts.gridtofullscreen3d', Widget_DCE_Dynamicposts_gridtofullscreen3d_Handler);
  elementorFrontend.hooks.addAction('frontend/element_ready/dce-search-results.gridtofullscreen3d', Widget_DCE_Dynamicposts_gridtofullscreen3d_Handler);
});

/**
 * demo3.js
 * http://www.codrops.com
 *
 * Licensed under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 *
 * Copyright 2019, Codrops
 * http://www.codrops.com
 */
