<?php

declare(strict_types=1);

namespace Framework;

abstract class Controller
{
    protected Request $request;
    protected TemplateViewerInterface $viewer;
    public function setRquest(Request $request): void
    {
        $this->request = $request;
    }

    public function setViewer(TemplateViewerInterface $viewer): void
    {
        $this->viewer = $viewer;
    }
}