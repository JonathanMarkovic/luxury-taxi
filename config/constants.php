<?php

declare(strict_types=1);

// Define the path of the application's root directory.
define('APP_BASE_DIR_PATH', realpath(dirname(__DIR__, 1)));

// Holds the name of the app's root directory.
define('APP_ROOT_DIR_NAME', basename(dirname(__FILE__, 2)));

// Define the path of the application's views directory.
define('APP_VIEWS_PATH', realpath(APP_BASE_DIR_PATH . '/app/Views'));

//* Values for setting up emailing
// TODO: CHANGE THIS TO SOLAF PERFORMANCE EMAIL
define('USERNAME', 'testingsolafwebsite@gmail.com');
define('MAILHOST', "smtp.gmail.com");
define('SEND_FROM', 'testingsolafwebsite@gmail.com');
define('SEND_FROM_NAME', "Solaf Performance");
//* Same as the send from email
define('REPLY_TO', 'testingsolafwebsite@gmail.com');
define('REPLY_TO_NAME', 'Tigran');

// Define the path of the application's lang directory
define('APP_LANG_PATH', realpath(APP_BASE_DIR_PATH . '/lang'));

//* HTTP response status code.
const HTTP_OK = 200;
const HTTP_CREATED = 201;
const HTTP_NO_CONTENT = 204;
const HTTP_METHOD_NOT_ALLOWED = 405;
const HTTP_UNSUPPORTED_MEDIA_TYPE = 415;
const HTTP_NOT_FOUND = 404;
//* HTTP response headers.
const HEADERS_CONTENT_TYPE = "Content-Type";
//* Supported Media Types.
const APP_MEDIA_TYPE_JSON = "application/json";
const APP_MEDIA_TYPE_XML = "application/xml";
const APP_MEDIA_TYPE_YAML = "application/yaml";
