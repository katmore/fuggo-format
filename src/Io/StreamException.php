<?php
namespace FuggoFormat\Io;

use RuntimeException;
use Throwable;

class StreamException extends RuntimeException {
   
   const MESSAGE_PREFIX = 'stream error: ';
   
   /**
    * @var object|null $data data associated with error
    */
   private $data;
   
   /**
    * @var string|null error message
    */
   private $errorMessage;
   
   /**
    * @return object|null data associated with error
    */
   final public function getData(): ?object {
      return $this->data;
   }
   
   /**
    * @return string error message
    */
   final public function getErrorMessage(): string {
      return $this->errorMessage;
   }
   
   /**
    * @return string exception message suffix
    */
   protected function getMessageSuffix() : string {
      return '';
   }
   
   /**
    * @param string $errorMessage
    *           error message
    * @param \Throwable $previous
    *           [optional] previous exception for chaining
    */
   public function __construct(string $errorMessage, object $data = null, Throwable $previous = null) {
      $this->errorMessage = $errorMessage;
      $this->data = $data;
      parent::__construct(static::MESSAGE_PREFIX . $errorMessage . $this->getMessageSuffix());
   }
}