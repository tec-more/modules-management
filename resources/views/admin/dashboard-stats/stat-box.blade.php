<div class="col-lg-3 col-md-4 col-sm-6">
    <div class="small-box bg-green">
        <div class="inner">
            <h3>{{ $count or 0 }}</h3>
            <p>插件</p>
        </div>
        <div class="icon">
            <i class="icon-paper-plane"></i>
        </div>
        <a href="{{ route('admin::plugins.index.get') }}" class="small-box-footer">
            更多 <i class="fa fa-arrow-circle-right"></i>
        </a>
    </div>
</div>
