<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[
    OA\Info(version: "0.0.1", title: "Task Api Documentation"),
    OA\Server(url: 'http://localhost:8888/api', description: "Task REST API server"),
    OA\Tag(name: 'Task', description: 'Управление списком задач')
]
abstract class Controller {}
