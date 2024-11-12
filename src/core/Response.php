<?php

namespace app\core;

class Response
{
    private array $objects;
    private string $view;
    private string $msg;

    /**
     * @param array $objects
     * @param string $view
     * @param string $msg
     */
    public function __construct(array $objects, string $view, string $msg = '')
    {
        $this->objects = $objects;
        $this->view = $view;
        $this->msg = $msg;
    }

    /**
     * getObjects
     *
     * @return array
     */
    public function getObjects(): array
    {
        return $this->objects;
    }

    /**
     * getMsg
     *
     * @return string
     */
    public function getMsg(): string
    {
        return $this->msg;
    }

    /**
     * getView
     *
     * @return string
     */
    public function getView(): string
    {
        return $this->view;
    }

    /**
     * setMsg
     *
     * @param string $msg
     * @return void
     */
    public function setMsg(string $msg): void
    {
        $this->msg = $msg;
    }
}
