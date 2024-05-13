/*
 * jQuery throttle / debounce - v1.1 - 3/7/2010
 * http://benalman.com/projects/jquery-throttle-debounce-plugin/
 *
 * Copyright (c) 2010 "Cowboy" Ben Alman
 * Dual licensed under the MIT and GPL licenses.
 * http://benalman.com/about/license/
 */
(function(b,c){var $=b.jQuery||b.Cowboy||(b.Cowboy={}),a;$.throttle=a=function(e,f,j,i){var h,d=0;if(typeof f!=="boolean"){i=j;j=f;f=c}function g(){var o=this,m=+new Date()-d,n=arguments;function l(){d=+new Date();j.apply(o,n)}function k(){h=c}if(i&&!h){l()}h&&clearTimeout(h);if(i===c&&m>e){l()}else{if(f!==true){h=setTimeout(i?k:l,i===c?e-m:e)}}}if($.guid){g.guid=j.guid=j.guid||$.guid++}return g};$.debounce=function(d,e,f){return f===c?a(d,e,false):a(d,f,e!==false)}})(this);
/**
 *  jquery-sticky-hooters v0.0.1
 *  Lightweight jQuery plugin providing sticky header and footer functionality for tables and lists.
 *
 *  @module     jquery-sticky-hooters
 *  @extends    jQuery
 *  @requires   jQuery throttle/debounce v1.1
 *              http://benalman.com/projects/jquery-throttle-debounce-plugin/
 *
 *  @example
 *      // Configuration object is optional if setting up a table
 *      // with a sticky header and sticky footer.
 *      $(<your-list-container>).stickyHooters({
 *          // these are in the context of <your-list-container>
 *          footerSelector: '<footer-selector>',   // {String} default is 'tfoot'
 *          headerSelector: '<header-selector>'    // {String} default is 'thead',
 *          top: '<number><units>',                // {String} (CSS value) default is '0'
 *          bottom: '<number><units>'              // {String} (CSS value) default is '0'
 *      });
 *
 *  @author     Kevin Boucher
 *  @license    Dual licensed under MIT and GNU GPL
 */
