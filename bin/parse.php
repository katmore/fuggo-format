#!/usr/bin/env php
<?php
if (!isset(array_filter([ __DIR__ . '/../vendor/autoload.php', __DIR__ . '/vendor/autoload.php'],function($autoload) {
   if (is_file($autoload)) {
      require $autoload;
      return true;
   }
})[0])) {
   trigger_error('missing vendor/autoload.php, have you run composer?',E_USER_ERROR);
};

