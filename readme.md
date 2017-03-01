# LangJS: Compile lang files to a js file for use on client side

[![Latest Stable Version](https://poser.pugx.org/awkwardideas/langjs/v/stable)](https://packagist.org/packages/awkwardideas/langjs)
[![Total Downloads](https://poser.pugx.org/awkwardideas/langjs/downloads)](https://packagist.org/packages/awkwardideas/langjs)
[![Latest Unstable Version](https://poser.pugx.org/awkwardideas/langjs/v/unstable)](https://packagist.org/packages/awkwardideas/langjs)
[![License](https://poser.pugx.org/awkwardideas/langjs/license)](https://packagist.org/packages/awkwardideas/langjs)

## Install Via Composer

composer require awkwardideas/langjs

## Add to config/app.php

Under Package Service Providers Add

AwkwardIdeas\LangJS\LangJSServiceProvider::class,

## Build LangJS script file
1. run ```php artisan langjs:build --d js/LangJS.js```
2. Adjust the --d path to whatever you would like under your public directory
3. Include this script file into your layout

* _You must run the build command any time you want to recompile the language file changes. A watchdog may be built in the future but is not currently available_
* _This only compiles the lang files within your resources/lang folder. Vendor lang files are not included currently._

## Javascript commands

* ```_lang(key [, replace, locale])```
  * Same setup as ```@lang('auth.user')```, will retrieve your language for password which you put in your php lang file auth.php.
  * Replace and locale are optional parameters
  * Locale will default to en, if not provided.
  * If key has a plural form, the singular version will be returned, instead of the full term value
  
* ```_choice(key, number [, replace, locale])```
  * Same setup as ```@choice('auth.user',2)```
  * Replace and locale are optional parameters
  * Locale will default to en, if not provided.