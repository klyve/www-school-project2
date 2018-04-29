<?php
function regexReplace($string) {
    $matches = [];
    $pattern = '/\{(\w+)\}/';
    $string = trim($string, '/');
    preg_match_all($pattern, $string, $matches);
    if(count($matches) > 0) {
        foreach($matches[0] as $key => $value) {
            $string = preg_replace('/'.$value.'/', '(?<'.$matches[1][$key].'>[a-zA-Z0-9]+)', $string);
        }
        $string = preg_replace('/\//', '\/', $string);
    }
    $string = '/(?<uri>^'.$string.'$)/';
    return $string;
}

$signatures = [

    'user',
    'user/login',
    'user/register',
    'user/password',
    'user/refresh',

    'video',
    'video/{videoid}',
    'video/{videoid}/rate',
    'video/{videoid}/comment',
    'video/{videoid}/comment/{commentid}',
    'video/{videoid}/tag',
    'video/{videoid}/tag/{tagname}',

    'playlist',
    'playlist/{playlistid}',
    'playlist/{playlistid}/tag',
    'playlist/{playlistid}/tag/{tagname}',
    'playlist/{playlistid}/video',
    'playlist/{playlistid}/video/{id}',
    'playlist/{playlistid}/reorder',
    'playlist/{playlistid}/subscribe',
    
    'tempfile',
    'error',
    'test',
    'graphql',
];

$routes = [
    '/video/23/rate',
    'playlist/3243/video/233',
    'playlist/2334/reorder',
    'video/234/comment/123',
    'video/234/tag',
    'video/234/tag/1234',
];


for($i = 0; $i < count($signatures); ++$i) {

    for($j = 0; $j < count($routes); ++$j) {

        $regex = regexReplace($signatures[$i]);

        $res = preg_match($regex, trim($routes[$j], '/'), $matches);
         
        if(!$res) {
            continue;
        }

        print_r($i . " " . $j . "REGEX MATCH: " . $signatures[$i] . " --> " . $routes[$j] . "\n");
    }
}