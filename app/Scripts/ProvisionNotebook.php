<?php

namespace App\Scripts;

use App\Notebook;
use Throwable;

class ProvisionNotebook extends Script
{
    /**
     * @var Notebook
     */
    private $notebook;

    public function __construct(Notebook $notebook)
    {
        $this->notebook = $notebook;
    }

    /**
     * @return string
     * @throws Throwable
     */
    public function script(): string
    {
        return view('scripts.notebook.create-notebook', [
            'user'      => $this->notebook->owner,
            'notebook'  => $this->notebook,
            'script' => $this,
        ])->render();
    }

    public function name(): string
    {
        return sprintf("Provisioning Notebook #%s", $this->notebook->id);
    }

    /**
     * @return array|string
     * @throws Throwable
     */
    public function composerBaseConfiguration()
    {
        return view("scripts.composer.composer-json", [
            "id" => $this->notebook->getRouteKey(),
            "username" => $this->notebook->owner->username,
        ])->render();
    }

    /**
     * @return array|string
     * @throws Throwable
     */
    public function containerDockerfile()
    {
        return view("scripts.docker.base-container-dockerfile", [
            "id" => $this->notebook->getRouteKey(),
            "username" => $this->notebook->owner->username,
        ])->render();
    }
}
