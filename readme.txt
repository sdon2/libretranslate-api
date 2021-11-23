Laravel Package for Adding Translation Capabilities using LibreTranslate
========================================================================

1. Installation:
composer require sdon2/libretranslate-api

2. Publish:
php artisan vendor:publish

3. Migrate:
After publishing you must migrate. (Make sure don't have a table named 'libretranslate_translations')

4. Check configuration in config/libretranslate.php

5. For translations use any of these servers (or) self host your server: https://github.com/LibreTranslate/LibreTranslate#mirrors