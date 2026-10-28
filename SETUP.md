# Setup нового проекта на стартовой теме

## wp-config.php (до строки "That's all")
```php
define('WP_DEBUG', true);          // на проде false
define('WP_DEBUG_DISPLAY', false); // ошибки в debug.log, не в HTML
define('WP_DEBUG_LOG', true);
define('WP_POST_REVISIONS', 3);
define('DISABLE_WP_CRON', true);   // + системный cron: */10 * * * * curl -s https://site/wp-cron.php
define('DISALLOW_FILE_EDIT', true);
define('WP_AUTO_UPDATE_CORE', 'minor');
define('EMPTY_TRASH_DAYS', 7);
```

## .htaccess (над блоком "# BEGIN WordPress")
См. файл `htaccess.txt` рядом: запрет PHP в uploads, закрытый xmlrpc.php, readme/license, wp-config, листинг папок, gzip и кеш статики.

## После установки
- Настройки → Постоянные ссылки → «Название записи», сохранить.
- Настройки → Чтение → «Попросить поисковики не индексировать» на время разработки.
- ACF-поле `logo` (Site options) — тип Image, return format «Image Array».
- `npm i && npm run build` (или `npm run watch`).

## В конце: удалить мусор
Когда все шаги выше выполнены, удалить из темы оба файла: `SETUP.md` (этот файл) и `htaccess.txt`.
Это одноразовые инструкции, в готовом проекте они не нужны (а `htaccess.txt` ещё и доступен по прямой ссылке).
```bash
rm SETUP.md htaccess.txt
```
