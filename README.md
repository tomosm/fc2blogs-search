課題
===

FC2 BLOG の新着情報 RSS(http://blog.fc2.com/newentry.rdf) 検索システム

## 説明

- cron により5分に一度、FC2 BLOG の新着情報 RSS(http://blog.fc2.com/newentry.rdf) を取得し、MySQLに保存する
- Web ページ上で指定した検索条件に一致したブログの一覧を表示する
- 検索条件は、日付、URL、ユーザー名、サーバー番号、エントリーNo.
- 検索結果の表示内容は日付、URL、タイトル、description
- 検索した時に新着から表示する
- ページャー機能をつける
- エントリーNo.の検索条件は「エントリーNo.が○○以上」という指定を可能にする
- 一度指定した検索条件は Cookie に保存し、次回の訪問時にフォーム内に表示されるようにする
- MySQL に保存したデータは、2週間以上古いデータは自動削除する
- フレームワークは使用しない
- **フレームワークは使用不可**
- **mod_rewrite は使用不可**

## 要件
### データベース・サーバー

- MySQL 5.5+

### PHP
PHP Version 5.5+

- PDO
- [PHPUnit](https://phpunit.de/)

### JavaScript

- [Mocha](https://mochajs.org/)

### ウェブブラウザ

- Firefox - 最新バージョン
- Chrome - 最新バージョン
- Safari - 最新バージョン
- Internet Explorer - IE 9+

## インストール

1. 全ファイルを任意の場所に配備 ex) /tmp/exam0098/
2. MySQL にテーブル(docs/sql/ddl/tables.ddl)を作成
3. セットアップスクリプト(./bin/setup.sh)を実行する
```
ex) $ /bin/bash /tmp/exam0098/bin/setup.sh
**スクリプト実行後の入力内容は以下を参考に環境に合わせて入力する**
セットアップを開始します。 (Y/n) >> y
1. WEB 設定
公開ディレクトリを絶対パスでを入力してください >> /home/exam0098/public_html
...
2. MySQL 設定
HOST を入力してください >> 127.0.0.1
DB NAME を入力してください >> exam0098
USER を入力してください >> exam0098
PASSWORD を入力してください >> xxxx
...
設定は完了しました。
```

## Licence

[MIT](https://opensource.org/licenses/MIT)

## Author

[Tomonori Murakami]
