<div class="headerbar">
      
    <a class="menutoggle"><i class="fa fa-bars"></i></a>
    {if $smarty.get.page == stats}
        <form class="searchform" action="index.html" method="post">
            <select name="current-website" id="current-website" class="form-control input-sm mb15">
                <?php if($_SESSION['bluewaveAccountType'] == 'superadmin'): ?>
                    {foreach $all_sites as $site}
                        {if !isset($site->key)}
                            <option value="{$site->name}" data-link="index.php?page=stats&amp;action=change_site&amp;id={$site->name}">{if isset($site->desc)}{$site->desc}{else}{$site->name}{/if}</option>
                        {/if}
                    {/foreach}                     
                <?php else: ?>
                    <!-- display my sites -->
                <?php endif; ?>
            </select>
        </form>
    {/if}

    <div class="header-right">
        <ul class="headermenu">           
            <li>
                <div class="btn-group">
                    <button class="btn btn-default dropdown-toggle tp-icon" data-toggle="dropdown">
                        <i class="glyphicon glyphicon-globe"></i>
                        <span class="badge">2</span>
                    </button>

                    <div class="dropdown-menu dropdown-menu-head pull-right">
                        <h5 class="title">You Have 2 New Notifications</h5>

                        <ul class="dropdown-list gen-list">
                            <li class="new">
                                <a href="">
                                    <span class="thumb"><img src="img/photos/user5.png" alt="" /></span>
                                    <span class="desc">
                                        <span class="name">Zaham Sindilmaca <span class="badge badge-success">new</span></span>
                                        <span class="msg">is now following you</span>
                                    </span>
                                </a>
                            </li>
                            <li class="new">
                                <a href="">
                                    <span class="thumb"><img src="img/photos/user5.png" alt="" /></span>
                                    <span class="desc">
                                        <span class="name">Weno Carasbong <span class="badge badge-success">new</span></span>
                                        <span class="msg">is now following you</span>
                                    </span>
                                </a>
                            </li>
                            <li class="new"><a href="">See All Notifications</a></li>
                        </ul>
                    </div>
                </div>
            </li>

            <li>
                <div class="btn-group">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        {if !empty($smarty.session.bluewaveAvatarPath)}
                            <div style="background-image: url(img/users/{$smarty.session.bluewaveAvatarPath});"></div>
                        {/if}

                        {$smarty.session.bluewaveFirstname}
                        <span class="caret"></span>
                    </button>

                    <ul class="dropdown-menu dropdown-menu-usermenu pull-right">
                        <li><a href="profile.html"><i class="glyphicon glyphicon-user"></i> Profiel</a></li>
                        <li><a href="#"><i class="glyphicon glyphicon-cog"></i> Account Instellingen</a></li>
                        <li><a href="index.php?page=users&amp;action=logout"><i class="glyphicon glyphicon-log-out"></i> Afmelden</a></li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</div>