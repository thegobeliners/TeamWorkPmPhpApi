<?php

namespace TeamWorkPm;

class Me extends Rest\Model
{
    /**
     * @return \TeamWorkPm\Response\Model
     */
    public function get()
    {
        return $this->rest->get("me");
    }
}