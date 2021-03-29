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

            if ($key !== 'last_name' &&
                $key !== 'middle_name' &&
                $key !== 'first_name') $value = (int)$value;

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

    /**
     * Set font size.
     * 
     * @param int $value
     * 
     * @return $this
     * 
     * @throws EPSertificates\Exceptions\TemplateSettingsException
     */
    public function fontSizeSet(int $value) : self
    {

        if ($value < 0) throw new TemplateSettingsException(
            ExceptionsList::HANDLERS['-31']['message'],
            ExceptionsList::HANDLERS['-31']['code']
        );

        $this->settings['font_size'] = (string)$value;

        return $this;

    }

    /**
     * Get font size.
     * 
     * @return int
     */
    public function fontSizeGet() : int
    {

        $result = 0;

        if (isset(
            $this->settings['font_size']
        )) $result = (int)$this->settings['font_size'];

        return $result;

    }

    /**
     * Set bolder font weight.
     * 
     * @return $this
     */
    public function bolderSet(bool $on) : self
    {

        $this->settings['bolder'] = $on ? '600' : '0';

        return $this;

    }

    /**
     * Get if font is bolder.
     * 
     * @return bool
     */
    public function bolderGet() : bool
    {

        $result = false;

        if (isset(
            $this->settings['bolder']
        )) $result = $this->settings['bolder'] === '600' ?
            true : $result;

        return $result;

    }

    /**
     * Set last name.
     * 
     * @param string $value
     * 
     * @return $this
     */
    public function lastnameSet(string $value) : self
    {

        $this->settings['last_name'] = $value;

        return $this;

    }

    /**
     * Get last name.
     * 
     * @return string
     */
    public function lastnameGet() : string
    {

        $result = '';

        if (isset(
            $this->settings['last_name']
        )) $result = $this->settings['last_name'];

        return $result;

    }

    /**
     * Set middle name.
     * 
     * @param string $value
     * 
     * @return $this
     */
    public function middlenameSet(string $value) : self
    {

        $this->settings['middle_name'] = $value;

        return $this;

    }

    /**
     * Get middle name.
     * 
     * @return string
     */
    public function middlenameGet() : string
    {

        $result = '';

        if (isset(
            $this->settings['middle_name']
        )) $result = $this->settings['middle_name'];

        return $result;

    }

    /**
     * Set first name.
     * 
     * @return $this
     */
    public function firstnameSet(string $value) : self
    {

        $this->settings['first_name'] = $value;

        return $this;

    }

    /**
     * Get first name.
     * 
     * @return string
     */
    public function firstnameGet() : string
    {

        $result = '';

        if (isset(
            $this->settings['first_name']
        )) $result = $this->settings['first_name'];

        return $result;

    }

}
