<?php
/**
 * Synopter for Elementor
 * Weekly, Daily and Hourly weather forecast for Elementor
 * Exclusively on https://1.envato.market/synopter-elementor
 *
 * @encoding        UTF-8
 * @version         1.1.0
 * @copyright       (C) 2018 - 2021 Merkulove ( https://merkulov.design/ ). All rights reserved.
 * @license         Envato License https://1.envato.market/KYbje
 * @contributors    Vitaliy Nemirovskiy (nemirovskiyvitaliy@gmail.com), Dmitry Merkulov (dmitry@merkulov.design)
 * @support         help@merkulov.design
 **/

/** Register plugin custom autoloader. */
spl_autoload_register( function ( $class ) {

    $namespace = 'JsonMachine\\';

    /** Bail if the class is not in our namespace. */
    if ( 0 !== strpos( $class, $namespace ) ) {
        return;
    }

    /** Build the filename. */
    $file = realpath( __DIR__ );
    $file = $file . DIRECTORY_SEPARATOR . str_replace( '\\', DIRECTORY_SEPARATOR, $class ) . '.php';

    /** If the file exists for the class name, load it. */
    if ( file_exists( $file ) ) {
        /** @noinspection PhpIncludeInspection */
        include( $file );
    }

} );