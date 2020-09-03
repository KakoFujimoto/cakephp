# CakePHP3コーディング

## アウトライン

- オークションアプリに画像アップローダーとカウントダウンタイマー追加（[DB定義書](https://docs.google.com/spreadsheets/d/1ArKGZ3wbabl3wbAF-Z8NGqVU9kXZNmabJZ3CF93oCo4/edit#gid=0)/[詳細設計書](https://docs.google.com/document/d/1-bsMadw-6Y8pNpBtJJdzMvYpkCoNGJBjXNciJX5Kudc/edit#heading=h.2ku92wj4gass)）



- ユーザー同士の評価機能追加（[DB定義書](https://docs.google.com/spreadsheets/d/1i22HgUykUn5hdNGy3_c2qXfKA_kp-SsIJ4Qp65YfoFw/edit#gid=0)[詳細設計書](https://docs.google.com/document/d/1t7oVXwr0tgAfDz58eBnsfhLHraRQAxvWdUCB1fIZ4Mc/edit))）

## 実装箇所

- 商品の詳細情報の欄を作成...入力時のバリデーションや出力時のエスケープ処理を実装
- オークションの出品画像アップローダーを追加...画像であるかのチェック、同時刻にアップロードがあっても別名で保存
- オークションの終了時刻のカウントダウンタイマー追加...JavaScriptでリアルタイムでオークション終了時までをカウントする

- 取引終了後のページを作成（出品者と落札者しかアクセス不可）
- 上のページで、発送先（名前・住所・電話番号）のやり取りや、発送連絡、受取連絡が可能
- 取引終了時（受取連絡終了時）にしか入力できない、出品者と落札者の評価（数字で１（低）〜５（高）までの数値評価とコメント）入力フォームを作成
- 相手から入力されたレーティング（数字の平均値）と、評価コメントは各ユーザーのページから確認可能


## 参考書籍
[CakePHP超入門](https://www.amazon.co.jp/gp/product/B07CKHQ4KR/)

## 開発環境
```
CakePHP3.8/Docker/MacOSX
```

## docker 起動前の準備

- docker/php/Dockerfile の DOCKER_UID をホストと合わせる

  ```
  # ホストのuidを調べる
  id -u

  # docker/php/Dockerfile の ARG DOCKER_UID=1000 の右辺を↑で調べた値にする
  vim docker/php/Dockerfile
  ```

  - Linux ではこれをやらないとゲスト側で作成したファイルをホスト側で編集できなくなる
  - Mac ではこの手順は不要との説もある
  - Windows の人は WSL (Windows Subsystem for Linux) を使おう

## docker の起動方法

- docker-compose.yml がある場所で下記のコマンドを実行する。初回起動には時間がかかる

  ```
  docker-compose up -d
  ```

## docker の終了方法

- docker-compose.yml がある場所で下記のコマンドを実行する

  ```
  docker-compose down
  ```

## 起動中のコンテナの bash を実行する方法(重要)

- php コンテナの場合

  ```
  docker-compose exec php bash
  ```

  - php コンテナの bash では composer コマンドや ./bin/cake ファイルが実行可能です！

- msyql コンテナの場合

  ```
  docker-compose exec mysql bash
  ```

  - mysql コマンドラインの起動方法

    ```
    # mysql コンテナの bash で
    mysql -u root -p # パスワードは"root"
    ```

## 起動中のコンテナの bash を終了する方法

- コンテナの bash で下記のショートカットキーを入力する

  ```
  ctrl + p + q
  ```

  - コンテナの bash で exit コマンドを打つとコンテナ自体が終了してしまう恐れがある

## php コンテナに cakephp をインストールする方法

- php コンテナの bash で /var/www/html/mycakeapp に移動して

  ```
  composer install
  ```

  - 時間がかかる。質問プロンプトが出たら Y と回答する

    ```
    Set Folder Permissions ? (Default to Y) [Y,n]? Y
    ```

## migration

- php コンテナの bash で /var/www/html/mycakeapp に移動して

  ```
  ./bin/cake migrations migrate
  ```

## ブラウザで テキストに記載されている url にアクセスする方法

- 下記のように読みかえてアクセスする。nginx コンテナ の port とドキュメントルートを設定しているため
  - http://localhost/mycakeapp/hello.html ⇒ http://localhost:10080/hello.html
  - http://localhost/mycakeapp/auction/add ⇒ http://localhost:10080/auction/add

## ブラウザで phpMyAdmin を表示する方法

- http://localhost:10081 にアクセスする
  - root 権限で操作可能

## ブラウザで オークションアプリを表示する方法(課題用のブランチにおいて)

- http://localhost:10080/auction にアクセスする
  - http://localhost:10080/users/add からユーザを作成できる
  - サンプルコードが入っている課題用のブランチでないとアクセスできません

## nginx のドキュメントルートを変更する方法

- docker/nginx/default.conf を編集することで nginx のドキュメントルートを変更可能

  ```diff
  server {
  - root  /var/www/html/mycakeapp/webroot;
  + root  /var/www/html/mylaravelapp/public;
    index index.php index.html;
    ...
  ```

## docker network 上での DB 接続情報

- docker-compose.yml を参照
  - DB ホスト: mysql
  - mysql の port: 3306
  - MYSQL_DATABASE: docker_db
  - MYSQL_ROOT_PASSWORD: root
  - MYSQL_USER: docker_db_user
  - MYSQL_PASSWORD: docker_db_user_pass
