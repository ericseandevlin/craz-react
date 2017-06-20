<div id="df-archive-content" class="clearfix">
	<?php
		$use_layout		= 'layout-5';
		$is_thumbnail	= 'no';
		$is_secondary	= 'yes';
		$title_size		= 'h3';
		$is_excerpt		= 'yes';
		$is_meta_full	= 'yes';
		if ( 'fullwidth' == $general_page_layout ) {
			$column 		= 'col-md-12';
			$main_wraper	= 'main-wraper';
			$use_size		= array(994,661);
		} else {
			if ( 'sidebar-left' == $general_page_layout ){
				$column			= 'col-md-8 col-md-push-4';
			} else {
				$column			= 'col-md-8';
			}
			$main_wraper	= 'main-wraper-sidebar';
			$use_size		= array(788,524);
		}
		if ( $query->have_posts() ) :
	?>
			<div id="df-content-render" class="<?php echo esc_attr( $column );?> no-padding animsition df-content-sticky push-top-6 push-bottom-4 clearfix" data-pagination="<?php echo esc_attr( $pagination_type );?>" data-max-num-pagination="<?php echo esc_attr( $total );?>">
				<?php
					if ( ( '' != $post_setting[ 'list_title' ] && 'enable' == $post_setting[ 'show_listitle' ] ) ) {
				?>
						<h5 class="archive-header-title"><?php echo $post_setting['list_title'];?></h5>

				<?php
					}
					while( $query->have_posts() ) : $query->the_post();
				?>
						<div class="col-md-12 archive-wraper style-5 <?php echo esc_attr( $main_wraper );?> clearfix">
							<div class="article-img-wrap">
								<?php DF_Content::df_load_feature_image( $use_layout, $use_size, $is_thumbnail, $is_secondary );?>
							</div>
							<div class="archive-wraper style-5 inner-wraper">
								<?php
									echo DF_Content_View::df_load_category();
									DF_Content::df_load_title_and_content( $use_layout, $title_size, $is_thumbnail, $is_excerpt );
								?>
								<div class="df-separator"></div>
								<?php if ( DF_Framework::df_is_mobile() ){ ?>
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
								<?php } else {?>
									<div class="post-meta">
										<div class="post-meta-avatar">
											<?php DF_Content::df_load_avatar_author(); ?>
										</div>
										<div class="post-meta-desc with-avatar">
											<?php DF_Content::df_load_author_and_date();?>
											<span class="pull-right">
												<?php DF_Content::df_load_comment_and_share( $is_meta_full );?>
											</span>
										</div>
									</div>
								<?php } ?>
							</div>
						</div>
				<?php
					endwhile;
					wp_reset_query();
					wp_reset_postdata();
				?>
				<?php if ( 'normal-pagination' == $pagination_type ) {?> 
						<div class="col-md-12 push-top-3 push-bottom-3">
						<?php echo $pagination;?>
						</div>
				<?php }?>
			</div>
	<?php
		endif;

		$disable_sidebar_mobile = ( $is_disable_sidebar_mobile == 'yes' ) ? 'hidden-xs' : '';
		if ( 'sidebar-left' == $general_page_layout ) {
			$sidebar_push = 'col-md-pull-8';
	?>
			<div class="col-md-4 col-xs-12 col-sm-12 <?php echo esc_attr( $sidebar_push );?> <?php echo esc_attr( $disable_sidebar_mobile );?> sidebar push-top-6">
				<?php DF_Content::df_get_sidebar();?>
			</div>
	<?php
		} else if ( 'sidebar-right' == $general_page_layout ) {
			$sidebar_push = '';
	?>
			<div class="col-md-4 col-xs-12 col-sm-12 <?php echo esc_attr( $sidebar_push );?> <?php echo esc_attr( $disable_sidebar_mobile );?> sidebar push-top-6">
				<?php DF_Content::df_get_sidebar();?>
			</div>
	<?php
		}
	?>
</div>
<?php DF_Content::df_load_back_top() ?>
