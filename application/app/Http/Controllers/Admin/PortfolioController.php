<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Models\Service;
use App\Models\Portfolio;
use Illuminate\Http\Request;
use App\Rules\FileTypeValidate;
use App\Http\Controllers\Controller;

class PortfolioController extends Controller
{
    public function index($status = 'all'){

        $query = Portfolio::searchable(['title'])->latest();

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
                'html' => view('Admin::components.tables.portfolio_data', compact('items'))->render(),
                'pagination' => $items->hasPages() ? view('Admin::components.pagination', compact('items'))->render() : '',
            ]);
        }

        $pageTitle = ucfirst($status) . ' Portfolio';
        return view('Admin::portfolio.index',compact('pageTitle','items'));

    }

    public function create(){
        $pageTitle = 'Add Portfolio';
        return view('admin.portfolio.create',compact('pageTitle'));
    }

    public function edit($id){
        $pageTitle = 'Update';
        $portfolio = Portfolio::findOrFail($id);
        return view('admin.portfolio.edit',compact('pageTitle','portfolio'));

    }

    public function store(Request $request){

        $request->validate([
            'title'=>'required',
            'sub_title'=>'required',
            'image' => ['required', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
        ]);

        $purifier = new \HTMLPurifier();

        $portfolio = new Portfolio();
        $portfolio->title = $request->title;
        $portfolio->sub_title = $request->sub_title;
        $portfolio->description = $purifier->purify($request->description);
        $portfolio->status = 1;


        if ($request->hasFile('image')) {
            try {
                $portfolio->image = fileUploader($request->image,getFilePath('portfolioImage'),getFileSize('portfolioImage'));

            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }
        $portfolio->save();

        $notify[] = ['success', 'Protfolio has been created successfully'];
        return back()->withNotify($notify);

    }

    public function update(Request $request,$id){
        $request->validate([
            'title'=>'required',
            'sub_title'=>'required',
            'image' => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
        ]);

        $purifier = new \HTMLPurifier();

        $portfolio = Portfolio::findOrFail($id);
        $portfolio->title = $request->title;
        $portfolio->sub_title = $request->sub_title;
        $portfolio->description = $purifier->purify($request->description);


        if ($request->hasFile('image')) {
            try {
                $old = $portfolio->image;
                $portfolio->image = fileUploader($request->image,getFilePath('portfolioImage'),getFileSize('portfolioImage'),$old);

            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }
        $portfolio->save();

        $notify[] = ['success', 'Protfolio has been updated successfully'];
        return back()->withNotify($notify);

    }

    public function delete($id){
        $portfolio = Portfolio::findOrFail($id);

        $path = getFilePath('portfolioImage') . '/' . $portfolio->image;
        fileManager()->removeFile($path);
        $portfolio->delete();

        $notify[] = ['success', 'Portfolio has been deleted'];
        return back()->withNotify($notify);
    }


    public function status($id){
        $portfolio = Portfolio::findOrFail($id);
        $portfolio->status = $portfolio->status == Status::ENABLE ? Status::DISABLE : Status::ENABLE;
        $portfolio->save();

        $notify[] = ['success', 'Portfolio status has been updated successfully'];
        return back()->withNotify($notify);
    }
}
