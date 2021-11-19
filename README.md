## How to reproduce bug
```
php -f index.php
```
Output:
```
/home/vitalii/projects/symfony/serializer-bug/index.php:36:
class Vitalii\SerializerBug\dto\UpdateRequest#30 (1) {
  private array $users =>
  *uninitialized*
}
```
<br>

Change `Symfony\Component\Serializer\Normalizer\CustomNormalizer` `denormalize()` method to:
```diff
/**
 * {@inheritdoc}
 */
public function denormalize($data, string $type, string $format = null, array $context = [])
{
    $object = $this->extractObjectToPopulate($type, $context) ?? new $type();
-   $object->denormalize($this->serializer, $data, $format, $context);
-   return $object;
+   return $object->denormalize($this->serializer, $data, $format, $context);
}
```
Output:
```
/home/vitalii/projects/symfony/serializer-bug/index.php:36:
class Vitalii\SerializerBug\dto\UpdateRequest#34 (1) {
  private array $users =>
  array(2) {
    [1] =>
    class Vitalii\SerializerBug\dto\User#35 (1) {
      public $id =>
      string(3) "452"
    }
    [2] =>
    class Vitalii\SerializerBug\dto\User#37 (1) {
      public $id =>
      string(4) "2123"
    }
  }
}
```
