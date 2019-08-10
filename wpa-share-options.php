<?php

$wpa_auto_insert_locations = array(
    'before-post' => 'Before Content',
    'after-post' => 'After Content',
    'both' => 'Both'
);

$wpa_inpost_style = array(
    'basic'=> 'Basic',
    'round' => 'Round', 
    'flex' => 'Flex'
);

$wpa_share_options = array(

    array(
        "name" => "General",
        "label" => __("General"),
        "type" => "section"
    ),
    
        array(  "name" => "Auto Insert",
        "desc" => "Enables automatic insert of sharing buttons on posts/pages",
        "id" => "auto-insert",
        "std" => "on",
        "type" => "checkbox"),
    
        array(  "name" => "Auto Insert Location",
        "desc" => "This option enables use of theme location.",
        "id" => "auto-insert-location",
        "type" => "select",
        "options" => $wpa_auto_insert_locations ),
    
        array(  "name" => "Use WPA Share CSS",
        "desc" => "Use plugin CSS code",
        "id" => "output-css",
        "std" => "on",
        "type" => "checkbox"),

        array(  "name" => "InPost Buttons Style",
        "desc" => "Select appearance",
        "id" => "inpost-style",
        "std" => "basic",
        "type" => "select",
        "options" => $wpa_inpost_style ),
    
    array( "type" => "close" )

);
    