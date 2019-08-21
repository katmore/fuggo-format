<?php
namespace FuggoFormat\Lexer;

class LineListenerProvider implements LineListenerProviderInterface {
   public function getListenersForEvent(object $event): iterable {
      return $this->listener;
   }
   private $listener = [];
   public function addListener(callable $listener) {
      $this->listener []= $listener;
      return $listener;
   }
}