<?php
/**
 * Plugin Name: Event Platform Sertificates
 * Plugin URI: https://github.com/chirontex/event-platform-sertificates
 * Description: Этот плагин позволяет выдавать участникам мероприятий именные сертификаты в PDF-формате.
 * Version: 0.6.1
 * Author: Дмитрий Шумилин
 * Author URI: mailto://ds@brandpro.ru
 */
use EPSertificates\Main;

require_once __DIR__.'/event-platform-sertificates-autoload.php';

new Main(
    plugin_dir_path(__FILE__),
    plugin_dir_url(__FILE__)
);
