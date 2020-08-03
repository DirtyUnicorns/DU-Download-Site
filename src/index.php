<?php

/*
 * Copyright (C) 2020 The Dirty Unicorns Project
 * Copyright (C) 2020 James Taylor <jmz.taylor16@gmail.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require_once 'functions.php';
require_once __DIR__.'/vendor/autoload.php';

$loader = new FilesystemLoader('templates');
$twig = new Environment($loader);

Flight::set("twig", $twig);

Flight::route('/', function(){
    echo Flight::get("twig")->render('index.html', [
        'devices' => getAllDevices(),
        'selected_name' => 'Home',
        'site_url' => getenv("SITE_URL"),
        'site_name' => getenv("SITE_NAME")
    ]);
});

Flight::route('/device/@device', function($device){
    $id = getDeviceId($device);
    echo Flight::get("twig")->render('content.html', [
        'devices' => getAllDevices(),
        'selected_name' => $device,
        'fileTypes' => getReleaseTypesForDevice($id),
        'files' => getFilesForDevice($id),
        'site_url' => getenv("SITE_URL"),
        'site_name' => getenv("SITE_NAME")
    ]);
});

Flight::route('/download/*', function(){
    updateDownload($_GET['filename']);
    getDownload($_GET['filename']);
});

Flight::route('/api/devices', function(){
    echo json_encode(getDbDevices());
});

Flight::route('/api/files/@id', function($id){
    echo json_encode(getFilesForDeviceApi($id));
});

Flight::start();
