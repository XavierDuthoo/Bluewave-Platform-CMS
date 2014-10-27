<div class="pageheader">
    <h2><i class="fa fa-globe"></i> {$site.name} <span>Aanpassen</span></h2>
    <div class="breadcrumb-wrapper">
        <span class="label">U bevindt zich hier:</span>
        <ol class="breadcrumb">
            <li><a href="index.php?page=sites&amp;action=overview">Applicaties</a></li>
            <li class="active">{$site.name}</li>
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
    {if isset($userEdited)}
    <div class="alert alert-success">De gebruiker werd aangepast!</div>
    {/if}
    {if isset($userExists)}
    <div class="alert alert-danger">Deze gebruiker is a geregistreerd.</div>
    {/if}

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">Site toevoegen</h4>
            <p>Nieuwe installatie? Voeg een site toe en stel in welke personen hem kunnen zien.</p>
        </div>


        <form class="form-horizontal form-bordered validate" method="POST" action="index.php?page=sites&amp;action=editinfo">
            <div class="panel-body panel-body-nopadding">
                <input type="hidden" name="id" value="{$site.id}">

                <div class="form-group">
                    <label class="col-sm-3 control-label" for="sitename">Site naam  <span class="asterisk">*</span></label>
                    <div class="col-sm-6">
                        <input type="text" name="sitename" value="{$site.name}" required id="sitename" placeholder="" class="form-control" />
                        <span class="help-block">Gebruiksvriendelijke korte naam die de klant ziet als hij zijn applicatie bekijkt.</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label"  for="identifier">API Identifier  <span class="asterisk">*</span></label>
                    <div class="col-sm-6">
                        <input type="text" id="identifier" name="identifier" disabled value="{$site.identifier}" required id="identifier" placeholder="" class="form-control" />
                        <span class="help-block">Naam van de site op de installatie, wordt gebruikt bij de API calls, als dit fout is werkt er gewoon niets.</span>
                    </div>
                </div>
            </div><!-- panel-body -->

            <div class="panel-footer">
                <div class="row">
                    <div class="col-sm-6 col-sm-offset-3">
                        <input type="submit" class="btn btn-primary wijzigen" value="Wijzigen">
                    </div>
                </div>
            </div><!-- panel-footer -->
        </form>

    </div><!-- panel -->
</div>
