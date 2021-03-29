<?php
/**
 * Event Platform Sertificates
 */
namespace EPSertificates;

use EPSertificates\Providers\Users;
use EPSertificates\Providers\SertificateSettings;
use EPSertificates\Handlers\TemplateFile;
use EPSertificates\Handlers\TemplateSettings;

class Main
{

    protected $path;
    protected $url;
    protected $wpdb;
    protected $admin_page = 'event-platform-sertificates-admin.php';
    protected $admin_notice = [];
    protected $form_failed_text = 'Произошла ошибка при отправке формы. Попробуйте ещё раз.';

    public function __construct(string $path, string $url)
    {
        
        global $wpdb;

        $this->wpdb = $wpdb;

        $this->path = $path;
        $this->url = $url;

        $this->adminPageInit();

        if (strpos($_SERVER['REQUEST_URI'], 'wp-admin') !== false &&
            strpos($_GET['page'], $this->admin_page) !== false) {

            if (isset($_FILES['epserts-template-file-upload'])) $this->handleFileUploading();

            if (isset($_POST['epserts-template-download-wpnp'])) $this->handleFileDownloading();

            if (isset($_POST['epserts-template-settings-wpnp'])) $this->handleTemplateSettings();
                
            $this
                ->adminScriptsStyles()
                ->filterFileUploaded()
                ->filterTemplateWidth()
                ->filterTemplateHeight()
                ->filterTemplateCoordinateX()
                ->filterTemplateCoordinateY()
                ->filterTemplateFontSize()
                ->filterTemplateFontBolder()
                ->filterTemplateLastname()
                ->filterTemplateFirstname()
                ->filterTemplateMiddlename()
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

            $template_file = new TemplateFile($this->path);

            if ($template_file->checkTemplateUploaded()) $answer = 'да';

            return $answer;

        });

