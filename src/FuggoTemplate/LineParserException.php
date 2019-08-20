<?php
namespace FuggoTemplate;

use RuntimeException;
use Throwable;

class LineParserException extends RuntimeException {

   /**
    * @var int column
    */
   private $column;

   /**
    * @var int|null line number
    */
   private $lineNo;

   /**
    * Provides line number of error, if applicable
    *
    * @return int|null line number
    */
   public function getLineNo(): ?int {
      return $this->lineNo;
   }

   /**
    * @return int column number of error
    */
   public function getColumn(): int {
      return $this->column;
   }

   /**
    * @param string $message
    *           error message
    * @param int $column
    *           column number of error
    * @param int $lineNo
    *           [optional] line number of error, if applicable
    * @param \Throwable $previous
    *           [optional] previous exception used for the exception chaining
    */
   public function __construct(string $message, int $column, int $lineNo = null, Throwable $previous = null) {
      $this->lineNo = $lineNo;
      $this->column = $column;
      parent::__construct($message);
   }
}