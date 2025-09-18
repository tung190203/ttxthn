<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
header('Content-Type: application/json; charset=utf-8');

function load_js($file_name)
{
    $list = file_get_contents('vrtour/'.$_REQUEST['folder'].'/'.$file_name.'.js');
    return json_decode($list,true);
}

$vr_hotspot     = load_js($file_name = 'hotspot');
$list_hotspot   = [ ];
foreach($vr_hotspot as $k => $val){
    $list_hotspot[$k]['potision']   = $val['potision'];
    $list_hotspot[$k]['url']        = $val['url'];
    $list_hotspot[$k]['url_en']     = $val['url_en'];
    $list_hotspot[$k]['opacity']    = $val['opacity'];
    $list_hotspot[$k]['tooltip']    = $val['tooltip'];
    $list_hotspot[$k]['tooltip_en'] = $val['tooltip_en'];
}

$connectmap         = load_js($file_name = 'connectmap');

$location           = load_js($file_name = 'location');

$investor           = load_js($file_name = 'investor');
$start_html         = '<div style="text-align: left; color: #000;">
    <p style="margin: 0; line-height: 16.76px;"><br style="display: inline-block; letter-spacing: 0px; white-space: pre-wrap; color: #000000; font-size: 46.17px; font-family: Arial, Helvetica, sans-serif;" /></p>
    <p style="margin: 0; line-height: 16.76px;"><br style="display: inline-block; letter-spacing: 0px; white-space: pre-wrap; color: #000000; font-size: 46.17px; font-family: Arial, Helvetica, sans-serif;" /></p>
    <p style="margin: 0; line-height: 16.76px;"><br style="display: inline-block; letter-spacing: 0px; white-space: pre-wrap; color: #000000; font-size: 46.17px; font-family: Arial, Helvetica, sans-serif;" /></p>
    <p style="margin: 0; line-height: 16.76px;"><br style="display: inline-block; letter-spacing: 0px; white-space: pre-wrap; color: #000000; font-size: 46.17px; font-family: Arial, Helvetica, sans-serif;" /></p>
    <p style="margin: 0; line-height: 16.76px;"><br style="display: inline-block; letter-spacing: 0px; white-space: pre-wrap; color: #000000; font-size: 46.17px; font-family: Arial, Helvetica, sans-serif;" /></p>
    <p style="margin: 0; line-height: 16.76px;"><br style="display: inline-block; letter-spacing: 0px; white-space: pre-wrap; color: #000000; font-size: 46.17px; font-family: Arial, Helvetica, sans-serif;" /></p>
    <div style="text-align: center; font-size: 36px;">
        <span STYLE="display:inline-block; letter-spacing:0px; white-space:pre-wrap;color:#000000;font-family:Arial, Helvetica, sans-serif;">
            <span STYLE="color:#0176bf;font-size:36px;font-family:SVN-Gotham;">';
$end_html           = '</span>
        </span>
    </div>
</div>';
$investor['name']       = $start_html.$investor['name'].$end_html;
$investor['name_en']    = $start_html.$investor['name_en'].$end_html;

$pano               = load_js($file_name = 'pano');
$start_html1        = '<div style="text-align:left; color:#000; "><DIV STYLE="line-height:100%;text-align:left;font-size:1.984564498346196vmin;"><SPAN STYLE="display:inline-block; letter-spacing:0vmin; white-space:pre-wrap;color:#000000;font-family:Arial, Helvetica, sans-serif;"><SPAN STYLE="color:#ffffff;font-size:1.98vmin;font-family:SVN-Gotham;">';
$end_html1          = '</SPAN></SPAN></DIV></div>';
$screen             = load_js($file_name = 'welcome_screen');
$screen['description'] = $start_html1.$screen['description'].$end_html1;
$plan               = load_js($file_name = 'plan');
$document           = load_js($file_name = 'document');

echo json_encode(compact('list_hotspot', 'connectmap', 'location', 'investor', 'pano', 'screen', 'plan','document'));