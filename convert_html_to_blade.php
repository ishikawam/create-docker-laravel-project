<?php

/**
 * Convert html to blade.
 * for AdminLTE
 * 
 * `php convert_html_to_blade.php file`
 * `php convert_html_to_blade.php from-file to-file`
 */

// ファイル名チェック
$file = $argv[1] ?? '';

$toFile = $argv[2] ?? $file;

if (! file_exists($file)) {
    echo "\nfile not exists.\n";
    exit(1);
}

if (! preg_match('/\.blade\.php$/', $toFile)) {
    echo "\nonly blade file.\n";
    exit(1);
}

if (! is_dir(dirname($toFile))) {
    mkdir(dirname($toFile), 0755, true);
}

$str = file_get_contents($file);

// 階層判定 `../`の数 2階層まで対応
$level = substr_count(max(preg_match_all('/"((\.\.\/)+)/', $str, $out) ? $out[1] : ['']), '../');
echo "\nlevel: {$level}\n";
$dirs = ['', 'pages/'];

// サイトルート相対パスに置き換え
$str = preg_replace('/ (href|src|action)="(#.*?)"/', ' \\1_="\\2"', $str);  // "#" を避ける
$str = preg_replace('/ (href|src|action)="(https?:\/\/.*?)"/', ' \\1_="\\2"', $str);  // https?://を避ける
$str = preg_replace('/ (href|src|action)="(\/\/.*?)"/', ' \\1_="\\2"', $str);  // //を避ける
// 置換
for ($i = $level; $i > 0; $i--) {
    $str = preg_replace('/ (href|src|action)="(' . str_repeat('\.\.\/', $i) . ')(.*)"/', ' \\1_="/admin-lte/' . array_shift($dirs) . '\\3"', $str);
}

if (preg_match('/ (href|src|action)="(.*)"/', $str, $out) ? $out[1] : false) {
    echo "\nDANGER!: unknown path pattern.\n";
}

// 戻す
$str = preg_replace('/ (href|src|action)_=/', ' \\1=', $str);

// html lang
$str = preg_replace('/<html lang=".*?">/', '<html lang="{{ str_replace(\'_\', \'-\', app()->getLocale()) }}">', $str);

// save
file_put_contents($toFile, $str);

echo "\nconvert html to blade, done.\n\n";
