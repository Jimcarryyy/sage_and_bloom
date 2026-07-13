<?php
/**
 * Team member card.
 *
 * @package starter_theme
 *
 * @var array $args Template arguments.
 */

$member = isset( $args['member'] ) ? $args['member'] : null;

if ( empty( $member ) ) {
	return;
}

$role = get_field( 'team_role', $member->ID );
$bio  = get_field( 'team_bio', $member->ID );
?>

<article class="team-card">
	<div class="team-card__inner">
		<div class="team-card__photo-wrap">
			<?php if ( has_post_thumbnail( $member->ID ) ) : ?>
				<?php
				echo get_the_post_thumbnail(
					$member->ID,
					'medium_large',
					array(
						'class' => 'team-card__photo',
						'alt'   => esc_attr( $member->post_title ),
					)
				);
				?>
			<?php else : ?>
				<div class="team-card__photo team-card__photo--placeholder" aria-hidden="true">
					<?php echo esc_html( mb_substr( $member->post_title, 0, 1 ) ); ?>
				</div>
			<?php endif; ?>
		</div>
		<div class="team-card__body">
			<h3 class="team-card__name"><?php echo esc_html( $member->post_title ); ?></h3>
			<?php if ( $role ) : ?>
				<p class="team-card__role"><?php echo esc_html( $role ); ?></p>
			<?php endif; ?>
			<?php if ( $bio ) : ?>
				<p class="team-card__bio"><?php echo esc_html( $bio ); ?></p>
			<?php endif; ?>
		</div>
	</div>
</article>
