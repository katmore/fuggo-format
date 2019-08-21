<?php
namespace FuggoFormat\Lexer;

use Psr\EventDispatcher;

interface LineListenerProviderInterface extends EventDispatcher\ListenerProviderInterface {
   public function addListener(callable $listener);
}