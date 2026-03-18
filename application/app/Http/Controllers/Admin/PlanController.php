<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use Carbon\Carbon;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\Subscription;

class PlanController extends Controller
{
    public function index($status = 'all'){
        $query = Plan::searchable(['name'])->latest();

        switch ($status) {
            case 'disable':
                $query->where('status', Status::DISABLE);
                break;
            case 'enable':
                $query->where('status', Status::ENABLE);
                break;
            case 'all':
                $query->whereIn('status', [Status::ENABLE, Status::DISABLE]);
                break;
            default:

                break;
        }

        $items = $query->paginate(getPaginate());

        if (request()->ajax()) {
            return response()->json([
                'html' => view('Admin::components.tables.plan_data', compact('items'))->render(),
                'pagination' => $items->hasPages() ? view('Admin::components.pagination', compact('items'))->render() : '',
            ]);
        }

        $pageTitle = ucfirst($status) . ' Plan';
        return view('Admin::plans.index',compact('pageTitle','items'));
    }

    public function create(){
        $pageTitle = 'Add Plan';
        return view('Admin::plans.create',compact('pageTitle'));
    }

    public function store(Request $request){
        $request->validate([
            'name'=>'required|unique:plans,name',
            'price'=>'required',
            'type'=>'required|in:0,1',
        ]);

        $monthly = Carbon::now()->month()->daysInMonth;
        $yearly = Carbon::now()->year()->daysInYear;

        $content =  json_encode($request->contents);

        $plan = new Plan();
        $plan->name = $request->name;
        $plan->price = $request->price;
        $plan->content = $content;
        $plan->type = $request->type ? 1 : 0;
        $plan->month = $request->type == 1 ? $monthly : null;
        $plan->year = $request->type == 0 ? $yearly : null;
        $plan->status = 1;
        $plan->save();

        $notify[] = ['success', 'Plan has been created successfully'];
        return to_route('admin.plan.index')->withNotify($notify);
    }

    public function delete($id){
        $plan = Plan::findOrFail($id);
        $plan->delete();

        $notify[] = ['success', 'Plan has been deleted successfully'];
        return back()->withNotify($notify);
    }

    public function status($id)
    {
        $plan = Plan::findOrFail($id);
        $plan->status = $plan->status == Status::ENABLE ? Status::DISABLE : Status::ENABLE;
        $plan->save();

        $notify[] = ['success', 'Plan status updated successfully'];
        return to_route('admin.plan.index')->withNotify($notify);
    }

    public function edit($id){
        $pageTitle = 'Update Plan';
        $plan = Plan::findOrFail($id);

        return view('Admin::plans.edit',compact('pageTitle','plan'));
    }

    public function update(Request $request, $id){
        $plan = Plan::findOrFail($id);

        $request->validate([
            'name' => [
                'required',
                Rule::unique('plans')->ignore($id),
            ],
            'price' => 'required',
            'type' => 'required',
        ]);


        $monthly = Carbon::now()->month()->daysInMonth;
        $yearly = Carbon::now()->year()->daysInYear;
        $content = json_encode($request->contents);

        $plan->name = $request->name;
        $plan->price = $request->price;
        $plan->content = $content;
        $plan->type = $request->type ? 1 : 0;
        $plan->month = $request->type == 1 ? $monthly : null;
        $plan->year = $request->type == 0 ? $yearly : null;
        $plan->save();

        $notify[] = ['success', 'Plan has been updated successfully'];
        return redirect()->route('admin.plan.index')->withNotify($notify);
    }

    public function subscriptions(){
        $pageTitle = "Subscription Lists";
        $items = Subscription::searchable(['plan:name', 'user:username', 'user:firstname', 'user:lastname'])->with(['user','plan'])->latest()->paginate(getPaginate());
        return view('Admin::plans.subscription',compact('pageTitle','items'));
    }
}
