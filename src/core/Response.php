<?php

namespace app\core;

class Response
{
    private array $objects;
    private string $view;
    private string $area = '';
    private string $action = '';
    private string $msg;

    /**
     * @param array $objects
     * @param string $area
     * @param string $action
     * @param string $view
     * @param string $msg
     */
    public function __construct(array $objects, string $view, string $msg = '')
    {
        $this->objects = $objects;
        $this->view = $view;
        $this->msg = $msg;
    }

    public function getObjects(): array
    {
        return $this->objects;
    }

    public function getArea(): string
    {
        return $this->area;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function getMsg(): string
    {
        return $this->msg;
    }

    public function getView(): string
    {
        return $this->view;
    }

    public function setMsg(string $msg): void
    {
        $this->msg = $msg;
    }

    public function setArea(string $area): void
    {
        $this->area = $area;
    }

    public function setAction(string $action): void
    {
        $this->action = $action;
    }
}
