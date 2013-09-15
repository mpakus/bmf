<?php

define('DC_USERS_TABLE', 'users');
define('DC_POSTS_TABLE', 'posts');
define('DC_TAGS_TABLE',  'tags');
define('DC_TAGGED_OBJECTS_TABLE',  'tagged_objects');
define('DC_TEXTS_TABLE', 'texts');
define('DC_CODES_TABLE', 'codes');
define('DC_PHOTOS_TABLE', 'photos');
define('DC_PICTURES_TABLE', 'pictures');
define('DC_VOTES_TABLE', 'votes');
define('DC_COMMENTS_TABLE', 'comments');
define('DC_MODULES_TABLE', 'modules');

$config['main']['layout'] = 'index';

$config['common'] = array(
    'front_cache_path' => './static/cache/',
    'front_cache_url' => '/static/cache/',
);