<div class="panel panel-default last-added" data-context="agenda" data-siteid="" data-blockid="" data-orderid="0" data-new="true" style="position: relative;">
    <div class="panel-heading">
        <div class="panel-btns">
            <a href="#" class="panel-close">&times;</a>
            <a href="#" class="minimize">&minus;</a>
        </div>
        <h4 class="panel-title">Agenda</h4>
    </div>
    <form action="index.php?page=sites" method="POST" class="ajax-update-complex form-horizontal form-bordered">
        <div class="panel-body">
            <div class="form-group">
                <input type="hidden" name="site_id" id="site_id" >
                <input type="hidden" name="id" id="id" >
                <div class="col-md-12">
                    <input type="text" name="name" id="name" placeholder="Agenda naam" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <h4>Evenementen in deze agenda:</h4>
                <button type="button" id="add-event" class="btn btn-default" data-toggle="modal" data-target="#addEvent" style="position: absolute; right: 30px; top: 174px;">Evenement toevoegen</button>
            </div>
            
        </div>

        <div class="panel-footer">
            <div class="form-group" style="margin-bottom: 0px; padding: 0;">
                <div class="col-md-12">
                    <input type="submit" class="btn btn-primary pull-right" value="Agenda toevoegen">
                </div>
            </div>
        </div>
    </form>

    <!-- Modal -->
    <div class="modal fade" id="addEvent" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Evenement toevoegen</h4>
                </div>
                <div class="modal-body">
                    <form action="#" method="POST" class="ajax-file-upload add-event">
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="event_title" style="position: relative; top: 10px;">Evenement</label>
                            <div class="col-sm-9">
                                <input type="text" name="event_title" id="event_title" placeholder="Naam voor het evenement" class="form-control" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="event_title" style="position: relative; top: 10px;">Start</label>
                            <div class='col-sm-9'>
                                <div class="form-group">
                                    <div class='input-group date datetimepicker' id='datetimepicker2'>
                                        <input type='text' class="form-control" id="start_date" placeholder="Start van het evenement" />
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="event_title" style="position: relative; top: 10px;">Eind</label>
                            <div class='col-sm-9'>
                                <div class="form-group">
                                    <div class='input-group date datetimepicker' id='datetimepicker2'>
                                        <input type='text' class="form-control" id="end_date" placeholder="Einde van het evenement" />
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="fb_url" style="position: relative; top: 10px;">FB evenement url</label>
                            <div class="col-sm-9">
                                <input type="text" name="fb_url" id="fb_url" placeholder="Naam voor het evenement" class="form-control" />
                                <span class="help-block">Bestaat uw evenement ook op facebook? Plak dan hier de link.</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label"  style="position: relative; top: 10px;">Foto</label>
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
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12">
                                <textarea id="wysiwyg" placeholder="Beschrijving voor het evenement. Schrijf in een korte paragraaf neer wat je nog wilt meedelen." name="content" class="form-control" rows="10"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Annuleren</button>
                    <button type="button" class="btn btn-primary trigger-add-event">Evenement toevoegen</button>
                </div>
            </div>
        </div>
    </div>
</div>