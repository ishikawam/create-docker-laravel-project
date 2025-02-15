create_docker_laravel_project
=============================

* スキャフォルディング
* これがいったいなにをやっているのかが明確＆シンプルに
* 目指すのは、レンタルサーバで稼働するサービスの開発環境を一瞬で構築、かつawsまで拡張できる
  * 石川はlocal、さくらのレンタルサーバ、さくらのVPS、AWS。

## setup

install

* docker-compose
* gsed
* koki-develop/tap/docker-tags

## なにをやっているか

* docker
  * php-fpm
  * nginx
  * mysql, postgres
* laravel
* bootstrap
  * admin-lte
* github actions
  * CI

## やらないこと

* aws
  * terraform
  * optimize

## commit

1. install laravel
2. install templates & docker
3. install others
4. install admin-lte
  * socialite(facebook), jetstream, livewire
  * setup views

## このあとにやることは

* Githubへpush
  * main, first, develop
  * mysql
    * Sequel Ace
      * MySQL Workbench
      * Models > Create EER Model from Database
      * 必要なテーブルに絞る
* convert
  * `php ~/scripts/create_docker_laravel_project/convert_html_to_blade.php`
    * @todo; これやめて`jeroennoten/laravel-adminlte`にしたほうがいいかも
* Swagger
  * docker compose run --rm php composer require zircote/swagger-php
  * docker compose run --rm node npm install swagger-ui --save-dev
  * https://www.zu-min.com/archives/1098
* memcached
* queue
* サーバ環境構築とCIセットアップ
  * awsの場合はSSL (app/Providers/AppServiceProvider.php に forceScheme()とか)
  * awsはMakefileをしっかり
* Issue作成
  * @todo; 一覧
  * @todo; Github CLI？
* API
  * Kernel.phpでEnsureFrontendRequestsAreStatefulを有効に
  * /api/内でエラーもjsonで返すためにRequireJson実装
  * swagger postのためにVerifyCsrfToken でtokensMatch
* CD
  * aws

## todo

* templatesにfacebookが入ってたりして、最小構成で作ったときがおかしい
* memcachedも選択肢に
* sqlite機能しない
* cliのみに対応。nginx外してcontroller, viewをリセットする選択肢も
* migrationにsocial_accountsを
* app/Exceptions/Handler.php 絶対書き換えるのでコメント入れておくとか
* laravel/slack-notification-channel と Exception通知
  * キューqueueも必須にしたい。supervisordも
* version_hash
* 外部からのPOST禁止 app/Http/Kernel.php
* https強制
  * このあたりはコメントアウトで入れておいて、@todo; 入れたい。
* flash message
* webpack.mix.js
* test, dusk
* maintenance.html メンテナンス中ページと表示
* public/favicon.ico, public/image/apple-touch-icon.png
* package.json > imagemin, sass, jquery
* docs/infra.drawio, ER図
* docker/php/default.ini : php8ならopache JIT, とか。
* Middlewareとconfigは他の生きてるリポジトリのを大いに参考にする
* awsやりたいなあ
  * その場合考えることは環境変数の伝播。
  * MakefileとterraformとLaravel serviceとLaravel run-taskで統一が理想。
* Log Level Policy
  * 順に emergency、alert、critical、error、warning、notice、info、debug
  * をどうするか
* 多言語対応
* パッケージコンポーネントの整理？廃止？
  * `<x-`から始まるタグ。jetstreamってわけではない。
  * デフォルトのはなんかじゃま、、なのでなくしてる
  * なくし方は`<x-`から始まるタグを置換する、`:value=`とかを対応する、
  * `<x-label`だけはそのままで改修する
* locale
  * config/app.php。fallback_localeはenのまま、localeはja。
* localにphpなくても実行できるようにしたい
  * php実行は必須なのでdockerでphp実行とかにすればいいのでは。dockerは必須だから。
* pest
  * composer require pestphp/pest-plugin-laravel --dev
  * ./vendor/bin/pest --init
* dockerのportsは `- "${LOCAL_WEB_PORT:-80}:80"` にすれば.envを読むので、いまのportを強引にさける処理はやめたい
  * LOCAL_DB_PORT=15432
  * LOCAL_WEB_PORT=10096
  * make openとかもそのportを指定できるようにしたい
  * include .env
  * LOCAL_DB_PORT ?= 13326
* php-fpmのチューニング
  * pm系
  * pm.max_children = 20
  * pm.start_servers = 10
  * pm.min_spare_servers = 5
  * pm.max_spare_servers = 15

## todo 検討

* app/Console/Command.php


やるぞ

* facebook選択
  * ここまでしなくてもなきがしてきた。
* pint
* larastan phpstan
