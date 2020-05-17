# LaravelでAirbnb風アプリケーションを作る!

本リポジトリは、Laravelを使った勉強用のAirbnb風のアプリケーションのリポジトリです。

## 1. 環境セットアップ

環境のセットアップ方法について記載します。

## 1.1. Cloud9環境

以下の手順はcloud9のターミナル起動画面にて実施

### PHPのバージョン確認

PHPのバージョンを確認します。

```sh
$ php -v
```

### MySqlのバージョン確認

mysqlのバージョンを確認します。

```sh
$ mysql --version
```

### パッケージのアップデート

パッケージのアップデートをします。

```sh
$ sudo yum -y update
```

### PHP 7.3 インストール 

PHP7.3のインストールをします。

```sh
$ sudo yum -y install php73 php73-cli php73-common php73-devel php73-mysqlnd php73-pdo php73-xml php73-gd php73-intl php73-mbstring php73-mcrypt php73-zip
```

### PHPバージョンの切り替え 

PHPのバージョンを切り替えます。

```sh
$ sudo alternatives --set php /usr/bin/php-7.3
```

### MySQL停止

MySQLを停止します。

```sh
$ sudo service mysqld stop
```

### MySQLアンインストール

MySQLをアンインストールします。

```sh
$ sudo yum -y erase mysql-config mysql55-server mysql55-libs mysql55
```

### MySQLインストール

MySQLをインストールします。

```sh
$ sudo yum -y install mysql57-server mysql57
```

### MySQL起動

MySQLを起動します。

```sh
$ sudo service mysqld start
```

```sh
sudo chkconfig mysqld on
```

### MySQLの初期設定

MySQLの初期設定（パスワード登録）をします。

```sh
$ mysql_secure_installation
```
対話式で以下のコマンドを入力して下さい。
パスワードの設定をするか　→　「Y」を入力 
パスワードの複雑さ　　　　→　「0」（最低）を入力

rootパスワード設定(控えておくこと)
rootパスワード設定ここでは「password」とします（2回入力）。

残りの質問は全て「Y」と回答し「All done!」と表示されることを確認（5回）

### MySqlログイン

MySQLにログインします。

```sh
$ mysql -u root -p
```

### データベース作成

データベースを作成します。

```sql
CREATE DATABASE airbnb CHARACTER SET utf8mb4;
```

### データベース確認

データベースを確認します。

```sql
show databases;
```

### MySQLログアウト

MySQLにログアウトします。

```sql
exit
```

### gitリポジトリ先ファイルバックアップ

一時フォルダ作成します。

```sh
$ mkdir ../tmp
```

ディレクトリをコピーします。

```sh
$ cp -r .c9/ ../tmp/.c9
```

ディレクトリとファイルを削除します。
git clone時にファイルがある場合エラーとなるため削除します。

```sh
$ rm -rf .c9/
$ rm README.md
```

### gitリポジトリをダウンロード

airbnbstyle配下の資産をダウンロードします。

```sh
$ git clone https://github.com/tomo1833/airbnbstyle/ .
```

### gitリポジトリ先ファイル戻し

一時退避したファイルをもどします。

```sh
$ cp -r ../tmp/.c9 .
```

### composer インストール

composerをインストールします。

```sh
$ curl -sS https://getcomposer.org/installer | php
```

### composer ファイル移動

composerファイルを移動します。

```sh
$ sudo mv composer.phar /usr/local/bin/composer
```
### composer アップデート

airbnbstyle配下で実施します。

```sh
$ cd airbnbstyle
```
composer を更新します。
gitにない資産をダウンロードします。

```sh
$ composer update
```
インストールの確認をします。

```sh
$ composer -V
```

### 環境ファイル更新

DB接続などの設定を記述するため環境ファイルを作成します。

```sh
$ cp .env.example ./.env
```

### 環境ファイル更新

viでファイルを書き換え保存します。

```sh
$ vi .env
```
MySQLの記述を以下の内容に置きかえます。

