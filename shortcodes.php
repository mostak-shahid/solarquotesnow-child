<?php
function admin_shortcodes_page(){
	//add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function = '', $icon_url = '', $position = null )
    add_menu_page( 
        __( 'Theme Short Codes', 'textdomain' ),
        'Short Codes',
        'manage_options',
        'shortcodes',
        'shortcodes_page',
        'dashicons-book-alt',
        3
    ); 
}
add_action( 'admin_menu', 'admin_shortcodes_page' );
function shortcodes_page(){
	?>
<div class="wrap">
    <h1>Theme Short Codes</h1>
    <ol>
        <li>[home-url slug=''] <span class="sdetagils">displays home url</span></li>
        <li>[site-identity class='' container_class=''] <span class="sdetagils">displays site identity according to theme option</span></li>
        <li>[site-name link='0'] <span class="sdetagils">displays site name with/without site url</span></li>
        <li>[copyright-symbol] <span class="sdetagils">displays copyright symbol</span></li>
        <li>[this-year] <span class="sdetagils">displays 4 digit current year</span></li>
        <li>[feature-image wrapper_element='div' wrapper_atts='' height='' width=''] <span class="sdetagils">displays feature image</span></li>
        <li>[listing-search-form result_page='' button_text='Search' placeholder=''] <span class="sdetagils">displays listing search form</span></li>
        <li>[listing-search-result posts_per_page='10'] <span class="sdetagils">displays listing search results</span></li>
        <li>[listing-search-verify] <span class="sdetagils">displays nothing</span></li>
    </ol>
</div>
<?php
}
function home_url_func( $atts = array(), $content = '' ) {
	$atts = shortcode_atts( array(
		'slug' => '',
	), $atts, 'home-url' );

	return home_url( $atts['slug'] );
}
add_shortcode( 'home-url', 'home_url_func' );
function site_identity_func( $atts = array(), $content = null ) {
	global $forclient_options;
	$logo_url = ($forclient_options['logo']['url']) ? $forclient_options['logo']['url'] : get_template_directory_uri(). '/images/logo.png';
	$logo_option = $forclient_options['logo-option'];
	$html = '';
	$atts = shortcode_atts( array(
		'class' => '',
		'container_class' => ''
	), $atts, 'site-identity' ); 
	
	
	$html .= '<div class="logo-wrapper '.$atts['container_class'].'">';
		if($logo_option == 'logo') :
			$html .= '<a class="logo '.$atts['class'].'" href="'.home_url().'">';
			list($width, $height) = getimagesize($logo_url);
			$html .= '<img class="img-responsive img-fluid" src="'.$logo_url.'" alt="'.get_bloginfo('name').' - Logo" width="'.$width.'" height="'.$height.'">';
			$html .= '</a>';
		else :
			$html .= '<div class="text-center '.$atts['class'].'">';
				$html .= '<h1 class="site-title"><a href="'.home_url().'">'.get_bloginfo('name').'</a></h1>';
				$html .= '<p class="site-description">'.get_bloginfo( 'description' ).'</p>';
			$html .= '</div>'; 
		endif;
	$html .= '</div>'; 
		
	return $html;
}
add_shortcode( 'site-identity', 'site_identity_func' );
function site_name_func( $atts = array(), $content = '' ) {
	$html = '';
	$atts = shortcode_atts( array(
		'link' => 0,
	), $atts, 'site-name' );
	if ($atts['link']) $html .=	'<a href="'.esc_url( home_url( '/' ) ).'">';
	$html .= get_bloginfo('name');
	if ($atts['link']) $html .=	'</a>';
	return $html;
}
add_shortcode( 'site-name', 'site_name_func' );
function copyright_symbol_func() {
	return '&copy;';
}
add_shortcode( 'copyright-symbol', 'copyright_symbol_func' );
function this_year_func() {
	return date('Y');
}
add_shortcode( 'this-year', 'this_year_func' );
function feature_image_func( $atts = array(), $content = '' ) {
	global $mosacademy_options;
	$html = '';
	$img = '';
	$atts = shortcode_atts( array(
		'wrapper_element' => 'div',
		'wrapper_atts' => '',
		'height' => '',
		'width' => '',
	), $atts, 'feature-image' );

	if (has_post_thumbnail()) $img = get_the_post_thumbnail_url();	
	elseif(@$mosacademy_options['blog-archive-default']['id']) $img = wp_get_attachment_url( $mosacademy_options['blog-archive-default']['id'] ); 
	if ($img){
		if ($atts['wrapper_element']) $html .= '<'. $atts['wrapper_element'];
		if ($atts['wrapper_atts']) $html .= ' ' . $atts['wrapper_atts'];
		if ($atts['wrapper_element']) $html .= '>';
		list($width, $height) = getimagesize($img);
		if ($atts['width'] AND $atts['height']) :
			if ($width > $atts['width'] AND $height > $atts['height']) $img_url = aq_resize($img, $atts['width'], $atts['height'], true);
			else $img_url = $img;
		elseif ($atts['width']) :
			if ($width > $atts['width']) $img_url = aq_resize($img, $atts['width']);
			else $img_url = $img;
		else : 
			$img_url = $img;
		endif;
		list($fwidth, $fheight) = getimagesize($img_url);
		$html .= '<img class="img-responsive img-fluid img-featured" src="'.$img_url.'" alt="'.get_the_title().'" width="'.$fwidth.'" height="'.$fheight.'" />';
		if ($atts['wrapper_element']) $html .= '</'. $atts['wrapper_element'] . '>';
	}
	return $html;
}
add_shortcode( 'feature-image', 'feature_image_func' );

