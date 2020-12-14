# IndicScript

![Check & Fix Styling](https://github.com/sanskritick/indicscript/workflows/Check%20&%20Fix%20Styling/badge.svg)  ![Tests](https://github.com/sanskritick/indicscript/workflows/Tests/badge.svg)

## Introduction

IndicScript is a transliteration library for Indian languages written in PHP. It supports the most popular Indian scripts and several different romanization schemes. Although IndicScript focuses on Sanskrit transliteration, it has partial support for other Indic scripts and is easy to extend.

## Requirements

IndicScript requires PHP 7.4

## Usage

IndicScript is simple to use.

First install the [Composer](http://getcomposer.org) package manager, then install IndicScript with:

```php
composer require sanskritick/indicscript
```

then invoke IndicScript like this:

```php
<?php

use Sanskritick\Script\IndicScript;

$indicscript = new IndicScript();
$output = $indicscript->transliterate($input, $from, $to);
```

In Laravel 5.5 the package's service provider and facade will be registered automatically. In older versions of Laravel, you must register them manually:

```bash
// config/app.php

'providers' => [
  ...
  Sanskritick\Script\IndicScriptServiceProvider::class,
],

'aliases' => [
  ...
  'IndicScript' => Sanskritick\Facades\IndicScript::class,
],
```

The facade is optional, but the rest of this guide assumes you're using it.

## Laravel Usage

```php
<?php

use Sanskritick\Facades\IndicScript;

$output = IndicScript::transliterate($input, $from, $to);
```

Here, `$from` and `$to` are the names of different **schemes**. In IndicScript, the word "scheme" refers to both scripts and romanizations. These schemes are of two types:

1. **Brahmic** schemes, which are _abugidas_. All Indian scripts are Brahmic schemes.
2. **Roman** schemes, which are _alphabets_. All romanizations are Roman schemes.

The list of all **Brahmic** and **Roman** schemes supported are available here [Schemes](Schemes.md)

### Disabling transliteration

When IndicScript sees the token `##`, it toggles the transliteration state:

```bash
    $indicscript->transliterate('ga##Na##pa##te', 'hk', 'devanagari'); // गNaपte
    $indicscript->transliterate('ध##र्म##क्षेत्रे', 'devanagari', 'hk'); // dhaर्मkSetre
```

When IndicScript sees the token `\`, it disables transliteration on the character that immediately follows. `\` is used for ITRANS compatibility; we recommend always using `##` instead.

```bash
    $indicscript->transliterate('a \\a', 'itrans', 'devanagari'); // अ a
    $indicscript->transliterate('\\##aham', 'itrans', 'devanagari'); // ##अहम्
```

### Transliterating to lossy schemes

A **lossy** scheme does not have the letters needed to support lossless translation. For example, Bengali is a lossy scheme because it uses `ব` for both `ba` and `va`. In future releases, IndicScript might let you choose how to handle lossiness. For the time being, it makes some fairly bad hard-coded assumptions. Corrections and advice are always welcome.

### Transliteration options

You can tweak the transliteration function by passing an `options` array:

```bash
$output = $indicscript->transliterate($input, $from, $to, $options);
```

`$options` maps options to values. Currently, these options are supported:

- `skip_sgml` - If TRUE, transliterate SGML tags as if they were ordinary words (`<b>iti</b>` → `<ब्>इति</ब्>`). Defaults to `FALSE`.
- `syncope` - If TRUE, use Hindi-style transliteration (`ajay` → `अजय`). In linguistics, this behavior is known as [schwa syncope](http://en.wikipedia.org/wiki/Schwa_deletion_in_Indo-Aryan_languages). Defaults to `FALSE`.

## Adding new schemes

Adding a new scheme is simple:

```bash
$indicscript->addBrahmicScheme($schemeName, $schemeData);
$indicscript->addRomanScheme($schemeName, $schemeData);
```

For help in creating `$schemeData`, see the comments on the `addBrahmicScheme` and `addRomanScheme` functions.
