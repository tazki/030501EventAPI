<div class="pages navbar-fixed">
 <div data-page="signin" class="page">
   <div class="navbar">
     <div class="navbar-inner">
     <div class="left"><a href="#" class="back link"><i class="icon icon-back"></i></a></div>
       <div class="center">Sign In</div>
       <div class="right"><a href="#" class="link icon-only"><i class="icon material-icons">more_vert</i></a></div>
     </div>
   </div>
   <div class="page-content main-background">
     <div class="content-block">
       <form id="ajaxForm" class="list-block inputs-list"method="POST" action="http://localhost/haku/index.php/api/member/login">
         <ul>
           <li>
             <div class="item-content">
               <div class="item-media"><i class="icon material-icons">person_outline</i></div>
               <div class="item-inner"> 
                 <div class="item-title floating-label">Email</div>
                 <div class="item-input">
                   <input type="text" name="user_email_address" />
                 </div>
               </div>
             </div>
           </li>
           <li>
             <div class="item-content">
               <div class="item-media"><i class="icon material-icons">lock_outline</i></div>
               <div class="item-inner"> 
                 <div class="item-title floating-label">Password</div>
                 <div class="item-input">
                   <input type="password" name="user_password" />
                 </div>
               </div>
             </div>
           </li>
<!--                   <li id="terms">
           <label class="label-checkbox item-content">
             <input type="checkbox" name="ks-checkbox" value="accepted"/>
             <div class="item-media"><i id="accepted" class="icon icon-form-checkbox"></i></div>
             <div class="item-inner">
               <div id="accept" class="item-title">Remember Me</div>
             </div>
           </label>
         </li> -->
         </ul>

         <!-- <input type="submit" value="test"> -->
       </form>
     </div>
      <a href="forgot.html" id="forgot" class="button">Forgot Password?</a>
     <div class="content-block">
       <div class="row">
         <div class="col-100">
          <a id="signin" class="button button-raised button-fill">SIGN IN</a>
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

   $('#signin').click(function()
   {
      $.ajax({
       url: baseUrl+'api/member/login',
       type: "POST",
       data: $("#ajaxForm").serialize()
     }).done(function(data)
     {
      console.log(data.status + ' ' + data.alert + ' ' + $.type(data.alert));

      $("#ajaxForm span.error").remove();
      $("#ajaxForm div.msg-holder").remove();
      if(data.status == 'danger' && $.type(data.alert) == 'object')
      {
       $.each(data.alert, function(fieldName, fieldMsg)
       {
        //console.log('key:'+fieldName+' value:'+fieldMsg);
        $("input[name='"+fieldName+"']").attr('style', 'border:1px solid #ff0000;');
        $("input[name='"+fieldName+"']").after(fieldMsg);
       });   
      }
      else
      {
        $('#ajaxForm').prepend('<div class="msg-holder '+data.status+'">'+data.alert+'</div>');
      }
     });
  });
});
</script>