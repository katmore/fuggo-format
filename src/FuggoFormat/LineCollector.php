<?php
namespace FuggoFormat;

use InvalidArgumentException;

class LineCollector implements LineIteratorInterface {
   /**
    * @var string[]
    */
   private $line = [];
   /**
    * @var int
    */
   private $lineIdx = 0;

   use LineIteratorTrait;
   public function nextLine(): ?string {
      return isset($this->line[$this->lineIdx]) ? $this->line[$this->lineIdx] : null;
   }
   public function endOfLines(): bool {
      return $this->lineIdx > count($this->line) - 1;
   }

   /**
    * @param string[] $line
    *           [optional] initial array of lines
    */
   public function __construct(array $line = null) {
      if ($line !== null) {
         array_walk($line,function ($l, $no) {
            if (!is_string($l)) {
               throw new InvalidArgumentException("line element $no is not a string");
            }
         });
      }
   }

   /**
    * @param string $line
    *           line
    */
   public function addLine(string $line): void {
      $this->line[] = $line;
   }
}