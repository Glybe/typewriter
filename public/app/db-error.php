<?php
declare(strict_types=1);

http_response_code(500);

echo file_get_contents(__DIR__ . '/../../resource/view/html/error.html');
