<?php

use Illuminate\Support\Facades\App;

$base_path = App::basePath();
require($base_path.'vendor/autoload.php');

$openapi = \OpenApi\Generator::scan([$base_path.'/app/Http/Controllers']);

header('Content-Type: application/json');
echo $openapi->toJson();