<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>SEMI SEA Reset Password</title>
<style>
  #btn-reset {
    color: white;
    border: none;
    background: #7bb642;
    padding: 10px;
    cursor:pointer;
    outline: none;
  }
  .passfield {
    outline:none;
    border:1px solid #BEBEBE;
    padding: 7px;
    width: 100%;
  }

  .lblpass {
    font-size: 13px;
    font-weight: 600;
  }
</style>
</head>
<body bgcolor="#8d8e90">
<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#8d8e90">
  <tr>
    <td><table width="600" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" align="center">
        <tr>
          <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="144">
                  <a href= "http://yourlink" target="_blank">
                    <img style="margin-left: 10px; margin-top: 20px;" src="<?php echo base_url('images/'); ?>semi_logo.png" width="144" height="76" border="0" alt=""/>
                  </a>
                </td>
                <td width="393">
                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td height="46" align="right" valign="middle">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0"></table>
                      </td>
                    </tr>
                    <tr>
                      <td height="30"><img src="<?php echo base_url('images/'); ?>PROMO-GREEN2_01_04.jpg" width="393" height="30" border="0" alt=""/></td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td align="center">&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="10%">&nbsp;</td>
                <td width="80%" align="justify" valign="top"><font style="font-family: Georgia, 'Times New Roman', Times, serif; color:#010101; font-size:24px"><strong><em>SEMI Southeast Asia Mobile Application Reset Password</em></strong></font><br /><br />
                  <form method="post" action="<?php echo $form_url; ?>">
                  <font style="font-family: Verdana, Geneva, sans-serif; color:#666766; font-size:13px; line-height:21px">
                    <?php if(isset($status))
                    {
                      echo '<p style="'.(($status=='danger') ? 'color:red;' : '').'">'.$alert.'</p>';
                    } ?>
                    <?php if(!isset($status) || (isset($status) && $status=='danger')) { ?>
                    <p>
                      <label class="lblpass">New Password</label>
                      <br>
                      <input class="passfield" type="password" name="user_password" />
                    </p>
                    <p>
                      <label class="lblpass">Confirm Password</label>
                      <br>
                      <input class="passfield" type="password" name="user_confirm_password" />
                    </p>
                    <p><input id="btn-reset" type="submit" value="Reset Password"></p>
                    <?php } ?>
                  </font>
                  </form>
                </td>
                <td width="10%">&nbsp;</td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>
            <img src="<?php echo base_url('images/'); ?>PROMO-GREEN2_07.jpg" width="598" height="7" style="display:block" border="0" alt=""/>
          </td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
      </table></td>
  </tr>
</table>
</body>
</html>