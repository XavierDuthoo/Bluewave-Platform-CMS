<div class="pageheader">
    <h2><i class="fa fa-users"></i> Gebruikers <span>Overzicht</span></h2>
    <div class="breadcrumb-wrapper">
        <span class="label">U bevindt zich hier:</span>
        <ol class="breadcrumb">
            <li class="active">Gebruikers</li>
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
    <div class="alert alert-success hide" id="ajaxDelete">De gebruiker werd verwijderd!</div>
    
    <div class="table-responsive">
        <table class="table table-striped mb30">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Voornaam</th>
                    <th>Achternaam</th>
                    <th>Email adres</th>
                    <th>Taal</th>
                    <th>Actief</th>
                    <th>Inloggen</th>
                    <th>Type</th>
                    <th>Toegevoegd op</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                {foreach $users as $user}
                    <tr>
                        <td>{$user.id}</td>
                        <td>{$user.firstname}</td>
                        <td>{$user.lastname}</td>
                        <td>{$user.email}</td>
                        <td>{$user.language}</td>
                        <td>
                            {if $user.activated}
                                <i class="fa fa-check"></i>
                            {else}
                                <i class="fa fa-times"></i>
                            {/if}
                        </td>
                        <td>
                            {if $user.banned}
                                <i class="fa fa-times"></i>
                            {else}
                                <i class="fa fa-check"></i>
                            {/if}
                        </td>
                        <td>{$user.type}</td>
                        <td>{date('d/m/Y - H:i', strtotime($user.created))}</td>
                        <td class="table-action">
                            <a href="index.php?page=users&amp;action=edit&amp;id={$user.id}">
                                <i class="fa fa-pencil"></i>
                            </a>
                            {if $user.id !== $smarty.session.bluewavePlatformUserID && $user.type != 'superadmin'}
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


    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">Gebruiker toevoegen</h4>
            <p>Voeg eerst gebruikers toe en voeg ze dan toe onder sites om te bepalen welke onderdelen ze kunnen zien. Indien u dit niet doet heeft de gebruiker geen toegang tot het platform. Gebruikers krijgen een email als ze toegevoegd worden om een wachtwoord te kiezen.</p>
        </div>


        <form class="form-horizontal form-bordered validate" method="POST" action="index.php?page=users&amp;action=add">
        <div class="panel-body panel-body-nopadding">


            <div class="form-group">
                <label class="col-sm-3 control-label" for="firstname">Voornaam  <span class="asterisk">*</span></label>
                <div class="col-sm-6">
                    <input type="text" name="firstname" required id="firstname" placeholder="" class="form-control" />
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label" for="lastname">Achternaam  <span class="asterisk">*</span></label>
                <div class="col-sm-6">
                    <input type="text" name="lastname" required id="lastname" placeholder="" class="form-control" />
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label" for="email">Email  <span class="asterisk">*</span></label>
                <div class="col-sm-6">
                    <input type="email" id="email" required name="email" placeholder="" class="form-control" />
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label" for="password">Wachtwoord</label>
                <div class="col-sm-6">
                    <input type="text" placeholder="Door gebruiker gekozen via link in activatie email" id="password" class="form-control" disabled="" />
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label" for="company">Bedrijf</label>
                <div class="col-sm-6">
                    <input type="text" name="company" id="company" placeholder="" class="form-control" />
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label" for="language">Voorkeurstaal</label>
                <div class="col-sm-6">
                    <select class="form-control input-sm mb15" name="language" id="language">
                        <option value="nl">Nederlands</option>
                        <option value="fr">Frans</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label" for="type">Rol</label>
                <div class="col-sm-6">
                    <select class="form-control input-sm mb15" name="type" id="type">
                        <option value="user">Gebruiker</option>
                        <option value="admin">Administrator</option>
                        <option value="superadmin">Super Administrator</option>
                    </select>
                    <span class="help-block">Gebruikers kunnen enkel hun eigen deel beheren, administrators kunnen gebruikers beheren en super administrators hebben alle rechten.</span>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label" for="type">Site</label>
                <div class="col-sm-6">
                    <select class="form-control input-sm mb15" name="type" id="type">
                        <option value="user">Gebruiker</option>
                        <option value="admin">Administrator</option>
                        <option value="superadmin">Super Administrator</option>
                    </select>
                </div>
            </div>


        </div><!-- panel-body -->

        <div class="panel-footer">
            <div class="row">
                <div class="col-sm-6 col-sm-offset-3">
                    <input type="submit" class="btn btn-primary" value="Toevoegen">
                </div>
            </div>
        </div><!-- panel-footer -->
        </form>

    </div><!-- panel -->
</div>