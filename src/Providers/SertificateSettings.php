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

    /**
     * Get all settings.
     * 
     * @return array
     * 
     * @throws EPSertificates\Exceptions\SertificateSettingsException
     */
    public function getAll() : array
    {

        $result = [];

        $select = $this->wpdb->get_results(
            "SELECT *
                FROM `".$this->wpdb->prefix.$this->table."`",
            ARRAY_A
        );

        if (is_array($select)) {

            foreach ($select as $row) {

                $result[$row['key']] = $row['value'];

            }

        } else throw new SertificateSettingsException(
            ExceptionsList::PROVIDERS['-12']['message'],
            ExceptionsList::PROVIDERS['-12']['code']
        );

        return $result;

    }

    /**
     * Get setting by key.
     * 
     * @param string $key
     * 
     * @return string
     * 
     * @throws EPSertificates\Exceptions\SertificateSettingsException
     */
    public function getByKey(string $key) : string
    {

        if (empty($key)) throw new SertificateSettingsException(
            ExceptionsList::PROVIDERS['-13']['message'],
            ExceptionsList::PROVIDERS['-13']['code']
        );

        $result = '';

        $select = $this->wpdb->get_results(
            $this->wpdb->prepare(
                "SELECT *
                    FROM `".$this->wpdb->prefix.$this->table."` AS t
                    WHERE t.key = %s",
                $key
            ),
            ARRAY_A
        );

        if (is_array($select)) {

            if (!empty($select)) $result = $select[0]['value'];

        } else throw new SertificateSettingsException(
            ExceptionsList::PROVIDERS['-12']['message'],
            ExceptionsList::PROVIDERS['-12']['code']
        );

        return $result;

    }

    /**
     * Set the setting.
     * 
     * @param string $key
     * Cannot be empty.
     * If setting with this key already exist, it will be rewrite.
     * 
     * @param string $value
     * Cannot be empty.
     * 
     * @return $this
     * 
     * @throws EPSertificates\Exceptions\SertificateSettingsException
     */
    public function set(string $key, string $value) : self
    {

        if (empty($key)) throw new SertificateSettingsException(
            ExceptionsList::PROVIDERS['-13']['message'],
            ExceptionsList::PROVIDERS['-13']['code']
        );

        if (empty($value)) throw new SertificateSettingsException(
            ExceptionsList::PROVIDERS['-14']['message'],
            ExceptionsList::PROVIDERS['-14']['code']
        );

        if (empty($this->getByKey($key))) {

            if ($this->wpdb->insert(
                $this->wpdb->prefix.$this->table,
                [
                    'key' => $key,
                    'value' => $value
                ],
                ['%s', '%s']
            ) === false) throw new SertificateSettingsException(
                ExceptionsList::PROVIDERS['-15']['message'],
                ExceptionsList::PROVIDERS['-15']['code']
            );

        } else {

            if ($this->wpdb->update(
                $this->wpdb->prefix.$this->table,
                ['value' => $value],
                ['key' => $key],
                ['%s'],
                ['%s']
            ) === false) throw new SertificateSettingsException(
                ExceptionsList::PROVIDERS['-16']['message'],
                ExceptionsList::PROVIDERS['-16']['code']
            );

        }

        return $this;

    }

}
