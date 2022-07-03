
<div class="row">
    <div class="col-md-12">
        <form class="form-horizontal" action="{{ route('change.user.password') }}" method="POST">
            @csrf
            <input type="hidden" name="user_id_edit" value="{{ $user->id }}">
            <div class="tile">
                <h3 class="tile-title"><i class="fa fa-lock"></i> Change Password</h3>
                <div class="tile-body">
                    <div class="form-group row add_asterisk">
                        <label class="control-label col-md-3">New Password</label>
                        <div class="col-md-8">
                            <div class="form-group">
                                <div class="input-group" >
                                    <input name="password" class="form-control password-toogle" type="password" placeholder="Enter Password" value="">
                                    <div class="input-group-append">
                                        <span style="cursor: pointer" toggle="#input-pwd" class="input-group-text fa fa-fw fa-eye toggle-password"></span>
                                    </div>
                                </div>
                              </div>
                            <div class="text-danger">{{ $errors->has('password') ? $errors->first('password'):'' }}</div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="control-label col-md-3"></label>
                        <div class="col-md-8">
                            <button class="btn btn-primary pull-right" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Update Password</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
