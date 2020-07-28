<?php

namespace Framework;

interface IMiddleware
{
    public function handle(): ?Response;
}
