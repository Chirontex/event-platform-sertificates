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

    protected $sertset;
    protected $path;
    protected $file = 'template.pdf';

    public function __construct(SertificateSettings $sertificate_settings, string $main_path)
    {
        
        $this->sertset = $sertificate_settings;

        if (substr($main_path, - 1) !== '/' ||
            substr($main_path, -1) !== '\\') $main_path .= '/';

        $this->path = $main_path.'usr/';

        if (!file_exists($this->path)) mkdir($this->path);

    }

    /**
     * Check if template was uploaded.
     * 
     * @return bool
     */
    public function checkTemplateUploaded() : bool
    {

        return file_exists($this->path.$this->file);

    }

    /**
     * Save uploaded template.
     * 
     * @param string $file_content
     * Template file content.
     * 
     * @return $this
     * 
     * @throws EPSertificates\Exceptions\TemplateSettingsException
     */
    public function saveUploadedTemplate(string $file_content) : self
    {

        if (file_put_contents(
            $this->path.$this->file,
            $file_content
        ) === false) throw new TemplateSettingsException(
            ExceptionsList::TEMPLATE_SETTINGS['-21']['message'],
            ExceptionsList::TEMPLATE_SETTINGS['-21']['code']
        );

        return $this;

    }

}
