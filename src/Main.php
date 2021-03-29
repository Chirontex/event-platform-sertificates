<?php
/**
 * Event Platform Sertificates
 */
namespace EPSertificates;

use EPSertificates\Providers\Users;
use EPSertificates\Providers\SertificateSettings;
use EPSertificates\Handlers\TemplateFile;
use EPSertificates\Handlers\TemplateSettings;
use EPSertificates\Exceptions\MainException;
use EPSertificates\Exceptions\ExceptionsList;
use Imagick;
use ImagickDraw;

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

        $this
            ->adminPageInit()
            ->shortcodeInit();

        if (strpos($_SERVER['REQUEST_URI'], 'wp-admin') !== false &&
            strpos($_GET['page'], $this->admin_page) !== false) {

            if (isset(
                $_FILES['epserts-template-file-upload']
            )) $this->handleFileUploading();

            if (isset(
                $_POST['epserts-template-download-wpnp']
            )) $this->handleFileDownloading();

            if (isset(
                $_POST['epserts-template-settings-wpnp']
            )) $this->handleTemplateSettings();
                
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

        if (isset(
            $_POST['epserts-download-sertificate-wpnp']
        )) $this->downloadCompleteSertificate();

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
     * Shortcode initiation.
     * 
     * @return $this
     */
    protected function shortcodeInit() : self
    {

        add_shortcode('epserts-client-download', function($atts, $content) {

            $atts = shortcode_atts([
                'class' => '',
                'style' => ''
            ], $atts);

            if (empty($content)) $content = 'Скачать сертификат';

            ob_start();

?>
<button class="<?= htmlspecialchars($atts['class']) ?>" style="<?= htmlspecialchars($atts['style']) ?>" onclick="document.getElementById('epserts-client-download-sertificate-form').submit();"><?= htmlspecialchars($content) ?></button>
<form action="" method="post" id="epserts-client-download-sertificate-form">
<?php wp_nonce_field('epserts-download-sertificate', 'epserts-download-sertificate-wpnp') ?>
</form>
<?php

            return ob_get_clean();

        });

        return $this;

    }

    /**
     * Initialize sertificate downloading.
     * 
     * @return $this
     */
    protected function downloadCompleteSertificate() : self
    {

        add_action('plugins_loaded', function() {

            $user_id = get_current_user_id();

            if ($user_id > 0) {

                if (wp_verify_nonce(
                    $_POST['epserts-download-sertificate-wpnp'],
                    'epserts-download-sertificate'
                ) !== false) {

                    $template_file = new TemplateFile($this->path);

                    $template_settings = new TemplateSettings(
                        new SertificateSettings($this->wpdb)
                    );

                    $y_coefficient = 6;

                    $template = new Imagick;
                    $template->setResolution(
                        (float)$template_settings->widthGet(),
                        (float)$template_settings->heightGet()
                    );
                    $template->readImage($template_file->getTemplatePath());

                    $draw = new ImagickDraw;
                    $draw->setFontSize((float)$template_settings->fontSizeGet());

                    if ($template_settings->bolderGet()) $draw->setFontWeight(600);

                    $query = "SELECT t.meta_value
                        FROM `".$this->wpdb->prefix."usermeta` AS t
                        WHERE t.user_id = '".$user_id."'
                        AND t.meta_key = '%s'";

                    $first_name = $this->wpdb->get_var(
                        sprintf($query, $template_settings->firstnameGet())
                    );

                    $last_name = $this->wpdb->get_var(
                        sprintf($query, $template_settings->lastnameGet())
                    );

                    $middle_name = $this->wpdb->get_var(
                        sprintf($query, $template_settings->middlenameGet())
                    );

                    $y1 = $template_settings->yGet() + (empty($middle_name) ?
                        (int)(($template_settings->fontSizeGet() * $y_coefficient)/2) : 0);

                    $draw->annotation(
                        (float)$template_settings->xGet(),
                        (float)$y1,
                        implode(' ', [trim($last_name), trim($first_name)])
                    );

                    if (!empty($middle_name)) {

                        $y2 = $template_settings->yGet();
                        $y2 += (int)($template_settings->fontSizeGet() * $y_coefficient);

                        $draw->annotation(
                            (float)$template_settings->xGet(),
                            (float)$y2,
                            $middle_name
                        );

                    }

                    $template->drawImage($draw);

                    $temp_dir = $template_file->getTemplateDir().'temp/';

                    if (!file_exists($temp_dir)) mkdir($temp_dir);

                    $arr = array_merge(range('a', 'z'), range(0, 9));

                    do {

                        $filename = '';

                        for ($i = 0; $i < 32; $i++) {

                            $filename .= $arr[rand(0, count($arr) - 1)];

                        }

                        $filename .= '.pdf';

                    } while (file_exists($temp_dir.$filename));

                    if ($template->writeImage($temp_dir.$filename)) {

                        $sertificate = file_get_contents($temp_dir.$filename);

                        if (!$sertificate) throw new MainException(
                            ExceptionsList::COMMON['-2']['message'],
                            ExceptionsList::COMMON['-2']['code']
                        );

                        unlink($temp_dir.$filename);

                        header('Content-type: application; charset=utf-8');
                        header('Content-disposition: attachment; filename=certificate.pdf');

                        echo $sertificate;

                        die;

                    } else throw new MainException(
                        ExceptionsList::COMMON['-1']['message'].
                            ' ('.$temp_dir.$filename.')',
                        ExceptionsList::COMMON['-1']['code']
                    );

                }

            }

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
