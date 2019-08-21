<?php
declare(strict_types = 1);

namespace FuggoFormat\Tests\Unit\Io;

use PHPUnit\Framework\TestCase;

use FuggoFormat\Io as FuggoIo;

class StreamDataTest extends TestCase {
   
   /**
    * @group StreamData
    * @group Io
    */
   public function testBooleanStreamMetaData() {
      
      $streamMetaData = new FuggoIo\StreamMetaData(['timed_out'=>true]);
      $this->assertTrue($streamMetaData->isTimedOut());
      
      $streamMetaData = new FuggoIo\StreamMetaData(['timed_out'=>false]);
      $this->assertFalse($streamMetaData->isTimedOut());
      
      $streamMetaData = new FuggoIo\StreamMetaData(['timed_out'=>'string value']);
      $this->assertNull($streamMetaData->isTimedOut());
      
      $streamMetaData = new FuggoIo\StreamMetaData(['foo'=>false]);
      $this->assertNull($streamMetaData->isTimedOut());
   }
   
}