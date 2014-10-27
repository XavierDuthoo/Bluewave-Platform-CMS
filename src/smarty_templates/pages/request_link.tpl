<div style="text-align: center; margin-top: 40px;">
    <img src="http://localhost/xd/bluewave-platform/src/img/bluewave.png" width="250" alt="Staels-Borco" />

    <div class="panel" style="width: 95%; max-width: 400px; text-align: center; margin: 40px auto; padding: 40px 20px;">
        <p style="margin: 20px auto 0px auto; line-height: 25px; width: 95%;">
        Met welk email adres bent u geregistreerd? We sturen een reset link naar dit email adres.<br/>
        {if !empty($emailNotFound)}<span class="error">Dit email adres werd niet gevonden</span>{/if}
        {if !empty($emailSend)}<span class="success">De email is verstuurd! Check ook uw spam folder indien u hem niet vindt.</span>{/if}
        </p>

        <form action="index.php?page=users&amp;action=request_link" class="form-horizontal" method="POST" style="width: 95%; max-width: 400px; text-align: center; margin: 30px auto 0px auto;">
            <div class="form-group" style="margin: 0 auto; width: 100%;">
                <label for="email">Email adres:</label>
                <input type="email" name="email" required id="email" value="" placeholder="" class="form-control" />
            </div>

            <div class="form-footer" style="margin-top: 15px;">
                <a href="index.php?page=users&amp;action=index" class="btn btn-default">Annuleren</a>
                <input type="submit" class="btn btn-primary" value="Zend email">
            </div>
        </form>
    </div>
</div>