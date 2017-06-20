<?php
/**
 * @package Ion
 */
?>

<li id="post-<?php the_ID(); ?>" class="post-list-item">

 	<ion-card>

		<a class="<?php if( class_exists( 'AppTransitions' ) ) {
			echo apply_filters('appp_transition_left', $classname );
			}
			?>" href="<?php the_permalink(); ?>">

			<ion-item>
			  <h2 class="title"><?php the_title(); ?></h2>
			</ion-item>

			<ion-card-content>
			  <div class="item-text"><?php the_excerpt(); ?></div>
			</ion-card-content>

		</a>
		<ion-row>
    <ion-col>
      <button ion-button icon-left clear small>
        <ion-icon name="thumbs-up"></ion-icon>
        <div>12 Likes</div>
      </button>
    </ion-col>

    <?php if ( have_comments() ) : ?>
		<ion-col>
		<button ion-button icon-left clear small>
		<ion-icon name="text"></ion-icon>
		<div>
			<?php
			echo get_comments_number();
			?>
			</div>
			</button>
			</ion-col>
		<?php endif; ?>

    <ion-col center text-center>
      <ion-note>
        <?php appp_posted_on(); ?>
      </ion-note>
    </ion-col>
  </ion-row>
 	</ion-card>

</li><!-- #post-## -->
