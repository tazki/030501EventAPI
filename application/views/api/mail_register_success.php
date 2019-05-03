<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>Notification email template</title>
   </head>
   <body bgcolor="#8d8e90">
      <table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#8d8e90">
         <tr>
            <td>
               <table width="600" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" align="center">
                  <tr>
                     <td>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                           <tr>
                              <td width="144"><a href= "#" target="_blank"><img style="margin-left: 10px;
                                 margin-top: 20px;" src="<?php echo base_url('images/'); ?>semi_logo.png" width="144" height="76" border="0" alt=""/></a></td>
                              <!-- <td width="144"><a href= "http://yourlink" target="_blank"><img src="images/semi_logo.png" width="144" height="76" border="0" alt=""/></a></td> -->
                              <td width="393">
                                 <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                       <td height="46" align="right" valign="middle">
                                          <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                          </table>
                                       </td>
                                    </tr>
                                    <tr>
                                       <td height="30"><img src="<?php echo base_url('images/'); ?>PROMO-GREEN2_01_04.jpg" width="393" height="30" border="0" alt=""/></td>
                                    </tr>
                                 </table>
                              </td>
                           </tr>
                        </table>
                     </td>
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
                              <td width="80%" align="justify" valign="top">
                                 <font style="font-family: Georgia, 'Times New Roman', Times, serif; color:#010101; font-size:24px"><strong><em>Activate Your SEMI Southeast Asia Mobile Application</em></strong></font><br /><br />
                                 <font style="font-family: Verdana, Geneva, sans-serif; color:#666766; font-size:13px; line-height:21px">
                                    <p>Congratulations, your SEMI Southeast Asia Mobile Application account has been created. To activate your account please click the following button below.</p>
                                    <br>
                                    <a href="<?php echo site_url('api/member/activate/'.$encoded_email); ?>" style="-webkit-border-radius: 8; -moz-border-radius: 8; border-radius: 8px; font-family: Arial; color: #ffffff; font-size: 20px; background:  #7bb544; padding: 10px 20px 10px 20px; text-decoration: none; position: relative; text-align: center; margin: 0 auto; display: block; text-decoration: none;">
                                      Activate Account
                                    </a>
                                    <br><br>
                                    <p>If you are unable to validate your account through the link above, you may click the link below, or copy and paste it to the address bar of your browser.</p>
                                    <br>
                                    <a href="<?php echo site_url('api/member/activate/'.$encoded_email); ?>"><?php echo site_url('api/member/activate/'.$encoded_email); ?></a>
                                    <br>
                                    <br>
                                    <p style="text-align:center;">
                                       <img src="<?php echo base_url('uploads/').$qrcode; ?>" width="200" height="200" border="0" alt=""/>
                                       <br>
                                       <?php echo $user_id; ?>
                                       <br>
                                       (Please use this QR code to print your badge onsite)
                                    </p>
                                    <br>
                                    <br>
                                    <p>Thank you,</p>
                                    <p>SEMI Southeast Asia Team</p>
                                    <img style="margin-left: 10px;"" src="<?php echo base_url('images/'); ?>semi_logo.png" width="144" height="76" border="0" alt=""/>
                                 </font>
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
                     <td><img src="<?php echo base_url('images/'); ?>PROMO-GREEN2_07.jpg" width="598" height="7" style="display:block" border="0" alt=""/></td>
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
               </table>
            </td>
         </tr>
      </table>
   </body>
</html>