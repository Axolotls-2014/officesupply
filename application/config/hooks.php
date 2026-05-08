<?php
defined('BASEPATH') OR exit('No direct script access');

$hook['post_controller_constructor'] = array(
    'class'       => 'EnvatoVerify',
    'function' => 'checkLogin',
    'filename'  => 'EnvatoVerify.php',
    'filepath'  => 'hooks',
    'params'   => array()
);
