<?php

namespace packages\Domain\Domain;

interface TargetAccountsRepositoryInterface
{
    public function getAllAccounts();

    public function getRandomAccount();
}
