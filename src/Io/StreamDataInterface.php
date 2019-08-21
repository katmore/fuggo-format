<?php
namespace FuggoFormat\Io;

interface StreamDataInterface {
   public function getStreamDataAsArray() : array;
   public function setStreamDataFromArray(array $streamData);
}