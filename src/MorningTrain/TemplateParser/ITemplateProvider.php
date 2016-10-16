<?php
/**
 * ITemplateProvider.php
 * @author Stefan Fodor
 * @year 2016
 */


namespace MorningTrain\TemplateParser;

/**
 * Interface ITemplateProvider
 * @package MorningTrain\TemplateParser
 */
interface ITemplateProvider {

    /**
     * Interface for getting a template from an ID
     * @param \MorningTrain\TemplateParser\string $id
     * @return string
     */
    public function getTemplateByID(string $id = '');

}