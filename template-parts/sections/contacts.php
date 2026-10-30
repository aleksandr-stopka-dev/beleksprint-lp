<?php
defined( 'ABSPATH' ) || exit;

$phone     = theme_opt( 'phone' );
$email     = theme_opt( 'email' );
$address   = theme_opt( 'address' );
$messengers = array(
	'viber'    => array( 'Viber', 'viber.svg' ),
	'whatsapp' => array( 'WhatsApp', 'whatsapp.svg' ),
	'telegram' => array( 'Telegram', 'tg.svg' ),
);
$tabs = array(
	'question' => 'Вопрос технологу',
	'call'     => 'Заказать звонок',
);
?>
<section class="section-padding contacts" id="contacts" aria-labelledby="contacts-title">
	<div class="container contacts__inner">
		<div class="contacts__info">
			<?php get_template_part( 'template-parts/components/eyebrow', null, array( 'text' => get_field( 'contacts_eyebrow' ) ) ); ?>
			<h2 class="text-h2" id="contacts-title"><?php echo theme_accent_text( get_field( 'contacts_title' ) ); ?></h2>
			<p class="contacts__text"><?php echo esc_html( get_field( 'contacts_text' ) ); ?></p>

			<div class="contacts__card">
				<?php if ( $phone ) : ?>
					<a class="contacts__phone" href="<?php echo esc_attr( 'tel:' . preg_replace( '/[^\d+]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a>
				<?php endif; ?>
				<?php if ( $email ) : ?>
					<a class="contacts__email" href="<?php echo esc_attr( 'mailto:' . $email ); ?>"><?php echo esc_html( $email ); ?></a>
				<?php endif; ?>
				<?php if ( $address ) : ?>
					<span class="contacts__address">Производство и офис: <?php echo esc_html( $address ); ?></span>
				<?php endif; ?>
				<div class="contacts__messengers">
					<?php foreach ( $messengers as $field => $messenger ) : ?>
						<?php $url = theme_opt( $field ); ?>
						<?php if ( $url ) : ?>
							<a class="contacts__messenger" href="<?php echo esc_url( $url, array( 'http', 'https', 'viber', 'whatsapp' ) ); ?>" aria-label="<?php echo esc_attr( $messenger[0] ); ?>" target="_blank" rel="noopener noreferrer">
								<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/brand/' . $messenger[1] ); ?>" alt="" width="44" height="44" loading="lazy" decoding="async">
							</a>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
			</div>
		</div>

		<form class="form contacts__form" data-form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="post" novalidate>
			<input type="hidden" name="action" value="theme_form">
			<input type="hidden" name="form_id" value="contacts">
			<input type="hidden" name="type" value="question">
			<input type="hidden" name="started" value="">
			<div class="form__trap" aria-hidden="true">
				<label>Не заполняйте <input type="text" name="website" tabindex="-1" autocomplete="off"></label>
			</div>

			<div class="form__tabs" role="group" aria-label="Тип обращения">
				<?php
				$first = true;
				foreach ( $tabs as $key => $label ) :
					?>
					<button class="form__tab" type="button" aria-pressed="<?php echo $first ? 'true' : 'false'; ?>" data-form-type="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></button>
					<?php
					$first = false;
				endforeach;
				?>
			</div>

			<div class="field field--dark">
				<label class="field__label" for="contacts-name">Имя</label>
				<input class="field__input" id="contacts-name" name="name" type="text" autocomplete="name" required>
				<p class="field__error" id="contacts-name-error" hidden></p>
			</div>

			<div class="field field--dark">
				<label class="field__label" for="contacts-phone">Телефон</label>
				<input class="field__input" id="contacts-phone" name="phone" type="tel" autocomplete="tel" inputmode="tel" required>
				<p class="field__error" id="contacts-phone-error" hidden></p>
			</div>

			<div class="form__collapse" data-form-question>
				<div class="form__collapse-inner">
					<div class="field field--dark">
						<label class="field__label" for="contacts-question">Вопрос технологу</label>
						<textarea class="field__input field__input--area" id="contacts-question" name="question" rows="3" placeholder="<?php echo esc_attr( get_field( 'contacts_question_placeholder' ) ); ?>"></textarea>
					</div>
				</div>
			</div>

			<?php get_template_part( 'template-parts/components/consent', null, array( 'name' => 'consent', 'purpose' => 'и на связь по указанному номеру', 'dark' => true, 'class' => 'form__consent' ) ); ?>

			<button class="btn btn--primary form__submit" type="submit" data-form-submit>Отправить вопрос</button>
			<p class="form__error" data-form-error hidden>Поставьте галочку согласия, чтобы отправить заявку.</p>
			<p class="form__status" role="status" aria-live="polite" data-form-status hidden></p>
		</form>
	</div>
</section>
