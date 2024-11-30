# Kontiki3 CMS Project Guide

## Table of Contents
1. [アプリケーション構成](#アプリケーション構成)
2. [Routingのルール](#routingのルール)
3. [継承可能なクラスとその役割](#継承可能なクラスとその役割)
4. [実装に必要なメソッドと要件](#実装に必要なメソッドと要件)
5. [データベース要件](#データベース要件)

---

## 1. アプリケーション構成
`kontiki3/project/apps` にアプリケーションフォルダを設置します。以下は`sample`アプリケーションの例です：

kontiki3/project/apps/sample
+ controller.php
+ model.php
+ option.php
+ views/
  + list.php
  + item.php
+ admin/
  + controller.php
  + model.php
  + option.php
  + views/
    + list.php
    + item.php
+ route.php
+ sql/
  + sample.sql

## Routingのルール

route.php はリクエストパスに基づいて対応するコントローラとメソッドを定義する連想配列です。

- キー：リクエストパスを指定します。各パスに応じて対応するコントローラとメソッドが定義されます。
- コントローラ：リクエストに対応するコントローラの完全修飾クラス名を指定します。
- メソッド：コントローラ内のメソッド名を指定します。
- 引数：%d（整数）や%s（文字列）をプレースホルダとして利用し、リクエストパスから引数として受け取ります。

```
return [
	'/sample/' => [
		'controller' => 'Kontiki3\Sample\Controller',
		'method' => 'actionList'
	],
	'/sample/%s' => [
		'controller' => 'Kontiki3\Sample\Controller',
		'method' => 'actionItem'
	],
	'/sample/admin/edit/%d' => [
		'controller' => 'Kontiki3\Sample\Admin\Controller',
		'method' => 'actionEdit'
	],
	'/sample/admin/delete/%d' => [
		'controller' => 'Kontiki3\Sample\Admin\Controller',
		'method' => 'actionHardDelete'
	],
];
```

## オートローダについて

このCMSでは、`kontiki3/project/apps`内のクラスと、それ以外のクラスで異なるオートロードの規則が適用されています。

1. `kontiki3/project/apps` 内のクラス
   - `Kontiki3\<ApplicationFolderName>\path\to\ClassName` の形式でアクセスします。
   - 例：`Kontiki3\Sample\Controller` は `kontiki3/project/apps/sample/controller.php` に対応します。

2. その他のクラス
   - パスをそのまま完全修飾クラス名として利用しますが、`classes`ディレクトリのディレクトリ名は含みません。
   - 例：`Kontiki3\Core\Input` は `kontiki3/core/classes/input.php` に対応します。

この仕組みにより、クラスのパスに基づいて自動的に適切なファイルが読み込まれるため、ファイルを直接インクルードする必要がありません。

## 継承可能なクラスとその役割

### Core\Models\Base\Model

`Core\Models\Base\Model` クラスは、CMSでの標準的なCRUD操作とデータ検証・フィルタリングのための抽象クラスです。

---

#### __construct()

データベース接続を初期化します。

- 引数: なし
- 戻り値: なし

---

#### getProperties()

モデルのプロパティを取得します。

- 引数: なし
- 戻り値: モデルで定義されている`$properties`の配列

---

#### getLastInsertId()

最後に挿入された行のIDを取得します。

- 引数: なし
- 戻り値: 挿入された行のID、または挿入がない場合は空文字列

---

#### filterAllowedFields(array $data, array $allowedFields)

指定されたデータ配列から、許可されたフィールドのみを含むようにフィルタリングします。

- $data: フィルタリング対象のデータ配列
- $allowedFields: 許可されたフィールドのリスト
- 戻り値: フィルタリングされたデータ配列

---

#### validateData(array $data, bool $isEdit, int $id = null)

入力データを、モデルのプロパティルールに基づいて検証します。新規作成または編集の検証に使用されます。

- $data: 検証対象のデータ配列
- $isEdit: 編集モードの場合は`true`、新規作成の場合は`false`
- $id: 編集時に、ユニーク性チェックで除外するレコードID（任意）
- 戻り値: 検証に失敗した場合はエラーの配列、成功した場合は`true`

---

#### isUnique(string $field, $value, int $excludeId = null)

データベース内で特定のフィールドの値がユニークかどうかをチェックします。

- $field: チェック対象のフィールド名
- $value: チェック対象の値
- $excludeId: 編集時にチェックから除外するレコードID（任意）
- 戻り値: ユニークであれば`true`、そうでなければ`false`

---

#### getTotalItems()

テーブル内の全レコードの数を取得します。

- 引数: なし
- 戻り値: テーブル内のレコード数

---

#### getItems(?OptionInterface $option = null)

`OptionInterface`フィルタを使用して、ソートやページネーションを適用したデータを取得します。

- $option: ページネーションやソート条件を含むフィルタオプション（任意）
- 戻り値: 条件に基づいたレコードの配列

---

#### getItemById(int $id, ?OptionInterface $option = null)

指定されたIDの単一レコードを取得します。

- $id: 取得したいレコードのID
- $option: フィルタオプション（任意）
- 戻り値: 該当するレコードの配列、存在しない場合は`null`

---

#### createItem(array $data)

指定されたデータで新しいレコードを作成します。

- $data: 新しいレコードとして挿入するデータ配列
- 戻り値: 成功時には`true`、失敗時には`false`

---

#### updateItem(int $id, array $data)

指定されたIDのレコードを、新しいデータで更新します。更新日時は`CURRENT_TIMESTAMP`で上書きされます。

- $id: 更新対象のレコードID
- $data: 更新するデータ配列
- 戻り値: 成功時には`true`、失敗時には`false`

---

#### hardDelete(int $id)

指定されたIDのレコードをデータベースから完全に削除します。

- $id: 削除対象のレコードID
- 戻り値: 成功時には`true`、失敗時には`false`

### SoftModel Class Overview

`SoftModel` クラスは `Core\Models\Base\Model` を継承し、ソフトデリート機能をサポートしたモデルです。

---

#### __construct()

親クラスのコンストラクタを呼び出し、データベース接続を初期化します。

- 引数: なし
- 戻り値: なし

---

#### getItems(?OptionInterface $option = null): array

指定されたフィルタオプションに基づいて、アイテムのリストを取得します。ソフトデリートされたアイテムの有無も条件に含められます。

- $option: ページネーション、ソート条件、削除状態などを含むフィルタオプション（任意）
- 戻り値: 条件に基づいたアイテムの配列

---

#### getItemById(int $id, Option $option = null)

指定されたIDのアイテムを取得します。オプションで削除状態に応じたフィルタも可能です。

- $id: 取得するアイテムのID
- $option: 削除状態などの条件を含むオプション（任意）
- 戻り値: 指定IDのアイテム情報の配列、見つからない場合は`null`

---

#### getItemByIdForAdmin(int $id)

削除状態に関わらず、指定されたIDのアイテムを取得します（管理者用）。

- $id: 取得するアイテムのID
- 戻り値: 指定IDのアイテム情報の配列、見つからない場合は`null`

---

#### softDelete(int $id)

指定されたIDのレコードをソフトデリート（論理削除）します。`deleted_at`フィールドに削除日時を設定します。

- $id: 削除対象のアイテムID
- 戻り値: 成功時には`true`、失敗時には`false`

---

#### restore(int $id)

ソフトデリートされたアイテムを復元します。`deleted_at`フィールドを`NULL`に設定します。

- $id: 復元対象のアイテムID
- 戻り値: 成功時には`true`、失敗時には`false`

---

#### updateItem(int $id, array $data)

指定されたIDのアイテムを、新しいデータで更新します。更新対象は削除されていないレコードのみで、`updated_at`フィールドが更新されます。

- $id: 更新対象のアイテムID
- $data: 更新するデータ配列
- 戻り値: 成功時には`true`、失敗時には`false`
