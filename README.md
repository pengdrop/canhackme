[![Rawsec's CyberSecurity Inventory](https://inventory.rawsec.ml/img/badges/Rawsec-inventoried-FF5050_flat.svg)](https://inventory.rawsec.ml/ctf_platforms.html#CanHackMe)
[![GitHub stars](https://img.shields.io/github/stars/safflower/canhackme.svg)](https://github.com/safflower/canhackme/stargazers)
[![GitHub license](https://img.shields.io/github/license/safflower/canhackme.svg)](https://github.com/safflower/canhackme/blob/master/LICENSE)

# CanHackMe

![main](https://i.imgur.com/xdRTHZ5.png)

CanHackMe is jeopardy CTF platform.

This platform tested on `Ubuntu 16.04` + `Apache 2.4` + `PHP 7.3`.

<https://canhack.me>

---

## How to set-up?

1. Install `Apache 2.4`.
     `.htaccess` file is not available with other software.

2. Install `PHP 7.3`.
     Lower versions are not supported.

3. Install `php-sqlite3` and `php-mbstrings` modules.

4. Set permission to access SQLite database file (default: `/@import/confs/.common.db`).

5. Modify `/@import/confs/common.php`, `.hash_salt.txt`, `.recaptcha_secretkey.txt`, `.wechall_authkey.txt` file.
     Make sure to change the hash salt (`__HASH_SALT__`) to a long random string.
     Don't make it public.

6. Register an account of administrator at the website.

7. You must access the sqlite database directly to add notifications and challenges.

