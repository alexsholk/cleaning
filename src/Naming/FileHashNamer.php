<?php

namespace App\Naming;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\ConfigurableInterface;
use Vich\UploaderBundle\Naming\NamerInterface;
use Vich\UploaderBundle\Naming\Polyfill\FileExtensionTrait;

/**
 * Генератор имени файла по его содержимому
 */
class FileHashNamer implements NamerInterface, ConfigurableInterface
{
    use FileExtensionTrait;

    protected $algorithm = 'sha1';
    protected $length;

    public function configure(array $options): void
    {
        $options = array_merge(['algorithm' => $this->algorithm, 'length' => $this->length], $options);

        $this->algorithm = $options['algorithm'];
        $this->length    = $options['length'];
    }

    public function name($object, PropertyMapping $mapping): string
    {
        /** @var UploadedFile $file */
        $file    = $mapping->getFile($object);
        $content = file_get_contents($file);
        $name    = hash($this->algorithm, $content);

        if (null !== $this->length) {
            $name = substr($name, 0, $this->length);
        }

        if ($extension = $this->getExtension($file)) {
            $name = sprintf('%s.%s', $name, $extension);
        }

        return $name;
    }
}