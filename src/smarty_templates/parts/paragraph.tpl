<div class="panel panel-default last-added" data-context="paragraph" data-siteid="" data-blockid="" data-orderid="0" data-new="true">
    <div class="panel-heading">
        <div class="panel-btns">
            <a href="#" class="panel-close">&times;</a>
            <a href="#" class="minimize">&minus;</a>
        </div>
        <h4 class="panel-title">Paragraaf</h4>
    </div>
    <form action="index.php?page=sites" method="POST" class="ajax-update-complex form-horizontal form-bordered">
        <div class="panel-body">
            <div class="form-group">
                <input type="hidden" name="site_id" id="site_id" >
                <input type="hidden" name="id" id="id" >
                <div class="col-md-11">
                    <input type="text" name="title" id="title" placeholder="Titel paragraaf" class="form-control">
                    <input type="hidden" name="title_color" id="title_color">
                </div>
                <div class="col-md-1">
                    <span id="colorSelector" class="colorselector">
                        <span></span>
                    </span>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-12">
                    <textarea id="wysiwyg" placeholder="Typ hier de paragraaf..." name="content" class="form-control" rows="10"></textarea>
                </div>
            </div>
        </div>

        <div class="panel-footer">
            <div class="form-group" style="margin-bottom: 0px; padding: 0;">
                <div class="col-md-12">
                    <input type="submit" class="btn btn-primary pull-right" value="Paragraaf toevoegen">
                </div>
            </div>
        </div>
    </form>
</div>