<?php
/**
 * Event Platform Sertificates
 */
namespace EPSertificates;

class Main
{

    protected $path;
    protected $url;
    protected $wpdb;
    protected $admin_page = 'event-platform-sertificates-admin.php';

    public function __construct(string $path, string $url)
    {
        
        global $wpdb;

        $this->wpdb = $wpdb;

        $this->path = $path;
        $this->url = $url;

        $this->adminPageInit();

        if (strpos($_SERVER['REQUEST_URI'], 'wp-admin') !== false &&
            strpos($_GET['page'], $this->admin_page) !== false) $this->adminScriptsStyles();

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

        });

        return $this;

    }

}
