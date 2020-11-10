<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class AdminPanelController extends Controller
{
    

    public function dashboard(){

    	$obj['iduser']          =  Session::get('iduser');
        $datalogin              =  app('LoginModel')->DataLogin($obj);
        $datapeserta            =  app('AdminPanelModel')->DataPeserta();
        $fungsi                 =  app('AdminPanelModel');


        $berhasilrefund         = 0;
        $belumrefund            = 0;
        $tiketrefund            = 0;
        $tiketnorefund          = 0;
        $totaltiket             = 0;
        $totalprice             = 0;
        $totalpriceout          = 0;
        foreach ($datapeserta as $key => $value) {
            if($value->logtransfer != null){
                $berhasilrefund += 1; 
                $tiketrefund    += $value->male+$value->female;
                $tiketperinvoice=   $value->male+$value->female;
                $obj['cdate']   =  $value->cdate; 
                $price          =  $fungsi->TotalPrice($obj);
                $totalpriceout  +=  $tiketperinvoice*$price;
            }else{
                $belumrefund    += 1; 
                $tiketnorefund  += $value->male+$value->female; 
            }
            unset($obj['cdate']);
            $totaltiket         +=  $value->male+$value->female;
            $tiketperinvoice    =   $value->male+$value->female;
            $obj['cdate']       =   $value->cdate;

            #menghitung uang berdasarkan periode harga
            $price              = $fungsi->TotalPrice($obj);
            $totalprice         += $tiketperinvoice*$price;
            // echo $value->invoice.'-'.$tiketperinvoice.'-'.$totalprice.'<br/>';
        }


            $sisaprice          = $totalprice - $totalpriceout;

        return view('homeadmin.home', compact('datalogin','datapeserta','berhasilrefund','belumrefund','totaltiket','tiketnorefund','tiketrefund','totalprice','totalpriceout','sisaprice','fungsi'));

    } 


    public function sendnotifmessage(){

    	/*
    	#pegangan notif

    	switch ($status) {
            case "0": $desc = "Verifikasi"; break;
            case "1": $desc = "Diterima"; break;
            case "2": $desc = "Ditolak"; break;
            case "3": $desc = "Sudah Di WA"; break;
            default: $desc = "";
        }
        */
    	#menampilkan data peserta award
        $datapeserta        = app('AdminPanelModel')->DataPeserta();
        $fungsi             = app('AdminPanelModel');

    	$no 			    = 1;

        return view('homeadmin.sendnotifmessage', compact('datalogin','datapeserta','no','fungsi'));

    }

    public function prosessendnotifmessage(Request $request){

        #mendefinisikan variabel
        $obj['idpenerimanotif']         = $request->idpenerima;
        $obj['key']                     = 'b100f506395d37a0840b92221a6f7f647b0357e7f612075a';
        $obj['url']                     = 'http://116.203.92.59/api/send_message';

        $totalkirim                     = count($obj['idpenerimanotif']);
        #membuat perulangan untuk send notif message
        for ($i=0; $i < $totalkirim; $i++) { 

            #membuat fungsi kirim notif lewat email
            $obj['idticket']            = $obj['idpenerimanotif'][$i];
            $dataperid                  = app('AdminPanelModel')->DataNotifPeserta($obj);
            $send                       = app('AdminPanelModel')->SendNotifToPeserta($obj, $dataperid);
            #echo "sukses";
        }


        return redirect('/sendnotifmessage')->with('success','Notif Berhasil Kami Kirimkan');

    }

    public function detaildatainsertprosestransfer(){

        $AdminPanelModel                =  app('AdminPanelModel');

        #menampilkan data peserta award
        $datapeserta                    = app('AdminPanelModel')->DataPesertaInsert();
        $no                             = 1;

        return view('homeadmin.detaildatainsertprosestransfer', compact('datalogin','datapeserta','no','AdminPanelModel'));
        

    }

    public function prosessendnotiftransfersukses(Request $request){
       
        #mendefinisikan variabel
        $idpenerimanotif                = $request->idpenerima;

        $totalkirim                     = count($idpenerimanotif);
        $tamplatedetail                 = collect([]);


        #membuat perulangan untuk send notif message
        for ($i=0; $i < $totalkirim; $i++) { 

            $obj['idticket']            = $idpenerimanotif[$i];
            $AdminPanelModel            = app('AdminPanelModel');
            $dataperid                  = app('AdminPanelModel')->DataNotifPeserta($obj);
            $obj['cdate']               = $dataperid->cdate;
            $totalprice                 = app('AdminPanelModel')->TotalPrice($obj);

            $totaltiket                 = $dataperid->male+$dataperid->female;
            if($totalprice == null){
                $hargaticket = 0;
                $donasi      = 0;
            }else{
                $hargaticket = $totalprice;
                $donasi      = 5;
            }

            $tamplate = "   <div class=col-3>
                            <div class='card m-b-30'>
                                <div class='card-body'>
                                    TO                  : ".$dataperid->nama." 
                                    <input type='hidden' name='idticket[]' value='".$obj['idticket']."' />
                                    <br/>
                                    Jumlah Tiket        : ".$totaltiket." <br/>
                                    Total Transfer      : ".$AdminPanelModel->formatCurrency($totaltiket*$hargaticket+$donasi)." <br/>
                                    <font color='red' size='1px;'>(INFORMASI INI AKAN DIKIRIM VIA WA) </font><br/>
                                </div>
                            </div>
                            </div> 
                        ";

            // echo $obj['idpenerimanotif'][$i];
            $tamplatedetail->push($tamplate);
           
        }

        $buttonback         = 'failed';


        return view('homeadmin.validasidetaildatainsertprosestransfer', compact('tamplatedetail','buttonback'));

    }


    public function stepsuksesnotiftransfer(Request $request){

        #mendefinisikan variabel
        $idpenerimanotif                = $request->idticket;

        $totalkirim                     = count($idpenerimanotif);
        $tamplatedetail                 = collect([]);

        $obj['key']                     = 'b100f506395d37a0840b92221a6f7f647b0357e7f612075a';
        $obj['url']                     = 'http://116.203.92.59/api/send_message';


        #membuat perulangan untuk send notif message
        for ($i=0; $i < $totalkirim; $i++) { 

            $obj['idticket']            = $idpenerimanotif[$i];
            $AdminPanelModel            = app('AdminPanelModel');
            $dataperid                  = app('AdminPanelModel')->DataNotifPeserta($obj);
            $obj['cdate']               = $dataperid->cdate;
            $totalprice                 = app('AdminPanelModel')->TotalPrice($obj);

            $totaltiket                 = $dataperid->male+$dataperid->female;
            if($totalprice == null){
                $hargaticket = 0;
                $donasi      = 0;
            }else{
                $hargaticket = $totalprice;
                $donasi      = 5;
            }

            $totaltransfer   = $AdminPanelModel->formatCurrency($hargaticket*$totaltiket+$donasi);

            #kirim notif bahwa pesan telah berhasil terkirim
            $send                       = app('AdminPanelModel')->SendNotifToPesertaSukses($obj, $dataperid, $totaltransfer);

            $validation                 = app('AdminPanelModel')->DataNotifPesertaValidation($obj);

            if($validation->logtransfer == 'Success'){
                $tema = "<font color='blue' size='1px;'>(INFORMASI TELAH BERHASIL DIKIRIM) </font>";
            }else{
                $tema = "<font color='blue' size='1px;'>(INFORMASI GAGAL DIKIRIM) </font>";
            }

            $tamplate = "   <div class=col-3>
                            <div class='card m-b-30'>
                                <div class='card-body'>
                                    TO                  : ".$dataperid->nama." 
                                    <input type='hidden' name='idticket[]' value='".$obj['idticket']."' />
                                    <br/>
                                    Jumlah Tiket        : ".$totaltiket." <br/>
                                    Total Transfer      : ".$AdminPanelModel->formatCurrency($totaltiket*$hargaticket+$donasi)." <br/>".$tema."<br/>
                                </div>
                            </div>
                            </div> 
                        ";

            // echo $obj['idpenerimanotif'][$i];
            $tamplatedetail->push($tamplate);
           
        }

        $buttonback         = 'active';


        return view('homeadmin.validasidetaildatainsertprosestransfer', compact('tamplatedetail','buttonback'));

    }

    public function downloaddata($datefrom, $dateto){
        

        // $iduser = Session::get('user_login');
        // $obj['iduser'] = $iduser;
        // $obj['isdelete'] = '0';
        // $userDao = app('UserDao');
        // $user = $userDao->getUserById($obj);
        
        // $reportInventoryDao     = app('ReportInventoryDao');
        // $obj['iddatastockist']  = $user->iddatastockist;
        // $obj['from']            = $stockistUtil->toSqlDate($request->from);
        // $obj['to']              = $stockistUtil->toSqlDate($request->to);

        // $listReportInventory = $reportInventoryDao->getListReportInventory($obj);


        // $listreport = 'true';
        $datefrom           = $datefrom;
        $dateto             = $dateto;
        $obj['from']        = date('Y-m-d', strtotime($datefrom));
        $obj['to']          = date('Y-m-d', strtotime($dateto));
        $listReportData     = app('AdminPanelModel')->listReportData($obj);
        $AdminPanelModel    = app('AdminPanelModel');

        // dd($from);
        // dd($listReportInventory);
        
        include app_path('Providers/Report/reportsendnotifone.php', compact('datefrom','dateto','listReportData','AdminPanelModel'));

    }

    public function downloaddatasuccess($datefrom, $dateto){

        $datefrom           = $datefrom;
        $dateto             = $dateto;
        $obj['from']        = date('Y-m-d', strtotime($datefrom));
        $obj['to']          = date('Y-m-d', strtotime($dateto));
        $listReportData     = app('AdminPanelModel')->listReportDataSuccess($obj);
        $AdminPanelModel    = app('AdminPanelModel');

        // dd($from);
        // dd($listReportInventory);
        
        include app_path('Providers/Report/reportsuccesstransfer.php', compact('datefrom','dateto','listReportData','AdminPanelModel'));

    }

}
