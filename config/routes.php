<?php

/**
 * Public Pages Routes:
 */
$this->get('/', 'App\Controllers\HomeController:index');


/**
 * API Routes:
 */
$this->post('/api/stats', 'App\Controllers\Api\StatsController:record');

// Catch all for any API route
$this->any('/api/{endpoint}', 'Core\BaseApiController:notFound');