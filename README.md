# LGGR

Lggr is a PHP error logging class developed for simple, quick, searchable logging. It focuses on ease of use with the intent of promoting more logging.

## Features
- Set up the class somewhere global, do all your logging with one-line calls.
- Logs are written in a standard format making it easy for you to read through them.
- Error file and line numbers provided to assist in finding what happened.

## "Hello, world"
```php
<?php

include ("lggr.php");
$log = new lggr($_SYSTEM["DOCUMENT_ROOT"]."/logs/");

// This will be written to the default "eventlog.log" file
$log->logEmergency("OOOOH SNAP! Foo::bar() exploded due to bad values of something.");
```

## Logging Basics
### Setup
```php

include ("lggr.php");
$log = new lggr($_SYSTEM["DOCUMENT_ROOT"]."/logs/");
```
After including lggr, instantiate it. You need to provide one parameter, a full path to the folder you want to store your log files in. Include the trailing slash.

### Basic Use
```php
<?php

$log->logNotice("User attempted to access forbidden areas.");
```
Log levels are defined with the method name you use. Just prefix your log level with "log". Lggr doesn't force you to use any particular log levels, rather it's up to you to choose what makes sense. Common log levels include

```php
<?php
$log->logNotice("message");
$log->logWarning("message");
$log->logError("message");
$log->logCritical("message");
$log->logEmergency("message");
```

### Custom files
```php

$log->file("email")->logWarning("This is written to a file called email.log");
```
Lggr allows you to easily define a different file to write a log item into, just call the file() method before your log[Level]() method. If the file doesn't exist it will be created when the log is saved.

## Log Output
Half the trouble with ad-hoc logs is searching. Lggr uses a format derived from *NIX based logs.

[Date Time] [Level] [User IP]   [File path & line number]   [Message]
```
[09/12/12 04:11:30]	WARNING	127.0.0.1	/lggr/examples/index.php:5	This is an example of a warning log.
[09/12/12 04:11:30]	EMERGENCY	127.0.0.1	/examples/index.php:6	This is an emergency line in a different file.
```

## Design Goals
Lggr was designed to scratch my own itch. I grew tired of always reinventing the wheel whenever I needed to keep track of actions within the micro sites I built, and I began to slow myself down with inconsistent log formats. I work on a lot of small, one-off projects, so I wanted a utility that was lightweight and very easy to use. The easier it is to use, the more likely I will be to use it frequently.