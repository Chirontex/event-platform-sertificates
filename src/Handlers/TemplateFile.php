<?php
/**
 * Event Platform Sertificates
 */
namespace EPSertificates\Handlers;

use EPSertificates\Exceptions\TemplateFileException;
use EPSertificates\Exceptions\ExceptionsList;

class TemplateFile
{

    protected $path;
    protected $file = 'template.pdf';

    public function __construct(string $main_path)
    {

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
     * @param string $pathfile
     * File temporary place.
     * 
     * @return $this
     * 
     * @throws EPSertificates\Exceptions\TemplateFileException
     */
    public function saveUploadedTemplate(string $pathfile) : self
    {

        if (move_uploaded_file(
            $pathfile,
            $this->path.$this->file
        ) === false) throw new TemplateFileException(
            ExceptionsList::TEMPLATE_SETTINGS['-21']['message'],
            ExceptionsList::TEMPLATE_SETTINGS['-21']['code']
        );

        return $this;

    }

    /**
     * Return template file directory.
     * 
     * @return string
     */
    public function getTemplateDir() : string
    {

        return $this->path;

    }

    /**
     * Return template file path.
     * 
     * @return string
     */
    public function getTemplatePath() : string
    {

        return $this->path.$this->file;

    }

    /**
     * Return template file name.
     * 
     * @return string
     */
    public function getTemplateFilename() : string
    {

        return $this->file;

    }

    /**
     * Read template file.
     * 
     * @return string
     */
    public function getTemplateContent() : string
    {

        $result = '';

        if ($this->checkTemplateUploaded()) $result = file_get_contents(
            $this->path.$this->file
        );

        return $result;

    }

}
