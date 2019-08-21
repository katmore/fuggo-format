<?php
namespace FuggoFormat\Io;

use Serializable;

class FsStream implements StreamInterface, Serializable {

   use FsStreamTrait;
   use FsStreamSerializerTrait;

   /**
    * @param resource|string $source
    *           a stream resource handle or a path/uri
    *           
    * @throws \InvalidArgumentException
    * @throws \FuggoFormat\Io\FsErrorException
    */
   public function __construct($source) {
      if (is_string($source)) {
         return $this->setStreamSourcePath($source);
      }
      $this->setStreamSourceHandle($source);
   }
   public function getStreamData(): ?object {
   }

}