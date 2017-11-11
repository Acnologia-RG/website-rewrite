<?php
/**
 * Columns Output for Pages - columns.php
 *
 * @summary        Outputs Advanced Custom Fields (ACF) columns.
 * @param $postId  (integer)
 *                 The ID of the Post you would like to get columns from. Defaults to
 *                 the post in the current WordPress loop if available and a valid
 *                 post is not provided.
 * @return $output (boolean | string)
 *                 If no output exists, or the post does not exist, FALSE is returned.
 *                 If column fields are filled in for the post, the HTML and content
 *                 is returned.
 */
function columns( $postId=NULL ) {
    // Return value (no information default value)
    $output = FALSE;
    // Get Post if one is not provided
    if ( ( $postId === NULL ) || !is_int( $postId ) || ( (int)$postId <= 0 ) ) {
        global $post;
        $postId = $post->ID;
    }
    // Continue if Post exists
    if ( get_post_status ( $postId ) ) {
        // Prepare output as string
        $output = '';
        // loop through the rows of data
        while ( have_rows('flexible_content_area') ) : the_row();
            // Single Column Display:
            if ( get_row_layout() == 'single_column' ) :
                $output .= '<section class="single-column">';
                    $output .= '<div class="column">';
                        $output .= get_sub_field( 'single_column' );
                    $output .= '</div>';
                $output .= '</section>';
            // Two Column Display:
            elseif ( get_row_layout() == 'two_column' ) :
                $output .= '<section class="two-column">';
                    $output .= '<div class="column">';
                        $output .= get_sub_field( 'two_column_1' );
                    $output .= '</div>';
                    $output .= '<div class="column">';
                        $output .= get_sub_field( 'two_column_2' );
                    $output .= '</div>';
                $output .= '</section>';
            // Three Column Display:
            elseif ( get_row_layout() == 'three_column' ) :
                $output .= '<section class="three-column">';
                    $output .= '<div class="column">';
                        $output .= get_sub_field( 'three_column_1' );
                    $output .= '</div>';
                    $output .= '<div class="column">';
                        $output .= get_sub_field( 'three_column_2' );
                    $output .= '</div>';
                    $output .= '<div class="column">';
                        $output .= get_sub_field( 'three_column_3' );
                    $output .= '</div>';
                $output .= '</section>';
            endif;
        endwhile;
        // If no valid outputs detected, $output is FALSE
        if ( empty( $output ) ) $output = FALSE;
    }
    return $output;
}