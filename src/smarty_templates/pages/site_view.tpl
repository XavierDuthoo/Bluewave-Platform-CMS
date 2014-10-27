<div class="pageheader">
    <h2><i class="fa fa-globe"></i> {$site.name} <span>Applicaties</span></h2>
    <div class="breadcrumb-wrapper">
        <span class="label">U bevindt zich hier:</span>
        <ol class="breadcrumb">
            <li><a href="index.php?page=sites&amp;action=index">Applicaties</a></li>
            <li class="active">{$site.name}</li>
        </ol>
    </div>
</div>

<div class="contentpanel site-dashboard" data-siteid="">
    <div class="row"><div class="col-md-12 saved-wrapper"></div></div>

    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Gebruikers</h3>
                </div>

                <div class="panel-body">
                    <p>Deze gebruikers zien deze applicatie op hun systeem als ze inloggen.</p>
                    <table class="table table-striped mb30">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Naam</th>
                                <th>Bedrijf</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody>
                            {foreach $site.no_sort.users as $user}
                                <tr>
                                    <td>{$user.id}</td>
                                    <td>{$user.firstname} {$user.lastname}</a></td>
                                    <td>{$user.company}</td>
                                    <td class="table-action">
                                        {if $smarty.session.bluewaveAccountType == 'superadmin'}
                                            <a href="index.php?page=users&amp;action=delete&amp;id={$user.id}" class="delete-row">
                                                <i class="fa fa-trash-o"></i>
                                            </a>
                                        {/if}
                                    </td>
                                </tr>
                            {/foreach}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-3 col-md-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Netwerk naam</h3>
                </div>

                <div class="panel-body">
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolorem, necessitatibus, molestiae quia similique praesentium odit illo laudantium quos quod error tenetur incidunt harum.</p>

                    <form action="index.php?page=sites&amp;action=edit" class="ajax-update-simple" data-sortable="false" data-context="ssid" data-siteid="{$site.id}" data-id="" style="margin-top: 20px;">
                        <select id="netwerken">
                            {foreach $site.no_sort.ssids as $ssid}                       
                                    <option data-id="{$ssid->_id}" value="">{$ssid->name}</option>                          
                            {/foreach}
                        </select>
                        <input type="text" name="ssid" id="ssid" autocomplete='off' value="{$site.no_sort.ssids[0]->name}" placeholder="" class="form-control" />
                        <input type="submit" class="btn btn-primary ssid" value="Opslaan" style="margin-top:7px;">
                    </form>
                </div>
            </div>
        </div>

        <div class="col-sm-3 col-md-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Landingspagina</h3>
                </div>
                <div class="panel-body">
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolore, velit similique vitae unde ut cumque facilis iure magni facere quaerat?</p>
                    <form action="index.php?page=sites&amp;action=edit" class="ajax-update-simple" data-sortable="false" data-context="landing" data-siteid="" data-id="" style="margin-top: 20px;">
                        {foreach $site.no_sort.settings as $setting}                                              
                            {if isset($setting->redirect_url)}                        
                                <input type="url" name="landing" required id="landing" value="{$setting->redirect_url}" placeholder="http://url.be" class="form-control" />
                            {/if}
                        {/foreach}
                        <input type="submit" class="btn btn-primary landingpage" value="Opslaan" style="margin-top:7px;">
                    </form>
                </div>
            </div>
        </div>

        <div class="col-sm-3 col-md-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Popup</h3>
                </div>

                <div class="panel-body">
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ratione, temporibus, id aspernatur quas labore odit porro unde ipsa sint aut iure hic. Voluptatem!</p>

                   <form action="index.php?page=sites&amp;action=edit" class="ajax-update-simple" data-sortable="false" data-context="popup" data-siteid="{$site.id}" data-id="{$site.no_sort.popup.id}" style="margin-top: 20px;">
                        <div class="ckbox ckbox-primary">
                            <input type="checkbox" {if $site.no_sort.popup.active == 1}value="1"{else}value="0"{/if} id="popup-checkbox" {if $site.no_sort.popup.active == 1}checked="checked"{/if} />
                            <label for="popup-checkbox">Actief?</label>
                        </div>

                        <textarea class="form-control" rows="2" id="popup-content" placeholder="Popup tekst">{$site.no_sort.popup.content}</textarea>
                        <input type="submit" class="btn btn-primary" value="Opslaan" style="margin-top:7px;">
                    </form>
                </div>
            </div>
        </div>

        <div class="col-sm-3 col-md-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Timer</h3>
                </div>

                <div class="panel-body">
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Recusandae distinctio cum asperiores fugit dicta facilis!</p>

                    <form action="index.php?page=sites&amp;action=edit" class="ajax-update-simple" data-sortable="false" data-context="timer" data-siteid="{$site.id}" data-id="{$site.no_sort.timer.id}" style="margin-top: 20px;">
                        <div class="ckbox ckbox-primary">
                            <input type="checkbox" {if $site.no_sort.timer.active == 1}value="1"{else}value="0"{/if} id="timer-checkbox" {if $site.no_sort.timer.active == 1}checked="checked"{/if} />
                            <label for="timer-checkbox">Actief?</label>
                        </div>

                        <input type="number" id="timer-seconds" class="spinner-input" data-initvalue="{$site.no_sort.timer.seconds}" data-rule="quantity" /><br/>
                        <input type="submit" class="btn btn-primary" value="Opslaan" style="margin-top:7px;">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <hr />

    <div class="row">
        <div class="cold-md-12 col-sm-12">
            <div class="btn-group mr5 pull-right">
                <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
                    Blok toevoegen <span class="caret"></span>
                </button>
                <ul class="dropdown-menu add-block" role="menu">
                    <li><a href="#" data-context="agenda">Agenda</a></li>
                    <li><a href="#" data-context="document">Document</a></li>
                    <li><a href="#" data-context="paragraph">Paragraaf</a></li>
                    <li><a href="#" data-context="slider">Slider</a></li>
                </ul>
            </div>

            {if !empty($site.url)}
                <div class="btn-group mr5 pull-right">
                    <a href="{$site.url}" class="btn btn-primary" style="margin-right: 5px;">Bekijk applicatie</a>
                </div>
            {/if}
        </div>
    </div>

    <div class="content-blocks">    
        <div class="panel panel-default" data-context="agenda" data-siteid="" data-blockid="" data-orderid="" data-new="false" style="position: relative;">
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
                        <input type="hidden" name="site_id" id="site_id" value="" >
                        <input type="hidden" name="id" id="id" value="" >
                        <div class="col-md-12">
                            <input type="text" name="name" id="name" placeholder="Agenda naam" value="" class="form-control">
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

        {foreach $site.sorted as $block}                
            {if $block.context == 'agenda'}
                <div class="panel panel-default" data-context="agenda" data-siteid="{$site.id}" data-blockid="{$block.id}" data-orderid="{$block.order_number}" data-new="false" style="position: relative;">
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
                                <input type="hidden" name="site_id" id="site_id" value="{$site.id}" >
                                <input type="hidden" name="id" id="id" value="{$block.id}" >
                                <div class="col-md-12">
                                    <input type="text" name="name" id="name" placeholder="Agenda naam" value="{$block.name}" class="form-control">
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

            {elseif $block.context == 'document'}
                <div class="panel panel-default" data-context="document" data-siteid="{$site.id}" data-blockid="{$block.id}" data-orderid="{$block.order_number}" data-new="false">
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
                                    <input type="text" name="name" id="name" placeholder="Naam document" value="{$block.name}" class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Document</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <input type="text" class="form-control" value="{$block.url}" disabled="disabled" />
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
                                        <input type="text" class="form-control" value="{$block.cover}" disabled="disabled" />
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
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

            {elseif $block.context == 'paragraph'}

                <div class="panel panel-default last-added" data-context="paragraph" data-siteid="{$site.id}" data-blockid="{$block.id}" data-orderid="{$block.order_number}" data-new="false">
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
                                    <input type="text" name="title" id="title" placeholder="Titel paragraaf" value="{$block.title}" class="form-control">
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
                                    <textarea id="wysiwyg" placeholder="Typ hier de paragraaf..." name="content" class="form-control" rows="10">{$block.content}</textarea>
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

            {/if}
        {/foreach}

    </div>
</div>
