<?php

namespace Raketa\BackendTestTask\View\Interface;

interface ViewInterface
{
    public function toArray(mixed $toArrayData): array;
}