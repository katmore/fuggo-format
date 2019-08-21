<?php
namespace FuggoFormat\PhpSource;

use FuggoFormat\LineDispatcher;
use FuggoFormat\LineListenerProvider;
use FuggoFormat\InterpolatedLine;

class CommentHeaderInterpolator extends LineDispatcher {
   
   public function __construct(LineListenerProvider $interpolatedLineprovider) {
      parent::__construct($provider);
      $this->dispatch(new InterpolatedLine('test'));
   }
   
}