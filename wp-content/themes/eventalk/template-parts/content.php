<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\Eventalk;

$thumb_size = THEME_PREFIX . '-size1';

$comments_number = number_format_i18n( get_comments_number() );
$comments_html   = $comments_number < 2 ? esc_html__( 'Comment' , 'eventalk' ) : esc_html__( 'Comments' , 'eventalk' );
$comments_html  .= ': '. $comments_number;
$has_entry_meta_1  = RDTheme::$options['blog_date'] || ( RDTheme::$options['blog_cats'] && has_category() ) ? true : false;
$has_entry_meta_2  = RDTheme::$options['blog_author_name'] || RDTheme::$options['blog_comment_num'] ? true : false;
$thumb_class 	   = has_post_thumbnail() ? '' : ' nothumb';
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-each' ); ?>>
	<div class="blog-layout5">
	    <div class="entry-image">
	        <div class="item-image">
	        	<?php
				if ( has_post_thumbnail() ){ ?>
	        	<a href="<?php the_permalink();?>" rel="bookmark">
			        <?php the_post_thumbnail( $thumb_size ); ?>						
				</a>
				<?php }
					?>	
	        </div>
	        <div class="item-content">
	        	<ul class="news-meta-info mar20-ul">					
					<?php if ( RDTheme::$options['blog_date'] ): ?>
						<li class="item-date">
							<a href="<?php the_permalink();?>" rel="bookmark"><span class="updated published"> <i class="fa fa-calendar" aria-hidden="true"></i> <?php the_time( get_option( 'date_format' ) );?></span></a>
						</li>
					<?php endif; ?>	
					<?php if ( RDTheme::$options['blog_author_name'] ): ?>
						<li class="vcard-author"><i class="fa fa-user" aria-hidden="true"></i> <span class="vcard author"><?php the_author_posts_link();?></span>
						</li>
					<?php endif; ?>							
					<?php if ( RDTheme::$options['blog_comment_num']): ?>
						<li><i class="fa fa-comment-o" aria-hidden="true"></i> <?php echo wp_kses_post( $comments_html );?>
						</li>
					<?php endif; ?>	
									
				</ul>	  
	            <h2 class="entry-title title size-blog title-semibold color-dark hover-primary">
	                <a href="<?php the_permalink();?>"><?php the_title();?></a>
	            </h2>	                
		        <div class="entry-summary">		        	
		       		<?php the_excerpt();?>		 
		   		</div>     	
	        </div>
	    </div>
	</div>
</article>