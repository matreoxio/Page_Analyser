<?php
/*
 * Class to connect to the requested website and obtain all of the available HTML and load it 
 * to dom obejct. 
 */

//Get content
@$url = $_POST['URL'];

@$html = file_get_contents($url);
$dom = new DOMDocument;

//Parse the html, @ is used to supress any parsing errors
@$dom->loadHTML($html);


