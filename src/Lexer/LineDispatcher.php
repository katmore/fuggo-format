<?php
namespace FuggoFormat\Lexer;

class LineDispatcher implements LineDispatcherInterface {
   /**
    * @var \FuggoFormat\Lexer\LineListenerProviderInterface
    */
   private $provider;
   
   use LineDispatcherTrait;
   
   protected function getProvider(): LineListenerProviderInterface {
      return $this->provider;
   }
   
   public function __construct(LineListenerProviderInterface $provider) {
      $this->provider = $provider;
   }
}