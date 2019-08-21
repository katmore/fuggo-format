<?php
namespace FuggoFormat\DataPopulate;

use ReflectionObject;
use ReflectionProperty;

trait MappablePropertiesTrait {

   public function mappablePropertiesAsArray() : array {
      $map = [];
      array_filter($this->enumerateMappableProperties(),function(MappableProperty $p) use(&$map) {
         $key = $p->getMapKey();
         $map[$key] = $p->getValue();
      });
      return $map;
   }
   
   public function mapPropertiesFromArray(array $map) {
      array_filter($this->enumerateMappableProperties(),function (MappableProperty $p) use($map) {
         $key = $p->getMapKey();
         if (array_key_exists($key, $map)) {
            $p->setValueIfMatchesDocBlockPropertyType($map[$key]);
         }
      });
   }
   /**
    * @return \FuggoFormat\DataPopulate\MappableProperty[]
    */
   public function enumerateMappableProperties(): array {
      return array_filter(
         array_map(function (ReflectionProperty $p) {
            return new MappableProperty($p,$this);
         },(new ReflectionObject($this))->getProperties()),function (MappableProperty $p) {
            return $p->isMappable();
         });
   }
}