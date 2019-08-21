<?php
namespace FuggoFormat\Io;

use FuggoFormat\DataPopulate;

class StreamMetaData implements StreamDataInterface {
   
   use DataPopulate\MappablePropertiesTrait;
   
   /**
    * @var bool 
    * @MappableProperty timed_out
    */
   private $timedOut;

   /**
    * @var bool 
    * @MappableProperty blocked
    */
   private $blocked;

   /**
    * @var bool 
    * @MappableProperty eof
    */
   private $eof;

   /**
    * @var int 
    * @MappableProperty unread_bytes
    */
   private $unreadBytes;

   /**
    * @var string 
    * @MappableProperty stream_type
    */
   private $streamType;

   /**
    * @var string 
    * @MappableProperty wrapper_type
    */
   private $wrapperType;

   /**
    * @var mixed 
    * @MappableProperty wrapper_data
    */
   private $wrapperData;

   /**
    * @var string 
    * @MappableProperty mode
    */
   private $mode;

   /**
    * @var bool 
    * @MappableProperty seekable
    */
   private $seekable;

   /**
    * @var string 
    * @MappableProperty uri
    */
   private $uri;
   public function getStreamDataAsArray(): array {
      return $this->mappablePropertiesAsArray();
   }
   public function setStreamDataFromArray(array $streamData) {
      $this->mapPropertiesFromArray($streamData);
   }
   public function __construct(array $streamMetaData = null) {
      if ($streamMetaData !== null) {
         $this->setStreamDataFromArray($streamMetaData);
      }
   }

   /**
    * @return bool
    */
   public function isTimedOut(): ?bool {
      return $this->timedOut;
   }

   /**
    * @return bool
    */
   public function isBlocked(): ?bool {
      return $this->blocked;
   }

   /**
    * @return bool
    */
   public function isEof(): ?bool {
      return $this->eof;
   }

   /**
    * @return int
    */
   public function getUnreadBytes(): ?int {
      return $this->unreadBytes;
   }

   /**
    * @return string
    */
   public function getStreamType(): ?string {
      return $this->streamType;
   }

   /**
    * @return string
    */
   public function getWrapperType(): ?string {
      return $this->wrapperType;
   }

   /**
    * @return mixed
    */
   public function getWrapperData() {
      return $this->wrapperData;
   }

   /**
    * @return string
    */
   public function getMode(): ?string {
      return $this->mode;
   }

   /**
    * @return bool
    */
   public function isSeekable(): ?bool {
      return $this->seekable;
   }

   /**
    * @return string
    */
   public function getUri(): ?string {
      return $this->uri;
   }
}