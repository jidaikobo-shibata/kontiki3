CREATE TABLE IF NOT EXISTS information (
	id INTEGER PRIMARY KEY AUTOINCREMENT,
	title TEXT NOT NULL, -- 表題
	content TEXT DEFAULT '' NOT NULL, -- 本文
	slug TEXT UNIQUE NOT NULL, -- 自分自身のURL。UNIQUEなので自動index
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- 記事作成日
	updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- 最終更新日
	deleted_at TIMESTAMP NULL, -- ソフトデリート日時（NULLなら削除されていない）
	is_draft INTEGER DEFAULT 0 NOT NULL -- 記事のステータス（0: 公開, 1: 下書き）
);
