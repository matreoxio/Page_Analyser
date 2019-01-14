<?php
/*
 * This class is responsible for extracting information and returniong it back
 * to index.php where it will be displayed to the user
 */

include 'retrieve_page.php';

//Function to retrieve website title
function page_title($dom) {
    @$documentTitle = $dom->getElementsByTagName('title')->item(0)->textContent;
    return $documentTitle;
}

//Function to count how many links are available on the website
function numb_of_links($dom) {
    $links_numb = $dom->getElementsByTagName('a')->length . '<br>';
    return $links_numb;
}

//Function to return all of links found on the website
function all_links($dom) {
//Array to store acquired urls
    $list_of_urls = array();

//Look through dom and get all <a> tags
    $links = $dom->getElementsByTagName('a');

//Count of number of links found
    $numb_of_links = 0;

//Iterate over the extracted links and add them to the $list_of_urls array
    foreach ($links as $link) {
        //Add links to the list_of_urls array
        $list_of_urls[$numb_of_links] = $link->getAttribute('href');
        $numb_of_links++;
    }
    return $list_of_urls;
}

//Function return array of unique links
function unique_domains($dom) {
    
    $list_of_urls = all_links($dom);
    
    $domains = array_map(function($d) {
        @$parts = parse_url($d)['host'];
        return $parts[0];
    }, $list_of_urls);

//array of unique domains
    $new = array_keys(array_unique(array_combine($list_of_urls, $domains)));
    return $new;
}

//Function to return number of unique links found in unique_domains
function numb_of_unique_domains($dom) {

    $unique_links = unique_domains($dom);
    $link_count = 0;
    
    //Count how many unique links there are 
    foreach ($unique_links as $unique) {
        $link_count ++;
    }
    return $link_count;
}

//Use CURL to check websites certificate to check if its secure
function secure_page($domain){

    //Check if domain name has been entered to avoid any errors
    if($domain!=""){
        //Use CURL to get SSL Certificate
        if($fp = tmpfile())
        {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$domain);
            curl_setopt($ch, CURLOPT_STDERR, $fp);
            curl_setopt($ch, CURLOPT_CERTINFO, 1);//Output SSL cert info
            curl_setopt($ch, CURLOPT_VERBOSE, 1);//output verbose information
            curl_setopt($ch, CURLOPT_HEADER, 1);//Include head in the outpu
            curl_setopt($ch, CURLOPT_NOBODY, 1);//Excludes header from the output
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);//verify the peer's certificate
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);//the existence of a common name and also verify that it matches the hostname provided
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//Stop CURL from echoing data to screen
            $result = curl_exec($ch);
            curl_errno($ch)==0 or die("Error:".curl_errno($ch)." ".curl_error($ch));
            
            fseek($fp, 0);//Seek on a file pointer
            $str='';  
            while(strlen($str.=fread($fp,8192))==8192);//Assign data to $str from $fp
            fclose($fp); 
        }
            //Filter retrieved data
            list($a, $b) = explode('*/*', $str);
            $data = explode("*", $a);
            return $data;
    } else {  
        //If $domain is empty return empty array
        $safe = ["",""];
        return $safe;
    }

}

//Search for UA code in the retrieved content
//NOTE: Analytics code may be hidden, not finding the UA doesn't mean that 
//google analytics isn't being used
function google_analytics($dom) {

//Get all code that can be found between html tags
    $links = $dom->getElementsByTagName('html');

//loop through retrieved code and load it into $hi in a string form
    foreach ($links as $node) {
        $code = $node->nodeValue;
}
//Check available code for matches to google analytics content
//I am looking for the UA code 
        if (preg_match("/\bua-\d{4,9}-\d{1,4}\b/i", @$code)){
            return 'Yes';
        } else {
            return 'No';
        }
}
