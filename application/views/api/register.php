<div class="pages navbar-fixed">
   <div data-page="signup" class="page">
      <div class="navbar">
         <div class="navbar-inner">
            <div class="left"><a href="forms.html" class="back link"><i class="icon icon-back"></i></a></div>
            <div class="center">Register</div>
            <div class="right"><a href="#" class="link icon-only"><i class="icon material-icons">more_vert</i></a></div>
         </div>
      </div>
      <div id="registerHolder" class="page-content main-background">
         <div class="content-block">
            <p id="register-text">DELEGATE INFORMATION</p>
            <form id="ajaxForm" class="list-block inputs-list" method="POST" action="http://localhost/haku/index.php/api/member/register">
               <div class="list-block accordion-list reg-form">
                  <ul>
                     <li class="accordion-item accordion-item-expanded">
                        <a href="#" class="item-link item-content">
                           <div class="item-inner">
                              <div class="item-title acc-title">PERSONAL</div>
                           </div>
                        </a>
                        <div class="accordion-item-content" style="">
                           <div class="content-block">
                              <ul>
                                 <li>
                                    <div class="item-content">
                                       <div class="item-media"><i class="icon material-icons">accessibility</i></div>
                                       <div class="item-inner">
                                          <div class="item-title floating-label">Salutation <span class="asterisk">*</span></div>
                                          <div class="item-input">
                                             <input type="text" placeholder="" name="user_salutation" />
                                          </div>
                                       </div>
                                    </div>
                                 </li>
                                 <li>
                                    <div class="item-content">
                                       <div class="item-media"><i class="icon material-icons">person</i></div>
                                       <div class="item-inner">
                                          <div class="item-title floating-label">First Name <span class="asterisk">*</span></div>
                                          <div class="item-input">
                                             <input type="text" placeholder="" name="user_first_name"/>
                                          </div>
                                       </div>
                                    </div>
                                 </li>
                                 <li>
                                    <div class="item-content">
                                       <div class="item-media"><i class="icon material-icons">person</i></div>
                                       <div class="item-inner">
                                          <div class="item-title floating-label">Last Name <span class="asterisk">*</span></div>
                                          <div class="item-input">
                                             <input type="text" placeholder="" name="user_last_name"/>
                                          </div>
                                       </div>
                                    </div>
                                 </li>
                                 <li>
                                    <div class="item-content">
                                       <div class="item-media"><i class="icon material-icons">work</i></div>
                                       <div class="item-inner">
                                          <div class="item-title floating-label">Job Title <span class="asterisk">*</span></div>
                                          <div class="item-input">
                                             <input type="text" placeholder="" name="user_job_title"/>
                                          </div>
                                       </div>
                                    </div>
                                 </li>
                                 <li>
                                    <div class="item-content">
                                       <div class="item-media"><i class="icon material-icons">location_city</i></div>
                                       <div class="item-inner">
                                          <div class="item-title floating-label">City <span class="asterisk">*</span></div>
                                          <div class="item-input">
                                             <input type="text" placeholder="" name="user_city"/>
                                          </div>
                                       </div>
                                    </div>
                                 </li>
                                 <li>
                                    <div class="item-content">
                                       <div class="item-media"><i class="icon material-icons">pin_drop</i></div>
                                       <div class="item-inner">
                                          <div class="item-title floating-label">State <span class="asterisk">*</span></div>
                                          <div class="item-input">
                                             <input type="text" placeholder="" name="user_state"/>
                                          </div>
                                       </div>
                                    </div>
                                 </li>
                                 <li>
                                    <div class="item-content">
                                       <div class="item-media"><i class="icon material-icons">edit_location</i></div>
                                       <div class="item-inner">
                                          <div class="item-title floating-label">Postal Code <span class="asterisk">*</span></div>
                                          <div class="item-input">
                                             <input type="text" placeholder="" name="user_post_code"/>
                                          </div>
                                       </div>
                                    </div>
                                 </li>
                              </ul>
                           </div>
                        </div>
                     </li>
                     <li class="accordion-item">
                        <a href="#" class="item-link item-content">
                           <div class="item-inner">
                              <div class="item-title acc-title">CONTACT</div>
                           </div>
                        </a>
                        <div class="accordion-item-content">
                           <div class="list-block">
                              <ul>
                                 <li>
                                    <div class="item-content">
                                       <div class="item-media"><i class="icon material-icons">email</i></div>
                                       <div class="item-inner">
                                          <div class="item-title floating-label">E-mail <span class="asterisk">*</span></div>
                                          <div class="item-input">
                                             <input type="email" placeholder="" name="user_email_address"/>
                                          </div>
                                       </div>
                                    </div>
                                 </li>
                                 <li>
                                    <div class="item-content">
                                       <div class="item-media"><i class="icon material-icons">phone_android</i></div>
                                       <div class="item-inner">
                                          <div class="item-title floating-label">Mobile <span class="asterisk">*</span></div>
                                          <div class="item-input">
                                             <input type="text" placeholder="" name="user_mobile_number"/>
                                          </div>
                                       </div>
                                    </div>
                                 </li>
                                 <li>
                                    <div class="item-content">
                                       <div class="item-media"><i class="icon material-icons">call</i></div>
                                       <div class="item-inner">
                                          <div class="item-title floating-label">Telephone <span class="asterisk">*</span></div>
                                          <div class="item-input">
                                             <input type="text" placeholder="" name="user_phone_number"/>
                                          </div>
                                       </div>
                                    </div>
                                 </li>
                                 <li>
                                    <div class="item-content">
                                       <div class="item-media"><i class="icon material-icons">scanner</i></div>
                                       <div class="item-inner">
                                          <div class="item-title floating-label">Fax</div>
                                          <div class="item-input">
                                             <input type="text" placeholder="" name="user_fax_number"/>
                                          </div>
                                       </div>
                                    </div>
                                 </li>
                              </ul>
                           </div>
                        </div>
                     </li>
                     <li class="accordion-item">
                        <a href="#" class="item-link item-content">
                           <div class="item-inner">
                              <div class="item-title acc-title">COMPANY</div>
                           </div>
                        </a>
                        <div class="accordion-item-content">
                           <div class="list-block">
                              <ul>
                                 <li>
                                    <div class="item-content">
                                       <div class="item-media"><i class="icon material-icons">account_balance</i></div>
                                       <div class="item-inner">
                                          <div class="item-title floating-label">Name <span class="asterisk">*</span></div>
                                          <div class="item-input">
                                             <input type="text" placeholder="" name="user_company_name"/>
                                          </div>
                                       </div>
                                    </div>
                                 </li>
                                 <li>
                                    <div class="item-content">
                                       <div class="item-media"><i class="icon material-icons">place</i></div>
                                       <div class="item-inner">
                                          <div class="item-title floating-label">Address <span class="asterisk">*</span></div>
                                          <div class="item-input">
                                             <input type="text" placeholder="" name="user_company_address"/>
                                          </div>
                                       </div>
                                    </div>
                                 </li>
                                 <li>
                                    <div class="item-content">
                                       <div class="item-media"><i class="icon material-icons">flag</i></div>
                                       <div class="item-inner">
                                          <div class="item-title floating-label">Country <span class="asterisk">*</span></div>
                                          <div class="item-input">
                                             <select name="user_company_country_id">
                                                <option value="1">Singapore</option>
                                                <option value="2">Philippines</option>
                                             </select>
                                          </div>
                                       </div>
                                    </div>
                                 </li>
                              </ul>
                           </div>
                        </div>
                     </li>
                  </ul>
               </div>
               <!-- <input type="submit" value="test"> -->
            </form>
         </div>
         <div class="content-block">
            <div class="row">
               <div class="col-100">
                  <!--href="survey.html"-->
                  <a  id="btnsignup" class="button button-raised button-fill">REGISTER A NEW ACCOUNT</a>
               </div>
            </div>
         </div>
      </div>
      <div id="surveyHolder" class="page-content" style="display:none;">
         <div class="list-block inputs-list">
            <ul>
               <li class="accordion-item accordion-item-expanded">
                  <a href="#" class="item-link item-content">
                     <div class="item-inner">
                        <div class="item-title survey-question">Please indicate your level of management or supervisory responsibilities.</div>
                     </div>
                  </a>
                  <div class="accordion-item-content">
                     <div class="list-block media-list">
                        <ul>
                           <li>
                              <label class="label-radio item-content">
                                 <input type="radio" name="user_supervisory_responsibilities" value="Executive Management" />
                                 <div class="item-media"><i class="icon icon-form-radio"></i></div>
                                 <div class="item-inner">
                                    <div class="item-title-row">
                                       <div class="item-title">Executive Management</div>
                                    </div>
                                    <div class="item-text">(Chairman, CEO, CFO, CMO, President, Chief, Managing Director)</div>
                                 </div>
                              </label>
                           </li>
                           <li>
                              <label class="label-radio item-content">
                                 <input type="radio" name="user_supervisory_responsibilities" value="Senior Management" />
                                 <div class="item-media"><i class="icon icon-form-radio"></i></div>
                                 <div class="item-inner">
                                    <div class="item-title-row">
                                       <div class="item-title">Senior Management</div>
                                    </div>
                                    <div class="item-text">(Vice President, Director)</div>
                                 </div>
                              </label>
                           </li>
                           <li>
                              <label class="label-radio item-content">
                                 <input type="radio" name="user_supervisory_responsibilities" value="Other Management" />
                                 <div class="item-media"><i class="icon icon-form-radio"></i></div>
                                 <div class="item-inner">
                                    <div class="item-title-row">
                                       <div class="item-title">Other Management</div>
                                    </div>
                                    <div class="item-text">(Program Manager, Manager)</div>
                                 </div>
                              </label>
                           </li>
                           <li>
                              <label class="label-radio item-content">
                                 <input type="radio" name="user_supervisory_responsibilities" value="Non-Management" />
                                 <div class="item-media"><i class="icon icon-form-radio"></i></div>
                                 <div class="item-inner">
                                    <div class="item-title-row">
                                       <div class="item-title">Non-Management</div>
                                    </div>
                                    <div class="item-text">(Staff, Professional, etc.)</div>
                                 </div>
                              </label>
                           </li>
                           <li>
                              <label class="label-radio item-content">
                                 <input type="radio" name="user_supervisory_responsibilities" value="Student" />
                                 <div class="item-media"><i class="icon icon-form-radio"></i></div>
                                 <div class="item-inner">
                                    <div class="item-title-row">
                                       <div class="item-title">Student</div>
                                    </div>
                                    <div class="item-text" style="visibility: hidden;">student</div>
                                 </div>
                              </label>
                           </li>
                           <li>
                              <label class="label-radio item-content">
                                 <input type="radio" name="user_supervisory_responsibilities" value="Others" />
                                 <div class="item-media"><i class="icon icon-form-radio"></i></div>
                                 <div class="item-inner">Others
                                    <div class="item-input item-input-field">
                                       <input type="text" name="user_supervisory_responsibilities_textbox" placeholder="OTHERS (PLEASE DESCRIBE)" />
                                    </div>
                                 </div>
                              </label>
                           </li>
                        </ul>
                     </div>
                  </div>
               </li>
               <li class="accordion-item">
                  <a href="#" class="item-link item-content">
                     <div class="item-inner">
                        <div class="item-title survey-question"> Please indicate your primary job function.</div>
                     </div>
                  </a>
                  <div id="second-questionaire" class="accordion-item-content">
                     <div class="list-block media-list">
                        <ul id="primaryJobFunction"></ul>
                     </div>
                  </div>
               </li>
               <li class="accordion-item">
                  <a href="#" class="item-link item-content">
                     <div class="item-inner">
                        <div class="item-title survey-question"> Which of the following best describes your company</div>
                     </div>
                  </a>
                  <div id="third-questionaire" class="accordion-item-content" style="">
                     <div class="list-block media-list">
                        <ul id="bestDescribesYourCompany"></ul>
                     </div>
                  </div>
               </li>
               <li class="accordion-item">
                  <a href="#" class="item-link item-content">
                     <div class="item-inner">
                        <div class="item-title survey-question"> Would you like to receive information by email from SEMI?</div>
                     </div>
                  </a>
                  <div id="fourth-questionaire" class="accordion-item-content" style="">
                     <div class="list-block media-list">
                        <ul>
                           <li>
                              <label class="label-radio item-content">
                                 <input type="radio" name="user_receive_information_by_email_from_semi" value="yes" />
                                 <div class="item-media"><i class="icon icon-form-radio"></i></div>
                                 <div class="item-inner">
                                    <div class="item-title-row">
                                       <div class="item-title">Yes</div>
                                    </div>
                                 </div>
                              </label>
                           </li>
                           <li>
                              <label class="label-radio item-content">
                                 <input type="radio" name="user_receive_information_by_email_from_semi" value="no" />
                                 <div class="item-media"><i class="icon icon-form-radio"></i></div>
                                 <div class="item-inner">
                                    <div class="item-title-row">
                                       <div class="item-title">No</div>
                                    </div>
                                 </div>
                              </label>
                           </li>
                        </ul>
                     </div>
                  </div>
               </li>
            </ul>
         </div>
         <div class="content-block">
            <div class="row">
               <div class="col-100">
                  <a href="home.html" id="btnsignup" class="button button-raised button-fill">SAVE</a>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<script type="text/javascript" src="<?php echo base_url('js/jquery.min.js'); ?>"></script>
