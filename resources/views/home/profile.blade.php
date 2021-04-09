@extends('layouts.part')

@push('link')

@endpush

@section('title')
<h2>PROFIL</h2>
@endsection

@section('breadcrumb')
<li class="active"><i class="material-icons">person</i> Profil </li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>PROFIL - {{ Auth::user()->name }}</h2>
            </div>

            <div class="body">
            	<ul class="nav nav-tabs tab-nav-right" role="tablist">
                    <li role="presentation" class="active"><a href="#profil-settings" data-toggle="tab">Atur Profil</a></li>
                    <li role="presentation"><a href="#change-pass" data-toggle="tab">Ubah Password</a></li>
                </ul>

                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane fade in active" id="profil-settings">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-condensed">
			            		<tr>
			            			<td style="width: 6%;" align="center"><i class="material-icons">person</i></td>
			            			<td>Nama</td>
			            			<td>{{ Auth::user()->name }}</td>
			            		</tr>
			            		<tr>
			            			<td style="width: 6%;" align="center"><i class="material-icons">person_outline</i></td>
			            			<td>Username</td>
			            			<td>{{ Auth::user()->username }}</td>
			            		</tr>
			            		<tr>
			            			<td style="width: 6%;" align="center"><i class="material-icons">person_pin</i></td>
			            			<td>Role</td>
			            			<td>{{ Auth::user()->role }}</td>
			            		</tr>
			            		<tr>
			            			<td style="width: 6%;" align="center"><i class="material-icons">email</i></td>
			            			<td>E-mail</td>
			            			<td>{{ Auth::user()->email }}</td>
			            		</tr>
			            	</table>
                        </div>
                        <div align="center">
						    <button type="button" name="edit_profil" id="edit_profil" class="btn btn-info btn-sm">
								<i class="material-icons">edit</i><span class="icon-name">Ubah Data Profil</span>
							</button>
						</div>
                    </div>

                    <div role="tabpanel" class="tab-pane fade" id="change-pass">
                        <span id="form_result_pass"></span>
						<form id="ubah_pass_form" method="POST" class="form-horizontal">
			                @csrf
			                
			                <div class="form-group">
			                    <label for="current_password" class="col-sm-2 control-label">Password Saat Ini</label>
			                     <div class="col-sm-9">
		                            <div class="form-line">
		                                <input id="current_password" type="password" class="form-control" name="current_password" required placeholder="Masukkan Password">
		                                @if ($errors->has('current_password'))
					                        <span class="help-block">
					                            <strong>{{ $errors->first('password') }}</strong>
					                        </span>
					                    @endif
		                            </div>
		                        </div>
	                            <div class="col-sm-1">
	                                <span toggle="#password-field" id="password-field-1" class="toggle-password" style="cursor: pointer;"><i class="material-icons">visibility</i></span>
	                            </div>
			                </div>
			 				
			                <div class="form-group">
			                    <label for="password" class="col-sm-2 control-label">Password Baru</label>
			                     <div class="col-sm-9">
		                            <div class="form-line">
		                                <input id="password" type="password" class="form-control" name="password" required placeholder="Masukkan minimal 8 karakter">
		                                @if ($errors->has('password'))
					                        <span class="help-block">
					                            <strong>{{ $errors->first('password') }}</strong>
					                        </span>
					                    @endif
		                            </div>
		                        </div>
	                            <div class="col-sm-1">
	                                <span toggle="#password-field" id="password-field-2" class="toggle-password" style="cursor: pointer;"><i class="material-icons">visibility</i></span>
	                            </div>
			                </div>
			 				
			                <div class="form-group">
			                    <label for="password-confirm" class="col-sm-2 control-label">Konfirmasi Password Baru</label>
			                     <div class="col-sm-9">
		                            <div class="form-line">
		                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required placeholder="Konfirm Password baru">
					                    @if ($errors->has('password_confirmation'))
					                        <span class="help-block">
					                            <strong>{{ $errors->first('password_confirmation') }}</strong>
					                        </span>
					                    @endif
		                            </div>
		                        </div>
	                            <div class="col-sm-1">
	                                <span toggle="#password-field" id="password-field-3" class="toggle-password" style="cursor: pointer;"><i class="material-icons">visibility</i></span>
	                            </div>
			                </div>
			 				
			                <div class="form-group">
			                    <div class="col-12 text-center">
			                        <button class="btn btn-danger" type="submit">Submit - Ubah Password</button>
			                    </div>
			                </div>
			            </form>
                    </div>
                </div>
            </div> 
            <!-- end body -->

        </div>
    </div>
</div>

<!-- uabh profil modal -->
<div id="ubahProfilModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Ubah Data Profil</h4>
			</div>
			<div class="modal-body">
				<span id="form_result"></span>
				<form method="post" id="ubah_data_form">
					@csrf

					<div class="form-group">
						<label class="control-label">Nama</label>
						<div class="form-line col-md-8">
							<input type="text" name="name" id="name" class="form-control">
						</div>
					</div><br>
					<div class="form-group">
						<label class="control-label">Username</label>
						<div class="form-line col-md-8">
							<input type="text" name="username" id="username" class="form-control">
						</div>
					</div><br>
					<div class="form-group">
						<label class="control-label">Email</label>
						<div class="form-line col-md-8">
							<input type="text" name="email" id="email" class="form-control">
						</div>
					</div>
					<br>
					<div class="form-group" align="center">
						<input type="hidden" name="action" id="action" value="Add">
						<input type="hidden" name="hidden_id" id="hidden_id">
						<input type="submit" name="action_button" id="action_button" class="btn btn-info" value="Ubah">
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

@endsection

@push('script')
<script src="{{ asset('/js/profile.js') }}"></script>
@endpush