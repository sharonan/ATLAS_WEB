<?php


if(DEVELOPMENT_ENVIRONMENT == true){
	// Live environment
	define('DB_HOST', '');
	define('DB_USER', '');
	define('DB_PASS', '');
	define('DB_NAME', '');
} else {
	// Development Environment
	define('DB_HOST', '');
	define('DB_USER', '');
	define('DB_PASS', '');
	define('DB_NAME', '');
}


date_default_timezone_set('UTC');

define('TITLE', "Atlas");
define('DOMAIN', "getatlas.com");


// Update for JS and CSS changes
define('VERSION', 70);

/* User action message alert */
define('ERROR_EMAIL_EXISTS', 	"That email already exists.");
define('ERROR_DATABASE', 	"Database error.");
define('WELCOME_EMAIL_SUBJECT', 'Welcome');
define('EVENT', "https://api.parse.com/1/classes/event");
define('USER', "https://api.parse.com/1/classes/_User");
define('ITEMUSER', "https://api.parse.com/1/classes/item_user");
define('USEROBJECT', "https://api.parse.com/1/functions/userObject");
define('INVITETOEVENT', "https://api.parse.com/1/functions/inviteToEvent");
define('VERIFYEMAIL', "https://api.parse.com/1/classes/email_address");

// BETA
//define('APPLICATION_ID', 'd8ccXhrq3W1xVQBo36n6HjnC8PznadKXGOndtZcK');
//define('JAVASCRIPT_KEY', 'm4S5MxOtfeV87e4hGHdBDvAGKSsfAvS9FRQcdo9w');
//define('REST_API_KEY', '7JALWw66e3lR4vvyYhQqw6H1kkDtRhnDUCuukVIb');

// DEV
//define('APPLICATION_ID', 'FvEPBedzfhHchdviDbKOayUm2W9aPAzCrVcIRTT4');
//define('JAVASCRIPT_KEY', 'nPgVAfkwjmaSkDqLTjhEzlJjfHH3QjTJPAZOz8JJ');
//define('REST_API_KEY', 'ts6EZiaJ0BUH9hBFOUe6P7kfqAoyst1VMuYtkWBc');

// DEMO
//define('APPLICATION_ID', 'JRi0WKejt1nd6qy4iry27KAVmEvgexvcShZduaMG');
//define('JAVASCRIPT_KEY', 'XqH08NKyno2l7dwbr12BwCejr8ZTj2GPE0nFQ3fm');
//define('REST_API_KEY', 'eKlnfrlAveUMku8m3W5fiVUZ0bphD4PanRRRxEwP');

// PROD
//define('APPLICATION_ID', 'HZeCitmFRtWOC0o1OZwqpeFZp8RBD08uf7YQhiVX');
//define('JAVASCRIPT_KEY', 'ay4Tw2TtBc9YmR3FNWI799bsq1YOJHboU7Vzqy67');
//define('REST_API_KEY', 'vt9KG5IoenJ0JQ2ZG0D3SJr2UphHwDqgk0GzO0dh');

// QA
define('APPLICATION_ID', 'ZfzVtSyJdEemMVz7RVC5DnWNxrvrcJiFSpopUxHg');
define('JAVASCRIPT_KEY', 'sPHpBrIKIUxbI6e0ItAQC7Qx1F4EmjVeXCw4chM1');
define('REST_API_KEY', 'xgdOSQTtW1BEQy8KUopsRCVHFMa6h7QBGmda2V09');

if(DEVELOPMENT_ENVIRONMENT == true)
	define('PATH', '');
else
	define('PATH', '');
