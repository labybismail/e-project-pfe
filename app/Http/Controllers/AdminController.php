<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
public function __construct()
{
    $this->middleware('auth:admin')
        ->except(['showAdminLoginForm','adminLogin']);
}

    public function index()
    {
        if(auth()->guard('admin')->check()){
            return view("admin.index")->with([
                "products" => Product::all(),
                "orders" => Order::all()
                ]);
        }else{
            return redirect('/admin/login');
        }
    }

    public function showAdminLoginForm()
    {
        if(auth()->guard('admin')->check()){
            return redirect()->back();
        }else{
            return view('admin.login');
        }
    }
    public function adminLogin(Request $request)
    {
        $this->validate($request,[
            'email'=>'required|email',
            'password'=>'required|min:4'
        ]);
        if(auth()->guard("admin")->attempt([
            'email'=>$request->email,
            'password'=>$request->password
        ],$request->get('remember'))){
            return redirect('/admin');
        }else{
            return redirect()->route('admin.login');
        };
    }

    public function adminLogout()
    {
        auth()->guard('admin')->logout();
        return redirect()->route('admin.login');
    }

    public function getProducts()
    {
        return view("admin.products.index")->with([
        "products" => Product::latest()->paginate(5)
        ]);
    }
    public function getOrders()
    {
        return view("admin.orders.index")->with([
        "orders" => Order::latest()->paginate(5)
        ]);
    }
}
