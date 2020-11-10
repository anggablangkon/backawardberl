<?php

namespace App\Providers\Model;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AdminPanelModel extends ServiceProvider
{
   
    public function boot(){
    }

    public function register(){
    }

    public function __construct(){

    }

    public function DataPeserta(){

    	$result 	 	= DB::table('mticket as tb1')
    						->select('tb1.idticket','tb1.idticketprice','tb1.nama','tb1.telp','tb1.domisili','tb1.invoice','tb1.lognotifadmin','tb1.lognotifadmin','tb1.cdate','tb1.male','tb1.female','tb1.logtransfer')
                            ->where('tb1.isdelete',0)
    						->where('tb1.status',1)
    						->where('tb1.paidstatus',1)
    						->get();

    	return $result;
    }

    public function DataPesertaInsert(){

        $result         = DB::table('mticket')
                            ->select('idticket','idticketprice','nama','telp','domisili','invoice','lognotifadmin','lognotifadmin','email','nama_rekening','nomor_rekening','nama_bank','domisili_bank','male','female','cdate','logtransfer')
                            ->where('isdelete',0)
                            ->where('status',1)
                            ->where('paidstatus',1)
                            ->whereNotNull('email')
                            ->get();
        return $result;
    }

    public function DataNotifPeserta($obj){

    	$result 		= DB::table('mticket')
    						->select('nama','idticket','invoice','telp','domisili','email','male','female','cdate','logtransfer')
    						->where('idticket', $obj['idticket'])
    						->first();

    	return $result;

    }

    public function DataNotifPesertaValidation($obj){

        $result         = DB::table('mticket')
                            ->select('logtransfer')
                            ->where('idticket', $obj['idticket'])
                            ->first();

        return $result;

    }

