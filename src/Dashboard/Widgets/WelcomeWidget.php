<?php

namespace Bozboz\Admin\Dashboard\Widgets;

use Bozboz\Permissions\RuleStack;
use Illuminate\Support\Facades\Auth;

class WelcomeWidget extends CardWidget
{
    public function getBody()
    {
        $user = Auth::user();
        $profileLink = '';
        if (RuleStack::with('edit_profile', $user)->then('edit_anything')->isAllowed()) {
            $profileLink = "<a href = '/admin/users/{$user->id}/edit' class='btn btn-link' ><i class='fa fa-wrench' ></i > Edit Profile </a> | ";
        }
        return <<<HTML
<h1>Hello, {$user->first_name}</h1>
{$profileLink}
<a href = '/admin/logout' class="btn btn-link"><i class='fa fa-sign-out' ></i > Logout </a>
HTML;

    }
}
