/**
 * Menuar for Elementor
 * Customizable menu for Elementor editor
 * Exclusively on https://1.envato.market/menuar-elementor
 *
 * @encoding        UTF-8
 * @version         1.0.1
 * @copyright       (C) 2018 - 2021 Merkulove ( https://merkulov.design/ ). All rights reserved.
 * @license         Envato License https://1.envato.market/KYbje
 * @contributors    Nemirovskiy Vitaliy (nemirovskiyvitaliy@gmail.com), Dmitry Merkulov (dmitry@merkulov.design), Cherviakov Vlad (vladchervjakov@gmail.com)
 * @support         help@merkulov.design
 **/

"use strict";

/**
 * Mdp menuar main object
 * @type {{menu: menuarElementor.menu, addMenu: menuarElementor.addMenu}}
 */
const menuarElementor = {

    /**
    * Main logic menu method
    * @param wrapperName
    * */
    menu: function ( wrapperName ) {
        const menuWrapper = document.querySelector( `.${wrapperName} .mdp-menuar-elementor-menu-wrapper` );
        const menuarNav = document.querySelector( `.${wrapperName} .mdp-menuar-elementor-nav` );
        const dropdownLinks = document.querySelectorAll( `.${wrapperName} .mdp-menuar-elementor-main-menu--dropdown-link` );
        const submenuIndicator = document.querySelectorAll( `.${wrapperName} .mdp-menuar-submenu-indicator` );
        const menuItems = document.querySelectorAll( `.${wrapperName} .mdp-menuar-elementor-main-menu > .mdp-menuar-elementor-main-menu--item` );
        const submenuItems = document.querySelectorAll( `.${wrapperName} .mdp-menuar-elementor-main-menu--dropdown > .mdp-menuar-elementor-main-menu--item` );
        const mediaSize = menuarNav.dataset.breakpoint;
        const clickArea = menuarNav.dataset.clickArea;

        // reset active items on mouse enter
        function resetActiveItems() {
            const activeItems = menuWrapper.querySelectorAll( `.${wrapperName} .mdp-menuar-elementor-main-menu > .mdp-elementor-main-menu--hover` );
            const activeSubmenuItems = menuWrapper.querySelectorAll( '.mdp-menuar-elementor-main-menu--dropdown > .mdp-elementor-main-menu--hover' );

            activeItems.forEach( item => {
                item.classList.remove( 'mdp-elementor-main-menu--hover' );
            } );

            activeSubmenuItems.forEach( item => {
                item.classList.remove( 'mdp-elementor-main-menu--hover' );
            } );
        }

        let listenerMouseEnter = function ( e ) {
            resetActiveItems();
            e.target.classList.add( 'mdp-elementor-main-menu--hover' );
        }

        let listenerSubmenuMouseEnter = function ( e ) {
            if ( e.relatedTarget.classList.contains( 'mdp-menuar-elementor-main-menu--dropdown' ) ) {
                e.relatedTarget.querySelectorAll( '.mdp-elementor-main-menu--hover' ).forEach( item => {
                    item.classList.remove( 'mdp-elementor-main-menu--hover' );
                } );
            }

            e.target.classList.add( 'mdp-elementor-main-menu--hover' );

            e.target.addEventListener( 'mouseleave', ( e ) => {
                if ( e.relatedTarget !== null ) {
                    if ( e.relatedTarget.classList.contains( 'mdp-menuar-elementor-main-menu--item' )
                        ||  e.relatedTarget.classList.contains( 'mdp-menuar-elementor-main-menu--dropdown-link' )
                        ||  e.relatedTarget.classList.contains( 'mdp-menuar-submenu-indicator' )
                        ||  e.relatedTarget.classList.contains( 'mdp-menuar-elementor-category-post-count' )
                        ||  e.relatedTarget.classList.contains( 'mdp-menuar-elementor-main-menu--link' ) ) {
                        e.target.classList.remove( 'mdp-elementor-main-menu--hover' );
                    } else if ( e.relatedTarget.classList.contains( 'mdp-menuar-elementor-main-menu--dropdown' ) ) {
                        e.relatedTarget.querySelectorAll( '.mdp-elementor-main-menu--hover' ).forEach( item => {
                            item.classList.remove( 'mdp-elementor-main-menu--hover' );
                        } );
                        e.target.classList.add( 'mdp-elementor-main-menu--hover' );
                    }
                }
            } );
        }


        //check if element has parent
        function isDescendant( parent, child ) {
            let node = child.parentNode;
            while ( node !== null ) {
                if( node === parent ) {
                    return true;
                }
                node = node.parentNode;
            }
            return false;
        }

        if ( mediaSize.replace( /[^0-9]/g,'' ) > 0 ) {
            const toggleBtn = document.querySelector( `.${wrapperName} .mdp-menuar-elementor-toggle-icon` );
            const toggleCloseBtn = document.querySelector( `.${wrapperName} .mdp-menuar-elementor-toggle-close-icon` );

            //close on click outside menu
            document.addEventListener( 'click', e => {
                if( !isDescendant( menuarNav, e.target ) ) {
                    menuWrapper.classList.remove( 'mdp-menuar-elementor-main-menu-wrapper--active' );
                    toggleBtn.style.display = 'block';
                    toggleCloseBtn.style.display = 'none';
                }
            } );

            //toggle burger button
            toggleBtn.addEventListener( 'click', () => {
                toggleBtn.style.display = 'none';
                toggleCloseBtn.style.display = 'block';
                menuWrapper.classList.add( 'mdp-menuar-elementor-main-menu-wrapper--active' );
            } );

            //toggle close button
            toggleCloseBtn.addEventListener( 'click', () => {
                toggleBtn.style.display = 'block';
                toggleCloseBtn.style.display = 'none';
                menuWrapper.classList.remove( 'mdp-menuar-elementor-main-menu-wrapper--active' );
            } );
        }

        // function that checks screen size
        function checkSizeOfScreen( mediaSize ) {
            if( window.matchMedia( `(max-width: ${mediaSize})` ).matches ) {
                menuWrapper.classList.add( 'mdp-menuar-elementor-main-wrapper--mobile' );
                const clickAreas = clickArea === 'text' ? dropdownLinks : submenuIndicator;
                resetActiveItems();
                menuItems.forEach( item => {
                    item.removeEventListener( 'mouseenter', listenerMouseEnter );
                } );
                submenuItems.forEach( item => {
                    item.removeEventListener( 'mouseenter', listenerSubmenuMouseEnter );
                } );
                clickAreas.forEach( area => {
                    area.onclick =  ( e ) => {
                        if( area.tagName === 'A' ) {
                            e.preventDefault();
                        }
                        let dropdown = area.closest( "li" ).querySelector( '.mdp-menuar-elementor-main-menu--dropdown' );
                        if ( dropdown.classList.contains( 'mdp-menuar-elementor-main-menu--dropdown-show' ) ) {
                            dropdown.classList.remove( 'mdp-menuar-elementor-main-menu--dropdown-show' );
                        } else {
                            dropdown.classList.add( 'mdp-menuar-elementor-main-menu--dropdown-show' );
                        }
                    } ;
                } );
            } else {
                menuItems.forEach( item => {
                    item.addEventListener( 'mouseenter', listenerMouseEnter );
                } );

                submenuItems.forEach( item => {
                    item.addEventListener( 'mouseenter', listenerSubmenuMouseEnter );
                } );

                menuWrapper.classList.remove( 'mdp-menuar-elementor-main-wrapper--mobile' );
            }
        }

        // check screen size on resizing
        window.addEventListener( 'resize', () => {
           checkSizeOfScreen( mediaSize );
        } );

        // check screen size on refresh
        checkSizeOfScreen( mediaSize );
    },

    /**
    * Adding menu method
    * */
    addMenu: function () {
        const menuBox = document.querySelectorAll( '.mdp-menuar-elementor-box' );

        for ( let i = 0; i < menuBox.length; i++ ) {
            menuBox[i].classList.add( 'mdp-menuar-elementor-box-' + i );
            this.menu( 'mdp-menuar-elementor-box-' + i );
        }
    }
};

/**
 * Init for Front-End
 * @param callback
 */
document.addEventListener( 'DOMContentLoaded', menuarElementor.addMenu.bind( menuarElementor ) );