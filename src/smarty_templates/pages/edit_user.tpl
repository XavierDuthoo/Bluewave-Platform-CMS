<div class="pageheader">
    <h2><i class="fa fa-users"></i> {$user.firstname} {$user.lastname} <span>Aanpassen</span></h2>
    <div class="breadcrumb-wrapper">
        <span class="label">U bevindt zich hier:</span>
        <ol class="breadcrumb">
            <li><a href="index.php?page=users&amp;action=overview">Gebruikers</a></li>
            <li class="active">{$user.firstname} {$user.lastname}</li>
        </ol>
    </div>
</div>

<div class="contentpanel">
    {if isset($smarty.get.message) && $smarty.get.message == 'updated'}
        <div class="alert alert-success" id="added">Je account werd bijgewerkt</div>
    {/if}

    {if isset($smarty.get.message) && $smarty.get.message == 'noAccess'}
        <div class="alert alert-danger">U heeft geen toegang tot deze pagina!</div>
    {/if}

    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">Gebruiker aanpassen</h4>
        </div>


        <form class="form-horizontal form-bordered validate" method="POST" action="index.php?page=users&amp;action=edit">
        <div class="panel-body panel-body-nopadding">
            <input type="hidden" name="id" value="{$user.id}" />

            <div class="form-group">
                <label class="col-sm-3 control-label" for="firstname">Voornaam  <span class="asterisk">*</span></label>
                <div class="col-sm-6">
                    <input type="text" name="firstname" required id="firstname" value="{$user.firstname}" placeholder="" class="form-control" />
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label" for="lastname">Achternaam  <span class="asterisk">*</span></label>
                <div class="col-sm-6">
                    <input type="text" name="lastname" required id="lastname" value="{$user.lastname}" placeholder="" class="form-control" />
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label" for="email">Email  <span class="asterisk">*</span></label>
                <div class="col-sm-6">
                    <input type="email" id="email" required name="email" value="{$user.email}" placeholder="" class="form-control" />
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label" for="password">Wachtwoord</label>
                <div class="col-sm-6">
                    <input type="password" placeholder="" name="password" id="password" class="form-control" />
                    <span class="help-block">Als u het wachtwoord leeg laat blijft het ongewijzigd. Als u het wachtwoord voor een gebruiker wijzigt krijgt de gebruiker een automatische email.</span>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label" for="company">Bedrijf</label>
                <div class="col-sm-6">
                    <input type="text" name="company" id="company" value="{$user.company}" placeholder="" class="form-control" />
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label" for="language">Voorkeurstaal</label>
                <div class="col-sm-6">
                    <select class="form-control input-sm mb15" name="language" id="language">
                        <option value="nl" {if $user.language == 'nl'}selected="selected"{/if}>Nederlands</option>
                        <option value="fr" {if $user.language == 'fr'}selected="selected"{/if}>Frans</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label" for="type">Rol</label>
                <div class="col-sm-6">
                    <select class="form-control input-sm mb15" name="type" id="type">
                        <option value="user" {if $user.type == 'user'}selected="selected"{/if}>Gebruiker</option>
                        <option value="admin" {if $user.type == 'admin'}selected="admin"{/if}>Administrator</option>
                        <option value="superadmin" {if $user.type == 'superadmin'}selected="superadmin"{/if}>Super Administrator</option>
                    </select>
                    <span class="help-block">Gebruikers kunnen enkel hun eigen deel beheren, administrators kunnen gebruikers beheren en super administrators hebben alle rechten.</span>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label" for="banned"></label>
                <div class="col-sm-6" style="margin-top: 8px;">
                    <div class="ckbox ckbox-default">
                        <input type="checkbox" value="1" id="banned" name="banned" {if $user.banned == 0}checked="checked"{/if} />
                        <label for="banned" style="margin-top: 1px;">Inloggen toestaan</label>
                    </div>
                    <span class="help-block">Hiermee kunt u de gebruiker (tijdelijk) toegang ontzeggen tot het platform. Als u dit uitvinkt zal de gebruiker niet meer kunnen inloggen. De bestaande sessie blijft echter actief dus dit zal enkel effect hebben als hij opnieuw moet inloggen.</span>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label" for="password">Laatste login</label>
                <div class="col-sm-6">
                    <input type="text" placeholder="" value="{date('d/m/Y - H:i', strtotime($user.last_login))}" id="password" class="form-control" disabled="" />
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label" for="password">Laatste aanpassing</label>
                <div class="col-sm-6">
                    <input type="text" placeholder="" id="password" value="{date('d/m/Y - H:i', strtotime($user.modified))}" class="form-control" disabled="" />
                </div>
            </div>


        </div><!-- panel-body -->

        <div class="panel-footer">
            <div class="row">
                <div class="col-sm-6 col-sm-offset-3">
                    <input type="submit" class="btn btn-primary" value="Aanpassen">
                    <a href="index.php?page=users&amp;action=overview" class="btn btn-default">Annuleren</a>
                </div>
            </div>
        </div><!-- panel-footer -->
        </form>

    </div><!-- panel -->
</div>