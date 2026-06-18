<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class AsidebarTabsComponent extends Component
{
    private string $userType = '';

    private array $navigationPermissions = [];

    private array $allNavigation = [];

    protected array $tabs = [];


    public function __construct()
    {
        $this->allNavigation = config('navigation');

        $this->navigationPermissions = config('navigationPermissions');

        $this->userType = $this->getUserType();

        $this->setUserTabs();
    }

    private function getUserType(): string
    {
        return Auth::user()->type;
    }

    private function setUserTabs(): void
    {
        $tabs = [];

        $allowedUserNavigation = $this->navigationPermissions[$this->userType];

        foreach ($allowedUserNavigation as $section => $allowedTabs) {
            foreach ($allowedTabs as $tab) {
                $tabs[$section][$tab] = $this->allNavigation[$section][$tab];
            }
        }

        $this->tabs = $tabs;
    }

    public function render(): View|Closure|string
    {
        return view('components.asidebar-tabs-component')
            ->with('tabs', $this->tabs);
    }
}
