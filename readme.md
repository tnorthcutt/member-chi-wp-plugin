# Member Chi - WP Plugin

This plugin connects WordPress sites to [Chi](https://app.memberchi.com).
 
## Dev Notes
- Be sure to run `composer install`. `/vendor` is ignored by `git`, but is not ignored by `.distignore`, so building a release includes contents of `/vendor`.
- To build a release:
    - Be sure to bump version number in all locations
        - `member-chi.php`
        - `README.txt`
    - Add `changelog.md` entry
    - Run `wp dist-archive ./` when in the plugin's directory