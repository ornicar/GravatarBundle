<?php

namespace Ornicar\GravatarBundle;


class GravatarImage
{
    private $content;
    private $size;
    private $type;

    public function __construct($content, $size, $type)
    {
        $this->content = $content;
        $this->size = $size;
        $this->type = $type;
    }


    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param mixed $size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }
} 