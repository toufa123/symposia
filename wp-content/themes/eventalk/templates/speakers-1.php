<?php
/**
 * Template Name: Speakers 1
 * 
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\Eventalk;

RDTheme::$options['speakers_arc_style'] = 'style1';

$query = Helper::speakers_query();

global $wp_query;
$wp_query = NULL;
$wp_query = $query;

get_template_part( 'template-parts/archive', 'speakers' );

wp_reset_postdata(); 