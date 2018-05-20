<?php

namespace App\Model;

use App\Repository\ParamRepository;

class ParamModel
{
    protected $repository;

    public function __construct(ParamRepository $repository)
    {
        $this->repository = $repository;
    }

    public function get(string $code)
    {
        return $this->repository->get($code);
    }
}