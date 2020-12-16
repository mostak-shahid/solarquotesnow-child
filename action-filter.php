<?php
add_filter( 'enter_title_here', 'my_title_place_holder', 20, 2 );

function my_title_place_holder( $title, $post ) {
    if ( $post->post_type == 'listing' ) {
        $my_title = "Business name";
        return $my_title;
    } elseif ( $post->post_type == 'query' ) {
        $my_title = "Company name";
        return $my_title;
    }
    return $title;
}

add_filter( 'manage_listing_posts_columns', 'filter_cpt_columns' );

function filter_cpt_columns( $columns ) {
    unset( $columns['date'] );
    // this will add the column to the end of the array
    $columns['listing_company_verification'] = 'Verification';
    $columns['listing_company_contact'] = 'Contact';
    $columns['date'] = 'Date';
    //add more columns as needed

    // as with all filters, we need to return the passed content/variable
    return $columns;
}

add_filter( 'manage_edit-listing_sortable_columns', 'my_sortable_listing_column' );

function my_sortable_listing_column( $columns ) {
    $columns['listing_company_verification'] = 'Verification';
    $columns['listing_company_contact'] = 'Contact';

    return $columns;
}

add_filter( 'manage_query_posts_columns', 'filter_cpt_query_columns' );

function filter_cpt_query_columns( $columns ) {
    unset( $columns['date'] );
    // this will add the column to the end of the array
    $columns['fullName'] = 'Name';
    $columns['postcode'] = 'PostCode';
    $columns['date'] = 'Date';
    //add more columns as needed

    // as with all filters, we need to return the passed content/variable
    return $columns;
}
add_filter( 'manage_edit-query_sortable_columns', 'my_sortable_query_column' );

function my_sortable_query_column( $columns ) {
    $columns['fullName'] = 'Name';
    $columns['postcode'] = 'PostCode';
    return $columns;
}

add_action( 'manage_posts_custom_column', 'action_custom_columns_content', 10, 2 );

function action_custom_columns_content ( $column_id, $post_id ) {
    if ( $column_id == 'listing_company_verification' ) {
        $verification = get_post_meta( $post_id, 'listing_company_verification', true );
        if ( $verification == 'not_verified' ) echo 'Not Verified';
        elseif ( $verification == 'claimed' ) echo 'Claimed';
        elseif ( $verification == 'verified' ) echo 'Verified';
        else echo 'Verification not set yet';

    } elseif ( $column_id == 'listing_company_contact' ) {
        $phone = get_post_meta( $post_id, 'listing_company_phone', true );
        $email = get_post_meta( $post_id, 'listing_company_email', true );
        if ( $phone ) echo $phone.'<br/>';
        echo $email;

    } elseif ( $column_id == 'fullName' ) {
        $fullName = get_post_meta( $post_id, 'fullName', true );
        echo $fullName;

    } elseif ( $column_id == 'postcode' ) {
        $postcode = get_post_meta( $post_id, 'postcode', true );
        echo $postcode;

    }
}

add_action( 'template_redirect', 'mos_redirect_post' );

function mos_redirect_post() {
    if ( is_singular( 'query' ) ) {
//        wp_redirect( home_url(), 301 );
//        exit;

        global $wp_query;
        $wp_query->set_404();
        status_header( 404 );
        get_template_part( 404 ); 
        exit();
    }
}