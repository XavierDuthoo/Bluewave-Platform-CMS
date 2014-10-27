<div class="page-header statistieken">
    <h2>Statistieken</h2>
</div>

<div class="loader text-center" id="statsLoader">
    <div class="spinner"></div>
    Statistieken laden...
</div>

<div class="stats-wrapper out">
    <div id="chart_users"></div>
    <br/>
    <div id="chart_usage"></div>
</div>
<br/><br/>
<div class="loader text-center out hide" id="moreStats">
    <div class="spinner"></div>
    Nog meer statistieken laden...
</div>

<div class="stats-wrapper-2 col-lg-12 out">
    <div class="row">    
        <div class="col-lg-12">
            <h3 class="text-center users">Totaal gebruikers</h3>
            <div id="piechart" style="width: 900px; height: 300px;"></div>
            <div class='input-group date' id='datetimepicker'>
                <input type='text' class="form-control" />
                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
            </div>               
            <div class="totalusers"><p class="text-center"></p></div>
            <div id="chart_div" style="width: 1200px; height: 500px;"></div>
        </div>
        <div class="col-lg-12">
            <div id='kiesperiode'>
                <span>of bekijk hoeveel gebruikers aanwezig waren tussen een bepaalde periode:</span>
            </div>
            <div class='input-group date' id='datetimepickerstart'>
                <input type='text' class="form-control" />
                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
            </div>
            <div class='input-group date' id='datetimepickerend'>
                <input type='text' class="form-control" />
                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
            </div>
        </div>
    </div>    
</div>

<div class="hide">
    <div id="userID">{$userID}</div>
    <div id="key">{$key}</div>
</div>