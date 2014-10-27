<h5 class="sidebartitle">Navigatie</h5>
<ul class="nav nav-pills nav-stacked nav-bracket">
    <li class="nav-parent" {if $smarty.get.page == 'start'}class="active"{/if}>
        <a href="index.php?page=start"><i class="fa fa-tachometer"></i> <span>Dashboard</span></a>
        <ul class="children" {if isset($smarty.get.page) && $smarty.get.page == 'start'}style="display:block;"{/if}>
            <li>
                <a href="index.php?page=start&subpage=accesspoints"><span class="fa fa-caret-right"></span>Accesspoints</a>
            </li>
            <li>
                <a href="index.php?page=start&subpage=livestats"><span class="fa fa-caret-right"></span>Live</a>
            </li>
            <li>
                <a href="index.php?page=start&subpage=users"><span class="fa fa-caret-right"></span>All users</a>
            </li>
        </ul>
    </li>

    <li>
        <a href="index.php?page=stats"><i class="glyphicon glyphicon-stats"></i> <span>Statistieken</span></a>
    </li>

    <li class="nav-parent {if isset($smarty.get.page) && $smarty.get.page == 'sites'}nav-active active{/if}">
        <a href="#"><i class="fa fa-globe"></i> <span>Applicaties</span></a>

        <ul class="children" {if isset($smarty.get.page) && $smarty.get.page == 'sites'}style="display:block;"{/if}>
            <li {if !isset($smarty.get.action) && isset($smarty.get.page) && $smarty.get.page == 'sites'}class="active"{/if} >
                <a href="index.php?page=sites"><span class="fa fa-caret-right"></span> Overzicht</a>
            </li>
            <?php if($_SESSION['bluewaveAccountType'] == 'superadmin'): ?>
                {foreach $all_sites as $site}
                    {if !isset($site->key)}
                        <li class="site-view-link {if $smarty.get.page == sites && isset($site->name) && isset($smarty.get.id) && $site->name == $smarty.get.id}active{/if}" data-siteid="{$site->name}" data-identifier="{$site->name}">
                            <a href="index.php?page=sites&amp;action=view&amp;id={$site->_id}&amp;name={$site->name}">
                                <span class="fa fa-caret-right"></span> {if isset($site->desc)}{$site->desc}{else}{$site->name}{/if}
                            </a>
                        </li>
                    {/if} 
                {/foreach}
            <?php else: ?>
                <!-- display my sites -->
            <?php endif; ?>
        </ul>
    </li>

    <li {if !empty($smarty.get.page) && $smarty.get.page == 'users'}class="active"{/if} ><a href="index.php?page=users&amp;action=overview"><i class="fa fa-users"></i> <span>Gebruikers</span></a></li>

    <li><a href="maps.html"><i class="fa fa-bell"></i> <span>Vragen &amp; Problemen</span></a></li>
</ul>
