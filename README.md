[![Rawsec's CyberSecurity Inventory](https://inventory.raw.pm/img/badges/Rawsec-inventoried-FF5050_flat.svg)](https://inventory.raw.pm/ctf_platforms.html#CanHackMe)
[![GitHub stars](https://img.shields.io/github/stars/safflower/canhackme.svg)](https://github.com/safflower/canhackme/stargazers)
[![GitHub license](https://img.shields.io/github/license/safflower/canhackme.svg)](https://github.com/safflower/canhackme/blob/master/LICENSE)

# CanHackMe

## What's this?

![main](https://i.imgur.com/ItpTYc2.png)

CanHackMe is jeopardy CTF platform.

This platform tested on `Ubuntu 16.04` + `Apache 2.4` + `PHP 7.3`.

<https://canhack.me>

## How to install?

1. Install `Apache 2.4`.
	`.htaccess` file is not available with other software.

2. Install `PHP 7.3`.
	Lower versions are not supported.

3. Install `php-sqlite3` and `php-mbstrings` modules.

4. Set permission to access SQLite database file (default: `/@import/confs/.common.db`).

5. Modify `/@import/confs/common.php`, `.facebook_app_id.txt`, `.twitter_account.txt`, `.recaptcha_sitekey.txt`, `.recaptcha_secretkey.txt`, `.wechall_authkey.txt`, `.hash_salt.txt` file.
	Make sure to change the hash salt to a long random string. Don't make it public.

6. Register an account of administrator at the website.
	And modify `__ADMIN__` constant in `/@import/confs/common.php` file.

7. You must access the sqlite database directly to add notifications and challenges.

8. If you have any questions, feel free to contact me.
