<?php
include("lib.php");
require_once("ProximityRank.inc");

/*
This program builds an inverted index of the documents listed in a directory and
runs a search query on it.
*/
$rankingMethodList = ["cosine","proximity"];
$tokenizationMethodList = ["none","stem","chargram"];

if (isset($argv) && count($argv) == 5) {
    $dir = $argv[1];
    $query = $argv[2];
    $rankingMethod = $argv[3];
    $tokenizationMethod = $argv[4];
    
    $word_map = [];
    if (is_dir($dir)) {
        $files = glob($dir."/*.txt");
        createIndex($files, $word_map);
        
        $keywords = explode(" ", $query);
        $commonDocId = findCommonDocuments($word_map, $keywords);
        
        $pr = new ProximityRank(count($keywords));
        $rankedDocs = $pr->rankProximity($word_map, $keywords, 0, $commonDocId);
        print_r($rankedDocs);
        
    }
    else {
        echo "Error! Not a directory.";
    }
} else {
    print "Usage: php search_program <directory> <query> <ranking_method>"
          ." <tokenization_method>\n";
}

