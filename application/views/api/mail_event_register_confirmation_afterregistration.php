<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <title>Notification email template</title>
   </head>
   <body>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
         <tr>
            <td>
               <table width="980" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" align="center">
                  <tr>
                     <td>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                           <tr>
                              <td width="393">
                                 <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                       <td height="30"><img style="width:100%;" src="<?php echo $event_image; ?>" border="0" alt=""/></td>
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
                                    <strong><em>FORUM CONFIRMATION</em></strong>
                                 </font>
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
                              <td width="5%">&nbsp;</td>
                              <td width="80%"    align="justify" valign="top">
                                 <br>
                                 <font style="font-family: Verdana, Geneva, sans-serif; color:#666766; font-size:13px; line-height:21px">
                                    <br/>
                                    <span style="font-weight: bold;color: black;">Your Registration Details</span>
                                 </font>
                               </td>                              
                              <td width="10%">&nbsp;</td>
                           </tr>
                        </table>
                     </td>
                  </tr>
                  <tr>
                     <td>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-bottom: 50px;">
                           <tr>
                              <td width="10%">&nbsp;</td>
                              <td width="80%" align="justify" valign="top">
                                 <br>
                                 <font style="font-family: Verdana, Geneva, sans-serif; color:#666766; font-size:13px; line-height:21px">
                                    <div class="row">
                                      <div class="column" style="float: left;width: 25%;" style="float: left;width: 25%;">Registration ID</div>
                                      <div class="column" style="float: left;width: 25%;">: <?php echo $user_id; ?></div>
                                      <div class="column" style="float: left;width: 25%;">Postal</div>
                                      <div class="column" style="float: left;width: 25%;">: <?php echo $user_post_code; ?></div>
                                    </div>
                                    <div class="row">
                                      <div class="column" style="float: left;width: 25%;">Name</div>
                                      <div class="column" style="float: left;width: 25%;">: <?php echo $user_first_name.' '.$user_last_name; ?></div>
                                      <div class="column" style="float: left;width: 25%;">Tel</div>
                                      <div class="column" style="float: left;width: 25%;">: <?php echo $user_mobile_number; ?></div>
                                    </div>
                                    <div class="row">
                                      <div class="column" style="float: left;width: 25%;">Job Title</div>
                                      <div class="column" style="float: left;width: 25%;">: <?php echo $user_job_title; ?></div>
                                      <div class="column" style="float: left;width: 25%;">Fax</div>
                                      <div class="column" style="float: left;width: 25%;">: <?php echo $user_fax_number; ?></div>
                                    </div>
                                    <div class="row">
                                      <div class="column" style="float: left;width: 25%;">Company</div>
                                      <div class="column" style="float: left;width: 25%;">: <?php echo $user_company_name; ?></div>
                                      <div class="column" style="float: left;width: 25%;">Mobile</div>
                                      <div class="column" style="float: left;width: 25%;">: <?php echo $user_mobile_number; ?></div>
                                    </div>
                                    <div class="row">
                                      <div class="column" style="float: left;width: 25%;">Address</div>
                                      <div class="column" style="float: left;width: 25%;">: <?php echo $user_company_address; ?></div>
                                      <div class="column" style="float: left;width: 25%;">Email</div>
                                      <div class="column" style="float: left;width: 25%;">: <?php echo $user_email_address; ?></div>
                                    </div>
                                    <div class="row">
                                      <!-- <div class="column" style="float: left;width: 25%;">City</div>
                                      <div class="column" style="float: left;width: 25%;">: <?php #echo $user_city; ?></div> -->
                                      <div class="column" style="float: left;width: 25%;">Payment Method</div>
                                      <div class="column" style="float: left;width: 25%;">: <?php echo $payment_type; ?></div>
                                    </div>
                                    <div class="row">
                                      <!-- <div class="column" style="float: left;width: 25%;">State</div>
                                      <div class="column" style="float: left;width: 25%;">: <?php #echo $user_state; ?></div> -->
                                      <div class="column" style="float: left;width: 25%;">Payment Status</div>
                                      <div class="column" style="float: left;width: 25%;">: <?php echo $payment_status; ?></div>
                                    </div>
                                    <div class="row">
                                      <div class="column" style="float: left;width: 25%;">Country</div>
                                      <div class="column" style="float: left;width: 25%;">: <?php echo $user_company_country_id; ?></div>
                                      <!--div class="column" style="float: left;width: 25%;">Receipt</div>
                                      <div class="column" style="float: left;width: 25%;">: <a href="#">Please click here!</a></div-->
                                    </div>
                                 </font>
                               </td>                              
                              <td width="10%">&nbsp;</td>
                           </tr>
                        </table>
                     </td>
                  </tr>
                  <?php if(isset($program_registered) && !empty($program_registered)) { ?>
                  <tr>
                     <td>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                           <tr>
                              <td width="10%">&nbsp;</td>
                              <td width="80%"    align="center" valign="top">
                                 <font style="font-family: Georgia, 'Times New Roman', Times, serif; color:#010101; font-size:24px">
                                    <strong><em>PROGRAM REGISTERED</em></strong>
                                 </font>
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
                              <td width="80%" align="justify" valign="top">
                                 <br>
                                 <font style="font-family: Verdana, Geneva, sans-serif; color:#666766; font-size:13px; line-height:21px">
                                    <br/>
                                    <ul><?php echo $program_registered; ?></ul>                          
                                 </font>
                                 <br>
                              </td>
                              <td width="10%">&nbsp;</td>
                           </tr>
                        </table>
                     </td>
                  </tr>
                  <?php } ?>
                  <tr>
                     <td>
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                           <tr>
                              <td width="10%">&nbsp;</td>
                              <td width="80%" align="justify" valign="top">
                                 <br>
                                 <font style="font-family: Verdana, Geneva, sans-serif; color:#666766; font-size:13px; line-height:21px">
                                    <br/>
                                    <span>Please remember to show up early on the event days <?php echo $event_date_time; ?> to verify your attendance.</span>
                                    <br/>
                                    <br/>
                                    <span>Your badge can be collected on-site at the following schedule:</span>
                                    <br />
                                    <p>
                                       <span style="font-weight: bold; color: black;">Venue: </span>
                                       <span>Forum Pre-registration Desk</span>
                                        <span><?php echo $event_location; ?></span>
                                    </p>
                                    <p>
                                       <span style="font-weight: bold; color: black;">Registration Hours: </span>
                                       <span><?php echo $event_date_time; ?>, 08:15 hrs till an hour before show ends</span>
                                    </p>
                                    <p>
                                       <span style="font-weight: bold; color: black;">Attire: </span>
                                       <span>Business</span>
                                    </p>
                                    <p>Kindly bring this letter to collect your badge and badge holder on-site:</p>                            
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
                                    <strong><em>EXHIBITION INFORMATION</em></strong></font>
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
                                       <span style="font-weight: bold; color: black;">With your program badge, you are NOT required for register as visitor again and are able to access to the show floor which opens at the following hours:</span>
                                    </p>
                                    <p>
                                       <span style="font-weight: bold; color: black;">Venue: </span>
                                       <span><?php echo $event_location; ?></span>
                                    </p>
                                    <p>
                                       <span style="font-weight: bold; color: black;">Show Hours: </span>
                                       <span>10:00–17:00hrs, 8–9 May 2018</span>
                                       <span>10:00–16:00hrs, 10 May 2018</span>
                                    </p>
                                    <p>
                                       <span style="font-weight: bold; color: black;">Registration Hours: </span>
                                       <span><?php echo $event_date_time; ?>, 08:15hrs till an hour before show ends</span>
                                    </p>
                                    <p>
                                       <span style="font-weight: bold; color: black;">Attire: </span>
                                       <span>Business</span>
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
                                    <strong><em>CONTACT</em></strong></font>
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
                              <td width="80%" align="justify" valign="top">
                                 <br>
                                 <font style="font-family: Verdana, Geneva, sans-serif; color:#666766; font-size:13px; line-height:21px">
                                    <p>
                                       <span style="font-weight: bold; color: black;">For questions or assistance, please contact the <?php echo $event_name; ?> Registration Team Registration Team at:</span>
                                    </p>
                                    <p>
                                       <span style="font-weight: bold; color: black;">Contact Person: </span>
                                       <span>Ng Tiong Hian</span>
                                    </p>
                                    <p>
                                       <span style="font-weight: bold; color: black;">Telephone: </span>
                                       <span>(65).8452.1071</span>
                                    </p>
                                    <p>
                                       <span style="font-weight: bold; color: black;">Fax: </span>
                                       <span>(65).6774.6496</span>
                                    </p>
                                    <p>
                                       <span style="font-weight: bold; color: black;">Email: </span>
                                       <span>tionghian@dynamiquekonzepts.com</span>
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
                                    <strong><em>ADDITIONAL POLICIES</em></strong></font>
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
                                       <span style="font-weight: bold; color: black;">1.</span>
                                       <span>Cameras including still or video is strictly prohibited.</span>
                                    </p>
                                    <p>
                                       <span style="font-weight: bold; color: black;">2.</span>
                                       <span>Persons 12-16 years of age must be accompanied by an adult on the exhibition floor.</span>
                                    </p>
                                    <p>
                                       <span style="font-weight: bold; color: black;">3.</span>
                                       <span>No minor under the age of 12 is allowed on the exibition floor.</span>
                                    </p>
                                    <p>
                                       <span style="font-weight: bold; color: black;">4.</span>
                                       <span>Proper attire is required to gain entrance to the event - strictly no shorts, bermudas, slippers, singlet, sleeveless tops and any other attire that Show Management deems unit.</span>
                                    </p>
                                    <p>
                                       <span style="font-weight: bold; color: black;">5.</span>
                                       <span>No refunds will be made on any cancellations.</span>
                                    </p>
                                    <p>
                                       <span style="font-weight: bold; color: black;">6.</span>
                                       <span>Link to presentation materilas will be uploaded on the forum. Participants will be notified via email. (Please note that there would be presentations that will not be available due to speaker's wishes for non-publication.)</span>
                                    </p>
                                    <p>
                                       <span style="font-weight: bold; color: black;">7.</span>
                                       <span>Badges will not be issued without proof of payment.</span>
                                    </p>
                                    <div style="margin-top: 30px;">
                                       <p>*Programmes and timing are subject to changes.</p>
                                       <p>*No cancellation is allowed once registered. Replacement of participant is permitted with authorisation letter.</p>
                                       <p>*All prices are subject to 7% GST</p>
                                       <p>*Badges are non-transferable between conferences for single conference registration.</p>
                                    </div>
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
                                    <p>Thank you for participation. We wish you a productive and succesful visit.</p>
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
                     <td><img src="<?php echo base_url('images/'); ?>PROMO-GREEN2_07.jpg" width="980" height="7" style="display:block" border="0" alt=""/></td>`
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