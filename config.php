<?php  // Moodle configuration file

unset($CFG);
global $CFG;
$CFG = new stdClass();

// Database connection settings
$CFG->dbtype    = 'mysqli';
$CFG->dblibrary = 'native';
$CFG->dbhost    = getenv('DB_HOST');
$CFG->dbname    = getenv('DB_NAME');
$CFG->dbuser    = getenv('DB_USER');
$CFG->dbpass    = getenv('DB_PASS');
$CFG->prefix    = 'mdl_';
$CFG->dboptions = array (
    'dbpersist' => 0,
    'dbport' => getenv('DB_PORT'),
    'dbsocket' => '',
    'dbcollation' => 'utf8mb4_unicode_ci',
);

// Site paths and URLs
$CFG->wwwroot   = getenv('RENDER_EXTERNAL_URL');
$CFG->dirroot   = '/var/www/html';
$CFG->dataroot  = '/var/moodledata';
$CFG->admin     = 'admin';
$CFG->directorypermissions = 0777;

// Handle Render's HTTPS reverse proxy termination to prevent redirect loops
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
    $_SERVER['HTTPS'] = 'on';
}

// Prevent cache path errors on ephemeral filesystems
$CFG->localcachedir = sys_get_temp_dir() . '/moodle/localcache';
$CFG->cachedir = sys_get_temp_dir() . '/moodle/cache';

require_once(__DIR__ . '/lib/setup.php');