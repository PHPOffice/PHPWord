<?php

if (\PHP_VERSION_ID < 80300) {
    require_once __DIR__ . '/XMLWriter.withwakeup.php'; // @codeCoverageIgnore
} else {
    require_once __DIR__ . '/XMLWriter.withoutwakeup.php';
}
