<?php
defined( 'ABSPATH' ) || exit;

$products   = get_field( 'catalog_items' ) ?: array();
$quantities = get_field( 'quiz_quantities' ) ?: array();
$prints     = get_field( 'quiz_prints' ) ?: array();
$steps      = array(
	get_field( 'quiz_q_products' ),
	get_field( 'quiz_q_quantities' ),
	get_field( 'quiz_q_prints' ),
	get_field( 'quiz_q_contact' ),
);
$channels   = array(
	'tg'   => array( 'label' => 'Telegram', 'field' => 'Телефон или @ник в Telegram', 'placeholder' => '@company или +7…' ),
	'wa'   => array( 'label' => 'WhatsApp', 'field' => 'Номер WhatsApp', 'placeholder' => '+7 (___) ___-__-__' ),
	'mail' => array( 'label' => 'Почта', 'field' => 'Email', 'placeholder' => 'name@company.ru' ),
);
?>
<div class="quiz stacked" id="quiz" data-quiz data-endpoint="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>">
	<div class="quiz__trap" aria-hidden="true">
		<label>Не заполняйте <input type="text" name="website" tabindex="-1" autocomplete="off" data-quiz-trap></label>
	</div>
	<div class="quiz__card">
		<?php foreach ( array_slice( $steps, 0, 3 ) as $index => $title ) : ?>
			<section class="quiz__step" data-quiz-step="<?php echo esc_attr( $index ); ?>"<?php echo 0 === $index ? '' : ' hidden'; ?>>
				<div class="quiz__head">
					<h2 class="quiz__title"><?php echo esc_html( $title ); ?></h2>
					<span class="quiz__counter">Шаг <?php echo esc_html( $index + 1 ); ?> из 4</span>
				</div>
				<div class="quiz__progress" aria-hidden="true">
					<?php for ( $bar = 0; $bar < 4; $bar++ ) : ?>
						<span class="quiz__bar" data-quiz-bar="<?php echo esc_attr( $bar ); ?>"></span>
					<?php endfor; ?>
				</div>

				<?php if ( 0 === $index ) : ?>
					<div class="quiz__options quiz__options--products" role="group" aria-label="<?php echo esc_attr( $title ); ?>">
						<?php foreach ( $products as $product_index => $product ) : ?>
							<button class="quiz__tile" type="button" aria-pressed="false" data-quiz-option="product" data-value="<?php echo esc_attr( $product_index ); ?>" data-label="<?php echo esc_attr( $product['quiz_name'] ?: $product['title'] ); ?>">
								<?php echo wp_get_attachment_image( $product['image'], 'full', false, array( 'class' => 'quiz__tile-img', 'alt' => '', 'loading' => 'lazy' ) ); ?>
								<span class="quiz__tile-title"><?php echo esc_html( $product['quiz_label'] ?: $product['title'] ); ?></span>
							</button>
						<?php endforeach; ?>
					</div>
				<?php else : ?>
					<div class="quiz__options" role="group" aria-label="<?php echo esc_attr( $title ); ?>">
						<?php
						$choices = 1 === $index ? $quantities : $prints;
						$kind    = 1 === $index ? 'quantity' : 'print';
						foreach ( $choices as $choice_index => $choice ) :
							?>
							<button class="quiz__choice" type="button" aria-pressed="false" data-quiz-option="<?php echo esc_attr( $kind ); ?>" data-value="<?php echo esc_attr( $choice_index ); ?>" data-label="<?php echo esc_attr( $choice['title'] ); ?>">
								<span class="quiz__choice-title"><?php echo esc_html( $choice['title'] ); ?></span>
								<span class="quiz__choice-note"><?php echo esc_html( $choice['note'] ); ?></span>
							</button>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<div class="quiz__nav">
					<button class="quiz__back" type="button" data-quiz-back<?php echo 0 === $index ? ' disabled' : ''; ?>>← Назад</button>
					<button class="quiz__next" type="button" data-quiz-next disabled>Далее →</button>
				</div>
			</section>
		<?php endforeach; ?>

		<section class="quiz__step" data-quiz-step="3" hidden>
			<div class="quiz__head">
				<h2 class="quiz__title"><?php echo esc_html( $steps[3] ); ?></h2>
				<span class="quiz__counter">Шаг 4 из 4</span>
			</div>
			<div class="quiz__progress" aria-hidden="true">
				<?php for ( $bar = 0; $bar < 4; $bar++ ) : ?>
					<span class="quiz__bar" data-quiz-bar="<?php echo esc_attr( $bar ); ?>"></span>
				<?php endfor; ?>
			</div>

			<div class="quiz__summary">
				<p class="quiz__summary-title">Ваш запрос</p>
				<dl class="quiz__summary-list">
					<div class="quiz__summary-row">
						<dt>Продукт</dt>
						<dd data-quiz-summary="product">—</dd>
					</div>
					<div class="quiz__summary-row">
						<dt>Тираж</dt>
						<dd data-quiz-summary="quantity">—</dd>
					</div>
					<div class="quiz__summary-row">
						<dt>Печать</dt>
						<dd data-quiz-summary="print">—</dd>
					</div>
				</dl>
				<p class="quiz__promo"><?php echo theme_icon( 'icon-gift' ); ?><span><?php echo esc_html( get_field( 'quiz_promo' ) ); ?></span></p>
			</div>

			<div class="quiz__contact">
				<div class="quiz__channels" role="group" aria-label="Способ связи">
					<?php foreach ( $channels as $key => $channel ) : ?>
						<button class="quiz__channel" type="button" aria-pressed="<?php echo 'tg' === $key ? 'true' : 'false'; ?>" data-quiz-channel="<?php echo esc_attr( $key ); ?>" data-field-label="<?php echo esc_attr( $channel['field'] ); ?>" data-field-placeholder="<?php echo esc_attr( $channel['placeholder'] ); ?>">
							<?php echo esc_html( $channel['label'] ); ?>
						</button>
					<?php endforeach; ?>
				</div>

				<div class="field">
					<label class="field__label" for="quiz-contact" data-quiz-field-label><?php echo esc_html( $channels['tg']['field'] ); ?></label>
					<input class="field__input" id="quiz-contact" name="contact" type="text" placeholder="<?php echo esc_attr( $channels['tg']['placeholder'] ); ?>" data-quiz-contact>
				</div>

				<?php
				get_template_part(
					'template-parts/components/consent',
					null,
					array(
						'name'    => 'quiz_consent',
						'purpose' => 'и на получение расчёта в выбранном канале',
						'class'   => 'quiz__consent',
					)
				);
				?>
			</div>

			<div class="quiz__nav">
				<button class="quiz__back" type="button" data-quiz-back>← Назад</button>
				<button class="quiz__next" type="button" data-quiz-next disabled>Получить расчёт и скидку</button>
			</div>
			<p class="quiz__error" role="alert" data-quiz-error hidden></p>
		</section>

		<section class="quiz__step quiz__done" data-quiz-step="4" hidden aria-live="polite">
			<span class="quiz__done-icon"><?php echo theme_icon( 'icon-tick' ); ?></span>
			<h2 class="quiz__done-title"><?php echo esc_html( get_field( 'quiz_done_title' ) ); ?></h2>
			<p class="quiz__done-text"><?php echo esc_html( get_field( 'quiz_done_text' ) ); ?></p>
			<button class="quiz__restart" type="button" data-quiz-restart>Рассчитать другой продукт</button>
		</section>
	</div>
</div>
