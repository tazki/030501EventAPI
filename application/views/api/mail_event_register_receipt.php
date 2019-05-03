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
                              <td width="393">
                                 <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                       <td height="30">
                                          <img style="width:100%;" src="<?php echo $event_image; ?>" border="0" alt=""/>
                                       </td>
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
                              <td width="80%"    align="center" valign="top">
                                 <font style="font-family: Georgia, 'Times New Roman', Times, serif; color:#010101; font-size:24px">
                                    <strong><em>OFFICIAL RECEIPT</em></strong></font>
                              </td>                              
                              <td width="10%">&nbsp;</td>
                           </tr>
                        </table>
                     </td>
                  </tr>
                  <tr>
                     <td>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                           <tr>
                              <td width="10%">&nbsp;</td>
                              <td width="80%"    align="justify" valign="top">
                                 <br>
                                 <font style="font-family: Verdana, Geneva, sans-serif; color:#666766; font-size:13px; line-height:21px">
                                    <p>
                                       <span style="font-weight: bold; color: black;">Receipt No: </span>
                                       <span><?php echo $receipt_number; ?></span>
                                    </p>
                                    <p>
                                       <span style="font-weight: bold; color: black;">Date: </span>
                                       <span><?php echo $date_paid; ?></span>
                                    </p>
                                    <p>
                                       <span style="font-weight: bold; color: black;">Mode of Payment: </span>
                                       <span><?php echo $mode_of_payment; ?></span>
                                    </p>
                                    <p>
                                       <span style="font-weight: bold; color: black;">Status: </span>
                                       <span><?php echo $status_of_payment; ?></span>
                                    </p>                                    
                                 </font>
                                 <br>
                              </td>
                              
                              <td width="10%">&nbsp;</td>
                           </tr>
                        </table>
                     </td>
                  </tr>
                  <tr>
                     <td>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                           <tr>
                              <td width="10%">&nbsp;</td>
                              <td width="80%"    align="center" valign="top">
                                 <font style="font-family: Georgia, 'Times New Roman', Times, serif; color:#010101; font-size:24px">
                                    <strong><em>PRIMARY CONTACT INFORMATION</em></strong></font>
                              </td>                              
                              <td width="10%">&nbsp;</td>
                           </tr>
                        </table>
                     </td>
                  </tr>
                  <tr>
                     <td>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                           <tr>
                              <td width="10%">&nbsp;</td>
                              <td width="80%"    align="justify" valign="top">
                                 <br>
                                 <font style="font-family: Verdana, Geneva, sans-serif; color:#666766; font-size:13px; line-height:21px">
                                    <p>
                                       <span style="font-weight: bold; color: black;">Name: </span>
                                       <span><?php echo $user_first_name.' '.$user_last_name; ?></span>
                                    </p>
                                    <p>
                                       <span style="font-weight: bold; color: black;">Job Title: </span>
                                       <span><?php echo $user_job_title; ?></span>
                                    </p>
                                    <p>
                                       <span style="font-weight: bold; color: black;">Company Name: </span>
                                       <span><?php echo $user_company_name; ?></span>
                                    </p>
                                    <p>
                                       <span style="font-weight: bold; color: black;">Address: </span>
                                       <span><?php echo $user_company_address; ?></span>
                                    </p>
                                    <p>
                                       <span style="font-weight: bold; color: black;">Email Address: </span>
                                       <span><?php echo $user_email_address; ?></span>
                                    </p>
                                    <p>
                                       <span style="font-weight: bold; color: black;">Mobile:</span>
                                       <span><?php echo $user_mobile_number; ?></span>
                                    </p>
                                    <p>
                                       <span style="font-weight: bold; color: black;">Telephone: </span>
                                       <span><?php echo $user_phone_number; ?></span>
                                    </p>
                                    <p>
                                       <span style="font-weight: bold; color: black;">Fax: </span>
                                       <span><?php echo $user_fax_number; ?></span>
                                    </p>
                                 </font>
                                 <br>
                              </td>
                              
                              <td width="10%">&nbsp;</td>
                           </tr>
                        </table>
                     </td>
                  </tr>
                  <tr>
                     <td>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                           <tr>
                              <td width="10%">&nbsp;</td>
                              <td width="80%"    align="center" valign="top">
                                 <font style="font-family: Georgia, 'Times New Roman', Times, serif; color:#010101; font-size:24px">
                                    <strong><em>SUMMARY</em></strong></font>
                              </td>
                              
                              <td width="10%">&nbsp;</td>
                           </tr>
                        </table>
                     </td>
                  </tr>
                  <tr>
                     <td>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                           <tr>
                              <td width="10%">&nbsp;</td>
                              <td width="80%"    align="justify" valign="top">
                                 <br>
                                 <font style="font-family: Verdana, Geneva, sans-serif; color:#666766; font-size:13px; line-height:21px">
                                    <p>
                                       <span style="font-weight: bold; color: black;">NAME: </span>
                                       <span><?php echo $user_first_name.' '.$user_last_name; ?></span>
                                    </p>
                                    <p>
                                       <span style="font-weight: bold; color: black;">COMPANY NAME: </span>
                                       <span><?php echo $user_company_address; ?></span>
                                    </p>
                                    <p>
                                       <span style="font-weight: bold; color: black;">EMAIL ADDRESS: </span>
                                       <span><?php echo $user_email_address; ?></span>
                                    </p>
                                    <p>
                                       <span style="font-weight: bold; color: black;">QTY:</span>
                                       <span>1</span>
                                    </p>
                                    <p>
                                       <span style="font-weight: bold; color: black;">PACKAGE: </span>
                                       <span><?php echo $event_name; ?></span>
                                    </p>
                                    <p>
                                       <span style="font-weight: bold; color: black;">AMOUNT: </span>
                                       <span><?php echo $currency.' '.$total_amount; ?></span>
                                    </p>
                                    <p>
                                       <span style="font-weight: bold; color: black;">GRAND TOTAL: </span>
                                       <span><?php echo $currency.' '.$total_amount; ?></span>
                                    </p>
                                 </font>
                                 <br>
                              </td>                              
                              <td width="10%">&nbsp;</td>
                           </tr>
                        </table>
                     </td>
                  </tr>
                  <tr>
                     <td>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                           <tr>
                              <td width="10%">&nbsp;</td>
                              <td width="80%"    align="justify" valign="top">
                                 <br>
                                 <font style="font-family: Verdana, Geneva, sans-serif; color:#666766; font-size:13px; line-height:21px">
                                    <p>Thank you for registration!</p>
                                    <p>SEMI Southeast Asia Team</p>
                                    <img style="margin-left: 10px;"" src="<?php echo base_url('images/'); ?>semi_logo.png" width="144" height="76" border="0" alt=""/>
                                 </font>
                                 <br>
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