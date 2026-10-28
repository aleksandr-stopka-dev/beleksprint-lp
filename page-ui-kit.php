<?php
if ( ! current_user_can( 'manage_options' ) ) {
	global $wp_query;
	$wp_query->set_404();
	status_header( 404 );
	nocache_headers();
	include get_404_template();
	exit;
}

get_header();
?>

<main id="main" class="main">
	<section class="section-padding">
		<div class="container">
			<p class="text-eyebrow">Внутренняя страница · только для администратора</p>
			<h1 class="text-h1">UI-kit</h1>
			<p class="text-lg">Компоненты сайта во всех состояниях. Страница 404 для всех, кроме администратора.</p>

			<div class="ui-kit__block">
				<h2 class="text-h3">Типографика</h2>
				<div class="ui-kit__grid">
					<div class="ui-kit__cell">
						<p class="text-eyebrow">Eyebrow</p>
						<h3 class="text-h2">Заголовок секции <span class="text-accent">с акцентом</span></h3>
					</div>
					<div class="ui-kit__cell">
						<p class="text-lg">Оптовый заказ картонной упаковки и тубусов у белорусского производителя.</p>
						<p class="text-md">Основной текст: Open Sans 16px, строка 1.5. Для длинных абзацев — не длиннее 80 символов в строке.</p>
						<p class="text-sm">Текст карточки: 14px, строка 1.55.</p>
						<p class="text-h3">Заголовок карточки 20px</p>
					</div>
				</div>
			</div>

			<div class="ui-kit__block">
				<h2 class="text-h3">Кнопки</h2>
				<div class="ui-kit__row">
					<button class="btn btn--primary" type="button">
						Смотреть каталог
						<svg viewBox="0 0 18 18" aria-hidden="true"><path d="M3 9h12M10 4l5 5-5 5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
					</button>
					<button class="btn btn--primary" type="button" disabled>Недоступно</button>
					<button class="btn btn--primary" type="button" aria-busy="true" disabled>Отправка…</button>
					<button class="btn btn--dark" type="button">Рассчитать</button>
					<button class="btn btn--ghost" type="button">
						Спросить технолога
						<svg viewBox="0 0 18 18" aria-hidden="true"><path d="M3 9h12M10 4l5 5-5 5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
					</button>
				</div>
				<div class="ui-kit__dark is-dark">
					<button class="btn btn--outline" type="button">Получить прайс-лист</button>
				</div>
			</div>

			<div class="ui-kit__block">
				<h2 class="text-h3">Поля</h2>
				<div class="ui-kit__grid">
					<div class="field">
						<label class="field__label" for="uk-name">Имя</label>
						<input class="field__input" id="uk-name" type="text" autocomplete="name" placeholder="Как к вам обращаться">
					</div>
					<div class="field field--error">
						<label class="field__label" for="uk-phone">Телефон</label>
						<input class="field__input" id="uk-phone" type="tel" autocomplete="tel" placeholder="+375 (__) ___-__-__" aria-invalid="true" aria-describedby="uk-phone-error">
						<p class="field__error" id="uk-phone-error">Введите номер в формате +375 (__) ___-__-__</p>
					</div>
					<div class="field">
						<label class="field__label" for="uk-disabled">Недоступно</label>
						<input class="field__input" id="uk-disabled" type="text" value="zakaz@beleksprint.by" disabled>
					</div>
					<div class="field">
						<label class="field__label" for="uk-qty">Тираж</label>
						<input class="field__input" id="uk-qty" type="number" min="1" inputmode="numeric" placeholder="Например, 3000">
					</div>
					<div class="field">
						<label class="field__label" for="uk-question">Вопрос технологу</label>
						<textarea class="field__input field__input--area" id="uk-question" rows="3" placeholder="Например: какая коробка выдержит 2 кг?"></textarea>
					</div>
				</div>
				<div class="ui-kit__dark is-dark">
					<div class="ui-kit__grid">
						<div class="field field--dark">
							<label class="field__label" for="uk-dark-name">Имя</label>
							<input class="field__input" id="uk-dark-name" type="text" autocomplete="name" placeholder="Как к вам обращаться">
						</div>
						<div class="field field--dark field--error">
							<label class="field__label" for="uk-dark-phone">Телефон</label>
							<input class="field__input" id="uk-dark-phone" type="tel" autocomplete="tel" placeholder="+375 (__) ___-__-__" aria-invalid="true" aria-describedby="uk-dark-phone-error">
							<p class="field__error" id="uk-dark-phone-error">Введите номер телефона</p>
						</div>
						<div class="field field--dark">
							<label class="field__label" for="uk-dark-question">Вопрос технологу</label>
							<textarea class="field__input field__input--area" id="uk-dark-question" rows="3" placeholder="Например: какая коробка выдержит 2 кг?"></textarea>
						</div>
					</div>
				</div>
			</div>

			<div class="ui-kit__block">
				<h2 class="text-h3">Чекбокс согласия</h2>
				<div class="ui-kit__grid">
					<label class="check">
						<input class="check__input" type="checkbox">
						<span class="check__box" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M5 12l5 5 9-10" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round"></path></svg></span>
						<span>Я даю согласие на обработку персональных данных в соответствии с <a href="/privacy/">политикой конфиденциальности</a> (ст. 9 ФЗ-152).</span>
					</label>
					<label class="check">
						<input class="check__input" type="checkbox" checked>
						<span class="check__box" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M5 12l5 5 9-10" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round"></path></svg></span>
						<span>Отмечено</span>
					</label>
					<div>
						<label class="check check--error">
							<input class="check__input" type="checkbox" aria-describedby="uk-consent-error">
							<span class="check__box" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M5 12l5 5 9-10" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round"></path></svg></span>
							<span>Ошибка: согласие не отмечено</span>
						</label>
						<p class="field__error" id="uk-consent-error">Поставьте галочку согласия, чтобы отправить заявку.</p>
					</div>
					<label class="check">
						<input class="check__input" type="checkbox" disabled>
						<span class="check__box" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M5 12l5 5 9-10" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round"></path></svg></span>
						<span>Недоступно</span>
					</label>
				</div>
				<div class="ui-kit__dark is-dark">
					<label class="check check--dark">
						<input class="check__input" type="checkbox">
						<span class="check__box" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M5 12l5 5 9-10" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round"></path></svg></span>
						<span>Я даю согласие на связь по указанному номеру.</span>
					</label>
				</div>
			</div>
		</div>
	</section>
</main>

<?php
get_footer();
