# LaravelでAirbnb風アプリケーションを作る!

本リポジトリは、Laravelを使った勉強用のAirbnb風のアプリケーションのリポジトリです。

## 1. 環境セットアップ

### 1-1. クローン：gitリポジトリをダウンロード

```sh
$ git clone git@github.com:tomo1833/aribstyle.git
```


## windows環境（docker）

┏━━━━━━━┓　　　┏━━━━━━━┓　　　┏━━━━━━━┓
┃ nginx ┃　　　┃Laravel┃　　　┃ mysql ┃　
┃       ┃　　　┃       ┃　　　┃       ┃
┗━━━━━━━┛　　　┗━━━━━━━┛　　　┗━━━━━━━┛

## Nginx

webサーバーはnginxを使います

## 設定ファイル作成

### docker-compose.yml

docker-compose.ymlの内容を以下のように指定します。

```dickerfile
FROM php:7.2-fpm

# install composer
RUN cd /usr/bin && curl -s http://getcomposer.org/installer | php && ln -s /usr/bin/composer.phar /usr/bin/composer
RUN apt-get update \
    && apt-get install -y \
    git \
    zip \
    unzip \
    vim

RUN apt-get update \
    && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo_mysql pdo_pgsql

WORKDIR /var/www/html
```

```yml
version: '3'
services:
    web:
        image: nginx:1.15.6
        ports:
        - "8000:80"
        depends_on:
        - app
        volumes:
        - ./docker/web/default.conf:/etc/nginx/conf.d/default.conf
        - .:/var/www/html
    app:
        build: ./docker/php
        depends_on:
            - mysql
        volumes:
          - .:/var/www/html
    mysql:
        image: mysql:5.7
        environment:
            MYSQL_DATABASE: airbnb
            MYSQL_USER: root
            MYSQL_PASSWORD: password
            MYSQL_ROOT_PASSWORD: password
        ports:
            - "3306:3306"
        volumes:
            - ./mysql-data:/var/lib/mysql```
```

### サンプルページ（静的ファイル)

index.htmlはサンプル用に作成します。

```html
<?php phpinfo();
```

## dockerコンテナの作成と起動

```sh
$ docker-compose up -d
```

docker-compose upは作成と起動を一緒に実施します。

-dはオプションで デーモン（バックグラウンドで動作する）です。

以下のように出力されます。

```sh
・・・略・・・
Creating docker_web_server_web_1 ... done  
```

## ブラウザ表示確認

http://localhost:8000 でアクセスして画面が表示されることを確認します。


## Larabel

### マイグレーション

マイグレーションをします。

### シーダー

シダーを使ってデータを登録します。


### 1-2. 対象バージョン

|アプリ|バージョン|
|:--|:--|
|PHP|7.X|
|MySQL|5.X|

## 2. ER図

本アプリケーションで使用するデータベースのER図です。



## 3. 機能の概要

本アプリケーションは以下の画面があります。

* トップ画面
* 検索結果画面
* ルーム画面
* ログイン画面(Laravelが提供する機能)
* 会員（ユーザ）登録画面(Laravelが提供する機能)

### トップ画面

最初に表示される画面です。
ロケーション（場所）、チェックイン、チェックアウト（滞在期間）、人数で検索することができます。

### 検索結果画面

検索結果の画面です。
トップ画面で指定した条件に合致するルームを表示します。
また、Google mapを表示します。

### ルーム画面

検索結果画面で指定したルームの画面です。
ルームの確認、予約状況の確認、予約の登録、口コミの登録が出来ます。

### ログイン画面

会員のログイン画面です。
会員のログインが出来ます。
本アプリでは、Laravelが提供する機能を使ってログイン画面を表示します。

### 会員登録画面

会員の登録画面です。
会員の登録が出来ます。
本アプリでは、Laravelが提供する機能を使って会員の登録画面を表示します。


## 4. ライブラリに関して

本アプリで利用したライブラリを以下に記載します。

|ライブラリ|バージョン|備考|
|:--|:--|:--|
|composer|1.10.5|php用ライブラリ管理ツール|
|Google map api|X||
|bootstrap|4|cssフレームワークを採用しています。cdnを利用しています。|
|JQuery|X|cdnを利用しています。|