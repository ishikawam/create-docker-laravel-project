<?php

/**
 * Settings (config, .env)
 * configはデフォルト値をlocal開発環境に寄せる
 * 本番は環境変数、.env、で上書きする。
 *
 * env:
 * PROJECT_NAME
 * NGINX_PORT
 * DATABASE_NAME
 * MEMCACHED
 */

echo "\n[start Settings (config, .env).]\n";

// config/app.php
$str = file_get_contents('config/app.php');
$str = preg_replace('/\'en\'/', '\'ja\'', $str);
$str = preg_replace('/\'en_US\'/', '\'ja_JP\'', $str);
$str = preg_replace('/\'UTC\'/', '\'Asia/Tokyo\'', $str);
$str = preg_replace('/(\'APP_NAME\', )(\'.*?\')/', '\\1\'' . getenv('PROJECT_NAME') . '\'', $str);
$str = preg_replace('/(\'APP_ENV\', )(\'.*?\')/', '\\1\'local\'', $str);
$str = preg_replace('/(\'APP_URL\', )(\'.*?\')/', '\\1\'http://localhost:' . getenv('NGINX_PORT') . '\'', $str);
file_put_contents('config/app.php', $str);

// config/database.php
exec('sed -i -e "s/\'DB_CONNECTION\', \'.*\'/\'DB_CONNECTION\', \'' . getenv('DATABASE_NAME') . '\'/g" config/database.php');
exec('sed -i -e "s/\'DB_HOST\', \'.*\'/\'DB_HOST\', \'' . getenv('DATABASE_NAME') . '\'/g" config/database.php');
exec('sed -i -e "s/\'DB_DATABASE\', \'.*\'/\'DB_DATABASE\', \'laravel\'/g" config/database.php');  // database名をlaravelに
exec('sed -i -e "s/\'DB_USERNAME\', \'.*\'/\'DB_USERNAME\', \'root\'/g" config/database.php');  // userをrootに
exec('sed -i -e "s/\'DB_PASSWORD\', \'.*\'/\'DB_PASSWORD\', \'password\'/g" config/database.php');  // passwordをpasswordに

// config/session.php
exec('sed -i -e "s/\'SESSION_DRIVER\', \'.*\'/\'SESSION_DRIVER\', \'' . (getenv('MEMCACHED') ? 'memcached' : 'apc') . '\'/g" config/session.php');

// config/cache.php
exec('sed -i -e "s/\'CACHE_DRIVER\', \'.*\'/\'CACHE_DRIVER\', \'' . (getenv('MEMCACHED') ? 'memcached' : 'file') . '\'/g" config/cache.php');

// config/queue.php
exec('sed -i -e "s/mysql/' . getenv('DATABASE_NAME') . '/g" config/queue.php');

// .env
// APP_KEYのみにする
$str = file_get_contents('.env');
$str = preg_match('/^APP_KEY=.*$/m', $str, $out) ? $out[0] : '';
if ($str) {
    file_put_contents('.env', $str);
}

echo "\n[finish Settings (config, .env).]\n";

