<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\MenuMenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MenuController extends Controller
{
    public function index()
    {
        $pageTitle = 'Menu Management';
        $activeTemplate = $this->activeTemplate;

        $menus = Menu::searchable(['name'])
                ->withCount([
                    'items as items_count' => function ($query) use ($activeTemplate) {
                        $query->where('status', Status::ENABLE)
                            ->where('tempname', $activeTemplate);
                    }
                ])
                ->get();

        return view('Admin::menu.index',compact('pageTitle','menus'));
    }


    public function assignMenuItem($id)
    {
        $menu = Menu::findOrFail($id);
        $items = MenuItem::where('tempname',activeTemplate())->where('status',Status::ENABLE)->get();
        $activeTemplate = $this->activeTemplate;

        $assigned = Menu::with([
            'items' => function ($query) use ($activeTemplate) {
                $query->where('status', Status::ENABLE)
                    ->where('tempname', $activeTemplate);
            }
        ])->findOrFail($id);

        $pageTitle = $menu->name . ' - Assign Menu Items';
        $menus = Menu::searchable(['name'])->latest()->paginate(getPaginate());
        return view('Admin::menu.assign_item',compact('pageTitle', 'menu', 'items', 'assigned'));
    }

    public function assignMenuItemSubmit(Request $request,$id)
    {
        $request->validate([
            'menu_items' => 'nullable|array',
            'menu_items.*' => 'exists:menu_items,id',
        ]);

        $menu = Menu::findOrFail($id);

        $currentIds = $menu->items()
            ->where('tempname', activeTemplate())
            ->pluck('menu_items.id')
            ->toArray();

        // Remove only current template's items
        $menu->items()->detach($currentIds);

        // Re-attach new ones
        if ($request->menu_items) {
            $menu->items()->attach($request->menu_items);
                        $notify[] = ['success', 'Menu items updated successfully'];
            return back()->withNotify($notify);
        }

        $notify[] = ['info', 'You have not selected any menu items'];
        return back()->withNotify($notify);
    }
}
