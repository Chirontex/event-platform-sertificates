<?php
/**
 * Event Platform Sertificates
 */
namespace EPSertificates\Handlers;

use EPSertificates\Providers\SertificateSettings;

class TemplateSettings
{

    protected $sertificate_settings;
    protected $settings = [];

    public function __construct(SertificateSettings $sertificate_settings)
    {
        
        $this->sertificate_settings = $sertificate_settings;

        $this->settingsLoad();

    }

    /**
     * Load settings to this object.
     * 
     * @return $this
     */
    public function settingsLoad() : self
    {

        $this->settings = $this->sertificate_settings->getAll();

        return $this;

    }

    /**
     * Get loaded settings.
     * 
     * @return array
     */
    public function settingsGet() : array
    {

        return $this->settings;

    }

    /**
     * Save the settings stored in this object.
     * 
     * @return $this
     */
    public function settingsSave() : self
    {

        foreach ($this->settings as $key => $value) {

            if (empty($value)) $this->sertificate_settings->deleteByKey($key);
            else $this->sertificate_settings->set($key, (string)$value);

        }

        return $this;

    }

}
