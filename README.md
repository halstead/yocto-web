Yocto Project WordPress Site
=====================

This package contains the Yocto Project site theme and all related plugins, and implements a WordPress Boilerplate.

### Plugin Installation

Copy the contents of wp-content/mu-plugins and wp-content/plugins to your wp-content directory.

### Theme Building and Installation

1. Install gulp and Bower globally with `npm install -g gulp bower`
2. Navigate to the theme directory, then run `npm install`
3. Run `bower install`
4. To build, run `gulp`

After building, copy the following files from the theme's directory to the wp-content/themes/yocto directory:

```
dist/
lang/
lib/
templates/
404.php
base.php
functions.php
index.php
page.php
proxy.php
ruleset.xml
screenshot.png
search.php
searchform.php
single.php
single-software-item.php
style.css
template-block-builder.php
template-custom.php
```

#### Boilerplate Motivation

Typically, WordPress installations are a spaghetti of the WordPress core, plugins, themes and what have you. This makes upgrading WordPress a pain. The point of this boilerplate is to keep the WordPress core and everything else cleanly separated. This is achieved by using git submodules and some config hacking and Apache redirects :)