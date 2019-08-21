<?php
namespace FuggoFormat\Io;

trait FsStreamSerializerTrait {
   abstract protected function getStreamPath(): ?string;
   abstract protected function setStreamSourcePath(string $source): void;
   /**
    * @return string path/uri of stream
    */
   public function serialize(): string {
      return (string) $this->getStreamPath();
   }

   /**
    * @param string $serialized
    *           a path/uri
    */
   public function unserialize(string $serialized) {
      $this->setStreamSourcePath($serialized);
   }
}