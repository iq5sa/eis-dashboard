<?php

namespace App\Actions;

use TCG\Voyager\Actions\AbstractAction;

class ResetTicketLimitAction extends AbstractAction
{
    public function getTitle()
    {
        return 'Reset limit';
    }

    public function getIcon()
    {
        return 'voyager-refresh';
    }

    public function getPolicy()
    {
        return 'read';
    }

    public function getAttributes()
    {
        return [
            'class' => 'btn btn-sm btn-primary pull-right',
        ];
    }

    public function getDefaultRoute()
    {
        $user_id = $this->data["id"];

        return route("ticket.limit.reset",["user_id"=>$user_id]);
    }

    public function shouldActionDisplayOnDataType()
    {
        return $this->dataType->slug == 'users';
    }

    public function shouldActionDisplayOnRow($row)
    {
        return $row->role_id == 3;
    }
}