;(function($, window, document, undefined) {

     var ua = window.navigator.userAgent;
    var msie = ua.indexOf("MSIE ");

    if (!(msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)))  // If Internet Explorer, return version number
    {

    'use strict';

    // Defaults and constants
    var pluginName = 'stickyHooters',
        defaults = {
            footerSelector: 'tfoot',
            headerSelector: 'thead',
            top: '0',
            bottom: '0'
        },
        classNames = {
            outerWrapper: 'sticky-hooters_wrapper',
            innerWrapper: 'sticky-hooters_sticky-wrapper',
            innerWrapperHead: 'sticky-hooters_sticky-header',
            innerWrapperFoot: 'sticky-hooters_sticky-footer',
        };

    // StickyHooters constructor
    function StickyHooters(element, options) {
        this.element = element;
        this.settings = $.extend({}, defaults, options);

        this.footerElement = $(this.settings.footerSelector, this.element)[0];
        this.headerElement = $(this.settings.headerSelector, this.element)[0];
        this.isTable = this.element.tagName.toLowerCase() === 'table';

        this._defaults = defaults;
        this._name = pluginName;
        this.isVisible = function() {
            var viewportHeight = window.innerHeight;
            return this.element.getBoundingClientRect().top < viewportHeight && this.element.getBoundingClientRect().bottom > 0;
        };
        this.init();
    }

    jQuery.extend(StickyHooters.prototype, {

        /**
        *  Initializes DOM and sets event listeners.
        *
        *  @method init
        */
        init: function() {
            // Add DOM wrapper to provide known reference point
            jQuery(this.element).wrap('<div class="' + classNames.outerWrapper + '"></div>');

            if (this.footerElement || this.headerElement) {

                // Clone, wrap, decorate and store references to header/footer.
                if (this.footerElement) {
                    this.setupHooter(true);
                }
                if (this.headerElement) {
                    this.setupHooter();
                }

                /*
                    Add throttled scroll event listener and trigger scroll
                    event to initialize sticky header/footer positions.
                */
                window.addEventListener('scroll', jQuery.throttle(60, this.watchHooters.bind(this)));
                window.dispatchEvent(new Event('scroll'));
            }
        },

        /**
        *  Decorates DOM elements to support sticky functionality.
        *
        *  @method setupHooter
        *  @param {Boolean} Is this a sticky footer?
        */
        setupHooter: function(isFooter) {
            var insertAction = isFooter ? 'insertAfter' : 'insertBefore',
                element = isFooter ? 'footerElement' : 'headerElement',
                wrapperClasses = [
                    classNames.innerWrapper,
                    isFooter ? classNames.innerWrapperFoot : classNames.innerWrapperHead
                ];

            /**
                1. Create and store header/footer clone
                2. Wrap with sticky-hooters DIV
                3. Conditionally wrap with TABLE (THEAD/TFOOT only)
                5. Set clone width equal to container and hide
                6. Append to DOM
             */
            this[element].stickyClone = $(this[element]).clone(true)
                .wrap(
                    $('<div></div>').css({
                        bottom: isFooter ? this.settings.bottom : 'auto',
                        position: 'fixed',
                        top: !isFooter ? this.settings.top : 'auto'
                    }).addClass(wrapperClasses.join(' '))
                )
                .wrap(function () {
                        var classNames = this.element.getAttribute('class');
                        if (this.isTable) {
                            return $('<table></table>').addClass(classNames).css({
                                'table-layout': 'fixed'
                            });
                        }
                        return '';
                    }.bind(this)
                ).parents('.' + classNames.innerWrapper)
                .css({
                     width: $(this.element).parent().width(),
                     display: 'none'
                 })
                [insertAction](this.element)[0];
        },

        /**
        *  Hides inline element and displays sticky clone.
        *
        *  @method stick
        *  @param {HTMLElement} The header or footer item to be stuck.
        */
        stick: function(elem) {
            elem.isStuck = true;
            elem.style.visibility = 'hidden';
            elem.stickyClone.style.display = 'block';
        },

        /**
        *  Shows inline element and hides sticky clone.
        *
        *  @method unstick
        *  @param {HTMLElement} The header or footer item to be unstuck.
        */
        unstick: function(elem) {
            elem.isStuck = false;
            elem.style.visibility = 'visible';
            elem.stickyClone.style.display = 'none';
        },

        /**
        *  If sticky footer is enabled, this method will be called
        *  on scroll to make any required updates to the footer.
        *
        *  @method watchFooter
        *  @param {HTMLElement} The sticky footer item to be processed.
        */
        watchFooter: function(footer, header) {
            var footAdjust = parseInt(this.settings.bottom, 10),
                footRect = footer.getBoundingClientRect(),
                headRect = header.getBoundingClientRect(),
                viewHeight = window.innerHeight;

            if (footer.isStuck) {
                /**
                    Unstick this sticky hooter's footer element if:
                        1. Footer has moved above bottom of viewport, OR ...
                        2. Header has scrolled to the footer, OR ...
                        3. Sticky hooter element is no longer visible in the viewport
                 */
                if (footRect.top <= viewHeight - footRect.height - footAdjust ||
                    headRect.bottom > viewHeight - footRect.height - footAdjust ||
                    !this.isVisible()) {
                    this.unstick(footer);
                }
            } else {
                /**
                    Stick this sticky hooter's footer element if:
                        1. Footer element is below bottom of the viewport, AND ...
                        2. Header is above sticky footer, AND ...
                        3. Sticky hooter element is visible in the viewport
                 */
                if (footRect.top > viewHeight - footRect.height - footAdjust &&
                    headRect.bottom <= viewHeight - footRect.height / 2 - footAdjust &&
                    this.isVisible()) {
                    this.stick(footer);
                }
            }
        },

        /**
        *  If sticky footer is enabled, this method will be called
        *  on scroll to make any required updates to the footer.
        */
        watchHeader: function(header, footer) {
            var headAdjust = parseInt(this.settings.top, 10),
                footRect = footer ? footer.getBoundingClientRect() : null,
                headRect = header.getBoundingClientRect(),
                footOffset = footRect ? footRect.top : this.element.getBoundingClientRect().bottom,
                headHeight = footRect ? headRect.height / 2 : headRect.height;

            if (header.isStuck) {
                if (headRect.top > headAdjust || footOffset < headHeight + headAdjust) {
                    this.unstick(header);
                }
            } else {
                if (headRect.top <= parseInt(this.settings.top, 10) && footOffset > headHeight + headAdjust) {
                    this.stick(header);
                }
            }
        },

        /**
         *  Delegates scroll event handling to specific header
         *  and footer DOM manipulation methods.
         *
         *  @parameter {UIEvent} jQuery scroll Event object with injected
         *                       instance reference.
         */
        watchHooters: function(/*event*/) {
            if (!!this.footerElement) {
                this.watchFooter(this.footerElement, this.headerElement);
            }

            if (!!this.headerElement) {
                this.watchHeader(this.headerElement, this.footerElement);
            }
        }
    });

    /**
        Lightweight wrapper around the constructor,
        preventing multiple instantiations.
    */
    jQuery.fn[pluginName] = function(options) {
        return this.each(function() {
            if (!$.data(this, 'plugin_' + pluginName)) {
                $.data(this, 'plugin_' + pluginName, new StickyHooters(this, options));
            }
        });
    };
    }

})(jQuery, window, document);
