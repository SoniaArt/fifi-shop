<?php 
class DB_Config {
    const HOST = 'localhost';
    const NAME = 'fifi_db';
    const USER = 'fifi_user';
    const PASS = 'simple123';
    const SESSION_NAME = 'FIFISESSID';
    const SESSION_LIFETIME = 3600; 
    const REMEMBER_DAYS = 30;    
    const SITE_URL = 'http://localhost/FIFI/';
    const SITE_NAME = 'FIFI';
    const MAX_FILE_SIZE = 2 * 1024 * 1024;
    const ALLOWED_IMAGE_TYPES = ['image/jpeg', 'image/png', 'image/gif'];
}
?>