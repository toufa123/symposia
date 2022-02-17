<?php

namespace DynamicOOOS\TelegramBot\Api\Types;

use DynamicOOOS\TelegramBot\Api\BaseType;
use DynamicOOOS\TelegramBot\Api\InvalidArgumentException;
use DynamicOOOS\TelegramBot\Api\TypeInterface;
/**
 * Class PhotoSize
 * This object represents one size of a photo or a file / sticker thumbnail.
 *
 * @package TelegramBot\Api\Types
 */
class PhotoSize extends BaseType implements TypeInterface
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
    protected static $map = ['file_id' => \true, 'width' => \true, 'height' => \true, 'file_size' => \true];
    /**
     * Unique identifier for this file
     *
     * @var string
     */
    protected $fileId;
    /**
     * Photo width
     *
     * @var int
     */
    protected $width;
    /**
     * Photo height
     *
     * @var int
     */
    protected $height;
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
