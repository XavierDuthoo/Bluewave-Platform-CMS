<div style="text-align: center; margin-top: 40px;">
    <img src="img/bluewave.png" width="250" alt="Bluewave logo" />

    <div class="panel" style="width: 95%; max-width: 400px; text-align: center; margin: 40px auto; padding: 40px 20px;">
        {if isset($smarty.get.first_time)}
            <a href="#" class="avatar-preview" style="">
                <div id="uploadPreview"></div>
                <i class="fa fa-plus"></i>
            </a>
            <p style="margin: 20px auto 0px auto; line-height: 25px; width: 95%;">Kies een wachtwoord met <span class="length">minimum zes karakters voor uw account.</span> U kunt ook een foto installen als avatar. <span class="notequal error hide">De gekozen wachtwoorden komen niet overeen</span></p>
        {else}
            <p style="margin: 20px auto 0px auto; line-height: 25px; width: 95%;">Kies een nieuw wachtwoord voor uw account. <span class="length">Het wachtwoord moet minimum zes karakters hebben.</span> <span class="notequal error hide">De gekozen wachtwoorden komen niet overeen</span></p>
        {/if}

        <form action="index.php?page=users&amp;action=choose_pass&amp;key={$smarty.get.key}" class="form-horizontal choosePass" enctype="multipart/form-data" method="POST" style="width: 95%; max-width: 400px; text-align: center; margin: 30px auto 0px auto;">
            <input type="file" name="avatar" id="avatar" class="preview-file" style="display: block; width: 0; height: 0;" />

            <div class="form-group" style="margin: 0 auto; width: 100%;">
                <label for="password">Wachtwoord:</label>
                <input type="password" name="password" required id="password" value="" placeholder="" class="form-control" />
            </div>
            <br/>
            <div class="form-group" style="margin: 0 auto; width: 100%;">
                <label for="password_repeat">Wachtwoord herhalen:</label>
                <input type="password" name="password_repeat" required id="password_repeat" value="" placeholder="" class="form-control" />
            </div>

            <div class="form-footer" style="margin-top: 15px;">
                <a href="index.php?page=users&amp;action=index" class="btn btn-default">Annuleren</a>
                <input type="submit" class="btn btn-primary" value="Opslaan">
            </div>
        </form>
    </div>
</div>
