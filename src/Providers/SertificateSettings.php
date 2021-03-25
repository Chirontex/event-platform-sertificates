<?php
/**
 * Event Platform Sertificates
 */
namespace EPSertificates\Providers;

use EPSertificates\Exceptions\SertificateSettingsException;
use EPSertificates\Exceptions\ExceptionsList;
use wpdb;

class SertificateSettings
{

    protected $wpdb;
    protected $table = 'eps_sertificate_settings';

    public function __construct(wpdb $wpdb)
    {
        
        $this->wpdb = $wpdb;

        $this->tableCreate();

    }

    /**
     * Create the table.
     * 
     * @return $this
     * 
     * @throws EPSertificates\Exceptions\SertificateSettingsException
     */
    public function tableCreate() : self
    {

        if ($this->wpdb->query(
            "CREATE TABLE IF NOT EXISTS `".$this->wpdb->prefix.$this->table."` (
                `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                `key` VARCHAR(255) NOT NULL,
                `value` LONGTEXT NOT NULL,
                PRIMARY KEY (`id`),
                UNIQUE INDEX `key` (`key`)
            )
            COLLATE='utf8mb4_unicode_ci'
            AUTO_INCREMENT=0"
        ) === false) throw new SertificateSettingsException(
            ExceptionsList::PROVIDERS['-11']['message'],
            ExceptionsList::PROVIDERS['-11']['code']
        );

        return $this;

    }

}
