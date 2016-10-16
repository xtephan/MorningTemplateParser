<?php
/**
 * index.php
 * @author Stefan Fodor
 * @year 2016
 */

// Setup the project
require("./bootstrap/autoloader.php");

$parser = new MorningTrain\TemplateParser\Parser(

    // template ID
    "test",

    // List of tags to be replaced
    [
        "simple_text"                              => "Simple Text is GO",
        "multiple_text"                            => "GO",
        "html_replace"                             => array("value" => "<strong>GO</strong>", "html" => true),
        "text_replace"                             => "<strong>GO</strong>",
        "tag_with_spaces"                          => "Tags with spaces GO",
        "double_tag"                               => "Double is GO",
        "øåæé_tag"                                 => "UTF8 is GO",
        "tag-with-%!@#$%^*()_+=-~`?,./\\';][|\":?" => "Special is also GO",
        "unclosed_tag"                             => "Unclosed is NOGO!!!",
        "<span>Inner tag</span>"                   => "Tag with innerHTML is NOGO!!",
        "inexistent_tag"                           => "This should not crash"
    ],

    // Misc options
    [
        "clear_unused_tags" => false
    ]
);

// Parse!
$result = $parser->parseTemplate();
$template = $parser->getOriginalTemplate();

// Show the result
echo $result;

// Show the original template also
echo '<hr>';
echo "<h1>Original Template</h1>";
var_dump($template);

// All done
echo '<hr>';
echo "Done diddly done.";