function listing_search_form_func( $atts = array(), $content = '' ) {
    $html = '';
	$atts = shortcode_atts( array(
		'result_page' => '',
        'button_text' => 'Search',
        'placeholder' => ''
	), $atts, 'listing-search-form' );
    // $url = home_url( $atts['result_page'] );
    $html .= '<form class="listing_search_form" action="'.home_url( $atts['result_page'] ).'" method="get">';
        $html .= '<input type="text" class="listing_search_input" name="search" placeholder="'.atts['placeholder'].'" />';
        $html .= '<button type="submit" class="listing_search_button">'.$atts['button_text'].'</button>';
    $html .= '</form>';
	return $html;
}
add_shortcode( 'listing-search-form', 'listing_search_form_func' );

function listing_search_result_func( $atts = array(), $content = '' ) {
    $html = '';
	$atts = shortcode_atts( array(
		'posts_per_page' => 10,
	), $atts, 'listing-search-form' );
    
    $args  = array(
        'post_type' => 'listing',
        'posts_per_page' => $atts['posts_per_page'],
        'paged' => get_query_var('paged') ? get_query_var('paged') : 1,
        'orderby'   => 'meta_value_num',
        'meta_key'  => 'listing_company_priority',
        'order'   => 'DESC',
    );                            
    if (@$_GET['search']) {
        $args['meta_query']['relation'] = 'AND';
        $args['meta_query']['search'] = array(
            'key' => 'listing_company_address',
            'value' => $_GET['search'],
            'compare' => 'LIKE',
        );
    }
    $query = new WP_Query( $args ); 
    if ( $query->have_posts() ) :
        $html .= '<div class="listing-wrapper">';
        while ( $query->have_posts() ) : $query->the_post();
            $logo = get_field('listing_company_logo');
            $rating = (get_field('listing_company_rating'))?get_field('listing_company_rating'):0;
            $total_solar_installation = get_field('listing_total_solar_installation');
            $verification = get_field('listing_company_verification');    
            $address = get_field('listing_company_address');    
            $short_description = get_field('listing_short_description');    
            $trustscore = get_field('listing_company_trustscore');    
    
            $content = get_the_content();
            $content = apply_filters('the_content', $content);
            $content = str_replace(']]>', ']]&gt;', $content);
    
            $html .= '<div class="listing-unit">';
                $html .= '<div class="row">';
                    if ($logo):
                    $url = ($logo["width"]>=264 && $logo["height"] >= 264)?aq_resize($logo["url"],264,264,true):$logo["url"];
                    list($width, $height) = getimagesize($url);
                    $html .= '<div class="col-lg-2">';
                        $html .= '<div class="listing-img mb-10">';
                            $html .= '<a href="'.get_the_permalink().'"><img class="img-listing" src="'.$url.'" alt="'.$logo["alt"].'" width="'.$width.'" height="'.$height.'" /></a>'; 
                        $html .= '</div>'; 
                        // $html .= '<div class="listing-star"><img class="img-star" src="'.get_stylesheet_directory_uri().'/images/stars-'.$rating.'.svg"/></div>';
                        $html .= '<div class="listing-star"><img class="img-star" src="'.get_stylesheet_directory_uri().'/images/stars-'.get_avg_star_points(get_the_ID()).'.svg"/></div>';
    
                        $html .= '<div class="listing-review">'.get_review_count(get_the_ID()).' reviews</div>';
                        $html .= '<div class="listing-buttons">';
                            $html .= '<a href="'.home_url().'/request-a-quote/?post_id='.get_the_id().'" class="btn-block btn-request" data-id="'.get_the_id().'" data-title="'.get_the_title().'">Request a Quote</a>';
                            if ($verification == 'not_verified') :
                                // $html .= '<a class="btn-block btn-request">Business Claim</a>';
                                $attr = ' return='.home_url('/thank-you/').'?id='.get_the_ID();
                                $attr .= ' cancel_return='.home_url('/cencle-verification/').'?id='.get_the_ID();
                                $html .= do_shortcode('[wp_paypal button="subscribe" name="Business Claim" a3="25.00" p3="6" t3="M" '.$attr.']');
                            endif;
                        $html .= '</div>'; 
                    $html .= '</div>'; 
                    endif;
                    $html .= '<div class="col-10">';
                        $html .= '<div class="listing-text">';
                            if ($verification == 'verified') :
                                $html .= '<div class="listing_verified_icon float-right"><i class="fa fa-check"></i></div>';
                            endif;
                            $html .= '<div class="listing-title mb-10"><a href="'.get_the_permalink().'">'.get_the_title().'</a></div>'; 
                            if ($address) :
                                $html .= '<div class="listing-address mb-10"><strong>Address:</strong><br/>'.$address.'</div>';
                            endif;
                            // $html .= '<div class="listing-star"><img class="img-star" src="'.get_stylesheet_directory_uri().'/images/stars-'.$rating.'.svg"/></div>'; 
                            if ($total_solar_installation) : 
                            $html .= '<div class="listing-total-solar-installation"><b>Total Solar Installation:</b> '.$total_solar_installation.'</div>'; 
                            endif;
                            if ($trustscore) : 
                            $html .= '<div class="listing-trustscore"><b>TrustScore:</b> '.$trustscore.'</div>'; 
                            endif; 
                            if ($short_description) :
                                $html .= '<div class="listing-desc" style="height:45px">'.$short_description.'</div>'; 
                                $html .= '<div class="listing-more"><a href="#" class="btn btn-listing-expand">Read More</a></div>'; 
                                $html .= '<div class="listing-hide"><a href="'.get_the_permalink().'" class="btn btn-listing-hide" style="display:none; font-weight:700">View Company Details</a></div>'; 
                            endif;
                        $html .= '</div>';            
                    $html .= '</div>';              
                $html .= '</div>';
            $html .= '</div>';
        endwhile;
        $html .= '</div><!-- /.listing-wrapper -->';
    endif;
    wp_reset_postdata();
    
    $html .= '<div class="pagination-wrapper listing-pagination">'; 
        $html .= '<nav class="navigation pagination" role="navigation">';
            $html .= '<div class="nav-links">';

                $big = 999999999; // need an unlikely integer
                $html .= paginate_links( array(
                    'base' => str_replace( $big, '%#%', get_pagenum_link( $big ) ),
                    'format' => '?paged=%#%',
                    'current' => max( 1, get_query_var('paged') ),
                    'total' => $query->max_num_pages,
                    'prev_text'          => __('Prev'),
                    'next_text'          => __('Next')
                ) );

            $html .= '</div>';
        $html .= '</nav>';
    $html .= '</div>';
	return $html;
}
add_shortcode( 'listing-search-result', 'listing_search_result_func' );


