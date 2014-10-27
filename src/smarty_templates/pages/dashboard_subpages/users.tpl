<div class="pageheader dashboard">
    <h2><i class="fa fa-home"></i> Dashboard <span>Overzicht</span></h2>
    <div class="breadcrumb-wrapper">
        <span class="label">U bevindt zich hier:</span>
        <ol class="breadcrumb">
            <li><a href="index.php?page=start" class="active">Dashboard</a></li>
        </ol>
    </div>
</div>

<h2>Welkom {$smarty.session.bluewaveFirstname}</h2>
<div id="basicflot">
	<h3>All users</h3>
	<input type='text' id="txtSearch" placeholder='zoeken..' autocomplete="false" />
	<!--<table id="allusers" class="allusers">
		<tr>
		    <td valign="top" width="50%">
		      	<div id="myGrid" style="width:600px;height:500px;"></div>
		    </td>
		    <td valign="top">
		      	<h2>Demonstrates:</h2>
		      	<ul>
		        	<li>basic grid with minimal configuration</li>
		      	</ul>
		        <h2>View Source:</h2>
		        <ul>
		            <li><A href="https://github.com/mleibman/SlickGrid/blob/gh-pages/examples/example1-simple.html" target="_sourcewindow"> View the source for this example on Github</a></li>
		        </ul>
		    </td>
		</tr>
	</table>-->
				<div id="myGrid" style="width:1000px;height:500px;"></div>
				<div id="pager" style="width:1000px;height:20px;"></div>
</table>
</div>