```sh
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=airbnb
DB_USERNAME=root
DB_PASSWORD=password
```
DB_PASSWORDは、MySQLの初期設定で設定したパスワードを使います。


### マイグレーション

マイグレーション（テーブル作成）を行います。

```sh
$ php artisan migrate
```

### シーダー

シーダーを使ってデータを登録します。

```sh
$ php artisan db:seed --class=RoomsTableSeeder
```

### appキー作成

appキーを作成します。

```sh
$ php artisan key:generate
```

### サーバーを起動する

```sh
$ php artisan serve --port=8080
```

### 画面確認
 
「Preview」の「Preview Running Application」を実行します。
新規ウィンドウが立ち上がり、airbnb風のWEB画面が表示されるので拡大して動作を確認できます。
「Preview」ボタンが表示されない場合は、真ん中上側の▼を押下すると表示されます。

ここまでが、cloud9の環境構築の手順です。

## 1.2. docker（windows/mac環境）

Dockerを使って環境を構築します。
DockerとDocker Composeのインストール方法は省略します。

### gitリポジトリをダウンロード

airbnbstyle配下の資産をダウンロードします。

```sh
$ git clone git@github.com:tomo1833/airbnbstyle.git
```

### 設定ファイル作成

```sh
$ cd airbnbstyle
```

### docker-compose.yml

docker-compose.yml　ファイルを作成し、以下の内容を記載します。

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
            - ./default.conf:/etc/nginx/conf.d/default.conf
            - ./airbnbstyle/:/var/www/html
    app:
        build: .
        depends_on:
            - mysql
        volumes:
            - ./airbnbstyle/:/var/www/html

    mysql:
        image: mysql:5.7
        environment:
            MYSQL_DATABASE: airbnb
            MYSQL_USER: airbnb
            MYSQL_PASSWORD: password
            MYSQL_ROOT_PASSWORD: password
        ports:
            - "3306:3306"
        volumes:
            - ./data:/var/lib/mysql

```

### Dockerfile

php用のDockerfile　ファイルを作成し、以下の内容を記載します。

```dockerfile
FROM php:7.3-fpm

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

### default.conf

nginx用のdefault.conf　ファイルを作成し、以下の内容を記載します。

```sh
server {
    listen 80;

    root  /var/www/html/public;
    index index.php index.html;

    access_log /var/log/nginx/access.log;
    error_log  /var/log/nginx/error.log;
    
    location / {
        try_files $uri $uri/ /index.php$is_args$args;
    }


    location ~ \.php$ {
          fastcgi_split_path_info ^(.+\.php)(/.+)$;
          fastcgi_pass   app:9000;
          fastcgi_index  index.php;
          include        fastcgi_params;
          fastcgi_param  SCRIPT_FILENAME $document_root$fastcgi_script_name;
          fastcgi_param  PATH_INFO $fastcgi_path_info;
      }
}
```

### dokcer コンテナ作成

docker-compose.ymlファイルがある場所でdokcerイメージのビルドとコンテナの作成を行います。

```sh
$ docker-compose up -d
```

docker-compose upは作成と起動を一緒に実施します。

-dはオプションで デーモン（バックグラウンドで動作する）です。

### composer更新

docker appのコンテナにログインします。

```sh
$ docker-compose exec app sh
```
composerの更新をします。
docker appのコンテナの中で実施します。

```sh
$ composer update
```

### .env修正

設定ファイルの更新をします。
docker appのコンテナの中で実施します。

```sh
$ cp .env.example ./.env
```
設定ファイルの内容を書き換えて保存します。
docker appのコンテナの中で実施します。

```sh
$ vi .env
```

```sh
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=airbnb
DB_USERNAME=root
DB_PASSWORD=password
```

### マイグレーション

マイグレーション（テーブル作成）を行います.
docker appのコンテナの中で実施します。

```sh
$ php artisan migrate
```

### シーダー

シーダーを使ってデータを登録します。
docker appのコンテナの中で実施します。

```sh
$ php artisan db:seed --class=RoomsTableSeeder

```

### appキー作成

appキーを作成します。
docker appのコンテナの中で実施します

