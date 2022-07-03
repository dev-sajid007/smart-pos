
<div class="row">
    <div class="col-sm-8 mx-auto">
        <form action="" method="get">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">Search User</span>
                </div>
                <input class="form-control form-control-md" name="search" placeholder="Search anything.........." value="{{ request('search') }}">
                <div class="input-group-append">
                    <button style="cursor: pointer" type="submit" class="input-group-text"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>
    </div>
</div>
