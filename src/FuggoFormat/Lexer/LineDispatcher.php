<?php
namespace FuggoFormat\Lexer;

use Psr\EventDispatcher;

abstract class LineDispatcher implements EventDispatcher\EventDispatcherInterface {
   /**
    * @var \Psr\EventDispatcher\ListenerProviderInterface
    */
   private $provider;
   public function dispatch(Line $event): Line {
      foreach ($this->provider->getListenersForEvent($event) as $listener) {
         $listener($event);
      }
      return $event;
   }
   public function __construct(LineListenerProvider $provider) {
      $this->provider = $provider;
   }
}