    public function SendNotifToPeserta($obj, $dataperid){

    		$pesan  =  "Assalamualaikum wr.wb.\n";
            $pesan .=  "Salam Sukses dan Sejahterah untuk B Erl Lovers semua.\n\n\n";
            $pesan .=  "Sehubung dengan beredarnya pesan ini, kami selaku Tim Pusat B erl Cosmetics ingin memberitahukan bahwa Acara B Erl Award 2020, dengan berat hati harus diundur mengingat situasi serta kondisi yang tidak memungkinkan akibat kejadian luar biasa yang disebabkan Pandemic Covid-19 yang terjadi di berbagai belahan dunia termasuk Indonesia. \n";
            $pesan .=  "Untuk memutus rantai penyebaran Virus Covid-19 serta menaati peraturan pemerintah yang tercantum dalam KUHP Pasal 212, 216 dan 218 tentang Aturan Kerumunan di Suatu Tempat, oleh sebab itu setelah melakukan musyawarah dan mempertimbangkan dengan seksama, Acara B Erl Award 2020 terpaksa diundur sampai batas waktu yang tidak ditentukan. Sehingga untuk planing tahun ini terpaksa dibatalkan dan akan segera kami informasikan kembali jika semua sudah kondusif. \n\n\n";
            $pesan .=  "Kami selaku Tim Pusat serta Panitia Acara menyampaikan Permohonan Maaf yang sebesar-besarnya kepada semua B Erl Lovers yang sudah rela menunggu Acara B Erl Award. \n\n\n";
            $pesan  .= "Untuk proses pengembalian uang (refund) B Erl Lover bisa mengakses melalui online dengan mengakses link berikut \n\n\n";
            $pesan .=  "https://berlmember.xyz/refundaward/public/ \n\n\n";
            $pesan .=  "Demikian pemberitahuan kami, terimakasih kepada semua B erl lover yang sudah mendukung kami.";


            $data = array(
              "phone_no"=> $dataperid->telp,
              "key"     =>$obj['key'],
              "message" => $pesan
            );
            
            $data_string = json_encode($data);
            $ch = curl_init($obj['url']);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_VERBOSE, 0);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
            curl_setopt($ch, CURLOPT_TIMEOUT, 360);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
              'Content-Type: application/json',
              'Content-Length: ' . strlen($data_string))
            );
            // $res=curl_exec($ch);
            $res=curl_exec($ch);
            #memasukan log status pengiriman otp
            DB::table('mticket')
                ->where('idticket',$obj['idticket'])
                ->update([

                    'lognotifadmin'  => $res,
                    'mdate'          => date('Y-m-d')
                ]);

            curl_close($ch);

    }

    public function SendNotifToPesertaSukses($obj, $dataperid, $totaltransfer){

            $pesan   =  "*Assalamualaikum wr.wb.*\n";
            $pesan  .=  "Dana Pendaftaran B Erl Awards 2020 sebesar *Rp. ".$totaltransfer."* sudah kami transfer ke nomor rekening Saudara/i, mohon untuk di cek kembali pada mutasi rekening.\n\n";
            $pesan  .=  "Terimakasih sudah ikut berpartisipasi dalam acara B erl Awards 2020. Semoga kita selalu diberikan kesehatan dan keamanan. Jangan lupa untuk Menggunakan Masker, Mencuci Tangan dan Menjaga Jarak selama masa Pandemi ini agar dapat mencegah penyebarannya.\n\n\n";
            $pesan  .=  "*terima kasih.* \n*Management B Erl Cosmetics*";

            $data = array(
              "phone_no"=> $dataperid->telp,
              "key"     =>$obj['key'],
              "message" => $pesan
            );
            
            $data_string = json_encode($data);
            $ch = curl_init($obj['url']);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_VERBOSE, 0);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
            curl_setopt($ch, CURLOPT_TIMEOUT, 360);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
              'Content-Type: application/json',
              'Content-Length: ' . strlen($data_string))
            );
            // $res=curl_exec($ch);
            $res=curl_exec($ch);
            #memasukan log status pengiriman otp
            DB::table('mticket')
                ->where('idticket',$obj['idticket'])
                ->update([

                    'logtransfer'  => $res,
                    'mdate'        => date('Y-m-d')

                ]);
            curl_close($ch);

    }

    public function TotalPrice($obj){

        // $result         = DB::table('mticketprice')
        //                 ->select('price','startdate','enddate','isdelete')
        //                 ->where('isdelete',0)
        //                 ->where(DB::raw('date_format(startdate, %Y-%m-%d)'),'<=',DB::raw('date_format(endate, %Y-%m-%d)'))
        //                 ->first();

        $sql                = "select price, startdate, enddate, isdelete 
                              from mticketprice where isdelete = 0 and date_format(startdate, '%Y-%m-%d') 
                              <= date_format('".$obj['cdate']."','%Y-%m-%d')
                              and date_format(enddate, '%Y-%m-%d') >= date_format('".$obj['cdate']."','%Y-%m-%d')
                              ";

        $result             = collect(\DB::select($sql))->first();

        if($result == null){
            $hargatiket =  0;
        }else{
            $hargatiket = $result->price;
        }

        return $hargatiket;

    }

    public function formatCurrency ($val) {
        $val = number_format((float)$val,0,',','.');
        return $val;
    }

    public function FilterDataPerId($obj){
        
        echo $obj['idpenerimanotif'];
    
    }

    public function SearchTiket($obj){

        $sql                = " select tiket from mdetailtiket where invoice = '".$obj['invoice']."'
                                 and isdelete = 0
                              ";

        $result             = collect(\DB::select($sql))->first();

        if($result == null){
            $tiket =  null;
        }else{
            $tiket = $result->tiket;
        }

        return $tiket;

    }


    public function listReportData($obj){

        $sql = " select tb1.nama, tb1.invoice, tb1.telp, tb1.cdate,
                    tb1.male + tb1.female as total_tiket, tb1.email as email
                    , tb1.nama_rekening, tb1.nomor_rekening, tb1.nama_bank, tb1.domisili_bank
                    from mticket tb1
                    where tb1.isdelete = 0
                    and tb1.lognotif is not null
                    and tb1.lognotifadmin is not null
                    and tb1.email is not null
                    and logtransfer is null
                    and tb1.mdate  BETWEEN  '".$obj['from']."' and '".$obj['to']."'
               ";

        $result = DB::select($sql);

        return $result;
    }

    public function listReportDataSuccess($obj){

        $sql = " select tb1.nama, tb1.invoice, tb1.telp, tb1.cdate,
                    tb1.male + tb1.female as total_tiket, tb1.email as email
                    , tb1.nama_rekening, tb1.nomor_rekening, tb1.nama_bank, tb1.domisili_bank
                    from mticket tb1
                    where tb1.isdelete = 0
                    and tb1.lognotif is not null
                    and tb1.lognotifadmin is not null
                    and tb1.email is not null
                    and logtransfer is not null
                    and tb1.mdate  BETWEEN  '".$obj['from']."' and '".$obj['to']."'
               ";

        $result = DB::select($sql);

        return $result;
    }


}
