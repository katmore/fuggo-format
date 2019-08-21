<?php
namespace FuggoFormat;

use Psr\EventDispatcher;

class LineIteratorDispatcher extends LineDispatcher {
   public function __construct(EventDispatcher\ListenerProviderInterface $provider, LineIteratorInterface $lineIterator) {
      parent::__construct($provider);
      $i=0;
      while(null!==($line = $lineIterator->nextLine())) {
         $i++;
         $this->dispatch(new Line($line,$i));
      }
   }
}