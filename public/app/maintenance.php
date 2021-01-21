<?php
declare(strict_types=1);

http_response_code(503);

echo file_get_contents(__DIR__ . '/../../resource/view/html/maintenance.html');
