<?php

namespace App\Model;

use App\Repository\ParamRepository;

final class Param
{
    private $repository;

    public function __construct(ParamRepository $repository)
    {
        $this->repository = $repository;
    }

    public function get(string $code)
    {
        return $this->repository->get($code);
    }
}