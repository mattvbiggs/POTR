<h2>Site Tools</h2>

<div id="edit-user-tools" class="large-label"><i class="fa fa-cogs fa-lg"></i> User Site Settings</div>
<br />
<div id="edit-cpt-tools" class="large-label"><i class="fa fa-code fa-lg"></i> Manage CPT Codes</div>

<div id="manage-user">
	<h3>Modify User Details</h3>
	
	<form id="user_tools" class="nice-form">
		<table>
			<tr>
				<td>
					<label for="firstname">First Name:<br />
					<input type="text" id="firstname" value="<?php echo htmlentities($firstname); ?>"></label>
				</td>
				<td>
					<label for="lastname">Last Name:<br />
					<input type="text" id="lastname" value="<?php echo htmlentities($lastname); ?>"></label>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<label for="login">Login:<br />
					<input type="text" id="login" size="50" value="<?php echo htmlentities($login); ?>"></label>
				</td>
			</tr>
			<tr>
				<td>
					<label for="title">Title:<br />
					<input type="text" id="title" value="<?php echo htmlentities($title); ?>"></label>
				</td>
				<td>
					<label for="location">Location:<br />
					<input type="text" id="location" value="<?php echo htmlentities($Location); ?>"></label>
				</td>
			</tr>
		</table>
		
		<table>
			<tr>
				<td>
					<label for="oldpass">Old Password:<br />
					<input type="password" id="oldpass"></label>
				</td>
			</tr>
			<tr>
				<td>
					<label for="newpass">New Password:<br />
					<input type="password" id="newpass"></label>
				</td>
			</tr>
			<tr>
				<td>
					<label for="confirmpass">Confirm New Password:<br />
					<input type="password" id="confirmpass"></label>
				</td>
			</tr>
			<tr>
				<td>
					<input type="button" id="save_user" value="Save" /><div id="pass_error" class="input_error_nocr"></div>
				</td>
			</tr>
		</table>
			
	</form>
</div>

<div id="manage-cpt-codes">
	<h3>Manage CPT Codes</h3>
	<div>
        <form class="nice-form">
            <label for="edit-cpt-search">CPT Code:</label>
            <input type="text" id="edit-cpt-search" placeholder="Search for a CPT Code to edit" /><br />
            
            <button id="find-cpt-code" type="button" class="newcase">Search</button>
            <div id="edit-search-msg"></div>
        </form>
    </div>
    <div id="edit-cpt-code">        
        <form id="update-cpt" class="nice-form" autocomplete="off">
            <legend>Use this form to edit the CPT Code, Description, and RVU value. Click "Save Changes" when complete.</legend>
            <fieldset>
                <input type="hidden" id="cpt-code-id"/>    
            
                <label for="update-code">CPT Code:</label>
                <input type="text" id="update-code" />
            
                <label for="update-desc">Description:</label>
                <textarea id="update-desc"></textarea>
            
                <label for="update-rvu">wRVU:</label>
                <input type="text" id="update-rvu"/><br />
            
                <button id="submit-update" type="button" class="newcase">Save Changes</button>
            </fieldset>
        </form>
    </div>
</div>