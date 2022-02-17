<?php

namespace DynamicOOOS\TelegramBot\Api\Types;

use DynamicOOOS\TelegramBot\Api\BaseType;
use DynamicOOOS\TelegramBot\Api\InvalidArgumentException;
use DynamicOOOS\TelegramBot\Api\TypeInterface;
/**
 * Class Sticker
 * This object represents a sticker.
 *
 * @package TelegramBot\Api\Types
 */
class Sticker extends BaseType implements TypeInterface
{
    /**
     * {@inheritdoc}
     *
     * @var array
     */
    protected static $requiredParams = ['file_id', 'width', 'height'];
    /**
     * {@inheritdoc}
     *
     * @var array
     */
    protected static $map = ['file_id' => \true, 'width' => \true, 'height' => \true, 'thumb' => PhotoSize::class, 'file_size' => \true];
    /**
     * Unique identifier for this file
     *
     * @var string
     */
    protected $fileId;
    /**
     * Sticker width
     *
     * @var int
     */
    protected $width;
    /**
     * Sticker height
     *
     * @var int
     */
    protected $height;
    /**
     * Document thumbnail as defined by sender
     *
     * @var PhotoSize
     */
    protected $thumb;
    /**
     * Optional. File size
     *
     * @var int
     */
    protected $fileSize;
    /**
     * @return string
     */
    public function getFileId()
    {
        return $this->fileId;
    }
    /**
     * @param string $fileId
     */
    public function setFileId($fileId)
    {
        $this->fileId = $fileId;
    }
    /**
     * @return int
     */
    public function getFileSize()
    {
        return $this->fileSize;
    }
    /**
     * @param int $fileSize
     *
     * @throws InvalidArgumentException
     */
    public function setFileSize($fileSize)
    {
        if (\is_integer($fileSize)) {
            $this->fileSize = $fileSize;
        } else {
            throw new InvalidArgumentException();
        }
    }
    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }
    /**
     * @param int $height
     *
     * @throws InvalidArgumentException
     */
    public function setHeight($height)
    {
        if (\is_integer($height)) {
            $this->height = $height;
        } else {
            throw new InvalidArgumentException();
        }
    }
    /**
     * @return PhotoSize
     */
    public function getThumb()
    {
        return $this->thumb;
    }
    /**
     * @param PhotoSize $thumb
     */
    public function setThumb(PhotoSize $thumb)
    {
        $this->thumb = $thumb;
    }
    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }
    /**
     * @param int $width
     *
     * @throws InvalidArgumentException
     */
    public function setWidth($width)
    {
        if (\is_integer($width)) {
            $this->width = $width;
        } else {
            throw new InvalidArgumentException();
        }
    }
}