<script type="text/javascript">
$(function() {   
   var baseUrl = 'http://localhost/haku/index.php/';

   $('#btnsignup').click(function()
   {
      $('#registerHolder').fadeOut();
      $('#surveyHolder').fadeIn();
     //  $.ajax({
     //   url: baseUrl+'api/member/register',
     //   type: "POST",
     //   data: $("#ajaxForm").serialize()
     // }).done(function(data)
     // {
     //     console.log(data.status + ' ' + data.alert + ' ' + $.type(data.alert));

     //     $("#ajaxForm span.error").remove();
     //     $("#ajaxForm div.msg-holder").remove();
     //     if(data.status == 'danger' && $.type(data.alert) == 'object')
     //     {
     //        $.each(data.alert, function(fieldName, fieldMsg)
     //        {
     //           //console.log('key:'+fieldName+' value:'+fieldMsg);
     //           $("input[name='"+fieldName+"']").attr('style', 'border:1px solid #ff0000;');
     //           $("input[name='"+fieldName+"']").after(fieldMsg);
     //        });
     //     }
     //     else
     //     {
     //        $('#ajaxForm').prepend('<div class="msg-holder '+data.status+'">'+data.alert+'</div>');
     //     }
     // });
  });

   $.each(['Executive Management/Board Member', 'Product/Operations Management', 'Engineering - Design/R&D', 'Engineering - Fabrication & Process/Facility', 'Engineering - Chemicals/Materials', 'Engineering - Assembly/Test/Packaging/QA', 'Purchasing/Procurement', 'Marketing, Sales, Business Development', 'Manufacturing and Production', 'Environment, Health & Safety (EHS)', 'Government/Public Policy/Investor Relations', 'Human Resources', 'Financial/Industry Analyst', 'Training/Educator', 'Others'], function(index, value)
   {
      if(value == 'Others')
      {
         var radio = '<li>'+
            '<label class="label-radio item-content">'+
               '<input type="radio" name="user_primary_job_function" value="Others">'+
               '<div class="item-media"><i class="icon icon-form-radio"></i></div>'+
               '<div class="item-inner">'+value+
                  '<div class="item-input item-input-field">'+
                     '<input type="text" name="user_primary_job_function_textbox" placeholder="OTHERS (PLEASE DESCRIBE)" />'+
                  '</div>'+
               '</div>'+
            '</label>'+
         '</li>';
      }
      else
      {
         var radio = '<li>'+
            '<label class="label-radio item-content">'+
               '<input type="radio" name="user_primary_job_function" value="'+value+'">'+
               '<div class="item-media"><i class="icon icon-form-radio"></i></div>'+
               '<div class="item-inner">'+
                  '<div class="item-title-row">'+
                     '<div class="item-title">'+value+'</div>'+
                  '</div>'+
               '</div>'+
            '</label>'+
         '</li>';
      }
      $('#primaryJobFunction').append(radio);
   });

   $.each(['Fabless/IC Design', 'System Integrator', 'Packaging, Assembly & Test Services Provider', 'PC/Consumer/Commercial Electronics Manufacturer', 'Electronics Manufacturing Services (EMS) Provider', 'Device Manufacturer (IDM, Foundry)', 'Equipment Manufacturer - Semiconductor/MEMS/LED/FHE', 'Manufacturer - Subsystems/Components/Parts', 'Manufacturer - PV Cells and Modules', 'Manufacturer - FPD/LED/Optoelectronics/Photonics', 'Equipment Supplier - Fab & Facilities/Secondary', 'Secondary Equipment and Services', 'Materials Supplier - Semiconductor/MEMS/LED/FHE', 'Software - Manufacturing/Factory Automation', 'Professional Services and Consulting', 'R&D/Academic/Professional Organization/Institutions', 'Media/Publication', 'Government/Public Authority', 'Association/Non-profit', 'Others'], function(index, value)
   {
      if(value == 'Others')
      {
         var radio = '<li>'+
            '<label class="label-checkbox item-content">'+
               '<input type="checkbox" name="user_best_describes_your_company" value="Others">'+
               '<div class="item-media"><i class="icon icon-form-checkbox"></i></div>'+
               '<div class="item-inner">'+value+
                  '<div class="item-input item-input-field">'+
                     '<input type="text" name="user_best_describes_your_company_textbox" placeholder="OTHERS (PLEASE DESCRIBE)" />'+
                  '</div>'+
               '</div>'+
            '</label>'+
         '</li>';
      }
      else
      {
         var radio = '<li>'+
            '<label class="label-checkbox item-content">'+
               '<input type="checkbox" name="user_best_describes_your_company" value="'+value+'">'+
               '<div class="item-media"><i class="icon icon-form-checkbox"></i></div>'+
               '<div class="item-inner">'+
                  '<div class="item-title-row">'+
                     '<div class="item-title">'+value+'</div>'+
                  '</div>'+
               '</div>'+
            '</label>'+
         '</li>';
      }
      $('#bestDescribesYourCompany').append(radio);
   });
});
</script>