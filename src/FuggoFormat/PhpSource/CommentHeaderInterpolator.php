<?php
namespace FuggoFormat\PhpSource;

use FuggoFormat\Lexer;

class CommentHeaderInterpolator extends Lexer\LineDispatcher {
   public function __construct(Lexer\LineListenerProviderInterface $interpolatedLineListenerProvider, Lexer\LineListenerProviderInterface $originalLineListenerProvider) {
      
      parent::__construct($interpolatedLineListenerProvider);
      
      $firstLine = true;
      $originalLineListenerProvider->addListener(function(Lexer\Line $line) use(&$firstLine) {
         $token = token_get_all($line);
         if (null===($token = array_shift($token)) && $firstLine) {
            
         }
         
         $this->dispatch($line);
         $firstLine && $firstLine = false;
      });
      
   }
   // public function __construct(LineListenerProvider $interpolatedLineprovider) {
   // parent::__construct($interpolatedLineprovider);
   // $this->dispatch(new Line('test'));
   // }
}