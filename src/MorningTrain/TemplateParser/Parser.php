<?php
/**
 * Parser.php
 * @author Stefan Fodor
 * @year 2016
 */


namespace MorningTrain\TemplateParser;

/**
 * Class Parser
 * @package   MorningTrain\TemplateParser
 * @author    Stefan Fodor
 * @copyright Copyright (c) 2015
 * @version   1.0
 */
class Parser {

    const TAG_DELIMITER_START = '{';
    const TAG_DELIMITER_END = '}';

    /**
     * @var string
     */
    protected $regex_pattern = '';

    /**
     * @var string
     */
    protected $template_id = '';

    /**
     * @var string
     */
    protected $original_template = '';

    /**
     * @var array
     */
    protected $options = array();

    /**
     * Constructor
     * @param \MorningTrain\TemplateParser\string $template_id
     * @param array $replacements
     * @param array $options
     * @throws \Exception
     */
    public function __construct(
        string $template_id = '',
        array $replacements = array(),
        array $options = array()
    ) {

        if( strlen($template_id) == 0 ) {
            throw new \Exception('Invalid template_id given');
        }

        // Transfer from constructor argument to class proprieties
        $this->template_id = $template_id;
        $this->replacements = $replacements;
        $this->options = $this->setOptions($options);


        // Generate the regex pattern
        // This is a bit of black magic, but it is totally worth it,
        // as it allows to very easily change the tag delimiters,
        // from {replace_me} to for example [replace_me], or %replace_me%.
        // Construct 2 capturing groups: first one for the whole replacement tag,
        // second one only for the key to be replaces.
        // We want to capture in a match everything,
        // except the actual delimiters and html tags delimiters
        $this->regex_pattern = sprintf(
            "(%s([^%s%s<>]+)%s)",
            self::TAG_DELIMITER_START, self::TAG_DELIMITER_START,
            self::TAG_DELIMITER_END, self::TAG_DELIMITER_END
        );

    }

    /**
     * Returns default options for the parser
     * @return array
     */
    private function getDefaultOptions() {
        return [
            "clear_unused_tags" => false
        ];
    }

    /**
     * Sets the options with the user submitted ones or the defaults
     * @param array $options
     * @return array
     */
    private function setOptions( array $options = array() ) {

        $defaults = $this->getDefaultOptions();
        $result = [];

        // Loop though default options and replace them
        // with the user specific
        foreach($defaults as $thisKey => $thisDefaultValue) {
            $result[ $thisKey ] = array_key_exists($thisKey, $options) ? $options[$thisKey] : $thisDefaultValue;
        }

        return $result;
    }

    /**
     * Parse the template
     * @return string
     */
    public function parseTemplate()
    {

        // Get the template from the file based on the ID
        $template_provider = new FileTemplateProvider();
        $this->original_template = $template_provider->getTemplateByID( $this->template_id );

        // Replace the tags in the original template with the ones supplied by the user
        $result = preg_replace_callback(
            $this->regex_pattern, // pattern to search and replace
            array($this, "replaceCallback"), // callback method
            $this->original_template //original template to search in
        );

        return $result;
    }

    /**
     * Returns the replacement for a given template key
     * @param array $matches
     * @return string
     */
    private function replaceCallback(array $matches) {

        $replacement = '';

        // First match group contains the tag with the delimiters.
        // Second one the tag without
        $key_with_delimiters = $matches[0];
        $key = trim($matches[1]);

        // If we got a value for the current key, replace it
        if( array_key_exists($key, $this->replacements) ) {

            // Should the value be HTML escaped?
            $escape_html = is_array($this->replacements[$key]) ? !(bool)$this->replacements[$key]["html"] : true;

            // Replace the value
            $replacement = is_array($this->replacements[$key]) ? $this->replacements[$key]["value"] : $this->replacements[$key];

            //Escape the html is needed
            if($escape_html) {
                $replacement = htmlentities($replacement);
            }

        //
        } else {

            // Determine if we should leave the tag with no replacements or not
            $replacement = $this->options["clear_unused_tags"] ? '' : $key_with_delimiters;

        }

        return $replacement;
    }

    /**
     * @return string
     */
    public function getOriginalTemplate()
    {
        return $this->original_template;
    }

    /**
     * @param string $original_template
     */
    public function setOriginalTemplate($original_template)
    {
        $this->original_template = $original_template;
    }

}