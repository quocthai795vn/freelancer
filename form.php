<!-- Special version of Bootstrap that only affects content wrapped in .bootstrap-iso -->
<link rel="stylesheet" href="https://formden.com/static/cdn/bootstrap-iso.css" /> 

<!-- Inline CSS based on choices in "Settings" tab -->
<style>.bootstrap-iso .formden_header h2, .bootstrap-iso .formden_header p, .bootstrap-iso form{font-family: Arial, Helvetica, sans-serif; color: black}.bootstrap-iso form button, .bootstrap-iso form button:hover{color: white !important;} .asteriskField{color: red;}</style>

<!-- HTML Form (wrapped in a .bootstrap-iso div) -->
<div class="bootstrap-iso">
 <div class="container-fluid">
  <div class="row">
   <div class="col-md-6 col-sm-6 col-xs-12">
    <form method="post" action="submit.php">
     <div class="form-group ">
      <label class="control-label " for="name">
       SKU
      </label>
      <input class="form-control" id="name" name="name" type="text"/>
     </div>
     <div class="form-group ">
      <label class="control-label requiredField" for="email">
       Name
       <span class="asteriskField">
        *
       </span>
      </label>
      <input class="form-control" id="email" name="email" type="text"/>
     </div>
     <div class="form-group ">
      <label class="control-label " for="subject">
       Price
      </label>
      <input class="form-control" id="subject" name="subject" type="text"/>
     </div>
     <div class="form-group ">
      <label class="control-label " for="name1">
       Quantity
      </label>
      <input class="form-control" id="name1" name="name1" type="text"/>
     </div>
     <div class="form-group">
      <div>
       <button class="btn btn-primary " name="submit" type="submit">
        Submit
       </button>
      </div>
     </div>
    </form>
   </div>
  </div>
 </div>
</div>
