<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Lib\GoogleAuthenticator;
use App\Models\Form;
use App\Models\Plan;
use App\Models\Order;
use App\Models\Deposit;
use App\Models\Service;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\Subscription;

class UserController extends Controller
{
    public function home()
    {
        $pageTitle = 'Dashboard';
        $user = auth()->user();
        $subscribe = isSubscribe($user->id);


        $totalOrders = Order::where('user_id',$user->id)->count();

        $deposits = Deposit::selectRaw("SUM(amount) as amount, MONTHNAME(created_at) as month_name, MONTH(created_at) as month_num")
        ->whereYear('created_at', date('Y'))
        ->whereStatus(1)
        ->where('user_id',$user->id)
        ->groupBy('month_name', 'month_num')
        ->orderBy('month_num')
        ->get();
        $depositsChart['labels'] = $deposits->pluck('month_name');
        $depositsChart['values'] = $deposits->pluck('amount');

        $orders = Order::with('service')->where('user_id', $user->id)->latest()->limit(5)->get();
        return view('UserTemplate::dashboard', compact('pageTitle','subscribe','totalOrders','user','depositsChart','orders'));
    }

    public function depositHistory(Request $request)
    {
        $pageTitle = 'Deposit History';
        $deposits = auth()->user()->deposits();
        if ($request->search) {
            $deposits = $deposits->where('trx',$request->search);
        }
        $deposits = $deposits->with(['gateway'])->orderBy('id','desc')->paginate(getPaginate());
        return view('UserTemplate::deposit_history', compact('pageTitle', 'deposits'));

    }

    public function show2faForm()
    {
        $general = gs();
        $ga = new GoogleAuthenticator();
        $user = auth()->user();
        $secret = $ga->createSecret();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($user->username . '@' . $general->site_name, $secret);
        $pageTitle = '2FA Setting';
        return view('UserTemplate::twofactor', compact('pageTitle', 'secret', 'qrCodeUrl'));
    }

    public function create2fa(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'key' => 'required',
            'code' => 'required',
        ]);
        $response = verifyG2fa($user,$request->code,$request->key);
        if ($response) {
            $user->tsc = $request->key;
            $user->ts = 1;
            $user->save();
            $notify[] = ['success', 'Google authenticator activated successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'Wrong verification code'];
            return back()->withNotify($notify);
        }
    }

    public function disable2fa(Request $request)
    {
        $request->validate([
            'code' => 'required',
        ]);

        $user = auth()->user();
        $response = verifyG2fa($user,$request->code);
        if ($response) {
            $user->tsc = null;
            $user->ts = 0;
            $user->save();
            $notify[] = ['success', 'Two factor authenticator deactivated successfully'];
        } else {
            $notify[] = ['error', 'Wrong verification code'];
        }
        return back()->withNotify($notify);
    }

    public function transactions(Request $request)
    {
        $pageTitle = 'Transactions';
        $remarks = Transaction::distinct('remark')->where('user_id', auth()->id())->orderBy('remark')->get('remark');

        $transactions = Transaction::where('user_id',auth()->id())->searchable(['trx', 'amount'])->dateFilter()->filter(['trx_type', 'remark'])->latest()->paginate(getPaginate());
        return view('UserTemplate::transactions', compact('pageTitle','transactions','remarks'));
    }


    public function attachmentDownload($fileHash)
    {
        $filePath = decrypt($fileHash);
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $general = gs();
        $title = slug($general->site_name).'- attachments.'.$extension;
        $mimetype = mime_content_type($filePath);
        header('Content-Disposition: attachment; filename="' . $title);
        header("Content-Type: " . $mimetype);
        return readfile($filePath);
    }

    public function userData()
    {
        $user = auth()->user();
        if ($user->reg_step == 1) {
            return to_route('user.home');
        }
        $pageTitle = 'User Data';
        return view('UserTemplate::user_data', compact('pageTitle','user'));
    }

    public function userDataSubmit(Request $request)
    {
        $user = auth()->user();
        if ($user->reg_step == 1) {
            return to_route('user.home');
        }
        $request->validate([
            'firstname'=>'required',
            'lastname'=>'required',
        ]);
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->address = [
            'country'=> $user->address->country,
            'address'=>$request->address,
            'state'=>$request->state,
            'zip'=>$request->zip,
            'city'=>$request->city,
        ];
        $user->reg_step = 1;
        $user->save();

        $notify[] = ['success','Registration process completed successfully'];
        return to_route('user.home')->withNotify($notify);

    }


         // get orders table
     public function getOrders(){
        $pageTitle = 'Orders List';
        $orders = Order::where('user_id', auth()->user()->id)
        ->latest()
        ->with('service')
        ->paginate(getPaginate());
        return view($this->activeTemplate.'user.orders',compact('pageTitle','orders'));
    }

    public function approvedOrders(){

        $pageTitle = 'Approved Orders';
        $orders = Order::where('status',1)->where('user_id', auth()->user()->id)
        ->latest()
        ->with('service')
        ->paginate(getPaginate());
        return view($this->activeTemplate.'user.orders',compact('pageTitle','orders'));
    }

    public function pendingOrders(){

        $pageTitle = 'Pending Orders';
        $orders = Order::where('status',0)->where('user_id', auth()->user()->id)
        ->latest()
        ->with('service')
        ->paginate(getPaginate());
        return view($this->activeTemplate.'user.orders',compact('pageTitle','orders'));
    }

    // subscription
    public function fetchSubscription(){
        $pageTitle = "Subscriptions";
        $user = auth()->user();
        $subscriptions = Subscription::with('plan')->where('user_id',$user->id)->latest()->paginate(getPaginate());
        return view($this->activeTemplate.'user.subscriptions',compact('subscriptions','pageTitle'));
    }


     // file download
     public function serviceFileDownload($id) {

        $user = auth()->user();
        $siteName = gs()->site_name;
        $service = Service::findOrFail($id);


        if (isset($service->file)) {
            $file = getFilePath('serviceFile') . '/' . $service->file;
            $fileName =$siteName.'_'.$user->username . '_' . $service->file;
            return response()->download($file, $fileName);
        }else{
            $notify = ['error', 'This File Empty'];
            return back()->withNotify($notify);
        }

    }

}