        return $this;

    }

    /**
     * Handle template file uploading.
     * 
     * @return $this
     */
    protected function handleFileUploading() : self
    {

        add_action('plugins_loaded', function() {

            if (wp_verify_nonce(
                $_POST['epserts-template-upload-wpnp'],
                'epserts-template-upload'
            ) === false) $this->adminNotify(
                'danger',
                $this->form_failed_text
            );
            else {

                $template_file = new TemplateFile($this->path);

                $notice = 'Файл шаблона успешно %s.';

                $notice = sprintf(
                    $notice,
                    $template_file->checkTemplateUploaded() ?
                        'заменён' : 'загружен'
                );

                $template_file->saveUploadedTemplate(
                    $_FILES['epserts-template-file-upload']['tmp_name']
                );

                $this->adminNotify('success', $notice);

            }

        });

        return $this;

    }
    
    /**
     * Handle template file downloading.
     * 
     * @return $this
     */
    protected function handleFileDownloading() : self
    {

        add_action('plugins_loaded', function() {

            if (wp_verify_nonce(
                $_POST['epserts-template-download-wpnp'],
                'epserts-template-download'
            ) === false) $this->adminNotify(
                'danger',
                $this->form_failed_text
            );
            else {

                $template_file = new TemplateFile($this->path);

                $content = $template_file->getTemplateContent();

                header('Content-type: application; charset=utf-8');
                header('Content-disposition: attachment; filename='.$template_file->getTemplateFilename());

                echo $content;

                die;

            }

        });

        return $this;

    }

    /**
     * Handle template settings.
     * 
     * @return $this
     */
    protected function handleTemplateSettings() : self
    {

        add_action('plugins_loaded', function() {

            if (wp_verify_nonce(
                $_POST['epserts-template-settings-wpnp'],
                'epserts-template-settings'
            ) === false) $this->adminNotify('danger', $this->form_failed_text);
            else {

                $template_settings = new TemplateSettings(
                    new SertificateSettings($this->wpdb)
                );

                $template_settings
                    ->widthSet((int)$_POST['epserts-template-width'])
                    ->heightSet((int)$_POST['epserts-template-height'])
                    ->xSet((int)$_POST['epserts-template-coordinate-x'])
                    ->ySet((int)$_POST['epserts-template-coordinate-y'])
                    ->FontSizeSet((int)$_POST['epserts-template-fontsize'])
                    ->bolderSet(isset($_POST['epserts-template-font-bolder']))
                    ->lastnameSet(
                        empty($_POST['epserts-template-user-lastname']) ?
                        '' : trim($_POST['epserts-template-user-lastname'])
                    )
                    ->middlenameSet(
                        empty($_POST['epserts-template-user-middlename']) ?
                        '' : trim($_POST['epserts-template-user-middlename'])
                    )
                    ->firstnameSet(
                        empty($_POST['epserts-template-user-name']) ?
                        '' : trim($_POST['epserts-template-user-name'])
                    );

                $template_settings->settingsSave();

                $this->adminNotify(
                    'success',
                    'Настройки шаблона сохранены.'
                );

            }

        });

        return $this;

    }

    /**
     * Filter template width.
     * 
     * @return $this
     */
    protected function filterTemplateWidth() : self
    {

        add_filter('epserts-template-width', function() {

            $template_settings = new TemplateSettings(
                new SertificateSettings($this->wpdb)
            );

            return $template_settings->widthGet();

        });

        return $this;

    }

    /**
     * Filter template height.
     * 
     * @return $this
     */
    protected function filterTemplateHeight() : self
    {

        add_filter('epserts-template-height', function() {

            $template_settings = new TemplateSettings(
                new SertificateSettings($this->wpdb)
            );

            return $template_settings->heightGet();

        });

        return $this;

    }

    /**
     * Filter X coordinate.
     * 
     * @return $this
     */
    protected function filterTemplateCoordinateX() : self
    {

        add_filter('epserts-template-coordinate-x', function() {

            $template_settings = new TemplateSettings(
                new SertificateSettings($this->wpdb)
            );

            return $template_settings->xGet();

        });

        return $this;
        
    }

    /**
     * Filter Y coordinate.
     * 
     * @return $this
     */
    protected function filterTemplateCoordinateY() : self
    {

        add_filter('epserts-template-coordinate-y', function() {

            $template_settings = new TemplateSettings(
                new SertificateSettings($this->wpdb)
            );

            return $template_settings->yGet();

        });

        return $this;

    }

    /**
     * Filter font size.
     * 
     * @return $this
     */
    protected function filterTemplateFontSize() : self
    {

        add_filter('epserts-template-fontsize', function() {

            $template_settings = new TemplateSettings(
                new SertificateSettings($this->wpdb)
            );

            return $template_settings->fontSizeGet();

        });

        return $this;

    }

    /**
     * Filter if font is bolder.
     * 
     * @return $this
     */
    protected function filterTemplateFontBolder() : self
    {

        add_filter('epserts-template-font-bolder', function() {

            $template_settings = new TemplateSettings(
                new SertificateSettings($this->wpdb)
            );

            return $template_settings->bolderGet() ?
                'checked="true"' : '';

        });

        return $this;

    }

    /**
     * Filter lastname metadata name.
     * 
     * @return $this
     */
    protected function filterTemplateLastname() : self
    {

        add_filter('epserts-template-user-lastname', function() {

            $template_settings = new TemplateSettings(
                new SertificateSettings($this->wpdb)
            );

            return $template_settings->lastnameGet();

        });

        return $this;

    }

    /**
     * Filter firstname metadata name.
     * 
     * @return $this
     */
    protected function filterTemplateFirstname() : self
    {

        add_filter('epserts-template-user-name', function() {

            $template_settings = new TemplateSettings(
                new SertificateSettings($this->wpdb)
            );

            return $template_settings->firstnameGet();

        });

        return $this;

    }

    /**
     * Filter middlename metadata name.
     * 
     * @return $this
     */
    protected function filterTemplateMiddlename() : self
    {

        add_filter('epserts-template-user-middlename', function() {

            $template_settings = new TemplateSettings(
                new SertificateSettings($this->wpdb)
            );

            return $template_settings->middlenameGet();

        });

        return $this;

    }

    /**
     * Create admin notice.
     * 
     * @param string $type
     * Available types: 'success', 'warning', 'danger' (== 'error').
     * 
     * @param string $text
     * Notice text.
     * 
     * @return $this
     */
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
