# Atte
- 勤怠管理システム
![Atte_top_image](https://github.com/suzuki-miyu79/basic_simulation_project/assets/144597636/c47a8d01-5c8d-4edf-81d3-658ffbcc52e9)

## 作成した目的
- 人事評価のためのアプリケーションです。

## アプリケーションURL
開発環境：http://localhost/

本番環境：http://43.207.106.146/

#### 本番環境テスト用アカウント
- メールアドレス：aka@abc
- パスワード：12345678

## 機能一覧
- 会員登録、ログイン、ログアウト機能
- 打刻機能（勤務開始、勤務終了、休憩開始、休憩終了）
- 検索機能（ユーザー名のキーワード検索）
- 日付別勤怠情報取得、ユーザー別勤怠情報取得機能
- ページネーション機能

## 使用技術（実行環境）
#### プログラミング言語
- フロントエンド：HTML/CSS

- バックエンド：PHP(8.1.2)

#### フレームワーク
- Laravel 10.35.0

#### データベース
- MySQL

## テーブル設計
![table_image](https://github.com/suzuki-miyu79/basic_simulation_project/assets/144597636/1bb54bc1-b7fc-44b1-a562-3df0f91da80e)

## ER図
![er](https://github.com/suzuki-miyu79/basic_simulation_project/assets/144597636/97afd063-8e80-4740-8a47-4a45584774e3)

# 環境構築
#### 1.Laravel Sailをインストール
- Laravel sailをインストールするディレクトリに移動し、Laravel sailをインストールします。
  
　curl -s "https://laravel.build/basic_simulation_project" | bash

#### 2.Laravel sailを起動する
- 「basic_simulation_project」ディレクトリへ移動し、Laravel sailを起動するコマンドを実行します。
  
　cd basic_simulation_project
 
　./vendor/bin/sail up

 #### 3.docker-compose.ymlを編集し、phpMyAdminを追加する
 - 次の設定をdocker-compose.ymlに追加します。

   phpmyadmin:
        image: phpmyadmin/phpmyadmin
        links:
            - mysql:mysql
        ports:
            - 8080:80
        environment:
            MYSQL_USERNAME: '${DB_USERNAME}'
            MYSQL_ROOT_PASSWORD: '${DB_PASSWORD}'
            PMA_HOST: mysql
        networks:
            - sail
