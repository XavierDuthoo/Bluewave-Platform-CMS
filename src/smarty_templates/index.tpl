<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="shortcut icon" href="img/favicon.png" type="image/png">

  <title>Bluewave Platform</title>

  <link rel="stylesheet" href="css/screen.css">
  <link href="css/vendor/style.default.css" rel="stylesheet">
  <link href="css/vendor/jquery.datatables.css" rel="stylesheet">
  <link href="css/vendor/colorpicker.css" rel="stylesheet"/>
  <link href="css/vendor/bootstrap-fileupload.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="css/vendor/jquery-ui-1.8.16.custom.css">
  <link rel="stylesheet" href="css/vendor/slick.grid.css" />
  <link rel="stylesheet" href="css/vendor/slick.pager.css" />
  <link rel="stylesheet" href="css/vendor/datepicker.css" />
  <!--<link rel="stylesheet" href="css/vendor/footable.core.css" />
  <link rel="stylesheet" href="css/vendor/footable.metro.css" />-->

  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
      <script src="js/vendor/html5shiv.js"></script>
      <script src="js/vendor/respond.min.js"></script>
  <![endif]-->
</head>
<body {if isset($login) && $login == true}class="signin"{/if}>
    <!-- Preloader -->
    <div id="preloader">
        <div id="status"><i class="fa fa-spinner fa-spin"></i></div>
    </div>

    <section>
      {if isset($login) && isset($smarty.get.message) && $smarty.get.message == 'no_access'}
        <div class="alert alert-danger">U hebt geen toegang tot deze pagina. Gelieve in te loggen.</div>
      {/if}

      {if isset($login) && isset($pass_wrong)}
        <div class="alert alert-danger">Ongeldige email/wachtwoord combinatie, gelieve opnieuw te proberen.</div>
      {/if}

      {if isset($login) && isset($invalid_key)}
        <div class="alert alert-danger">Deze link is niet meer geldig... Contacteer iemand van Blue Wave.</div>
      {/if}

      {if isset($login) && isset($pass_created)}
        <div class="alert alert-success">Wachtwoord gewijzigd! U kunt nu inloggen.</div>
      {/if}

      {if !isset($login)}
      <div class="leftpanel">
        <div class="logopanel small">
            <h1><a href="index.php?page=start">Blue Wave Online Marketing</a></span></h1>
          </div>
              
          <div class="leftpanelinner">    
            <!-- This is only visible to small devices -->
            <div class="visible-xs hidden-sm hidden-md hidden-lg">   
              <div class="media userlogged">
                <img alt="" src="img/photos/loggeduser.png" class="media-object">
                <div class="media-body">
                  <h4>John Doe</h4>
                    <span>"Life is so..."</span>
                </div>
              </div>
                
              <h5 class="sidebartitle actitle">Account</h5>
              <ul class="nav nav-pills nav-stacked nav-bracket mb30">
                <li><a href="profile.html"><i class="fa fa-user"></i> <span>Profile</span></a></li>
                <li><a href=""><i class="fa fa-cog"></i> <span>Account Settings</span></a></li>
                <li><a href=""><i class="fa fa-question-circle"></i> <span>Help</span></a></li>
                <li><a href="signout.html"><i class="fa fa-sign-out"></i> <span>Sign Out</span></a></li>
              </ul>
            </div>
            
          {$leftmenu}
        </div>
      </div>

      <div class="mainpanel">
        {$headerbar}
      {/if}
      
        {$content}
      
      {if !isset($login)}
      </div>
      {/if}
    
    </section>

