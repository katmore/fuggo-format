<?php
namespace FuggoFormat\Io;

use Throwable;

class FsErrorException extends StreamException {
   
   const MESSAGE_PREFIX = 'filesystem error: ';

   /**
    * @var string|null path associated with this error
    */
   private $path;
   
   /**
    * @return string|null path associated with this error
    */
   public function getPath(): ?string {
      return $this->path;
   }
   
   /**
    * @return string exception message suffix
    */
   protected function getMessageSuffix(): string {
      return $this->path !== null ? " in {$this->path}" : '';
   }

   /**
    * @param string $errorMessage
    *           error message
    * @param string $path
    *           [optional] path associated with error
    * @param object $data
    *           [optional] data associated with error
    * @param \Throwable $previous
    *           [optional] previous exception for chaining
    */
   public function __construct(string $errorMessage, string $path = null, object $data = null, Throwable $previous = null) {
      $this->path = $path;
      parent::__construct($errorMessage,$data,$previous);
   }
}