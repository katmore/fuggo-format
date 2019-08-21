<?php
namespace FuggoFormat;

use Psr\EventDispatcher;

class LineListenerProvider implements EventDispatcher\ListenerProviderInterface {
   public function getListenersForEvent(object $event): iterable {
      return $this->listener;
   }
   private $listener = [];
   public function addListener(callable $listener) {
      $this->listener []= $listener;
   }
}