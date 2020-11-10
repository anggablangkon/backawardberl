@extends('layouts.layoutsadmin')

@section('title','Dashboard Admin Panel')

@section('content')

<div class="row">
	<div class="col-sm-12">
		<div class="float-right page-breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="#">Sistem</a></li>
				<li class="breadcrumb-item active">Data Proses Notif Transfer</li>
			</ol>
		</div>
		<h5 class="page-title">Data Proses Notif Transfer</h5>
	</div>
</div>
<!-- end row -->

<form action="{{url('/prosessendnotiftransfersukses')}}" method="POST">
{{csrf_field()}}
<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#myDownload" ><i class="fa fa-download"></i> Download Data </button>
<br/><br/>
<div class="row">
	<div class="col-12">
		<div class="card m-b-30">
			<div class="card-body table-responsive">
				<table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
					<thead>
						<tr>
							<th width="10px;" >
								<div class="form-group row">
									<div class="col-12">
										<div class="custom-control custom-checkbox">
											<input type="checkbox" onchange="checkAll(this)" class="custom-control-input" id="customCheck">
											<label class="custom-control-label" for="customCheck"></label>
										</div>
									</div>
								</div>
							</th>
							<th>Nama</th>
							<th>Invoice</th>
							<th>No Tlp</th>
							<th>Tagihan Tiket</th>
							<th width="5px;">Status<br/>Transfer</th>
							<th width="10px;">Detail</th>
						</tr>
					</thead>

					<tbody>
						@foreach($datapeserta as $key => $values)
						<tr>
							<td>
								<div class="form-group row">
									<div class="col-12">
										@if($values->logtransfer != 'Success')
										<div class="custom-control custom-checkbox">
											<input type="checkbox" name="idpenerima[]" class="custom-control-input" id="customCheck{{$values->idticket}}" value="{{$values->idticket}}">
											<label class="custom-control-label"  for="customCheck{{$values->idticket}}"></label>
										</div>
										@endif
									</div>
								</div>
							</td>
							<td>{{$values->nama}}</td>
							<td>{{$values->invoice}}</td>
							<td>{{$values->telp}}</td>
							<td>
								@php
									$obj['cdate'] 	= $values->cdate;
									$totaltiket 	= $values->male+$values->female;
									$totalprice 	= $AdminPanelModel->TotalPrice($obj);
									if($totalprice == null){
                                        $hargaticket = 0;
                                        $donasi 	 = 0;
                                    }else{
                                        $hargaticket = $totalprice;
                                        $donasi 	 = 5;
                                    }
								@endphp
								{{$totaltiket}} x {{$AdminPanelModel->formatCurrency($hargaticket)}} <br/> 
								<p style="color: blue;">
									Total Transfer : {{$AdminPanelModel->formatCurrency($totaltiket*$hargaticket+$donasi)}}
								</p>
							</td>
							<td align="center">

								@if($values->logtransfer == 'Success')
								<i class="dripicons-checkmark " style="color: green;"></i>
								@else
								<i class="dripicons-cross" style="color: red;"></i>
								@endif
							</td>
							<td>
								<a href="" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#myModal{{$values->idticket}}"> Detail </a>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>

			</div>
		</div>
	</div> <!-- end col -->
</div> <!-- end row -->

<div class="row">
	<div class="col-12">
		<div class="card m-b-30">
			<div class="card-body">
				<button type="submit" class="btn btn-primary">LAKUKAN PROSES KIRIM NOTIF</button>
			</div>
		</div>
	</div> <!-- end col -->
</div> <!-- end row -->
</form>


<!--  Modal content for the above example -->
@foreach($datapeserta as $key => $values)

<!-- sample modal content -->
<div id="myModal{{$values->idticket}}" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title mt-0" id="myModalLabel">Detail Data</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label>Nama</label>
					<input value="{{$values->nama}}" disabled class=form-control > <br/>
					<label>Alamat</label>
					<textarea disabled class="form-control">{{$values->domisili}}</textarea>
					<h4>Detail Data Tiket</h4>
					<label>Tiket Pria</label>
					<input value="{{$values->male}}" disabled class=form-control >
					<label>Tiket Wanita</label>
					<input value="{{$values->female}}" disabled class=form-control >  
					<h4>Detai Data Inputan</h4>
					<label>Nama Pemilik Rekening</label>
					<input value="{{$values->nama_rekening}}" disabled class=form-control > 
					<label>Nomor Rekening</label>
					<input value="{{$values->nomor_rekening}}" disabled class=form-control > 
					<label>Nama Bank</label>
					<input value="{{$values->nama_bank}}" disabled class=form-control > 
					<label>Domisili Bank</label>
					<input value="{{$values->domisili_bank}}" disabled class=form-control > 
					<label>Email</label>
					<input value="{{$values->email}}" disabled class=form-control >
					<br/>
					<label>Tgl Order : {{date('d M Y',strtotime($values->cdate))}}</label>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">
					Tutup
				</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@endforeach

<!-- modal download data -->
<div id="myDownload" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title mt-0" id="myModalLabel">Download Data Kedalam Excell</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form >
				<div class="form-group">
					<label>Pilih tanggal pengiriman notif</label>
					<br/>
					<div class="row">
						<div class="col-sm-5">
							<input class="form-control" autocomplete="off" type="text" id="from" name="date" >
						</div>
						<div class="col-sm-1">
							s/d
						</div>
						<div class="col-sm-5">
							<input class="form-control" autocomplete="off" type="text" id="to" name="date" >
						</div>
					</div>
					<br/>
					<input type="hidden" name="url" id="url" value="{{url('/downloaddata')}}/">
					<input type="hidden" name="url" id="urlsuccess" value="{{url('/downloaddatasuccess')}}/">
					<button type="button" id="butdownload"> <i class="fa fa-download"></i> Download Belum Transfer </button>
					<button type="button" id="butdownloadsuccess"> <i class="fa fa-download"></i> Download Sudah Transfer </button>
				</form>
				</div>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

@endsection

@section('javascript')
 <script type="text/javascript">
  function checkAll(ele) {
       var checkboxes = document.getElementsByTagName('input');
       if (ele.checked) {
           for (var i = 0; i < checkboxes.length; i++) {
               if (checkboxes[i].type == 'checkbox' ) {
                   checkboxes[i].checked = true;
               }
           }
       } else {
           for (var i = 0; i < checkboxes.length; i++) {
               if (checkboxes[i].type == 'checkbox') {
                   checkboxes[i].checked = false;
               }
           }
       }
   }


	$( function() {
		$( "#from" ).datepicker({
			format: 'dd-mm-yyyy',
			autoSize: true,
			changeMonth: true,
			changeYear: true,
			clearBtn: true,
			clearBtn: true,
		});
	});

	$( function() {
		$( "#to" ).datepicker({
			format: 'dd-mm-yyyy',
			autoSize: true,
			changeMonth: true,
			changeYear: true,
			clearBtn: true,
			clearBtn: true,
		});
	});

    $('#butdownload').on('click', function() {

	 	var datefrom 	= $('#from').val();
	 	var dateto 	 	= $('#to').val();
	 	var url 		= $('#url').val();

  		window.open(url+datefrom+'/'+dateto, "_blank");
  	 });

    $('#butdownloadsuccess').on('click', function() {

    	var datefrom 	= $('#from').val();
    	var dateto 		= $('#to').val();
    	var url 		= $('#urlsuccess').val();

    	window.open(url+datefrom+'/'+dateto, "_blank");

    });

 </script>
 @endsection