<?php

namespace packages\UseCase\Twitter;

interface UserTwitterAccountUseCaseInterface
{
    /**
     * @param $screen_name
     */
    public function deleteHandle($screen_name);
}
