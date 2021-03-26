<?php
/**
 * Event Platform Sertificates
 */
namespace EPSertificates\Providers;

use EPSertificates\Exceptions\UsersException;
use EPSertificates\Exceptions\ExceptionsList;
use wpdb;

class Users
{

    protected $wpdb;

    public function __construct(wpdb $wpdb)
    {
        
        $this->wpdb = $wpdb;

    }

    /**
     * Get users metadata names list.
     * 
     * @return array
     * 
     * @throws EPSertificates\Exceptions\UsersException
     */
    public function getMetadataList() : array
    {

        $result = [];

        $select = $this->wpdb->get_results(
            "SELECT t.meta_key
                FROM `".$this->wpdb->prefix."usermeta` AS t
                GROUP BY t.meta_key",
            ARRAY_A
        );

        if (is_array($select)) {

            foreach ($select as $row) {

                $result[] = $row['meta_key'];

            }

        } else throw new UsersException(
            ExceptionsList::PROVIDERS['-12']['message'].
                ' ('.$this->wpdb->prefix.'usermeta)',
            ExceptionsList::PROVIDERS['-12']['code']
        );

        return $result;

    }

}
