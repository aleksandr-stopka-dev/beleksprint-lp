<?php
defined( 'ABSPATH' ) || exit;

function theme_forms_config() {
	return array(
		'contacts' => array(
			'title'  => 'Заявка с формы контактов',
			'fields' => array(
				'type'     => array( 'label' => 'Тип обращения', 'type' => 'choice', 'choices' => array( 'question' => 'Вопрос технологу', 'call' => 'Заказать звонок' ) ),
				'name'     => array( 'label' => 'Имя', 'type' => 'text', 'required' => true ),
				'phone'    => array( 'label' => 'Телефон', 'type' => 'tel', 'required' => true ),
				'question' => array( 'label' => 'Вопрос', 'type' => 'textarea' ),
				'consent'  => array( 'label' => 'Согласие на обработку данных', 'type' => 'consent', 'required' => true ),
			),
		),
		'quiz'     => array(
			'title'  => 'Заявка с квиза',
			'fields' => array(
				'product'  => array( 'label' => 'Продукт', 'type' => 'text', 'required' => true ),
				'quantity' => array( 'label' => 'Тираж', 'type' => 'text', 'required' => true ),
				'print'    => array( 'label' => 'Печать', 'type' => 'text', 'required' => true ),
				'channel'  => array( 'label' => 'Канал связи', 'type' => 'choice', 'choices' => array( 'tg' => 'Telegram', 'wa' => 'WhatsApp', 'mail' => 'Почта' ), 'required' => true ),
				'contact'  => array( 'label' => 'Контакт', 'type' => 'text', 'required' => true ),
				'consent'  => array( 'label' => 'Согласие на обработку данных', 'type' => 'consent', 'required' => true ),
			),
		),
	);
}

function theme_form_clean( $name, $field, $raw ) {
	$raw = is_string( $raw ) ? $raw : '';

	switch ( $field['type'] ) {
		case 'textarea':
			return mb_substr( sanitize_textarea_field( $raw ), 0, 2000 );
		case 'choice':
			$value = sanitize_key( $raw );
			return isset( $field['choices'][ $value ] ) ? $value : '';
		case 'consent':
			return '1' === $raw ? '1' : '';
		default:
			return mb_substr( sanitize_text_field( $raw ), 0, 200 );
	}
}

function theme_form_error( $message, $status ) {
	wp_send_json_error( array( 'message' => $message ), $status );
}

function theme_handle_form() {
	$forms   = theme_forms_config();
	$form_id = isset( $_POST['form_id'] ) ? sanitize_key( wp_unslash( $_POST['form_id'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing

	if ( ! isset( $forms[ $form_id ] ) ) {
		theme_form_error( 'Неизвестная форма.', 400 );
	}

	$success = 'Заявка принята — свяжемся в рабочее время.';

	// phpcs:disable WordPress.Security.NonceVerification.Missing
	if ( ! empty( $_POST['website'] ) ) {
		wp_send_json_success( array( 'message' => $success ) );
	}

	$started = isset( $_POST['started'] ) ? absint( $_POST['started'] ) : 0;
	if ( ! $started || ( time() - $started ) < 3 ) {
		theme_form_error( 'Попробуйте ещё раз через пару секунд.', 400 );
	}

	$ip     = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	$ip_key = 'theme_form_' . md5( $ip );
	$count  = (int) get_transient( $ip_key );
	if ( $count >= 5 ) {
		theme_form_error( 'Слишком много заявок. Попробуйте позже.', 429 );
	}

	$data   = array();
	$errors = array();

	foreach ( $forms[ $form_id ]['fields'] as $name => $field ) {
		$raw   = isset( $_POST[ $name ] ) ? wp_unslash( $_POST[ $name ] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$value = theme_form_clean( $name, $field, $raw );

		if ( ! empty( $field['required'] ) && '' === $value ) {
			$errors[ $name ] = 'Заполните поле.';
		}

		$data[ $name ] = $value;
	}
	// phpcs:enable

	if ( $errors ) {
		wp_send_json_error(
			array(
				'message' => 'Проверьте поля формы.',
				'errors'  => $errors,
			),
			422
		);
	}

	set_transient( $ip_key, $count + 1, 10 * MINUTE_IN_SECONDS );

	$lines = array();
	foreach ( $forms[ $form_id ]['fields'] as $name => $field ) {
		if ( 'consent' === $field['type'] ) {
			$lines[] = $field['label'] . ': да';
			continue;
		}
		if ( '' === $data[ $name ] ) {
			continue;
		}
		$value   = 'choice' === $field['type'] ? $field['choices'][ $data[ $name ] ] : $data[ $name ];
		$lines[] = $field['label'] . ': ' . $value;
	}
	$body    = implode( "\n", $lines );
	$subject = $forms[ $form_id ]['title'] . ' — ' . wp_parse_url( home_url(), PHP_URL_HOST );

	wp_insert_post(
		array(
			'post_type'    => 'lead',
			'post_status'  => 'publish',
			'post_title'   => $subject,
			'post_content' => $body,
		)
	);

	$to      = theme_opt( 'leads_email' );
	$headers = array( 'Content-Type: text/plain; charset=UTF-8' );
	if ( 'quiz' === $form_id && 'mail' === $data['channel'] && is_email( $data['contact'] ) ) {
		$headers[] = 'Reply-To: ' . $data['contact'];
	}

	if ( ! $to || ! wp_mail( $to, $subject, $body, $headers ) ) {
		theme_form_error( 'Не удалось отправить. Позвоните нам.', 500 );
	}

	wp_send_json_success( array( 'message' => $success ) );
}
add_action( 'wp_ajax_theme_form', 'theme_handle_form' );
add_action( 'wp_ajax_nopriv_theme_form', 'theme_handle_form' );

add_action(
	'init',
	function () {
		register_post_type(
			'lead',
			array(
				'label'               => 'Заявки',
				'public'              => false,
				'show_ui'             => true,
				'exclude_from_search' => true,
				'menu_icon'           => 'dashicons-email',
				'supports'            => array( 'title', 'editor' ),
				'capabilities'        => array( 'create_posts' => 'do_not_allow' ),
				'map_meta_cap'        => true,
			)
		);
	}
);

add_filter(
	'wp_mail_from_name',
	function () {
		return 'Beleksprint';
	}
);
