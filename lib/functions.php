<?php
/**
 * KR Read More Inline - functions
 *
 * This file contains all the content filters
 */
 


/**
* Display the post content replacing the default behaviour of the more tag 
*
* @since 1.0
*
*/

function kr_read_more_inline_content() {
	
	// get unfiltered content after more tag
	$more_content =  get_the_content('', TRUE);	
		
	global $more;
	$temp = $more;
	$more = false;
	$excerpt = get_the_content( '' );
	$more = $temp;
	
	
	if (preg_match( '/<span id="more-\d+"><\/span>/i', get_the_content() )) {
		echo _read_more_less( $excerpt, $more_content );
	}
	else {
		echo  apply_filters('the_content',get_the_content() );
	}
}

	
function get_field_read_more($field, $post_id = '') {
					
	$content = get_field($field, $post_id, FALSE);	
	
	$content_arr = get_extended( $content );
	
	$clean_content_arr = array_map( 'trim', $content_arr);
	
	if( !empty( $clean_content_arr['extended'] ) ) {
					
		return _read_more_less( $clean_content_arr['main'], $clean_content_arr['extended'] );
	}
	else {
		return get_field($field, $post_id);	
	}

}



function get_text_read_more( $content ) {
					
	$content_arr = get_extended( $content );
	
	$clean_content_arr = array_map( 'trim', $content_arr);
	
	if( !empty( $clean_content_arr['extended'] ) ) {
					
		return _read_more_less( $clean_content_arr['main'], $clean_content_arr['extended'] );
	}
	else {
		return wpautop( $content );	
	}
}


add_shortcode('custom-read-more', 'read_more_shortcode');
function read_more_shortcode( $attr = array(), $content = '' ) {
						
	$content_arr = preg_split('/<span id="more-\d+"><\/span>/i', $content );
	
	if( count( $content_arr ) == 2 ) {
		$excerpt = $content_arr[0];
		$more_content = $content_arr[1];
		return _read_more_less( $excerpt, $more_content );
	}
	else {
		return $content;
	}
}



function _read_more_less( $main, $extended ) {
				
	
	$defaults = array(
		'more_text'  => 'read more...',
		'less_text'  => 'read less...'
	);
	
	/* Apply filters to the arguments. */
	$args = apply_filters( 'kr_read_more_inline_args', $args );
	
	/* Parse the arguments and extract them for easy variable naming. */
	$args = wp_parse_args( $args, $defaults );	
	
	
	wp_enqueue_script('kr-read-more-inline');
	wp_localize_script('kr-read-more-inline', 'read_more_inline_script_vars', array(
				'more_text' => $args['more_text'],
				'less_text' => $args['less_text']
			)
		);													
		
	
	$more_link = sprintf(' <p class="read-more-link"><a class="read-more" href="#">%s</a></p>', $args['more_text'] );
			
	if( !empty( $extended ) ) {
					
		$main 	  = apply_filters('the_content', $main );
		$extended = apply_filters('the_content', $extended );
					
		return  sprintf( '<div class="more-content">%s<div class="read-more-content">%s</div>%s</div>', $main, $extended, $more_link );								
	}
	else {
		
		return $main;
		
	}
}

function custom_read_more( $content ) {
				
		$out =  '<div class="more-content no-preview"><p><span class="read-more-link"><a class="read-more" href="#"><span>read more...</span></a></span></p>';
		$out .= '<div class="read-more-content">';
			$out .=  apply_filters( 'the_content', $content . '<span class="read-less-link"><a class="read-less" href="#"><span>read less...</span></a></span>' );
			$out .=  '</div>';
		$out .=  '</div>';
		
		return $out;
}