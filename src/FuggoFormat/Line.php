<?php
namespace FuggoFormat;

use Serializable;
use InvalidArgumentException;

class Line implements Serializable {
   /**
    * @var string
    */
   private $line;
   private $lineNo;
   public function serialize(): string {
      return $this->line;
   }
   public function unserialize(string $serialized): void {
      self::__construct($serialized);
   }
   public function __toString() {
      return $this->serialize();
   }
   public function getLineNo(): int {
      return $this->lineNo;
   }
   public function __construct(string $line, int $lineNo = null) {
      if (false !== strpos($line,"\n")) {
         throw new InvalidArgumentException("line cannot contain any newline chars");
      }
      $this->line = $line;
      $this->lineNo = $lineNo !== null ? $lineNo : 0;
   }
}