<?php if($this->user_permissions->is_view('s_users')){ ?>
<script type="text/javascript" src="<?=base_url()?>js/s_users.js"></script>
<h2>Users</h2>
<table style="margin-left:150px;width: 400px;" border="0">
    <tr>
        <td class="content" style="width: 400px;" valign="top" >
            <div class="form" id="form">
                <form action="<?=base_url()?>index.php/main/save/s_users" method="post" name="form_" id="form_">
                    <table style="width: 400px;">
                        <tr>
                            <td style="width: 130px;">User Code</td>
                            <td>
                                <input type="text" class="input_txt" id="cCode" name="cCode" title="User Code" />
                                <input type='hidden' value="0" name="code_" id="code_" />
                            </td>
                        </tr><tr>
                            <td>Description</td>
                            <td><input type="text" class="input_txt" id="discription" name="discription" title="User Description" maxlength="150"/></td>
                        </tr><tr>
                            <td>Login Name</td>
                            <td><input type="text" class="input_txt" id="loginName" name="loginName" title="User Name" style="width: 200px;" maxlength="40"/></td>
                        </tr><tr>
                            <td>Password</td>
                            <td><input type="text" class="input_pass" id="userPassword" name="userPassword" title="Password" style="width: 200px;"/></td>
                        </tr><tr>
                            <td>Password Again</td>
                            <td><input type="text" class="input_pass" id="r_pass" title="Password Again" style="width: 200px;"/></td>
                        </tr><tr>
                            <td>Is Admin</td>
                            <td><input type="checkbox" title="1" name="isAdmin" id="isAdmin" /></td>
                        </tr><tr>
                            <td>Permission Level</td>
                            <td>
                                <select name="permission" id="permission">
                                    <option value="0">None</option>
                                    <option value="1">Level 01</option>
                                    <option value="2">Level 02</option>
                                    <option value="3">Level 03</option>
                                </select>
                            </td>
                        </tr><tr>
                            <td>Branch</td>
                            <td>
                                <?//=$branch;?>
                            </td>
                        </tr>
                    </table>
                </form>
                    <div style="text-align: right; padding: 5px;">
                        <input type="button" id="btnExit" title="Exit" />
                        <button id="btnSave">Save</button>
                        <button id="btnReset">Reset</button>
                    </div>
            </div>
        </td>
        
    </tr>
</table>
<?php } ?>