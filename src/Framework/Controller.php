<?php

declare(strict_types=1);

namespace Framework;

abstract class Controller
{
    protected Request $request;
    protected Viewer $viewer;
    public function setRquest(Request $request): void
    {
        $this->request = $request;
    }

    public function setViewer(Viewer $viewer): void
    {
        $this->viewer = $viewer;
    }
}