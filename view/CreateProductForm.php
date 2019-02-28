<!-- Special version of Bootstrap that only affects content wrapped in .bootstrap-iso -->
<link rel="stylesheet" href="https://formden.com/static/cdn/bootstrap-iso.css" />
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>

<!-- Inline CSS based on choices in "Settings" tab -->
<style>
    .bootstrap-iso .formden_header h2,
    .bootstrap-iso .formden_header p,
    .bootstrap-iso form {
        font-family: Arial, Helvetica, sans-serif;
        color: black
    }
    
    .bootstrap-iso form button,
    .bootstrap-iso form button:hover {
        color: white !important;
    }
    
    .asteriskField {
        color: red;
    }
</style>

<!-- HTML Form (wrapped in a .bootstrap-iso div) -->
<div class="bootstrap-iso">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12">
                <form method="post" action="../controller/Product/Add.php">
                    <div class="form-group">
                        <label class="control-label " for="sku">
                            SKU
                        </label>
                        <input class="form-control" id="sku" name="sku" type="text" />
                    </div>
                    <div class="form-group">
                        <label class="control-label requiredField" for="name">
                            Name
                            <span class="asteriskField">
                                *
                            </span>
                        </label>
                        <input class="form-control" id="name" name="name" type="text" />
                    </div>
                    <div class="form-group">
                        <label class="control-label " for="price">
                            Price
                        </label>
                        <input class="form-control" id="price" name="price" type="text" />
                    </div>
                    <div class="form-group">
                        <label class="control-label " for="qty">
                            Quantity
                        </label>
                        <input class="form-control" id="qty" name="qty" type="text" />
                    </div>
                    <div class="form-group">
                        <label class="control-label " for="attribute-set">
                            Attribute Name
                        </label>
                        <input class="form-control" id="attribute-set" name="attribute-set" type="text" />
                    </div>
                    <label class="control-label " for="qty">
                            Attribute Product
                        </label>
                    <div class="panel panel-default">
                        <div class="panel-body">

                            <div id="education_fields">

                            </div>
                            <div class="col-sm-5 nopadding">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="attribute[]" value="" placeholder="Attribute">
                                </div>
                            </div>
                            <div class="col-sm-5 nopadding">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="optionValue[]" value="" placeholder="Value">
                                </div>
                            </div>

                            <div class="col-sm-2 nopadding">
                                <div class="form-group">
                                    <div class="input-group">

                                        <div class="input-group-btn">
                                            <button class="btn btn-success" type="button" onclick="education_fields();"> <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clear"></div>

                        </div>
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

<script type="text/javascript">
    var room = 1;

    function education_fields() {

        room++;
        var objTo = document.getElementById('education_fields')
        var divtest = document.createElement("div");
        divtest.setAttribute("class", "form-group removeclass" + room);
        var rdiv = 'removeclass' + room;
        divtest.innerHTML = `<div class="col-sm-5 nopadding">
    <div class="form-group">
        <input type="text" class="form-control" name="attribute[]" value="" placeholder="Attribute">
    </div>
</div>
<div class="col-sm-5 nopadding">
    <div class="form-group">
        <input type="text" class="form-control" name="optionValue[]" value="" placeholder="Value">
    </div>
</div>
<div class="col-sm-2 nopadding">
    <div class="form-group">
        <div class="input-group">
            <div class="input-group-btn">
                <button class="btn btn-danger" type="button" onclick="remove_education_fields(${room});"> <span class="glyphicon glyphicon-minus" aria-hidden="true"></span> </button>
            </div>
        </div>
    </div>
</div>
<div class="clear"></div>`;

        objTo.appendChild(divtest)
    }

    function remove_education_fields(rid) {
        $('.removeclass' + rid).remove();
    }
</script>