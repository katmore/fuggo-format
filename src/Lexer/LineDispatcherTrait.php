<?php
namespace FuggoFormat\Lexer;

trait LineDispatcherTrait {
   abstract protected function getProvider(): LineListenerProviderInterface;
   public function dispatch(Line $event): Line {
      foreach ($this->getProvider()
         ->getListenersForEvent($event) as $listener) {
         $listener($event);
      }
      unset($listener);

      return $event;
   }
}