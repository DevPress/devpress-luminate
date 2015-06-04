<?php
/**
 * Template Name: Post Showcase
 *
 * Displays a showcase layout of posts.
 *
 * @package Luminate
 */

get_header(); ?>

	<div id="primary" class="content-area clearfix">

		<main id="main" class="site-main" role="main">

			<?php

			$args = array(
				'posts_per_page' => 5,
				'post_type' => 'post'
			);

			// If a showcase tag has been selected in the customizer, let's use it
			if ( 'luminate-all-posts' != get_theme_mod( 'showcase-tag' ) ) {
				$args['tag'] = get_theme_mod( 'showcase-tag' );
			}

			$query = new WP_Query( $args );

			if ( $query->have_posts() ) :
				$count = 1;
				while ( $query->have_posts() ) : $query->the_post();

					// Set default image sizes to use
					$thumbnail = 'luminate-showcase-crop';
					$width = 880;
					$height = 660;

					// If it's the first post, use large image size
					if ( 1 == $count ) {
						$thumbnail = 'luminate-showcase-feature';
						$width = 1050;
						$height = 525;
					}

					// If no image is set, we'll use a fallback image
					if ( has_post_thumbnail() ) {
						$image = wp_get_attachment_image_src( get_post_thumbnail_id(), $thumbnail, true )[0];
						$class = "image-thumbnail";
					} else {
						$format = get_post_format();
						$image = get_template_directory_uri() . '/images/post.svg';
						$formats = array( 'audio', 'gallery', 'image', 'link', 'video' );
						if ( in_array( $format, $formats ) ) {
							$image = get_template_directory_uri() . '/images/' . $format . '.svg';
						}
						$class = 'fallback-thumbnail';
					}

					if ( post_password_required() ) {
						$image = get_template_directory_uri() . '/images/lock.svg';
						$class = 'fallback-thumbnail';
					}
					?>

					<article id="post-<?php the_ID(); ?>" <?php post_class( 'module featured-' . $count ); ?>>

						<a href="<?php the_permalink(); ?>" class="entry-image-link">
							<figure class="entry-image <?php echo $class; ?>">
								<img src="<?php echo esc_url( $image ); ?>" height="<?php echo $height; ?>" width="<?php echo $width; ?>">
							</figure>
						</a>

						<header class="entry-header">
							<?php the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>
							<?php if ( 'post' == get_post_type() ) : ?>
								<div class="entry-meta entry-header-meta">
									<?php luminate_posted_on(); ?>
								</div><!-- .entry-meta -->
							<?php endif; ?>
						</header><!-- .entry-header -->

						<div class="entry-content clearfix">
							<?php
							if ( has_excerpt() ) :
								the_excerpt();
							elseif ( @strpos( $post->post_content, '<!--more-->') ) :
								the_content();
							elseif ( str_word_count( $post->post_content ) < 100 ) :
								the_content();
							else:
								the_excerpt();
							endif;
							?>

							<p class="read-more">
								<a href="<?php the_permalink(); ?>">
									<?php _e( 'Read More', 'luminate' ); ?>
								</a>
							</p>

						</div><!-- .entry-content -->

					</article><!-- #post-## -->

				<?php
				$count++;
				endwhile;
			else :
				if ( current_user_can( 'edit_posts' ) ) { ?>
					<div class="admin-msg">
						<p><?php _e( 'Sorry, there are no posts available to display.', 'luminate' ); ?></p>
						<p><?php _e( 'Make featured images are set and there are enough posts published for the selected tag.', 'luminate' ); ?></p>
					</div>
				<?php }
			endif;
			?>

			<?php wp_reset_query(); ?>

		</main>

	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
