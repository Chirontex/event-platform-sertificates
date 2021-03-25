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

    public function __construct(string $path, string $url)
    {
        
        global $wpdb;

        $this->wpdb = $wpdb;

        $this->path = $path;

        $this->url = $url;

    }

}
