@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">

            @if($errors->any())
                <div class="text-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card">
                <div class="card-header">URL Shortener Service</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('home.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <h5>
                                        Enter a URL to Shorten
                                    </h5>
                                    <input type="text" name="original" class="form-control" placeholder="https://" spellcheck="false" data-ms-editor="true" value="{{ old('original') }}">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <h5>
                                    Custom Short URL</h5>
                                    <input type="text" name="shorten" placeholder="(optional)" class="form-control" spellcheck="false" data-ms-editor="true" value="{{ old('shorten') }}">
                                </div>
                            </div>
                        </div>
                        <div style="text-align: center;">
                            <button class="btn btn-info btn-round" style="font-size: 14px; margin-top: 16px;">Shorten URL</button>
                        </div>
                    </form>
                </div>
            </div>
            <br>
            <div class="card">
                <div class="card-header">History</div>
                <div class="card-body">
                    <table id="my_table" class="table table-bordered table-responsive table-hover table-striped">
                        <thead>
                            <tr>
                                <th>Original URL</th>
                                <th>Shorten URL</th>
                                <th>Date</th>
                                <th>View</th>
                                <th>&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($links as $link)
                                <tr>
                                    <td>
                                        {{ $link->original }}
                                    </td>
                                    <td id="td_link_id_{{ $link->id }}">
                                        {{ env('APP_URL') }}/{{ $link->shorten }}
                                    </td>
                                    <td>
                                        {{ $link->created_at->format('Y-m-d H:i:s') }}
                                    </td>
                                    <td>
                                        {{ $link->views }}
                                    </td>
                                    <td>
                                        <a href="{{ route('home.show', $link->shorten) }}" class="btn btn-success" style="display: inline-block;" target="_blank">
                                            <i class="fa fa-external-link" aria-hidden="true"></i>
                                            OPEN
                                        </a>

                                        <a class="btn btn-primary" style="display: inline-block;" 
                                            onclick="copyToClipboard('{{ env('APP_URL') }}/{{ $link->shorten }}')">
                                            <i class="fa fa-clone" aria-hidden="true"></i>
                                            COPY
                                        </a>

                                        <a href="{{ route('home.qrcode', $link->shorten) }}" class="btn btn-info" style="display: inline-block;" target="_blank">
                                            <i class="fa fa-qrcode" aria-hidden="true"></i>
                                            QR Code
                                        </a>

                                        <form method="POST" action="{{ route('home.delete', $link) }}" style="display: inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger"  onclick="return confirm('คุณต้องการลบ ใช่ หรือ ไม่')"><i class="fa fa-trash-o" aria-hidden="true" style="margin-right: 6px"></i>DELETE</button>
                                        </form>   
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal HTML -->
<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-confirm">
		<div class="modal-content">
			<div class="modal-header justify-content-center">
				<div class="icon-box">
					<i class="fa fa-check" aria-hidden="true"></i>
				</div>
			</div>
			<div class="modal-body text-center">
				<h4 id="alert-modal-title"></h4>	
				<p id="alert-modal-content"></p>
				<button class="btn btn-success" data-dismiss="modal" onclick="closeModal()"><span>ปิด</span></button>
			</div>
		</div>
	</div>
</div> 

@endsection

@section('script')
    <script>

        $(document).ready( function () {
            $('#my_table').DataTable();
        } );


        function copyToClipboard(text) {
            var dummy = document.createElement("textarea");
            // to avoid breaking orgain page when copying more words
            // cant copy when adding below this code
            // dummy.style.display = 'none'
            document.body.appendChild(dummy);
            //Be careful if you use texarea. setAttribute('value', value), which works with "input" does not work with "textarea". – Eduard
            dummy.value = text;
            dummy.select();
            document.execCommand("copy");
            document.body.removeChild(dummy);

            $('#alert-modal-title').text('สำเร็จ');
            $('#alert-modal-content').text('copy to clipboard');
            $('#myModal').modal('show');
            
        }

        function closeModal(){
            $('#myModal').modal('hide');
        }

    </script>
@endsection