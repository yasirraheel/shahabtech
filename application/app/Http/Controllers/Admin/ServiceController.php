<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Models\Order;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Rules\FileTypeValidate;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{

    public function index($status = 'all'){

        $query = Service::searchable(['title', 'price'])->latest();

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
                'html' => view('Admin::components.tables.service_data', compact('items'))->render(),
                'pagination' => $items->hasPages() ? view('Admin::components.pagination', compact('items'))->render() : '',
            ]);
        }

        $pageTitle = ucfirst($status) . ' Services';
        return view('Admin::services.index',compact('pageTitle','items'));

    }

    public function create(){
        $pageTitle = 'Add Service';
        return view('admin.services.create',compact('pageTitle'));
    }

    public function edit($id){

        $pageTitle = 'Update';
        $service = Service::findOrFail($id);
        return view('admin.services.edit',compact('pageTitle','service'));

    }

    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'title'=>'required',
            'price'=>'required|gt:0',
            'icon'=>'required',
            'file.*' => ['nullable', 'file', new FileTypeValidate(['zip', 'rar','doc', 'pdf', 'xls','ppt'])],
        ]);

        // field validation
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $purifier = new \HTMLPurifier();

        $service = new Service();
        $service->title = $request->title;
        $service->icon = $request->icon;
        $service->price = $request->price;
        $service->description = $purifier->purify($request->description);
        $service->status = 1;

        if ($request->hasFile('file')) {
            try {
                $filePath = fileUploader($request->file('file'), getFilePath('serviceFile'));
                $service->file = $filePath;
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your file'];
                return back()->withNotify($notify);
            }
        }
        $service->save();

        if ($service) {
            return response()->json([
                'message' => 'Service has been created successfully',
                'product' => $service,
            ]);
        } else {
            return response()->json([
                'message' => 'Service could not be created. Please try again later.',
            ], 500);
        }

    }

    public function update(Request $request){

        $validator = Validator::make($request->all(), [
            'title'=>'required',
            'price'=>'required|gt:0',
            'icon'=>'required',
            'file.*' => ['nullable', 'file', new FileTypeValidate(['zip', 'rar','doc', 'pdf', 'xls','ppt'])],
        ]);

        // field validation
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $purifier = new \HTMLPurifier();

        $service = Service::find($request->id);
        $service->title = $request->title;
        $service->icon = $request->icon;
        $service->price = $request->price;
        $service->description = $purifier->purify($request->description);


        if ($request->hasFile('file')) {
            try {
                $old = $service->file;
                $filePath = fileUploader($request->file('file'), getFilePath('serviceFile'), $old);
                $service->file = $filePath;
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your file'];
                return back()->withNotify($notify);
            }
        }

        $service->save();

        if ($service) {
            return response()->json([
                'message' => 'Service has been updated successfully',
                'product' => $service,
            ]);
        } else {
            return response()->json([
                'message' => 'Service could not be updated. Please try again later.',
            ], 500);
        }

    }

    public function delete($id){

        $service = Service::findOrFail($id);
        $filePath = getFilePath('serviceFile') . '/' . $service->file;
        fileManager()->removeFile($filePath);

        $service->delete();

        $notify[] = ['success', 'Service has been deleted'];
        return back()->withNotify($notify);
    }


    public function status($id)
    {
        $service = Service::findOrFail($id);
        $service->status = $service->status == Status::ENABLE ? Status::DISABLE : Status::ENABLE;
        $service->save();

        $notify[] = ['success', 'Service status has been updated successfully'];
        return back()->withNotify($notify);
    }



    public function orders($status = 'all')
    {
        $query = Order::searchable(['user:username', 'user:firstname', 'user:lastname', 'service:title', 'order_number'])->with(['service','user'])->latest();

        switch ($status) {
            case 'pending':
                $query->where('status', Status::DISABLE);
                break;
            case 'approved':
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
                'html' => view('Admin::components.tables.order_data', compact('items'))->render(),
                'pagination' => $items->hasPages() ? view('Admin::components.pagination', compact('items'))->render() : '',
            ]);
        }

        $pageTitle = ucfirst($status) . ' Orders';
        return view('Admin::orders.index',compact('pageTitle','items'));
    }

}
