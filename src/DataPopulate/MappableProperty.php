<?php
namespace FuggoFormat\DataPopulate;

use ReflectionProperty;
use phpDocumentor\Reflection\DocBlockFactory;
use phpDocumentor\Reflection\DocBlock;

class MappableProperty {
   
   use DocBlockTypeTrait;
   /**
    * @var \phpDocumentor\Reflection\DocBlockFactory
    */
   private static $factory;

   /**
    * @return \phpDocumentor\Reflection\DocBlockFactory
    */
   final protected static function getDocBlockFactory(): DocBlockFactory {
      if (static::$factory === null) {
         static::$factory = DocBlockFactory::createInstance();
      }
      return static::$factory;
   }
   public function getMapKey(): ?string {
      if (null === ($tag = $this->getMappablePropertyTag())) return null;

      if (empty($tag = trim($tag))) {
         return $this->getReflectionProperty()
            ->getName();
      }

      return $tag;
   }
   public function getValue() {
      $this->getReflectionProperty()->setAccessible(true);
      return $this->getReflectionProperty()->getValue($this->object);
   }
   public function setValueIfMatchesDocBlockPropertyType($value) :bool {
      if ($this->valueMatchesDocBlockPropertyType($value)) {
         $this->getReflectionProperty()->setAccessible(true);
         $this->getReflectionProperty()->setValue($this->object, $value);
         return true;
      }
      return false;
   }
   /**
    * @var \phpDocumentor\Reflection\DocBlock\Tag
    */
   private $mappablePropertyTag;
   private $searchedMappablePropertyTag = false;
   protected function getMappablePropertyTag(): ?DocBlock\Tag {
      if ($this->mappablePropertyTag === null) {
         if (!$this->searchedMappablePropertyTag) {
            $docblock = static::getDocBlockFactory()->create($this->getReflectionProperty()
               ->getDocComment());
            $tag = $docblock->getTagsByName('MappableProperty');
            if (null !== ($tag = array_shift($tag))) {
               $this->mappablePropertyTag = $tag;
            }
            $this->searchedMappablePropertyTag = true;
         }
      }
      return $this->mappablePropertyTag;
   }
   private $mappable;
   public function isMappable(): bool {
      if ($this->mappable === null) {
         $this->mappable = $this->getMappablePropertyTag() !== null;
      }
      return $this->mappable;
   }
   public function getReflectionProperty(): ReflectionProperty {
      return $this->reflectionProperty;
   }
   private $varType = null;
   /**
    * @return string[]
    */
   protected function enumerateDocBlockPropertyTypes(): array {
      if ($this->varType === null) {
         if (!empty($c = $this->getReflectionProperty()
            ->getDocComment())) {
            if (!empty(
               $type = array_unique(
                  array_reduce(static::getDocBlockFactory()->create($c)
                     ->getTagsByName('var'),
                     function ($carry, $item) {
                        return array_merge($carry,explode('|',current(explode(' ',$item->__toString()))));
                     },[])))) {
               $this->varType = $type;
            }
         }
         $this->varType = [
            'mixed'
         ];
      }
      return $this->varType;
   }
   public function valueMatchesDocBlockPropertyType($value): bool {
      return !empty(
         array_filter($this->enumerateDocBlockPropertyTypes(),
            function ($type) use ($value) {
               return static::valueIsDocBlockType($value,$type);
            }));
   }
   
   /**
    * @var \ReflectionProperty
    */
   private $reflectionProperty;
   /**
    * @var object
    */
   private $object;
   /**
    * @param
    *           \ReflectionProperty
    */
   public function __construct(ReflectionProperty $p, object $object) {
      $this->reflectionProperty = $p;
      $this->object = $object;
   }
}