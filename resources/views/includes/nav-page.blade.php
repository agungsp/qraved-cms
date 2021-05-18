<div class="card mb-4">
    <div class="card-body d-flex justify-content-between p-2">
        <div class="form-inline">
            <input type="search" id="search" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="search">
            <button id="btnSearch" class="btn qraved-btn-primary ml-1">
                <i class="fa fa-search"></i>
            </button>
        </div>

        <div>
            @if ($showBtnExport ?? false)
                <button id="btnExport" class="btn btn-success">
                    <i class="fas fa-file-csv"></i> Export
                </button>
            @endif

            @if ($showBtnNew ?? true)
                <button id="btnNew" class="btn qraved-btn-primary">
                    <i class="fa fa-plus"></i> New
                </button>
            @endif
        </div>
    </div>
</div>
