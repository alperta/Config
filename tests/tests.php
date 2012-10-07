<?php

require_once __DIR__ . '/EnhanceTestFramework.php';

\Enhance\Core::discoverTests('.', true, array('yaml'));

\Enhance\Core::runTests();