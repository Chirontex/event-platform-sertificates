<?php
/**
 * Event Platform Sertificates
 */
namespace EPSertificates;

use EPSertificates\Providers\Users;
use EPSertificates\Providers\SertificateSettings;
use EPSertificates\Handlers\TemplateSettings;

class Main
{

    protected $path;
    protected $url;
    protected $wpdb;
    protected $admin_page = 'event-platform-sertificates-admin.php';
    protected $admin_notice = [];

    public function __construct(string $path, string $url)
    {
        
        global $wpdb;

        $this->wpdb = $wpdb;

        $this->path = $path;
        $this->url = $url;

        $this->adminPageInit();

        if (strpos($_SERVER['REQUEST_URI'], 'wp-admin') !== false &&
            strpos($_GET['page'], $this->admin_page) !== false) {
                
            $this
                ->adminScriptsStyles()
                ->filterFileUploaded()
                ->filterUsersMetadata();
        
        }

    }

    /**
     * Add admin page to menu.
     * 
     * @return $this
     */
    protected function adminPageInit() : self
    {

        add_action('admin_menu', function() {

            add_menu_page(
                'Сертификат',
                'Сертификат',
                8,
                $this->path.$this->admin_page
            );

        });

        return $this;

    }

    /**
     * Add scripts and styles to the admin page.
     * 
     * @return $this
     */
    protected function adminScriptsStyles() : self
    {

        add_action('admin_enqueue_scripts', function() {

            wp_enqueue_style(
                'bootstrap-min',
                (file_exists($this->path.'css/bootstrap.min.css') ?
                    $this->url : 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/').
                'css/bootstrap.min.css',
                [],
                '5.0.0-beta3'
            );

            wp_enqueue_style(
                'epserts-admin',
                $this->url.'css/admin.css',
                [],
                '0.1.2'
            );

            wp_enqueue_script(
                'bootstrap-bundle-min',
                (file_exists($this->path.'js/bootstrap.bundle.min.js') ?
                    $this->url : 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/').
                'js/bootstrap.bundle.min.js',
                [],
                '5.0.0-beta3'
            );

            wp_enqueue_script(
                'epserts-admin',
                $this->url.'js/admin.js',
                [],
                '0.1.0'
            );

        });

        return $this;

    }

    /**
     * Add users metadata names list to filter.
     * 
     * @return $this
     */
    protected function filterUsersMetadata() : self
    {

        add_filter('epserts-users-metadata', function() {

            $users = new Users($this->wpdb);

            return $users->getMetadataList();

        });

        return $this;

    }

    /**
     * Add template upload checking to filter.
     * 
     * @return $this
     */
    protected function filterFileUploaded() : self
    {

        add_filter('epserts-file-uploaded', function($answer) {

            $template_settings = new TemplateSettings(
                new SertificateSettings($this->wpdb),
                $this->path
            );

            if ($template_settings->checkTemplateUploaded()) $answer = 'да';

            return $answer;

        });

        return $this;

    }

    protected function handleFileUploading() : self
    {

        add_action('plugins_loaded', function() {

            if (wp_verify_nonce(
                $_POST['epserts-template-upload-wpnp'],
                'epserts-template-upload'
            ) === false) $this->adminNotify(
                'danger',
                'Произошла ошибка при отправке формы. Попробуйте ещё раз.'
            );
            else {

                //

            }

        });

        return $this;

    }

    protected function adminNotify(string $type, string $text) : self
    {

        if ($type === 'danger') $type = 'error';

        $this->admin_notice = [
            'type' => $type,
            'text' => $text
        ];

        add_action('admin_notices', function($prev_notices) {

            ob_start();

?>
<div class="notice notice-<?= $this->admin_notice['type'] ?> is-dismissible" style="max-width: 500px; margin-left: auto; margin-right: auto;">
    <p style="text-align: center;"><?= $this->admin_notice['text'] ?></p>
</div>
<?php

            echo $prev_notices.ob_get_clean();

        });

        return $this;

    }

}
