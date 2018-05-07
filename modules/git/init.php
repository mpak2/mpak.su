<? # modules/git/init.php

# CREATE TABLE mp_git_index(id INTEGER PRIMARY KEY,`time` INTEGER,`name` TEXT,`commit` TEXT,`abbreviated_commit` TEXT,`tree` TEXT,`abbreviated_tree` TEXT,`parent` TEXT,`abbreviated_parent` TEXT,`refs` TEXT,`encoding` TEXT,`sanitized_subject_line` TEXT,`body` TEXT,`commit_notes` TEXT,`verification_flag` TEXT,`signer` TEXT,`signer_key` TEXT, `author_id` INTEGER, `git-author` INTEGER)

# CREATE TABLE mp_settings ( `id` INTEGER PRIMARY KEY, `modpath` TEXT, `name` TEXT, `value` TEXT, `aid` INTEGER, `description` TEXT)

mpsettings("{$arg['modpath']}_index", "Версии");
mpsettings("{$arg['modpath']}_author", "Автор");
