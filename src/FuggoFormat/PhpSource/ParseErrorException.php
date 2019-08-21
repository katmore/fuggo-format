<?php
namespace FuggoFormat\PhpSource;

use RuntimeException;
use Throwable;

class ParseErrorException extends RuntimeException {
   private $source;
   private $lineNo;
   private $errorMessage;
   public function __construct(string $errorMessage, int $lineNo=null, string $source=null, Throwable $previous=null) {
      $this->errorMessage = $errorMessage;
      $this->lineNo = $lineNo;
      $this->source = $source;
      parent::__construct($errorMessage.($source!==null?" in $source":'').($lineNo!==null?" on line $lineNo":''),null,$previous);
   }
}