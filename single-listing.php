<?php
/**
 * The template for displaying all pages.
 *
 * @package Betheme
 * @author Muffin group
 * @link https://muffingroup.com
 */

get_header();
?>

                        
<?php
    $logo = get_field('listing_company_logo');
    $map_url = get_field('listing_company_map_url');
    $business_hours = get_field('listing_business_hours');
    $total_solar_installation = get_field('listing_total_solar_installation');
    $verification = get_field('listing_company_verification');
    $address = get_field('listing_company_address');
    $trustscore = get_field('listing_company_trustscore'); 
?>

<div id="Content">
	<div class="content_wrapper clearfix">
		<div class="sections_group">
			<div class="entry-content" itemprop="mainContentOfPage">
               <div class="container">
                    <div class="row">
                        <?php // while (have_posts()) : the_post(); ?>
                        <div class="col-lg-8">
                                <?php if ($verification == 'verified') : ?>
                                    <div class="listing_verified_icon float-right"><i class="fa fa-check"></i></div>
                                <?php endif;?>
                                <?php 
                                //$mfn_builder = new Mfn_Builder_Front(get_the_ID());
                                //$mfn_builder->show();
                                the_content();
                                ?>
                                <?php 
                                $attr = 'POSTID='.get_the_ID();
                                echo do_shortcode('[WPCR_SHOW '.$attr.' NUM="5" PAGINATE="1" PERPAGE="5" SHOWFORM="1"]');
                                ?>
                            </div>
                        <?php // endwhile;?>
                        <div class="col-lg-4"> 
                            <div class="listing-buttons">
                            <a href="<?php echo home_url('/request-a-quote/').'?post_id='.get_the_id()?>" class="btn-block btn-request">Request a Quote</a>
                            
                            <?php if ($verification == 'not_verified') :
                                // $html .= '<a class="btn-block btn-request">Business Claim</a>';
                                $attr = ' return='.home_url('/thank-you/').'?id='.get_the_ID();
                                $attr .= ' cancel_return='.home_url('/cencle-verification/').'?id='.get_the_ID();
                                echo do_shortcode('[wp_paypal button="subscribe" name="Business Claim" a3="25.00" p3="6" t3="M" '.$attr.']');
                            endif; ?>
                            </div>  
                            <?php 
                            if ($logo) :                             
                                $url = ($logo["width"]>=350 && $logo["height"] >= 350)?aq_resize($logo["url"],350,350,true):$logo["url"];
                                list($width, $height) = getimagesize($url);
                            ?>
                                <div class="listing-logo mb-10"><img class="img-listing" src="<?php echo $url ?>" alt="<?php echo $logo["alt"]?>" width="<?php echo $width ?>" height="<?php echo $height ?>" /></div>
                            <?php endif?>
                            <?php if ($address) : ?>
                               <?php // var_dump($business_hours) ?>
                                <div class="listing-sidebar business_address mb-10">
                                    <h4 class="sidebar-title">Address</h4>
                                    <div class="address"><?php echo $address ?></div>
                                </div>
                            <?php endif?>
                            <?php if ($map_url) : ?>
                                <div class="embed-responsive embed-responsive-4by3 mb-10">
                                    <iframe class="embed-responsive-item" src="<?php echo $map_url ?>"></iframe>
                                </div>
                            <?php endif?>
                            <?php if ($business_hours) : ?>
                               <?php // var_dump($business_hours) ?>
                                <div class="listing-sidebar business_hours mb-10">
                                   <h4 class="sidebar-title">Business Hours</h4>
                                    <ul>
                                    <?php foreach($business_hours as $key => $value) : ?>
                                        <li class="d-table w-100"><div class="float-left"><?php echo ucfirst($key) ?></div><div class="float-right"><?php echo $value ?></div></li>
                                    <?php endforeach;?>
                                    </ul>
                                </div>
                            <?php endif?>
                            <?php if ($total_solar_installation) : ?>
                               <?php // var_dump($business_hours) ?>
                                <div class="listing-sidebar total_solar_installation mb-10">
                                   <strong>Total Solar Installation:</strong> <?php echo $total_solar_installation ?>
                                </div>
                            <?php endif?>
                            
                            <?php if ($trustscore) : ?>
                                <div class="listing-trustscore"><b>TrustScore:</b> <?php echo $trustscore?></div>
                            <?php endif; ?>
                        </div>
                    </div>                   
               </div>
				<div class="section section-page-footer">
					<div class="section_wrapper clearfix">

						<div class="column one page-pager">
							<?php
								wp_link_pages(array(
									'before' => '<div class="pager-single">',
									'after' => '</div>',
									'link_before' => '<span>',
									'link_after' => '</span>',
									'next_or_number' => 'number'
								));
							?>
						</div>

					</div>
				</div>

			</div>

			<?php if (mfn_opts_get('page-comments')): ?>
				<div class="section section-page-comments">
					<div class="section_wrapper clearfix">

						<div class="column one comments">
							<?php comments_template('', true); ?>
						</div>

					</div>
				</div>
			<?php endif; ?>

		</div>

		<?php get_sidebar(); ?>

	</div>
</div>
<div style="margin-bottom:30px"></div>
<?php get_footer();
