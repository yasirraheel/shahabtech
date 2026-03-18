<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\MenuMenuItem;
use App\Models\Page;
use Illuminate\Http\Request;

class MenuItemController extends Controller
{
    public function index()
    {
        $pageTitle = 'Menu Item Management';
        $items = MenuItem::searchable(['title'])->where('tempname', $this->activeTemplate)->latest()->paginate(getPaginate());
        $pages = Page::where('tempname',$this->activeTemplate)->get();
        return view('Admin::menu_item.index',compact('pageTitle','items', 'pages'));
    }

    public function storeOrUpdate(Request $request, $id = null)
    {
        $request->validate([
            'link_type' => 'required|in:1,2,3',
            'title' => 'required_unless:link_type,3|nullable|string|max:40',
            'url' => 'required_if:link_type,1,2|nullable|string',
            'page_id' => 'required_if:link_type,3|nullable|exists:pages,id',
        ]);

        if($request->link_type == Status::EXTERNAL_LINK) {
            $request->validate([
                'url' => 'required|url',
            ]);
        }

        $check = MenuItem::where('tempname', $this->activeTemplate)->where('title', $request->title)->whereNot('id', $id)->exists();
        if($check){
            $notify[] = ['error', 'This title has already been taken for your current template. Please choose another.'];
            return back()->withNotify($notify);
        }


        if ($id) {
            $menu = MenuItem::findOrFail($id);
            $message = 'Menu updated successfully';
        } else {
            $menu = new MenuItem();
            $menu->tempname = $this->activeTemplate;
            $message = 'Menu created successfully';
        }

        if($request->link_type == Status::PAGE_LINK) {
            $page = Page::find($request->page_id);
            if(!$page) {
                $notify[] = ['error', 'Page not found'];
                return back()->withNotify($notify);
            }

            $menu->title = $page->name;
            $menu->page_id = $request->page_id;
            $menu->link_type = $request->link_type;
            $menu->url = $page->slug;
            $menu->save();
            $notify[] = ['success', $message];
            return back()->withNotify($notify);
        }else{
            $menu->title = $request->title;
            $menu->link_type = $request->link_type;
            $menu->url = $request->url;
            $menu->save();

            $notify[] = ['success', $message];
            return back()->withNotify($notify);
        }

        $notify[] = ['error', 'Something went wrong. Please try again'];
        return back()->withNotify($notify);
    }

    public function status($id)
    {
        $menu = MenuItem::findOrFail($id);
        $menu->status = $menu->status == Status::ENABLE ? Status::DISABLE : Status::ENABLE;
        $menu->save();

        $notify[] = ['success', 'Status change has been successfully'];
        return back()->withNotify($notify);
    }

    public function remove($id)
    {
        $item = MenuItem::findOrFail($id);

        $check = MenuMenuItem::where('menu_item_id', $item->id)->exists();
        if ($check) {
            $menuItem = MenuMenuItem::where('menu_item_id', $item->id)->delete();
        } // Delete associated menu items
        $item->delete();

        $notify[] = ['success', 'Menu has been deleted successfully'];
        return back()->withNotify($notify);
    }
}
