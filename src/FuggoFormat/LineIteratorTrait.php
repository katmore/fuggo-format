<?php
namespace FuggoFormat;

trait LineIteratorTrait {
   abstract public function nextLine(): ?string;
   abstract public function endOfLines(): bool;
   /**
    * @var string|null
    */
   private $line;
   private $lineIdx;
   final public function current(): ?string {
      return $this->line;
   }
   final public function key(): ?int {
      return $this->lineIdx;
   }
   final public function next(): void {
      if (null!==($this->line = $this->nextSourceLine())) {
         $this->lineIdx++;
      }
   }
   final public function rewind(): void {
      $this->lineIdx = null;
   }
   final public function valid(): bool {
      return $this->lineIdx!==null;
   }
}