```sh
$ php artisan key:generate
```

## ブラウザ起動

http://localhost:8000 でアクセスして画面が表示されることを確認します。


## 1.3. 対象バージョン

今回利用する主要なソフトウェアのバージョンは以下の通りです。

|アプリ|バージョン|
|:--|:--|
|PHP|7.3|
|MySQL|5.7|

## 2. ER図


![ER図](https://user-images.githubusercontent.com/61913268/81294929-8cd79480-90aa-11ea-9742-f917c26a7e46.png)

本アプリケーションで使用するデータベースのER図です。


### 部屋（ルーム）管理テーブル

店舗を管理するテーブル(hops)です。

| カラム名 | カラム名（物理）| 属性 | 初期値 | 備考 |
| ---- | ---- | ---- | ---- | ---- |
| ID | id | | | |
| 名前 | name | | | |
| 住所 | address | | | |
| 部屋説明 | description | | | |
| 部屋画像 | image_url | | | |
| 料金 | price | | | |
| 大人人数 | adult | | | |
| 幼児人数 | children | | | |
| 作成日時 | created_ad | | | |
| 更新日時 | updated_ad | | | |

### 予約管理テーブル

予約を管理するテーブル（reservs)です。

| カラム名 | カラム名（物理）| 属性 | 初期値 | 備考 |
| ---- | ---- | ---- | ---- | ---- |
| ID | id | | | |
| 部屋ID | room_id | | | |
| ユーザーID | user_id | | | |
| 大人人数 | adult | | | |
| 幼児人数 | children | | | |
| 料金 | price | | | |
| 開始年月日 | start_date | | | |
| 終了年月日 | end_date | | | |
| 作成日時 | created_ad | | | |
| 更新日時 | updated_ad | | | |

※店舗IDとユーザーIDは外部キーを使用します。
  
### レビュー管理テーブル
  
レビュー（口コミ）を管理するテーブル（reviews)です。  

| カラム名 | カラム名（物理）| 属性 | 初期値 | 備考 |
| ---- | ---- | ---- | ---- | ---- |
| ID | id | | | |
| 部屋ID | room_id | | | |
| ユーザーID | useer_id | | | |
| コメント | comment | | | |
| 作成日時 | created_ad | | | |
| 更新日時 | updated_ad | | | |

## 3. 機能の概要

本アプリケーションは以下の画面があります。

* トップ画面
* 検索結果画面
* ルーム画面
* ログイン画面(Laravelが提供する機能)
* 会員（ユーザー）登録画面(Laravelが提供する機能)

### トップ画面

最初に表示される画面です。
ロケーション（場所）、チェックイン、チェックアウト（滞在期間）、人数で検索できます。

![TOP画面](https://user-images.githubusercontent.com/61913268/81470422-a81adf00-9225-11ea-9480-7353622d7d02.gif)


### 検索結果画面

検索結果の画面です。
トップ画面で指定した条件に合致するルームを表示します。
また、Google mapを表示します。

![検索画面](https://user-images.githubusercontent.com/61913268/81470525-22e3fa00-9226-11ea-90b4-cad11e51f328.gif)

### ルーム画面

検索結果画面で指定したルームの画面です。
ルームの確認、予約状況の確認、予約の登録、口コミの登録ができます。

![ルーム画面](https://user-images.githubusercontent.com/61913268/81470575-62124b00-9226-11ea-8cbd-a72d57a4c535.gif)


### ログイン画面

会員のログイン画面です。
会員のログインができます。
本アプリでは、Laravelが提供する機能を使ってログイン画面を表示します。

### 会員登録画面

会員の登録画面です。
会員の登録ができます。
本アプリでは、Laravelが提供する機能を使って会員の登録画面を表示します。


## 4. ライブラリに関して

本アプリで利用したライブラリを以下に記載します。

|ライブラリ|バージョン|備考|
|:--|:--|:--|
|composer|1.10.5|php用ライブラリ管理ツール|
|bootstrap|4|cssフレームワークを採用しています。cdnを利用しています。|
