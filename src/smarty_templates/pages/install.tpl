<div class="col-lg-4 col-offset-4" style="padding-top: 30px;">
    <form action="index.php?page=install&amp;action=save" method="POST" id="installForm">
        <fieldset>
            <legend>Database gegevens</legend>
            <div class="alert alert-info">Er werden nog geen db gegevens gevonden omdat het de eerste keer is dat u dit CMS opent.</div>

            <div class="alert alert-danger" id="warning-uri">this.URI is nog niet ingevuld voor dit CMS systeem in main.js, zolang u deze gegevens niet invult kan dit CMS zich niet installeren. Stel this.URI gelijk aan: <span id="hostname"></span> en refresh de pagina</div>

            <div class="form-group">
                <label for="databaseHost">Database host</label>
                <input type="text" name="databaseHost" class="form-control" id="databaseHost" required placeholder="Localhost waarschijnlijk" value="{$smarty.post.databaseHost|@default:''}">
            </div>

            <div class="form-group">
                <label for="databaseNaam">Database naam</label>
                <input type="text" name="databaseNaam" class="form-control" id="databaseNaam" required placeholder="Naam van de database" value="{$smarty.post.databaseNaam|@default:''}">
            </div>

            <div class="form-group">
                <label for="databaseUser">Database user</label>
                <input type="text" name="databaseUser" class="form-control" id="databaseUser" required placeholder="Username van database" value="{$smarty.post.databaseUser|@default:''}">
            </div>

            <div class="form-group">
                <label for="databasePassword">Database password</label>
                <input type="password" name="databasePassword" class="form-control" id="databasePassword" required placeholder="Wachtwoord van database">
            </div>

            <legend>Hardware gegevens</legend>
            <div class="form-group">
                <label for="equipment">Equipment</label>
                <select class="form-control" id="equipment" name="equipment">
                    <option value="meraki">Meraki</option>
                    <option value="ubnt">UBNT</option>
                </select>
            </div>
            <div class="form-group">
                <label for="key">API Key</label>
                <input type="text" name="key" value="" class="form-control" placeholder="API Key" id="key" />
            </div>
            <input type="hidden" id="userID" name="userID" />
            <input type="submit" class="btn btn-primary" disabled="disabled" value="Gegevens opslaan" id="btnStartInstall" />
        </fieldset>
    </form>
</div>