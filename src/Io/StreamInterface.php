<?php
namespace FuggoFormat\Io;

interface StreamInterface {
   public function getStreamHandle();
   public function getStreamData() : ?object;
}