function listing_search_verify_func( $atts = array(), $content = '' ) {
    $html = '';
    $post_id = @$_GET['id'];
    if ($post_id){
        $title = get_the_title($post_id);
        $html .= $_GET['id'];
        update_post_meta($post_id,'listing_company_verification', 'verified');
    }
	return $html;
}
add_shortcode( 'listing-search-verify', 'listing_search_verify_func' );


function listing_search_cencle_verify_func( $atts = array(), $content = '' ) {
    $html = '';
    $post_id = @$_GET['id'];
    if ($post_id){
        $title = get_the_title($post_id);
        $html .= $_GET['id'];
        update_post_meta($post_id,'listing_company_verification', 'claimed');
    }
	return $html;
}
add_shortcode( 'listing-search-cencle-verify', 'listing_search_cencle_verify_func' );

function listing_popup_form_func( $atts = array(), $content = '' ) {
    $html = '';
    $html .= '<div id="form-dialog" title="Dialog Title goes here...">This my first jQuery UI Dialog!</div>';
    
	return $html;
}
add_shortcode( 'listing-popup-form', 'listing_popup_form_func' );

function listing_form_func( $atts = array(), $content = '' ) {
    // $html = '';
    ob_start(); ?>

    <h3 class="form-title text-center"><span>Get 3 Solar Quotes</span><small>Fast. Free. No obligation to buy</small></h3>
    <form id="solar-form" action="" method="post">              
        <div class="wrap active">
            <div id="step-1" class="step step-1 current-step">
                <div class="form-group required-checkbox quotefor">            
                    <label class="control-label">What would you like quotes for?</label>
                    <div class="checkbox-inline">                
                        <label><input type="checkbox" id="quoteFor-1" name="quoteFor[]" value="Solar Power System" checked><span>Solar Power System</span></label>
                        <label><input type="checkbox" id="quoteFor-2" name="quoteFor[]" value="Battery Storage"><span>Battery Storage</span></label>
                        <label><input type="checkbox" id="quoteFor-3" name="quoteFor[]" value="Solar Hot Water"><span>Solar Hot Water</span></label>
                    </div>                
                    <div class="help-block">Please specify which quotes you are interested in</div>
                </div>
                <div class="buttons-group">
                    <button class="button button-next" type="button" data-next="2">Next</button>
                </div>
            </div>
        </div>
        <div class="wrap">
            <div id="step-2" class="step step-2">  
                <div class="form-group required-radio">
                    <label class="control-label">What is your association with this property?</label>
                    <div class="radio-inline">
                        <label><input type="radio" id="ownershipType-1" name="ownershipType" value="Owner" checked><span>Owner</span></label>
                        <label><input type="radio" id="ownershipType-2" name="ownershipType" value="Renter"><span>Renting</span></label>
                        <label><input type="radio" id="ownershipType-3" name="ownershipType" value="Building"><span>Building</span></label>
                        <label><input type="radio" id="ownershipType-4" name="ownershipType" value="Purchasing"><span>Purchasing</span></label>
                    </div>                
                    <div class="help-block">Please specify your association with this property</div>
                </div>
                <div class="buttons-group">
                    <button class="button button-back" type="button" data-back="1">Back</button>
                    <button class="button button-next" type="button" data-next="3">Next</button>
                </div>                
            </div>
        </div>
        <div class="wrap">
            <div id="step-3" class="step step-3">
                <div class="renter-data" style="display: none">
                    <div class="form-group ">
                        <label class="control-label">Property Type</label>
                        <div class="radio-inline">
                            <label><input type="radio" id="rentPropertyType" name="rentPropertyType" value="House"><span>House</span></label>
                            <label><input type="radio" id="rentPropertyType" name="rentPropertyType" value="Apartment (Strata)"><span>Apartment (Strata)</span></label>
                        </div>
                        <p class="help-block help-block-error"></p>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Do you rent your property direct from the owner?</label>
                        <div class="radio-inline">
                            <label><input type="radio" id="rentFrom" name="rentFrom" value="Yes"><span>Yes</span></label>
                            <label><input type="radio" id="rentFrom" name="rentFrom" value="No – I rent through an agency"><span>No – via agency</span></label>
                        </div>
                    </div>
                </div>
                <div class="building-data" style="display: none">
                    <div class="form-group field-solarform-buildstage has-success">
                        <label class="control-label">What stage are you at in the building process?</label>
                        <div class="radio-inline">
                            <label><input type="radio" id="buildStage" name="buildStage" value="Less than 3 mths until completion"><span>Less than 3 mths until completion</span></label>
                            <label><input type="radio" id="buildStage" name="buildStage" value="More than 3 mths to completion"><span>More than 3 mths to completion</span></label>
                        </div>
                    </div>
                </div>
                <div class="purchasing-data" style="display: none">
                    <div class="form-group">
                        <label class="control-label">What stage are you at in the purchasing process?</label>
                        <div class="radio-inline">
                            <label><input type="radio" id="purchaseStage" name="purchaseStage" value="Within 6 wks"><span>Within 6 wks</span></label>
                            <label><input type="radio" id="purchaseStage" name="purchaseStage" value="Within 6 - 12 wks"><span>Within 6 - 12 wks</span></label>
                            <label><input type="radio" id="purchaseStage" name="purchaseStage" value="More than 12 wks"><span>More than 12 wks</span></label>
                        </div>
                    </div>
                </div>
                <div class="owner-data">
                    <div class="form-group">
                        <label class="control-label">Do you have an existing solar system?</label>
                        <div class="radio-inline">
                            <label><input type="radio" id="existingSystemYesNo" name="existingSystemYesNo" value="No"><span>No</span></label>
                            <label><input type="radio" id="existingSystemYesNo" name="existingSystemYesNo" value="Yes"><span>Yes</span></label>
                        </div>
                        <div class="help-block">Please specify your existing solar system</div>
                    </div>    
                </div>    
                <div class="buttons-group">
                    <button class="button button-back" type="button" data-back="2">Back</button>
                    <button class="button button-next" type="button" data-next="4">Next</button>
                </div> 
            </div>
        </div>
        <div class="wrap">
            <div id="step-4" class="step step-4">
                <div class="form-group required-radio">
                    <label class="control-label">Roof Type</label>
                    <div class="radio-inline">
                        <label><input type="radio" id="roofType-1" name="roofType" value="Tin" checked=""><span>Tin Roof</span></label>
                        <label><input type="radio" id="roofType-2" name="roofType" value="Tile"><span>Tile Roof</span></label>
                    </div>
                </div>
                <div class="buttons-group">
                    <button class="button button-back" type="button" data-back="3">Back</button>
                    <button class="button button-next" type="button" data-next="5">Next</button>
                </div>
            </div> 
        </div>
        <div class="wrap">
            <div id="step-5" class="step step-5">
                <div class="form-group required-radio">
                    <label class="control-label">Number of Stories</label>
                    <div class="radio-inline">
                        <label><input type="radio" id="stories-1" name="stories" value="1"><span>1</span></label>
                        <label><input type="radio" id="stories-2" name="stories" value="2"><span>2</span></label>
                        <label><input type="radio" id="stories-3" name="stories" value="3+" checked><span>3+</span></label>
                    </div>
                </div>
                <div class="buttons-group">
                    <button class="button button-back" type="button" data-back="4">Back</button>
                    <button class="button button-next" type="button" data-next="6">Next</button>
                </div>
            </div>
        </div>
        <div class="wrap">
            <div id="step-6" class="step step-6"> 
                <div class="form-group required-radio">
                    <label class="control-label">What system size are you interested in?</label>
                    <div class="radio-inline">
                        <label><input type="radio" id="systemSize-1" name="systemSize" value="1.5kW"><span>1.5kW</span></label>
                        <label><input type="radio" id="systemSize-2" name="systemSize" value="2kW"><span>2kW</span></label>
                        <label><input type="radio" id="systemSize-3" name="systemSize" value="3kW"><span>3kW</span></label>
                        <label><input type="radio" id="systemSize-4" name="systemSize" value="4kW"><span>4kW</span></label>
                        <label><input type="radio" id="systemSize-5" name="systemSize" value="5kW"><span>5kW</span></label>
                        <label><input type="radio" id="systemSize-6" name="systemSize" value="6+kW"><span>6+kW</span></label>
                        <label><input type="radio" id="systemSize-7" name="systemSize" value="Not sure help me decide" checked=""><span>Help me decide</span></label>
                    </div>
                    <div class="help-block"></div>
                </div>
                <div class="buttons-group">
                    <button class="button button-back" type="button" data-back="5">Back</button>
                    <button class="button button-next" type="button" data-next="7">Next</button>
                </div>   
            </div>
        </div>
        <div class="wrap">
            <div id="step-7" class="step step-7" style="text-align: left">
                <div class="address-wrap">
                    <div class="form-group required-input">
                        <label class="control-label text-center">What is your address?</label>
                        <strong>Full Name</strong>
                        <input type="text" id="solarform-fullname" class="form-control" name="fullName" value="" placeholder="eg. Paul Smith" required>
                        <div class="help-block">Full Name cannot be blank.</div>
                    </div>
                    <div class="form-group required-input">
                        <strong>Email</strong>
                        <input type="text" id="email" class="form-control" name="email" value="" placeholder="your@emailaddress.com" required>

                        <p class="help-block">Email cannot be blank.</p>
                    </div>
                    <div class="form-group required-input">
                        <strong>Phone</strong>
                        <input type="text" id="phone" class="form-control" name="phone" value="" placeholder="Best Contact Number (incl. area code)" required>
                        <p class="help-block">Please provide a phone number</p>
                    </div>
                    <div class="form-group" id="alt-phone" style="display: none;">
                        <strong>Alt. Phone</strong>
                        <input type="text" id="mobile" class="form-control" name="mobile" value="" placeholder="Alternative Contact Number">
                    </div>
                    <div class="form-group">
                        <input type="checkbox" id="alt-phone-trigger" class="form-check-input" name="isAlternatePhone" value="1">
                        <label for="alt-phone-trigger"><b>I would like to add</b> an alternative phone number</label>
                    </div>
                    <div class="form-group required-input">
                        <strong>Address</strong>
                        <input type="text" id="address" class="form-control" name="fullAddress" placeholder="Enter your full address" required>
                        <p class="help-block">Address cannot be blank.</p>
                    </div>            
                    <div class="form-group required-input">
                        <strong>Suburb</strong>
                        <input type="text" id="suburb" class="form-control" name="suburb" value="" placeholder="Suburb" required>
                        <p class="help-block">Suburb can\'t be blank</p>
                    </div>
                    <div class="form-group required-input">
                        <strong>State</strong>
                        <input type="text" id="state" class="form-control" name="state" value="" placeholder="State" required>
                        <p class="help-block">State can\'t be blank</p>
                    </div>
                    <div class="form-group required-input">
                        <strong>Postcode</strong>
                        <input type="text" id="postcode" class="form-control" name="postcode" value="" placeholder="Postcode" required>
                        <p class="help-block">Postcode can\'t be blank</p>
                    </div>
                </div>
                <div class="buttons-group">
                    <button class="button button-back" type="button" data-back="6">Back</button>
                    <button class="button button-submit" type="submit" data-next="8">Submit</button>
                </div>
            </div>
        </div>
        <?php
        $post_id = @$_GET['post_id'];
        if($post_id) $title = get_the_title($post_id);
        else $title = 'General Query';
        ?>
        <input type="hidden" name="title" value="<?php echo $title ?>">
        <?php wp_nonce_field( 'quote_form_action', 'quote_form_field' );?>
    </form>    
    <?php
    $html = ob_get_clean();
    
	return $html;
}
add_shortcode( 'listing-form', 'listing_form_func' );