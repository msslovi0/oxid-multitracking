<?php
/**
 * Module information
 */
$aModule = array(
    'id'           => 'multitracking',
    'title'        => 'WIBROS Trackingsupport for multiple carriers',
    'email'        => 'oxid@wibros.de',
    'url'          => 'http://www.wibros.de',
    'description'  => 'Module for overwriting the default tracking url method to return urls for other carriers than DPD',
    'thumbnail'    => 'picture.png',
    'version'      => '1.0',
    'author'       => 'WIBROS GmbH',
    'extend'       => array(
        'oxorder' => 'multitracking/multitracking'
    )
);