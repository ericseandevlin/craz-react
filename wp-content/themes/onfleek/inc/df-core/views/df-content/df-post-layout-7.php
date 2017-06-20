<?php
	extract( DF_Content::$single_params );
/**
 * 	set layout option from theme option: full, framed, boxed
 *	set bg fixed, scroll 
 */
	$header = DF_Header::df_get_header_layout();
	$show_bg = '';
	if ( $header != 'boxed' ) {
		$show_bg = $bg_type;
	}
	if ( $header == 'boxed' && $layout_type == 'df-content-boxed' ) {
		$show_bg = $bg_type;
	}
?>
<div id="df-content-wrapper" class="lazy-wrapper df-content-wrapper <?php echo isset( $layout_type ) ? $layout_type: '';?> <?php echo isset( $show_bg ) ? $show_bg: '';?>">
	<div class="<?php echo ( $layout_type != 'df-content-full' ) ? 'boxed no-padding': '';?>">
		<div id="df-wrapper-ads-top">
			<div class="df-wrapper-inner">
				<div class="df-single-post-ads df-single-post-ads-top">
					<?php echo DF_Content::df_get_ads_before_content();?>
				</div>
			</div>
		</div>
	</div>
	<div class="<?php echo ( $layout_type != 'df-content-full' ) ? 'boxed no-padding': 'container-fluid';?> embed-wrap">
		<div class="container layout7">
			<div class="col-md-12 df-embed">
				<div class="embed-responsive embed-responsive-16by9">
					<?php
						switch ( $post_format ) {
							case 'video':
                            if ('' != $meta_content_post_format) {
							     echo wp_oembed_get( $meta_content_post_format );
                            }
							break;
							case 'audio':
                            if ('' != $meta_content_post_format) {
							     echo wp_oembed_get( $meta_content_post_format );
                            }
							break;
							default:
								// standard post format
								$thumbs = '';
								if ( has_post_thumbnail( get_the_ID() ) ) {
									$thumbs 	= get_the_post_thumbnail( get_the_ID(), $size = '', array( 'class' => 'img-responsive center-block' ) );
								} else {
									$image_id	= DF_Framework::$default_featured_img_id;
									$thumbs 	= wp_get_attachment_image( $image_id, $size='full' , false, array( 'class' => 'img-responsive center-block' ) );
								}
					?>
					<div class="df-post-media feature-image-wrapper">
						<?php echo $thumbs;?>
					</div>
					<?php
							break;
						}
					?>
				</div>
			</div>
			<div class="clearfix"></div>
			<?php while ( have_posts() ) : the_post();?>
				<div class="df-post-header layout7">
					<div class="df-breadcrumb-container">
						<?php if ( $is_breadcrumbs != 'no' ) { DF_Megamenu::df_get_breadcrumb(); }?>
					</div>
					<header>
						<?php
							if ( $is_show_categories_tag == 'yes' ) {
								echo DF_Content_View::df_load_category();
							}
						?>
						<h1 class="entry-title"><?php the_title();?></h1>
						<span class="df-subtitle"><?php if ( $meta_post_subtitle != '' ) { echo $meta_post_subtitle;}?></span>
						<?php if( DF_Framework::df_is_mobile() ) {?>
						<div class="post-meta with-margin-top single-mobile clearfix">
		                   	<div class="post-meta-avatar clearfix">
								<a href="#">
									<?php echo get_avatar( get_the_author_meta( 'ID' ), 32, $default = '' , $alt ='' , array( 'class' => 'img-responsive img-circle')  ); ?>
								</a>
	                    	</div>
		                    <div class="post-meta-desc with-avatar">
	                      		<div class="post-meta-desc-top">
	                        		<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" class="author-name">
	                          			<?php echo get_the_author();?>
	                        		</a>
		                        
			                        <?php
			                          $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
			                          $time_string = sprintf( $time_string,
			                            esc_attr( get_the_date( 'c' ) ),
			                            get_the_date()
			                          );
			                          $posted_on = sprintf(
			                            esc_html_x( '%s', 'post date', 'onfleek' ),
			                            '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
			                          );

			                          echo $posted_on;
			                        ?>
		                      	</div>
		                      	<div class="post-meta-desc-btm">
			                        <?php if( comments_open() ){?>
			                        <a href="#"><i class="ion-chatbubble"></i> <?php echo get_comments_number();?> comments</a><a href="#">
			                        <?php } 
									if ( class_exists( "DF_Social_Media" ) ) {
									?>
									<i class="ion-share"></i> <?php echo DF_Social_Media::df_get_all_social_media_counter( get_permalink() ) . " " . __( 'Shares', 'onfleek' ) ?></a>
									<?php } ?>
		                      	</div>
		                    </div>
	                  	</div>
						<?php } else {?>
						<div class="post-meta background">
							<ul class="list-inline">
							<?php	if ( $is_show_author_name == 'yes' ) {?>
								<li>
									<?php echo get_avatar( get_the_author_meta( 'ID' ), 64, $default = '' , $alt ='' , array( 'class' => ' img-circle')  ); ?>
								</li>
								<li>
									<div class="authors-meta">
										<div class="vcard">
											<span>by</span>
											<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php echo get_the_author();?></a>
							<?php	}
									if ( $is_show_date == 'yes') { 
							?>
											<span><?php echo the_date('F j, Y');?></span>
										</div>
									</div>
								</li>
							<?php	}?>
								<li class="pull-right">
									<ul class="list-inline no-margin">
									<?php	if ( $is_show_post_views == 'yes' ) { ?>
										<li>
											<i class="ion-eye"></i><span><?php echo DF_Content::df_get_page_view( get_the_ID() ). " " . __( 'views', 'onfleek' ); ?></span>
										</li>
									<?php	}
											if ($is_show_comment_counts == 'yes' ) { 
												if ( comments_open() ) {
									?>
										<li>
											<i class="ion-chatbubble"></i><span><?php echo get_comments_number();?></span>
										</li>
									<?php
											}
										}
									?>
									</ul>
								</li>
							</ul>
						</div>
						<?php } ?>
					</header>
				</div>
			<div class="push-top-2 white">
				<?php DF_Content::df_get_social_share('top');?>
			</div>
			<?php endwhile;?>
		</div>
	</div>
	<div class="boxed no-padding">
		<div class="container df-bg-content">
			<div class="row">
				<div class="content-single-wrap clearfix">
				<?php
					if ( 'fullwidth' == $meta_post_layout ) {
						$column 		= 'col-md-12';
					} else {
						if ( 'sidebar-left' == $meta_post_layout ){
							$column			= 'col-md-8 col-md-push-4';
						} else {
							$column			= 'col-md-8';
						}
					}
					while ( have_posts() ) : the_post();
				?>
					<div class="df-single-post df-content-sticky push-top-4 <?php echo esc_attr( $column );?>">
						<?php
							$thumbs = '';
							if ( has_post_thumbnail( get_the_ID() ) ) {
								$thumbs = get_the_post_thumbnail( get_the_ID(), $size = '', array( 'class' => 'img-responsive center-block' ) );
							} else {
								$thumbs = '';
							}
						?>
						<div class="df-post-content">
							<?php DF_Content::df_get_review('top');?>
							<article id="post-<?php the_ID();?>" <?php post_class();?>>
								<div class="entry-content">
							<?php
								the_content( sprintf(
									/* translators: %s: Name of current post. */
									wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'onfleek' ), array( 'span' => array( 'class' => array() ) ) ),
									the_title( '<span class="screen-reader-text">"', '"</span>', false )
								) );
								wp_link_pages( array(
									'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'onfleek' ),
									'after'  => '</div>',
								) );
							?>
								</div>
							</article>
							<?php	DF_Content::df_get_smart_list(); 
									DF_Content::df_get_review('bottom');
									if( $is_show_tag == 'yes') {
										echo get_the_tag_list(
											'<p class="tags">Tags:</p><ul class="list-inline tags"><li>',
											'</li><li>',
											'</li></ul>');
									}
							?>
							<div class="df-separator"></div>
							<?php DF_Content::df_get_social_share('bottom');?>
							<div class="clearfix"></div>
							<div class="df-single-post-ads df-single-post-ads-bottom">
								<?php echo DF_Content::df_get_ads_before_author();?>	
							</div>
							<?php
								if ( $is_show_author_box == 'yes' ) {
									require get_template_directory().'/inc/df-core/views/df-content/df-author-box.php';
								}
								if( $is_show_next_prev_post == 'yes' ){
									require get_template_directory() .'/inc/df-core/views/df-content/df-pagination-single.php';
								}
								
							DF_Content_View::df_comment($is_show_comment_counts);
							?>
						</div>
					</div>
				<?php
					endwhile;
					wp_reset_query();
					wp_reset_postdata();

						$disable_sidebar_mobile = ( $is_disable_sidebar_mobile == 'yes' ) ? 'hidden-xs' : '';
						if ( 'sidebar-left' == $meta_post_layout ) {
							$sidebar_push = 'col-md-pull-8';
				?>
							<div class="col-md-4 col-xs-12 col-sm-12 <?php echo esc_attr( $sidebar_push );?> <?php echo esc_attr( $disable_sidebar_mobile );?> sidebar push-top-4">
								<?php DF_Content::df_get_sidebar();?>
							</div>
				<?php
						} else if ( 'sidebar-right' == $meta_post_layout ) {
							$sidebar_push = '';
				?>
							<div class="col-md-4 col-xs-12 col-sm-12 <?php echo esc_attr( $sidebar_push );?> <?php echo esc_attr( $disable_sidebar_mobile );?> sidebar push-top-4">
								<?php DF_Content::df_get_sidebar();?>
							</div>
				<?php
						}
				?>
				</div>

				<?php
					echo DF_Content::df_get_ads_before_you_may_also_like(); 
					require get_template_directory(). '/inc/df-core/views/df-content/related-post.php';
				?>
			</div>
		</div>
	</div>
</div>
<?php DF_Content::df_load_back_top() ?>
