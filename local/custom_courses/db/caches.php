<?php
defined('MOODLE_INTERNAL') || die();

$definitions = [
    'mycache' => [
        'mode' => cache_store::MODE_APPLICATION,
        'simplekeys' => true,
        'simpledata' => true,
    ],
];