<div class="pageheader">
    <h2><i class="fa fa-globe"></i> Applicaties <span>Overzicht</span></h2>
    <div class="breadcrumb-wrapper">
        <span class="label">U bevindt zich hier:</span>
        <ol class="breadcrumb">
            <li class="active">Applicaties</li>
        </ol>
    </div>
</div>

<div class="contentpanel">
    {if isset($userAdded)}
        <div class="alert alert-success" id="added">De gebruiker werd aangemaakt en heeft een email gekregen om zijn account te activeren!</div>
    {/if}
    {if isset($deleteSuccess)}
        <div class="alert alert-success">De gebruiker werd verwijderd!</div>
    {/if}
    {if isset($smarty.get.message) && $smarty.get.message == 'editcomplete'}
        <div class="alert alert-success">De gebruiker werd aangepast!</div>
    {/if}
    {if isset($userExists)}
        <div class="alert alert-danger">Deze gebruiker is a geregistreerd.</div>
    {/if}
    <div class="alert alert-success hide" id="ajaxDelete">De site werd verwijderd!</div>

    <div class="table-responsive">
        <table class="table table-striped mb30">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Naam</th>
                    <th>API Identifier</th>
                    <th>URL</th>
                </tr>
            </thead>
            <tbody>
                {foreach $all_sites as $site}
                    {if !isset($site->key)}
                        <tr>
                            <td>{$site->_id}</td>
                            <td><a href="index.php?page=sites&amp;action=view&amp;id={$site->_id}&amp;name={$site->name}">{if isset($site->desc)}{$site->desc}{else}{$site->name}{/if}</a></td>
                            <td>{$site->name}</td>
                            <td class="table-action">
                                <a href="index.php?page=sites&amp;action=editinfo&amp;id={$site->_id}&amp;name={if isset($site->desc)}{$site->desc}{else}{$site->name}{/if}&amp;identifier={$site->name}">
                                    <i class="fa fa-pencil"></i>
                                </a>
                                {if $smarty.session.bluewaveAccountType == 'superadmin'}
                                    <a href="index.php?page=sites&amp;action=delete&amp;id={$site->_id}" data-deleteInSidebar="{$site->_id}" data-site="{$site->name}" class="delete-row">
                                        <i class="fa fa-trash-o"></i>
                                    </a>
                                {/if}
                            </td>
                        </tr>
                    {/if}
                {/foreach}
            </tbody>
        </table>
    </div>


    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">Site toevoegen</h4>
            <p>Nieuwe installatie? Voeg een site toe en stel in welke personen hem kunnen zien.</p>
        </div>


        <form class="form-horizontal form-bordered validate" method="POST" action="index.php?page=sites">
        <div class="panel-body panel-body-nopadding">


            <div class="form-group">
                <label class="col-sm-3 control-label" for="sitename">Site naam  <span class="asterisk">*</span></label>
                <div class="col-sm-6">
                    <input type="text" name="sitename" required id="sitename" placeholder="" class="form-control" />
                    <span class="help-block">Gebruiksvriendelijke korte naam die de klant ziet als hij zijn applicatie bekijkt.</span>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label" for="identifier">API Identifier  <span class="asterisk">*</span></label>
                <div class="col-sm-6">
                    <input type="text" name="identifier" required id="identifier" placeholder="" class="form-control" />
                    <span class="help-block">Naam van de site op de installatie, wordt gebruikt bij de API calls, als dit fout is werkt er gewoon niets.</span>
                </div>
            </div>

            <!--<div class="form-group">
                <label class="col-sm-3 control-label" for="url">URL</label>
                <div class="col-sm-6">
                    <input type="text" name="url" id="url" placeholder="" class="form-control" />
                    <span class="help-block">Naam van de html pagina die als reclame komt, waarschijnlijk op jullie servers.</span>
                </div>
            </div>-->
        </div><!-- panel-body -->

        <div class="panel-footer">
            <div class="row">
                <div class="col-sm-6 col-sm-offset-3">
                    <input type="submit" class="btn btn-primary add-site" value="Toevoegen">
                </div>
            </div>
        </div><!-- panel-footer -->
        </form>

    </div><!-- panel -->
</div>
