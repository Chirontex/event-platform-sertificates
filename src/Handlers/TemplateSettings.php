<?php
/**
 * Event Platform Sertificates
 */
namespace EPSertificates\Handlers;

use EPSertificates\Providers\SertificateSettings;
use EPSertificates\Exceptions\TemplateSettingsException;
use EPSertificates\Exceptions\ExceptionsList;

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

    /**
     * Set the width.
     * 
     * @param int $value
     * 
     * @return $this
     * 
     * @throws EPSertificates\Exceptions\TemplateSettingsException
     */
    public function widthSet(int $value) : self
    {

        if ($value < 0) throw new TemplateSettingsException(
            ExceptionsList::HANDLERS['-31']['message'],
            ExceptionsList::HANDLERS['-31']['code']
        );

        $this->settings['width'] = (string)$value;

        return $this;

    }

    /**
     * Get the width.
     * 
     * @return int
     */
    public function widthGet() : int
    {

        $result = 0;

        if (isset(
            $this->settings['width']
        )) $result = (int)$this->settings['width'];

        return $result;

    }

    /**
     * Set the height.
     * 
     * @param int $value
     * 
     * @return $this
     * 
     * @throws EPSertificates\Exceptions\TemplateSettingsException
     */
    public function heightSet(int $value) : self
    {

        if ($value < 0) throw new TemplateSettingsException(
            ExceptionsList::HANDLERS['-31']['message'],
            ExceptionsList::HANDLERS['-31']['code']
        );

        $this->settings['height'] = (string)$value;

        return $this;

    }

    /**
     * Get the height.
     * 
     * @return int
     */
    public function heightGet() : int
    {

        $result = 0;

        if (isset(
            $this->settings['height']
        )) $result = (int)$this->settings['height'];

        return $result;

    }

    /**
     * Set the X coordinate.
     * 
     * @param int $value
     * 
     * @return $this
     */
    public function xSet(int $value) : self
    {

        if ($value < 0) throw new TemplateSettingsException(
            ExceptionsList::HANDLERS['-31']['message'],
            ExceptionsList::HANDLERS['-31']['code']
        );

        $this->settings['x'] = (string)$value;

        return $this;

    }

    /**
     * Get the X coordinate.
     * 
     * @return int
     */
    public function xGet() : int
    {

        $result = 0;

        if (isset($this->settings['x'])) $result = (int)$this->settings['x'];

        return $result;

    }

    /**
     * Set the Y coordinate.
     * 
     * @param int $value
     * 
     * @return $this
     * 
     * @throws EPSertificates\Exceptions\TemplateSettingsException
     */
    public function ySet(int $value) : self
    {

        if ($value < 0) throw new TemplateSettingsException(
            ExceptionsList::HANDLERS['-31']['message'],
            ExceptionsList::HANDLERS['-31']['code']
        );

        $this->settings['y'] = (string)$value;

        return $this;

    }

    /**
     * Get the Y coordinate.
     * 
     * @return int
     */
    public function yGet() : int
    {

        $result = 0;

        if (isset($this->settings['y'])) $result = (int)$this->settings['y'];

        return $result;

    }

}
