<!DOCTYPE html>
<!--
        ___            ___  ___    __    ___      ___  ___________  ___      ___
       /  /           /  / /  /  _/ /   /  /     /  / / _______  / /   \    /  /
      /  /           /  / /  /_ / /    /  /_____/  / / /      / / /     \  /  /
     /  /           /  / /   __|      /   _____   / / /      / / /  / \  \/  /
    /  /_ _ _ _ _  /  / /  /   \ \   /  /     /  / / /______/ / /  /   \    /
   /____________/ /__/ /__/     \_\ /__/     /__/ /__________/ /__/     /__/


   Likhon the hackman, who claims himself as a hacker but really he isn't.
-->
<html lang="en">
<head>
    <meta charset="utf-8">
    <link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>jQuery Invoice Plugin Demo</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootswatch/3.3.7/darkly/bootstrap.min.css" rel="stylesheet">
    <style>

        .delete-btn {
            position: relative;
        }

        .delete {
            display: block;
            color: #000;
            text-decoration: none;
            position: absolute;
            background: #EEEEEE;
            font-weight: bold;
            padding: 0px 3px;
            border: 1px solid;
            top: -6px;
            left: -6px;
            font-family: Verdana;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div id="jquery-script-menu">
<div class="jquery-script-center">
<ul>
<li><a href="https://www.jqueryscript.net/other/Dynamic-Invoice-Generator-Plugin-jQuery.html">Download This Plugin</a></li>
<li><a href="https://www.jqueryscript.net/">Back To jQueryScript.Net</a></li>
</ul>
<div class="jquery-script-ads"><script type="text/javascript"><!--
google_ad_client = "ca-pub-2783044520727903";
/* jQuery_demo */
google_ad_slot = "2780937993";
google_ad_width = 728;
google_ad_height = 90;
//-->
</script>
<script type="text/javascript"
src="https://pagead2.googlesyndication.com/pagead/show_ads.js">
</script></div>
<div class="jquery-script-clear"></div>
</div>
</div>
    <div class="container" style="margin-top:150px;">
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <div>
                    <h2 class="text-center">jQuery Invoice Plugin Demo</h2>
                </div>
            </div>
            <div class="col-xs-12 col-md-12">
                <hr>
                <div class="row">
                    <div class="col-xs-6 col-md-6">
                        <address>
                            <strong>Billed To:</strong><br>
                            Likhon Likh<br>
                            122, Dhaka, Bangladesh<br>
                        </address>
                    </div>
                    <div class="col-xs-6 col-md-6 text-right">
                        <address>
                            <strong>Shipped To:</strong><br>
                            Shishir<br>
                            56, Dhaka, Bangladesh<br>
                        </address>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-6 col-md-6">
                        <address>
                            <strong>Payment Method:</strong><br>
                            Visa ending **** 1234<br>
                            likh.deshi@gmail.com
                        </address>
                    </div>
                    <div class="col-xs-6 col-md-6 text-right">
                        <address>
                            <strong>Order Date:</strong><br>
                            Jan 05, 2017<br><br>
                            Order No: <strong>1234</strong>
                        </address>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="item-row">
                                <th>Item</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                        <tr id="hiderow">
                            <td colspan="4">
                                <a id="addRow" href="javascript:;" title="Add a row" class="btn btn-primary">Add a row</a>
                            </td>
                        </tr>
                        <!-- should be the item row -->
                        <!--<tr class="item-row">
                            <td><input class="form-control item" placeholder="Item" type="text"></td>
                            <td><input class="form-control price" placeholder="Price" type="text"></td>
                            <td><input class="form-control qty" placeholder="Quantity" type="text"></td>
                            <td><span class="total">0.00</span></td>
                        </tr>-->
                        <tr>
                            <td></td>
                            <td></td>
                            <td class="text-right"><strong>Sub Total</strong></td>
                            <td><span id="subtotal">0.00</span></td>
                        </tr>
                        <tr>
                            <td><strong>Total Quantity: </strong><span id="totalQty" style="color: red; font-weight: bold">0</span> Units</td>
                            <td></td>
                            <td class="text-right"><strong>Discount</strong></td>
                            <td><input class="form-control" id="discount" value="0" type="text"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td class="text-right"><strong>Shipping</strong></td>
                            <td><input class="form-control" id="shipping" value="0" type="text"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td class="text-right"><strong>Grand Total</strong></td>
                            <td><span id="grandTotal">0</span></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="{{ asset('assets/admin/jq/jquery.invoice.js') }}"></script>
    <script>
        jQuery(document).ready(function(){
            jQuery().invoice({
                addRow : "#addRow",
                delete : ".delete",
                parentClass : ".item-row",

                price : ".price",
                qty : ".qty",
                total : ".total",
                totalQty: "#totalQty",

                subtotal : "#subtotal",
                discount: "#discount",
                shipping : "#shipping",
                grandTotal : "#grandTotal"
            });
        });
    </script>
    
</body>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-36251023-1']);
  _gaq.push(['_setDomainName', 'jqueryscript.net']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

</html>
