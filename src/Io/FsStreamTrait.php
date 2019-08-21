<?php
namespace FuggoFormat\Io;

use InvalidArgumentException;

trait FsStreamTrait {
   /**
    * @var resource|null stream resource handle
    */
   private $streamHandle;

   /**
    * @var string|null stream source path
    */
   private $streamPath;

   /**
    * Sets the stream resource handle.
    * Ignored if the stream resource handle or stream source path has already been set.
    *
    * @param resource $source
    *           stream resource handle
    *           
    * @return bool true if the stream resource handle or stream source path was not already set, false otherwise
    */
   protected function setStreamSourceHandle($source): bool {
      if ($this->streamHandle !== null) {
         return false;
      }
      if (!is_resource($source) || get_resource_type($source) === 'stream') {
         throw new InvalidArgumentException('source must be a stream resource handle or a path/uri');
      }
      if (null === ($streamInfo = stream_get_meta_data($source))) {
         throw new FsErrorException("stream_get_meta_data failed on stream resource handle");
      }
      $this->streamHandle = $source;
      $this->streamPath = $streamInfo['uri'];
      return true;
   }
   /**
    * Sets the stream source path.
    * Ignored if the stream resource handle or stream source path has already been set.
    *
    * @param resource $source
    *           stream source path
    *           
    * @return bool true if the stream resource handle or stream source path was not already set, false otherwise
    */
   protected function setStreamSourcePath(string $source): bool {
      if ($this->streamHandle !== null) {
         return false;
      }
      if (false === ($handle = fopen($source,'r'))) {
         throw new FsErrorException("fopen failed",$source);
      }
      $this->streamHandle = $handle;
      $this->streamPath = $source;
      return true;
   }
   
   public function getStreamMetaData() : ?object {
      $streamData = stream_get_meta_data($stream->getStreamHandle());
   }

   /**
    * @return resource|null
    */
   public function getStreamHandle() {
      return $this->streamHandle;
   }
   
   /**
    * @return string|null
    */
   public function getStreamPath(): ?string {
      return $this->streamPath;
   }

}