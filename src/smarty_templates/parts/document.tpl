<div class="panel panel-default last-added" data-context="document" data-siteid="" data-blockid="" data-orderid="0" data-new="true">
    <div class="panel-heading">
        <div class="panel-btns">
            <a href="#" class="panel-close">&times;</a>
            <a href="#" class="minimize">&minus;</a>
        </div>
        <h4 class="panel-title">Document</h4>
    </div>
    <form action="#" method="POST" enctype="multipart/form-data" class="ajax-file-upload form-horizontal form-bordered">
        <div class="panel-body">
            <div class="form-group">
                <input type="hidden" name="site_id" id="site_id" >
                <input type="hidden" name="id" id="id" >
                <div class="col-md-12">
                    <input type="text" name="name" id="name" placeholder="Naam document" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">Document</label>
                <div class="col-sm-9">
                    <div class="input-group">
                        <input type="text" class="form-control" disabled="disabled" />
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-default js-button-trigger-file">Kies bestand</button>
                            <div class="input-group hide">
                                <input type="file" id="file" name="files[]" class="input input--file" tabindex="-1" />
                            </div>
                        </span>
                    </div>
                    <span class="help-block">Het document dat de klant te zien krijgt als hij het aanklikt.</span>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">Poster</label>
                <div class="col-sm-4">
                    <div class="input-group">
                        <input type="text" class="form-control" disabled="disabled" />
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-default js-button-trigger-file">Kies bestand</button>
                            <div class="input-group hide">
                                <input type="file" id="file" name="files[]" class="input input--file" tabindex="-1" />
                            </div>
                        </span>
                    </div>
                    <span class="help-block">De foto die de klant ziet op het portaal (enkel png en jpg)</span>
                </div>
            </div>
        </div>

        <div class="panel-footer">
            <div class="form-group" style="margin-bottom: 0px; padding: 0;">
                <div class="col-md-12">
                    <input type="submit" class="btn btn-primary pull-right" value="Document toevoegen">
                    <!-- <a class="btn btn-primary start" id="up_btn">
                        <i class="glyphicon glyphicon-upload"></i>
                        <span>Start upload</span>
                    </a> -->
                </div>
            </div>
        </div>
    </form>
</div>
