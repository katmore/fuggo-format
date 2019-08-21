<?php
namespace FuggoFormat;

interface LineIteratorInterface {
   abstract public function nextLine(): ?string;
   abstract public function endOfLines(): bool;
}