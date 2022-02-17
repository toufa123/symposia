(function ($) {

    $(window).on('elementor/frontend/init', function () {
        var PremiumHorizontalScrollHandler = elementorModules.frontend.handlers.Base.extend({

            getDefaultSettings: function () {
                return {
                    selectors: {
                        hScrollElem: '.premium-hscroll-wrap',
                        sectionWrap: 'premium-hscroll-sections-wrap',
                        hscrollTemp: 'premium-hscroll-temp',
                    }
                }
            },

            getDefaultElements: function () {
                var selectors = this.getSettings('selectors'),
                    elements = {
                        $hScrollElem: this.$element.find(selectors.hScrollElem),
                    };

                elements.$sectionWrap = elements.$hScrollElem.find('.' + selectors.sectionWrap);
                elements.$hscrollTemp = elements.$hScrollElem.find('.' + selectors.hscrollTemp);

                return elements;
            },

            bindEvents: function () {
                this.run();
            },

            run: function () {

                var $scope = this.$element,
                    hScrollSettings = this.elements.$hScrollElem.data("settings"),
                    instance = null,
                    templates = this.getElementSettings('section_repeater');

                if (!templates.length) return;

                templates.forEach(function (template) {

                    if ("id" === template.template_type && "" !== template.section_id) {
                        if ($("#" + template.section_id).length == 0) {
                            $hScrollElem.html(
                                '<div class="premium-error-notice"><span>Section with ID <b>' +
                                template.section_id +
                                "</b> does not exist on this page. Please make sure that section ID is properly set from section settings -> Advanced tab -> CSS ID.<span></div>"
                            );
                            return;
                        }
                    }
                });

                instance = new premiumHorizontalScroll($scope, hScrollSettings, this.getElementSettings());
                instance.checkDisableOnOption();

            },

        });

        window.premiumHorizontalScroll = function ($scope, settings, controlSettings) {

            var self = this,
                $elem = $scope.find('.premium-hscroll-wrap'),
                id = settings.id,
                count = controlSettings.section_repeater.length,
                editMode = elementorFrontend.isEditMode(),
                currentDevice = elementorFrontend.getCurrentDeviceMode(),
                progressOffset = 300,
                currentActiveArr = [],
                currentActive = 0,
                prevActive = -1,
                loop = controlSettings.loop,
                entrance = controlSettings.entrance_animation,
                snapScroll = 'snap' === controlSettings.scroll_effect,
                controller = false,
                isScrolling = false,
                scene = null,
                offset = null,
                horizontalSlide = null,
                rtlMode = controlSettings.rtl_mode,
                scrollEvent = null,
                dimensions = null;

            $elem.find(".premium-hscroll-temp").each(function (index, template) {

                var hideOn = $(template).data('hide');

                if (-1 < hideOn.indexOf(currentDevice)) {
                    hideSection(template, index);
                }

            });

            function hideSection(template, index) {

                if (0 !== count) {
                    count--;
                    $(template).remove();
                    $elem.find('.premium-hscroll-total-slides').html(count > 9 ? count : ('0' + count));
                    $elem.find('.premium-hscroll-nav-item[data-slide="section_' + id + index + '"]').remove();
                }

                if (0 === count) {
                    $elem.find('.premium-hscroll-arrow, .premium-hscroll-nav, .premium-hscroll-pagination').remove();
                }

                if (settings.opacity) {
                    $elem.find(".premium-hscroll-temp:first").removeClass("premium-hscroll-hide");
                }

            }

            var $slides = $elem.find(".premium-hscroll-temp");

            if (settings.opacity)
                var targetIndex = 0;

            if (rtlMode)
                targetIndex = count - 1;


            if ('desktop' !== currentDevice) {
                if (snapScroll && settings.disableSnap) {
                    snapScroll = false;
                    entrance = false;
                }
                if (['tablet', 'tablet_extra'].includes(currentDevice)) {
                    progressOffset = 100;
                } else if (['mobile', 'mobile_extra'].includes(currentDevice)) {
                    progressOffset = 50;
                }
            } else if (snapScroll) {
                progressOffset = 30;
            }

            var $nav = $(".premium-hscroll-nav-item", $elem),
                $arrows = $(".premium-hscroll-wrap-icon", $elem);

            self.checkDisableOnOption = function () {

                var disableOn = controlSettings.disable_on;

                if (disableOn.includes(elementorFrontend.getCurrentDeviceMode())) {

                    $elem.find('.premium-hscroll-arrow, .premium-hscroll-progress, .premium-hscroll-nav, .premium-hscroll-pagination').remove();

                    $elem.find(".premium-hscroll-temp").each(function (index, slide) {
                        $(slide).removeClass('premium-hscroll-temp');
                    });

                    $elem.find('.premium-hscroll-sections-wrap').removeClass('premium-hscroll-sections-wrap');

                    return;
                }

                self.init();

            }

            self.init = function () {

                if (!count) return;

                self.setLayout();

                self.setSectionsData();

                self.handleAnimations();

                self.setScene();

                if (!loop) self.checkActive();

                scene.on("progress", self.onProgress);

                $nav.on("click.premiumHorizontalScroll", self.onNavDotClick);

                $arrows.on("click.premiumHorizontalScroll", self.onNavArrowClick);

                self.checkRemoteAnchors();

                self.checkLocalAnchors();

                $(document).on('elementor/popup/show', function () {
                    self.checkLocalAnchors();
                });

                $(window)
                    .on("resize", self.refresh);

                if (snapScroll)
                    document.addEventListener ?
                        document.addEventListener("wheel", self.onScroll, {
                            passive: false
                        }) :
                        document.attachEvent("onmousewheel", self.onScroll);

                document.addEventListener ?
                    document.addEventListener("keydown", self.onKeyboardPress) :
                    document.attachEvent("keydown", self.onKeyboardPress);

                if (snapScroll) {
                    $(window)
                        .on("load", function () {
                            var windowOuterHeight = $(window).outerHeight();

                            if (offset - windowOuterHeight < 150)
                                return;

                            if (0 === currentActive) {
                                elementorFrontend.waypoint(
                                    $elem,
                                    function (direction) {
                                        if ("down" === direction) {
                                            self.scrollToSlide(0);
                                        }
                                    }, {
                                    offset: 150,
                                    triggerOnce: false
                                }
                                );
                            }
                        });
                }
            };

            self.checkLocalAnchors = function () {

                $("a").on("click", function (event) {

                    var href = $(this).attr("href");

                    if (href) {

                        href = href.replace('#/', '');

                        self.checkAnchors(href);
                    }

                });

            }

            self.checkRemoteAnchors = function () {

                var url = new URL(window.location.href);

                if (!url)
                    return;

                var slideID = url.searchParams.get("slide");

                if (slideID)
                    self.checkAnchors(slideID);

            };

            self.checkAnchors = function (href) {

                var $slide = $elem.find(".premium-hscroll-temp[data-section='" + href + "']");

                if (!$slide.length)
                    return;

                var slideIndex = $slide.index();

                self.scrollToSlide(slideIndex, "anchors");

            };

            self.onKeyboardPress = function (e) {

                //If Keyboard scrolling is disabled, we should disable scroll when state is 'DURING'.
                if (!settings.keyboard && "DURING" === scene.state()) {
                    e.preventDefault();
                    return;
                }

                if ("BEFORE" === scene.state()) {
                    return;
                } else {
                    var downKeyCodes = [40, 34],
                        upKeyCodes = [38, 33];

                    if ("AFTER" === scene.state()) {
                        if (-1 !== $.inArray(e.keyCode, upKeyCodes)) {
                            var lastScrollOffset = self.getScrollOffset(
                                $slides.eq(count - 1)
                            );

                            if (
                                e.pageY - lastScrollOffset <= 300 &&
                                e.pageY - lastScrollOffset > 100
                            ) {

                                self.preventDefault(event);
                                self.scrollToSlide(count - 1);


                            } else if (e.pageY - lastScrollOffset < 100) {

                                self.preventDefault(event);
                                self.scrollToSlide(count - 2);
                            }

                            return;
                        }
                    } else {

                        if (-1 !== $.inArray(e.keyCode, downKeyCodes)) {
                            if (isScrolling) {
                                self.preventDefault(event);
                                return;
                            }

                            self.goToNext();
                        }


                        if (-1 !== $.inArray(e.keyCode, upKeyCodes)) {
                            if (isScrolling) {
                                self.preventDefault(event);
                                return;
                            }

                            self.goToPrev("keyboard");
                        }
                    }
                }
            };

            self.getResponsiveControlValue = function (ID) {
                var value = controlSettings[ID];

                if ('desktop' !== currentDevice) {
                    value = controlSettings[ID + '_' + currentDevice];
                }

                if ('scroll_speed' === ID) {

                    value = !value ? 1 : value;

                } else {
                    value = parseFloat(('' === value.size || undefined === value) ? self.getControlDefaultVal(ID) : value.size);
                }

                return value;

            };

            self.getControlDefaultVal = function (ID) {
                return 'distance' === ID ? 0 : 1;
            };

            self.setScene = function () {

                controller = new ScrollMagic.Controller();

                horizontalSlide = new TimelineMax();

                //Make sure spacer `.premium-hscroll-spacer` is set to content-box
                setTimeout(function () {
                    self.setHorizontalSlider();
                }, 200);

                var scrollSpeed = self.getResponsiveControlValue('scroll_speed');

                if (['desktop', 'laptop', 'widescreen'].includes(currentDevice)) {
                    scrollSpeed = scrollSpeed * 100 + "%";
                } else {
                    scrollSpeed = scrollSpeed * $elem.outerHeight();
                }


                scene = new ScrollMagic.Scene({
                    triggerElement: "#premium-hscroll-spacer-" + id,
                    triggerHook: "onLeave",
                    duration: scrollSpeed
                })
                    .setPin("#premium-hscroll-wrap-" + id, {
                        pushFollowers: true
                    })
                    .setTween(horizontalSlide)
                    .addTo(controller);

            };

            self.getDimensions = function () {

                var firstWidth = $slides.eq(0).innerWidth(),
                    distance = firstWidth * (count - 1),
                    progressWidth = firstWidth * count;

                var slidesInViewPort = self.getResponsiveControlValue('slides'),
                    distanceBeyond = self.getResponsiveControlValue('distance');

                distance = distance - (1 - 1 / slidesInViewPort) * $elem.outerWidth();

                distance = distanceBeyond + distance;

                if (rtlMode)
                    $("#premium-hscroll-scroller-wrap-" + id).css("transform", "translateX(" + -distance + "px)");

                var ease = Power0.easeNone;

                return {
                    distance: distance,
                    progressBar: progressWidth,
                    ease: ease
                };

            };

            self.setHorizontalSlider = function (progress) {

                // horizontalSlide = new TimelineMax();

                dimensions = self.getDimensions();

                if ('tablet' === currentDevice && !rtlMode && self.checkIpad()) {
                    horizontalSlide
                        .to("#premium-hscroll-scroller-wrap-" + id, 1, { left: rtlMode ? "0px" : -dimensions.distance, ease: dimensions.ease }, 0)
                } else {
                    horizontalSlide
                        .to("#premium-hscroll-scroller-wrap-" + id, 1, { x: rtlMode ? "0px" : -dimensions.distance, ease: dimensions.ease }, 0);
                }

                horizontalSlide.to("#premium-hscroll-progress-line-" + id, 1, { width: dimensions.progressBar + "px", ease: dimensions.ease }, 0);

                if ($scope.hasClass('custom-scroll-bar')) {

                    $elem.append('<div class="horizontal-content-scroller"><span></span></div>');

                    var progressWrap = $(".horizontal-content-scroller").outerWidth(),
                        progressBarSpan = $(".horizontal-content-scroller span").outerWidth();

                    var progressBarTransform = progressWrap - progressBarSpan;

                    horizontalSlide.to('.horizontal-content-scroller span', 1, { x: progressBarTransform, ease: dimensions.ease }, 0);
                }

                if ('undefined' !== typeof progress) {
                    scene.progress(0);
                    scene.update(true);
                }

            }

            self.checkIpad = function () {
                return /Macintosh/.test(navigator.userAgent) && 'ontouchend' in document;
            };

            self.setLayout = function () {
                $elem
                    .closest("section.elementor-section-height-full")
                    .removeClass("elementor-section-height-full");
            };

            self.setSectionsData = function () {

                var slidesInViewPort = self.getResponsiveControlValue('slides');
                var slideWidth = 100 / slidesInViewPort;

                $elem
                    .find(".premium-hscroll-slider")
                    .css("width", count * slideWidth + "%");

                $elem.find(".premium-hscroll-temp")
                    .css("width", 100 / count + "%");

                var scrollSpeed = self.getResponsiveControlValue('scroll_speed'); // will change to scroll_speed

                var width = parseFloat(
                    $elem.find(".premium-hscroll-sections-wrap").width() / count),
                    winHeight = $(window).height() * scrollSpeed;

                $slides.each(function (index, template) {

                    if ($(template).data("section")) {
                        var id = $(template).data("section");

                        self.getSectionContent(id);
                    }

                    var position = index * width;

                    $(template).attr("data-position", position);
                });

                offset = $elem.offset().top;

                $slides.each(function (index, template) {

                    var scrollOffset = (index * winHeight) / (count - 1);

                    $(template).attr("data-scroll-offset", offset + scrollOffset);
                });
            };

            self.onScroll = function (event) {
                if (isScrolling && null !== event) self.preventDefault(event);


                var delta = self.getDirection(event),
                    state = scene.state(),
                    direction = 0 > delta ? "down" : "up";

                if ("up" === direction && "AFTER" === scene.state()) {
                    var lastScrollOffset = self.getScrollOffset(
                        $slides.eq(count - 1)
                    );

                    if (
                        window.pageYOffset - lastScrollOffset <= 300 &&
                        window.pageYOffset - lastScrollOffset > 100
                    )
                        self.scrollToSlide(count - 1);
                }

                if ("DURING" === state) {
                    if ("down" === direction) {
                        if (!isScrolling && count - 1 !== currentActive) {
                            self.goToNext();
                        }
                    } else if ("up" === direction) {
                        if (!isScrolling && 0 !== currentActive) self.goToPrev();
                    }

                    if (
                        (0 !== currentActive && "up" === direction) || ("down" === direction && count - 1 !== currentActive)
                    ) {
                        self.preventDefault(event);
                    }
                }
            };

            self.getDirection = function (e) {
                e = window.event || e;
                var t = Math.max(
                    -1,
                    Math.min(1, e.wheelDelta || -e.deltaY || -e.detail)
                );
                return t;
            };

            self.setSnapScroll = function (event) {
                var direction = event.scrollDirection;

                if (
                    (0 !== currentActive && "REVERSE" === direction) ||
                    "FORWARD" === direction
                ) {
                    if (null !== scrollEvent) self.preventDefault(scrollEvent);
                }

                var $nextArrow = $(".premium-hscroll-next", $elem),
                    $prevArrow = $(".premium-hscroll-prev", $elem);

                if ("FORWARD" === direction) {
                    if (!isScrolling && count - 1 !== currentActive) {
                        $nextArrow.trigger("click.premiumHorizontalScroll");
                    }
                } else {
                    if (!isScrolling && 0 !== currentActive)
                        $prevArrow.trigger("click.premiumHorizontalScroll");
                }
            };

            self.refresh = function () {

                setTimeout(function () {
                    var sceneProgress = scene.progress();
                    self.setHorizontalSlider(sceneProgress);
                }, 200);

            };

            self.onProgress = function () {

                var progressFillWidth = $elem.find(".premium-hscroll-progress-line").outerWidth(),
                    elemWidth = $elem.outerWidth(),
                    slideArea = 100 / count;

                $slides.each(function (index) {

                    // console.log(slideArea * index);

                    // console.log(slideArea * index, scene.progress() * 100);

                    var scrollOffset = $slides.eq(index - 1).data("scroll-offset"),
                        scrollPosition = $(this).data("position");

                    if (settings.opacity && targetIndex !== index) {

                        if (window.pageYOffset >= scrollOffset + elemWidth / 8) {
                            $(this).removeClass("premium-hscroll-hide");
                        } else {
                            $(this).addClass("premium-hscroll-hide");
                        }

                    }

                    // if (scene.progress() * 100 >= slideArea * index) {
                    if (progressFillWidth >= scrollPosition - progressOffset) {

                        if (entrance && !isScrolling)
                            self.triggerAnimations();

                        if (-1 === currentActiveArr.indexOf(index)) {

                            currentActiveArr.push(index);

                            currentActive = index;
                            self.onSlideChange();
                        }

                    } else {

                        if (-1 !== currentActiveArr.indexOf(index)) {
                            currentActiveArr.pop();

                            currentActive = currentActiveArr[currentActiveArr.length - 1];
                            self.onSlideChange();
                        }

                    }
                });
            };

            self.onSlideChange = function () {

                prevActive = currentActive;

                self.addBackgroundLayer();

                if (settings.pagination && !snapScroll) {

                    var text = currentActive + 1 > 9 ? "" : "0";
                    $elem
                        .find(".premium-hscroll-current-slide")
                        .text(text + (currentActive + 1));
                }

                $nav.removeClass("active");

                $elem
                    .find(".premium-hscroll-nav-item")
                    .eq(currentActive)
                    .addClass("active");

                self.checkActive();

                if (entrance && !isScrolling)
                    self.restartAnimations(currentActive);
            };

            self.addBackgroundLayer = function () {

                if ($elem.find(".premium-hscroll-bg-layer[data-layer='" + currentActive + "']").length) {
                    $elem.find(".premium-hscroll-layer-active").removeClass("premium-hscroll-layer-active");

                    $elem.find(".premium-hscroll-bg-layer[data-layer='" + currentActive + "']").addClass("premium-hscroll-layer-active");
                }

            };

            self.getSectionContent = function (sectionID) {
                if (!$("#" + sectionID)
                    .length) return;

                var htmlContent = $("#" + sectionID);

                if (!editMode) {
                    $("#premium-hscroll-scroller-wrap-" + id)
                        .find('div[data-section="' + sectionID + '"]')
                        .append(htmlContent);
                } else {
                    $slides.find(".elementor-element-overlay")
                        .remove();
                    $("#premium-hscroll-scroller-wrap-" + id)
                        .find('div[data-section="' + sectionID + '"]')
                        .append(htmlContent.clone(true));
                }
            };

            self.checkActive = function () {
                if (!$arrows.length) return;

                if (loop) {
                    if (-1 === currentActive) {
                        currentActive = count - 1;
                    } else if (count === currentActive) {
                        currentActive = 0;
                    }
                } else {
                    if (0 === currentActive) {
                        $elem
                            .find(".premium-hscroll-arrow-left")
                            .addClass("premium-hscroll-arrow-hidden");
                    } else {
                        $elem
                            .find(".premium-hscroll-arrow-left")
                            .removeClass("premium-hscroll-arrow-hidden");
                    }

                    if (count - 1 === currentActive) {
                        $elem
                            .find(".premium-hscroll-arrow-right")
                            .addClass("premium-hscroll-arrow-hidden");
                    } else {
                        $elem
                            .find(".premium-hscroll-arrow-right")
                            .removeClass("premium-hscroll-arrow-hidden");
                    }
                }

            };

            self.onNavDotClick = function () {
                if (isScrolling) return;

                var $item = $(this),
                    index = $item.index();

                if (index === prevActive && "DURING" === scene.state()) return;


                currentActive = index;

                self.scrollToSlide(index);
            };

            self.onNavArrowClick = function (e) {
                if (isScrolling) return;

                if ($(e.target).closest(".premium-hscroll-arrow-left").length) {
                    self.goToPrev();
                } else if ($(e.target).closest(".premium-hscroll-arrow-right").length) {
                    self.goToNext();
                }

            };

            self.goToNext = function () {
                if (isScrolling) return;

                currentActive++;

                if (loop) {
                    if (-1 === currentActive) {
                        currentActive = count - 1;
                    } else if (count === currentActive) {
                        currentActive = 0;
                    }
                }

                self.scrollToSlide(currentActive);
            };

            self.goToPrev = function (trigger) {

                if (isScrolling || ("keyboard" === trigger && currentActive === 0))
                    return;

                currentActive--;


                if (loop) {
                    if (-1 === currentActive) {
                        currentActive = count - 1;
                    } else if (count === currentActive) {
                        currentActive = 0;
                    }
                }

                self.scrollToSlide(currentActive);
            };

            self.scrollToSlide = function (slideIndex, scrollSrc) {

                var targetOffset = self.getScrollOffset($slides.eq(slideIndex));

                if (!scrollSrc) {
                    if (isScrolling) return;
                }


                if (0 > currentActive || count - 1 < currentActive) return;

                isScrolling = true;

                prevActive = slideIndex;

                var spacerHeight = $("#premium-hscroll-spacer-" + id).outerHeight();

                if (!snapScroll) {

                    TweenMax.to(window, 1.5, {
                        scrollTo: {
                            y: targetOffset - spacerHeight
                        },
                        ease: Power3.easeOut,
                        onComplete: self.afterSlideChange
                    });

                } else {
                    $("html, body")
                        .stop()
                        .clearQueue()
                        .animate({
                            scrollTop: targetOffset
                        }, 1000);
                }

                if (settings.pagination && snapScroll)
                    $elem
                        .find(".premium-hscroll-current-slide")
                        .removeClass("zoomIn animated");

                if (settings.pagination && snapScroll) {
                    setTimeout(function () {

                        if (
                            currentActive + 1 !=
                            $elem.find(".premium-hscroll-current-slide")
                                .text()
                        ) {
                            //Lead zero
                            var text = currentActive + 1 > 9 ? "" : "0";
                            $elem
                                .find(".premium-hscroll-current-slide")
                                .text(text + (currentActive + 1))
                                .addClass("zoomIn animated");
                        }
                    }, 1000);
                }

                if (entrance) {
                    setTimeout(function () {
                        self.setAnimations();
                    }, 1000);
                }

                if (snapScroll) {
                    setTimeout(function () {
                        isScrolling = false;
                    }, 1500);
                }
            };

            self.afterSlideChange = function () {
                isScrolling = false;
            };

            self.handleAnimations = function () {
                if (entrance) {

                    self.hideAnimations();

                    elementorFrontend.waypoint($elem, function () {
                        self.setAnimations();
                    });
                } else {
                    self.unsetAnimations();
                }
            };

            self.hideAnimations = function () {

                $slides.find(".elementor-invisible").addClass("premium-hscroll-elem-hidden");

            };

            self.unsetAnimations = function () {
                $slides.find(".elementor-invisible")
                    .each(function (index, elem) {
                        $(elem)
                            .removeClass("elementor-invisible");
                    });
            };

            self.setAnimations = function () {

                self.restartAnimations();

                self.triggerAnimations();
            };

            self.restartAnimations = function (slideIndex) {
                var $unactiveSlides = $slides.filter(function (index) {
                    return index !== slideIndex;
                });

                $unactiveSlides.find(".animated")
                    .each(function (index, elem) {
                        var settings = $(elem)
                            .data("settings");

                        if (undefined === settings) return;

                        var animation = settings._animation || settings.animation;

                        $(elem)
                            .removeClass("animated " + animation)
                            .addClass("elementor-invisible");
                    });
            };

            self.triggerAnimations = function () {

                $slides
                    .eq(currentActive)
                    .find(".elementor-invisible")
                    .each(function (index, elem) {
                        var settings = $(elem)
                            .data("settings");

                        if (undefined === settings) return;

                        if (!settings._animation && !settings.animation) return;

                        var delay = settings._animation_delay ?
                            settings._animation_delay :
                            0,
                            animation = settings._animation || settings.animation;

                        setTimeout(function () {
                            $(elem)
                                .removeClass("elementor-invisible premium-hscroll-elem-hidden")
                                .addClass(animation + " animated");
                        }, delay);
                    });
            };

            self.getScrollOffset = function (item) {

                if (!$(item).length)
                    return;

                var slideOffset = $(item).data("scroll-offset");

                if ($("#upper-element").length > 0) {
                    slideOffset = slideOffset + $("#upper-element").closest(".premium-notbar-outer-container").outerHeight();
                    $(item).attr("data-scroll-offset", slideOffset);
                }

                return slideOffset;
            };

            self.preventDefault = function (event) {
                if (event.preventDefault) {
                    event.preventDefault();
                } else {
                    event.returnValue = false;
                }
            };
        };

        elementorFrontend.elementsHandler.attachHandler('premium-hscroll', PremiumHorizontalScrollHandler);

    });

})(jQuery);
