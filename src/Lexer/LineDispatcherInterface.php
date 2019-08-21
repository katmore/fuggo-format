<?php
namespace FuggoFormat\Lexer;

use Psr\EventDispatcher;

interface LineDispatcherInterface extends EventDispatcher\EventDispatcherInterface {
   public function dispatch(Line $event): Line;
   
}