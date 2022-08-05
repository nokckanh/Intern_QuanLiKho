<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\sanpham;
use App\nhap;
use App\xuat;
use Illuminate\Support\Facades\DB;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
   

    public function admintrangchu()
    {
    	return view('admin.admin');	
    }
//sp
    public function sanphamtrangchu()
    {
        $sanpham = sanpham::all();
        $sanpham = sanpham::paginate(10);

        return view('admin.sanpham')->with('sanpham',$sanpham);
    }

    public function addsanpham(Request $request){

        sanpham::create([
            'tensp' => $request['tensanpham'],
            'ncc'=>$request['ncc'] ,
            'thongtin' =>$request['thongtin'] 
        ]);
        return redirect()->route('sanpham')->with('status','Thêm thành công');
    }

    public function updatesanpham(Request $request ,$id)
    {
        $sanpham = sanpham::find($id);
        $sanpham ->tensp = $request->input('tensanpham');
        $sanpham ->ncc = $request->input('ncc');
        $sanpham ->thongtin = $request->input('thongtin');
        $sanpham ->update();

        return redirect()->route('sanpham')->with('status','Cập nhật thành công');
    }

    public function deletesanpham(Request $request,$id)
    {
        $sanpham =sanpham::findOrFail($id);
        $sanpham->delete();
        return redirect()->route('sanpham')->with('status','Xóa thành công');
    }
//nhap
    public function hoadonnhap()
    {
        
        $hoadonnhap = DB::table('nhap as n')
        ->join('sanpham as sp', 'n.sanpham_id', '=', 'sp.id')
        ->select('n.*','sp.tensp')
        ->paginate(10);
        

        $getnamesanpham = sanpham::all();

        return view('admin.hoadonnhap')->with([
            'hoadonnhap' => $hoadonnhap,
            'getnamesanpham' => $getnamesanpham
        ]);
    }

    
    public function addhoadonnhap(Request $request){

        nhap::create([
            'sanpham_id' => $request['idsanpham'],
            'gianhap'=>$request['gianhap'] ,
            'soluongnhap' =>$request['soluong'] ,
            'tongnhap' =>$request['tongtien'], 
            'ngaynhap' =>$request['date']
            
        ]);
        return redirect()->route('hoadonnhap')->with('status','Thêm thành công');
    }

    public function deletehoadonnhap(Request $request,$id)
    {
        $hoadonnhap =nhap::findOrFail($id);
        $hoadonnhap->delete();
        return redirect()->route('hoadonnhap')->with('status','Xóa thành công');
    }

    public function updatehoadonnhap(Request $request ,$id)
    {
        $hoadonnhap = nhap::find($id);
        $hoadonnhap ->sanpham_id = $request->input('idsp');
        $hoadonnhap ->gianhap = $request->input('gia');
        $hoadonnhap ->soluongnhap = $request->input('soluong');
        $hoadonnhap ->tongnhap = $request->input('tong');
        $hoadonnhap ->update();

        return redirect()->route('hoadonnhap')->with('status','Cập nhật thành công');
    }
//xuat
    public function hoadonxuat()
    {
        $hoadonxuat = DB::table('xuat as n')
        ->join('sanpham as sp', 'n.sanpham_id', '=', 'sp.id')
        ->select('n.*','sp.tensp')
        ->paginate(10);
        

        $getnamesanpham = sanpham::all();

        return view('admin.hoadonxuat')->with([
            'hoadonxuat' => $hoadonxuat,
            'getnamesanpham' => $getnamesanpham
        ]);

    }

    
    public function addhoadonxuat(Request $request){

        xuat::create([
            'sanpham_id' => $request['idsanpham'],
            'giaxuat'=>$request['giaxuat'] ,
            'soluongxuat' =>$request['soluong'] ,
            'tongxuat' =>$request['tongtien'] ,
            'ngayxuat' =>$request['date']
            
        ]);
        return redirect()->route('hoadonxuat')->with('status','Thêm thành công');
    }

    public function deletehoadonxuat(Request $request,$id)
    {
        $hoadonxuat =xuat::findOrFail($id);
        $hoadonxuat->delete();
        return redirect()->route('hoadonxuat')->with('status','Xóa thành công');
    }

    public function updatehoadonxuat(Request $request ,$id)
    {
        $hoadonxuat = xuat::find($id);
        $hoadonxuat ->sanpham_id = $request->input('idsp');
        $hoadonxuat ->giaxuat = $request->input('gia');
        $hoadonxuat ->soluongxuat = $request->input('soluong');
        $hoadonxuat ->tongxuat = $request->input('tong');
        $hoadonxuat ->update();

        return redirect()->route('hoadonxuat')->with('status','Cập nhật thành công');
    }

//bao cao ton
    public function baocaoton()
    {
        $baocaoton = DB::table('sanpham as sp')
        ->select('x.*','n.*', 'sp.*')
        // ->orderBy('id','desc')
        // ->where([
        //     ['n.sanpham_id', '=', 'sp.id'],
        //     ['x.sanpham_id', '=', 'sp.id'],
        // ])
        ->join('xuat as x', 'x.sanpham_id', '=', 'sp.id')
        ->join('nhap as n', 'n.sanpham_id', '=', 'sp.id')
        ->distinct()
        ->paginate(10);

        $page =1;
        if(isset($request->page)){
            $page = $request->page;
        }

        $index = ($page-1)*10+1;

        return view('admin.baocaoton')->with([
            'baocaoton'=>$baocaoton,
            'index' =>$index
        ]);
    }

    public function tinhtrang()
    {
        $sanpham = sanpham::all();
        $sanpham = sanpham::paginate(10);

        return view('admin.tinhtrang')->with('sanpham',$sanpham);
    }

    public function chitiet(Request $req)
    {

        $nhap = DB::table('nhap as n')
        ->where('sanpham_id',$req->id)
        ->join('sanpham as sp', 'n.sanpham_id', '=', 'sp.id')
        ->select('n.*','sp.tensp')
        ->paginate(10);
        
        $xuat = DB::table('xuat as n')
        ->where('sanpham_id',$req->id)
        ->join('sanpham as sp', 'n.sanpham_id', '=', 'sp.id')
        ->select('n.*','sp.tensp')
        ->paginate(10);
        
        
        
        //$nhap = nhap::where('sanpham_id',$req->id)->paginate(10);
        //$xuat = xuat::where('sanpham_id',$req->id)->paginate(10);
        
        $tongnhap=DB::table("nhap")->where('sanpham_id',$req->id)->get()->sum("soluongnhap");
        $tongxuat=DB::table("xuat")->where('sanpham_id',$req->id)->get()->sum("soluongxuat");
        $tongtiennhap=DB::table("nhap")->where('sanpham_id',$req->id)->get()->sum("tongnhap");
        $tongtienxuat=DB::table("xuat")->where('sanpham_id',$req->id)->get()->sum("tongxuat");
        //dd("$$tongtiennhap");
        return view('admin.chitiet')->with([
            'tongnhap'=>$tongnhap,
            'tongxuat'=>$tongxuat,
            'tongtiennhap'=>$tongtiennhap,
            'tongtienxuat'=>$tongtienxuat,
            'nhap'=>$nhap,
            'xuat'=>$xuat
        ]);
    }


}


