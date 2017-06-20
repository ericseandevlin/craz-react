<?php
	extract( DF_Content::$search_params );
	$header = DF_Header::df_get_header_layout();
	$show_bg = '';
	if ( 'boxed' != $header ) {
		$show_bg = $bg_type;
	}

	$disable_sidebar_mobile = ( $is_disable_sidebar_mobile == 'yes' ) ? 'hidden-xs' : '';
?>
<div id="df-content-wrapper" class="lazy-wrapper <?php echo ( isset( $layout_type ) ) ? esc_attr( $layout_type ) : '' ;?> <?php echo ( isset( $show_bg ) ) ? esc_attr( $show_bg ) : '' ;?>" data-pagination="<?php echo esc_attr( $pagination )?>">
	<?php
		if ( have_posts() ) {
	?>
		<div id="df-content-header">
			<div class="boxed no-padding">
				<div class="container">
					<div class="row">
						<div class="df-category-header push-top-6">
							<?php
								if ( 'no' != $is_breadcrumbs ) {
									DF_Megamenu::df_get_breadcrumb();
								}
							?>
							<h1 class="entry-title"><?php echo esc_html__( 'Search Results for: ', 'onfleek' ), '<span>' . get_search_query() . '</span>';?></h1>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="df-archive-content" data-pagination="<?php echo esc_attr( $pagination )?>">
			<div class="boxed no-padding">
				<div class="container">
					<div class="row">
						<?php
							$use_layout		= 'layout-4';
							$use_size		= 'df_size_376xauto';
							$is_thumbnail	= 'no';
							$is_secondary	= 'yes';
							$title_size		= 'h4';
							$is_excerpt		= 'yes';
							$is_meta_full	= 'yes';
							if ( 'fullwidth' == $search_layout ) {
								$column 	= 'col-md-12';
								$column_md	= 'col-lg-4 col-md-4';
							} else {
								if ( 'sidebar-left' == $search_layout ){
									$column	= 'col-md-8 col-md-push-4';
								} else {
									$column	= 'col-md-8';
								}
								$column_md	= 'col-lg-6 col-md-6';
							}
						?>
							<div class="clearfix"></div>
							<div id="df-content-render" class="<?php echo esc_attr( $column );?> no-padding animsition df-content-sticky push-bottom-3 clearfix" data-id="<?php echo esc_attr( get_query_var( 'paged' ) );?>">
								<div class="masonry-grid">
								<?php
									while( have_posts() ) : the_post();
								?>	
								<div class="<?php echo esc_attr( $column_md );?> col-sm-6 col-xs-12 archive-wraper grid-item">
									<div class="article-img-wrap">
										<?php DF_Content::df_load_feature_image( $use_layout, $use_size, $is_thumbnail, $is_secondary ); ?>
									</div>
									<?php
										echo DF_Content_View::df_load_category();
										DF_Content::df_load_title_and_content( $use_layout, $title_size, $is_thumbnail, $is_excerpt );
									?>
									<div class="df-separator"></div>
									<div class="post-meta with-margin-top">
										<div class="post-meta-avatar">
											<?php DF_Content::df_load_avatar_author(); ?>
										</div>
										<div class="post-meta-desc with-avatar">
											<div class="post-meta-desc-top">
												<?php DF_Content::df_load_author_and_date();?>
											</div>
											<div class="post-meta-desc-btm">
												<?php DF_Content::df_load_comment_and_share( $is_meta_full );?>
											</div>
										</div>
									</div>
								</div>
								<?php
									endwhile;
								?>
								</div>
							</div>
							<?php
								if ( 'sidebar-left' == $search_layout ) {
									$sidebar_push = 'col-md-pull-8';
							?>
									<div class="col-md-4 col-xs-12 col-sm-12 <?php echo esc_attr( $sidebar_push );?> <?php echo esc_attr( $disable_sidebar_mobile );?> sidebar">
										<?php DF_Content::df_load_sidebar( $sidebar_widget );?>
									</div>
							<?php
								} else if ( 'sidebar-right' == $search_layout ) {
									$sidebar_push = '';
							?>
									<div class="col-md-4 col-xs-12 col-sm-12 <?php echo esc_attr( $sidebar_push );?> <?php echo esc_attr( $disable_sidebar_mobile );?> sidebar">
										<?php DF_Content::df_load_sidebar( $sidebar_widget );?>
									</div>
							<?php
								}
							?>
					</div>
				</div>
			</div>
		</div>
	<?php
		} else {
	?>
		<div id="df-content-header">
			<div class="boxed no-padding">
				<div class="container">
					<div class="df-category-header push-top-6">
						<?php
							if ( 'no' != $is_breadcrumbs ) {
								DF_Megamenu::df_get_breadcrumb();
							}
						?>
						<h1 class="entry-title"><?php echo esc_html__( 'Search Results for: ', 'onfleek' ), '<span>' . get_search_query() . '</span>';?></h1>
					</div>
				</div>
			</div>
		</div>
		<div id="df-archive-content" data-pagination="<?php echo esc_attr( $pagination )?>">
			<div class="boxed no-padding">
				<div class="container">
					<div class="row">
						<?php
							if ( 'fullwidth' == $search_layout ) {
								$column 	= 'col-md-12';
								$column_md	= 'col-lg-4 col-md-4';
							} else {
								if ( 'sidebar-left' == $search_layout ){
									$column	= 'col-md-8 col-md-push-4';
								} else {
									$column	= 'col-md-8';
								}
								$column_md	= 'col-lg-6 col-md-6';
							}
						?>
							<div id="df-content-render" class="<?php echo esc_attr( $column );?> animsition df-content-sticky push-bottom-3" data-id="<?php echo esc_attr( get_query_var( 'paged' ) );?>">
								<h3>Page or resource not found</h3>		
							</div>
							<?php
								if ( 'sidebar-left' == $search_layout ) {
									$sidebar_push = 'col-md-pull-8';
							?>
									<div class="col-md-4 col-xs-12 col-sm-12 <?php echo esc_attr( $sidebar_push );?> <?php echo esc_attr( $disable_sidebar_mobile );?> sidebar">
										<?php DF_Content::df_load_sidebar( $sidebar_widget );?>
									</div>
							<?php
								} else if ( 'sidebar-right' == $search_layout ) {
									$sidebar_push = '';
							?>
									<div class="col-md-4 col-xs-12 col-sm-12 <?php echo esc_attr( $sidebar_push );?> <?php echo esc_attr( $disable_sidebar_mobile );?> sidebar">
										<?php DF_Content::df_load_sidebar( $sidebar_widget );?>
									</div>
							<?php
								}
							?>
					</div>
				</div>
				<?php if ( 'normal-pagination' !== $pagination ) {?>
				<div class="infinite-pre-loader">
					<div class="item-loader-container">
						<div class="la-ball-pulse">
							<div></div>
							<div></div>
							<div></div>
						</div>
					</div>
				</div>
				<?php }?>
			</div>
		</div>
	<?php } ?>
</div>