{literal}
    <script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td>
            <span class="preview"></span>
        </td>
        <td>
            <p class="name">{%=file.name%}</p>
            <strong class="error text-danger"></strong>
        </td>
        <td>
            <p class="size">Processing...</p>
            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
        </td>
        <td>
            {% if (!i && !o.options.autoUpload) { %}
                <button class="btn btn-primary start" disabled>
                    <i class="glyphicon glyphicon-upload"></i>
                    <span>Start</span>
                </button>
            {% } %}
            {% if (!i) { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade">
        <td>
            <span class="preview">
                {% if (file.thumbnailUrl) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
                {% } %}
            </span>
        </td>
        <td>
            <p class="name">
                {% if (file.url) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
                {% } else { %}
                    <span>{%=file.name%}</span>
                {% } %}
            </p>
            {% if (file.error) { %}
                <div><span class="label label-danger">Error</span> {%=file.error%}</div>
            {% } %}
        </td>
        <td>
            <span class="size">{%=o.formatFileSize(file.size)%}</span>
        </td>
        <td>
            {% if (file.deleteUrl) { %}
                <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                    <i class="glyphicon glyphicon-trash"></i>
                    <span>Delete</span>
                </button>
                <input type="checkbox" name="delete" value="1" class="toggle">
            {% } else { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>
{/literal}

    <script src="js/vendor/jquery-1.10.2.min.js"></script>
    <script src="js/vendor/jquery-migrate-1.2.1.min.js"></script>
    <script src="js/vendor/jquery-ui-1.10.3.min.js"></script>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script src="js/vendor/bootstrap.min.js"></script>
    <script src="js/vendor/modernizr.min.js"></script>
    <script src="js/vendor/jquery.sparkline.min.js"></script>
    <script src="js/vendor/toggles.min.js"></script>
    <script src="js/vendor/retina.min.js"></script>
    <script src="js/vendor/jquery.cookies.js"></script>
    <script src="js/vendor/moment-with-langs.js"></script>        
    <script src="js/vendor/jquery.autogrow-textarea.js"></script>
    <script src="js/vendor/bootstrap-fileupload.min.js"></script>
    <script src="js/vendor/bootstrap-datepicker.js"></script>
    <script src="js/vendor/jquery.maskedinput.min.js"></script>
    <script src="js/vendor/jquery.tagsinput.min.js"></script>
    <script src="js/vendor/jquery.mousewheel.js"></script>
    <script src="js/vendor/chosen.jquery.min.js"></script>
    <script src="js/vendor/dropzone.min.js"></script>
    <script src="js/vendor/colorpicker.js"></script>

    {if !empty($graphs) && $graphs == true}
      <script src="js/vendor/flot/flot.min.js"></script>
      <script src="js/vendor/flot/flot.resize.min.js"></script>
      <script src="js/vendor/morris.min.js"></script>
      <script src="js/vendor/raphael-2.1.0.min.js"></script>
    {/if}

    <script src="js/vendor/jquery.datatables.min.js"></script>
    <script src="js/vendor/chosen.jquery.min.js"></script>
    <script src="js/vendor/jquery.validate.min.js"></script>

    <script src="js/vendor/wysihtml5-0.3.0.min.js"></script>
    <script src="js/vendor/bootstrap-wysihtml5.js"></script>

    <script src="js/vendor/custom.js"></script>
    <script src="js/vendor/underscore.js"></script>
    <!--{if !empty($smarty.get.page) && $smarty.get.page == 'start'}
      <script src="js/vendor/flot/flot.min.js"></script>
      <script src="js/vendor/flot/flot.resize.min.js"></script>
      <script src="js/vendor/morris.min.js"></script>
      <script src="js/vendor/raphael-2.1.0.min.js"></script>
      <script src="js/vendor/dashboard.js"></script>
    {/if}-->

    <!-- upload functionality -->
    <script src="js/vendor/jquery.event.drag-2.2.js"></script>
    <script src="js/vendor/slick.core.js"></script>
    <script src="js/vendor/slick.grid.js"></script>
    <script src="js/vendor/slick.pager.js"></script>
    <script src="js/vendor/slick.dataview.js"></script>
    <script src="js/vendor/jquery.iframe-transport.js"></script>
    <script src="js/vendor/jquery.fileupload.js"></script>
    <!--<script src="js/vendor/footable/footable.js" type="text/javascript"></script>
    <script src="js/vendor/footable/footable.paginate.js" type="text/javascript"></script>
    <script src="js/vendor/footable/footable.bookmarkable.js" type="text/javascript"></script>
    <script src="js/vendor/footable/footable.filter.js" type="text/javascript"></script>
    <script src="js/vendor/footable/footable.memory.js" type="text/javascript"></script>
    <script src="js/vendor/footable/footable.plugin.template.js" type="text/javascript"></script>
    <script src="js/vendor/footable/footable.sort.js" type="text/javascript"></script>
    <script src="js/vendor/footable/footable.striping.js" type="text/javascript"></script>-->

    <!-- <script src="/site/templates/public/js/vendor/jquery.iframe-transport.min.js"></script>
    <script src="/site/templates/public/js/vendor/jquery.fileupload.js"></script>
    <script src="/site/templates/public/js/ui.js"></script> -->
    <script src="/site/templates/public/js/upload.js"></script>

    <script src="js/main.js"></script>


</body>
</html>
