<?php
/**
 * FileTemplateProvider.php
 * @author Stefan Fodor
 * @year 2016
 */


namespace MorningTrain\TemplateParser;

/**
 * Class FileTemplateProvider
 * @package   MorningTrain\TemplateParser
 * @author    Stefan Fodor
 * @copyright Copyright (c) 2015
 * @version   1.0
 */
class FileTemplateProvider implements ITemplateProvider {

    /**
     * @var string
     */
    private $base_template_path = '';

    /**
     * Constructor
     */
    public function __construct()
    {
        // arhhh... I have getcwd, but it will have to do since I don't really want to
        // add BASE_DIR
        $this->base_template_path = getcwd() . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR;
    }

    /**
     * @param \MorningTrain\TemplateParser\string $id
     * @return string
     * @throws \Exception
     */
    public function getTemplateByID(string $id = '')
    {
        // Real path to the file
        $template_path = realpath($this->base_template_path . $id . '.html');

        // Check is they are trying to get outside the template folder
        if( substr($template_path, 0, strlen($this->base_template_path)) != $this->base_template_path ) {
            throw new \Exception("Someone if a naughty boy..");
        }

        // Small check to see if the ID exists
        if( !file_exists( $template_path) ) {
            throw new \Exception("Template file could not be found");
        }

        // Read the template
        $template = file_get_contents($template_path);

        // All fine?
        if( $template === false ) {
            throw new \Exception("Error reading the template file");
        }

        return $template;
    }

}