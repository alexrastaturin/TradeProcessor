<?php

include('protected/Components/Application.php');
$app = Components\Application::init('protected/config.php');
$app->runConsume();


