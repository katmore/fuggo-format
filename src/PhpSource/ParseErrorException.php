<?php
namespace FuggoFormat\PhpSource;

use RuntimeException;
use Throwable;

class ParseErrorException extends RuntimeException {

   /**
    * @var string|null source of error
    */
   private $source;

   /**
    * @var int|null line number of error
    */
   private $lineNo;

   /**
    * @var string|null error message
    */
   private $errorMessage;

   /**
    * @var object|null $data data associated with error
    */
   private $data;

   /**
    * @return object|null data associated with error
    */
   public function getData(): ?object {
      return $this->data;
   }

   /**
    * @return string|null source of error
    */
   public function getSource(): ?string {
      return $this->source;
   }

   /**
    * @return int|null line number of error
    */
   public function getLineNo(): ?int {
      return $this->lineNo;
   }

   /**
    * @return string error message
    */
   public function getErrorMessage(): string {
      return $this->errorMessage;
   }

   /**
    * @param string $errorMessage
    *           error message
    * @param object $data
    *           [optional] data associated with error
    * @param int $lineNo
    *           [optional] line number of error
    * @param string $source
    *           [optional] source of error
    * @param \Throwable $previous
    *           [optional] previous exception for chaining
    */
   public function __construct(string $errorMessage, object $data = null, int $lineNo = null, string $source = null,
         Throwable $previous = null) {
      $this->errorMessage = $errorMessage;
      $this->lineNo = $lineNo;
      $this->source = $source;
      $this->data = $data;
      parent::__construct('parse error: '.$errorMessage . ($source !== null ? " in $source" : '') .
                           ($lineNo !== null ? " on line $lineNo" : ''),null,$previous);
   }
}