#Content URL Rewriter plugin#
##What is it?##
This plugin will rewrite url's in your content just before it is shown on the page. It will
not alter the data in the database, but will ensure your URL's will all point to your new
website location.
It accomplishes this by checking the URL defined in the database against the variable
defined in the database with the define in de `WP_SITEURL` define.

You can specify alternate domains that need to be renamed by defining a `WP_OLD_URLS` define
which contains a ; separated list of url's to replace as well

##Installation##
Use composer and install it as a plugin or mu-plugin

