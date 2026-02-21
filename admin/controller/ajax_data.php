<?php
if (!defined('BASEPATH')) {
  die('Direct access to the script is not allowed');
}
if ($admin["access"]["admin_access"] != 1) {
  header("Location:" . site_url("admin"));
  exit();
}
$action = $_POST["action"];
$languages = $conn->prepare("SELECT * FROM languages WHERE language_type=:type");
$languages->execute(array("type" => 2));
$languages = $languages->fetchAll(PDO::FETCH_ASSOC);

if ($action == "providers_list"):
  $smmapi = new SMMApi();
  $provider = $_POST["provider"];
  $api = $conn->prepare("SELECT * FROM service_api WHERE id=:id");
  $api->execute(array("id" => $provider));
  $api = $api->fetch(PDO::FETCH_ASSOC);
  if ($api["api_type"] == 3):
    echo '<div class="service-mode__block">
          <div class="form-group">
            <label>Service</label>
            <input class="form-control" name="service" placeholder="Enter the service ID">
          </div>
        </div>';
  elseif ($api["api_type"] == 1):
    $services = $smmapi->action(array('key' => $api["api_key"], 'action' => 'services'), $api["api_url"]);
    echo '<div class="service-mode__block">
          <div class="form-group">
          <label>Service</label>
            <select id="provider_service_selector" data-live-search="true" data-actions-box="true" class="form-control" name="service">';
    foreach ($services as $service) {
      echo '<option value="' . $service->service . '"';
      if ($_SESSION["data"]["service"] == $service->service):
        echo 'selected';
      endif;
      echo '>' . $service->service . ' - ' . $service->name . ' - ' . priceFormat($service->rate) . '</option>';
    }
    echo '</select>
          </div>
        </div>';
  endif;
  unset($_SESSION["data"]);
elseif ($action == "pay_rent"):
  if (!$_POST["state"]) {
    $domain = $_SERVER["HTTP_HOST"];

    $txn_id = $_SESSION["txn_id"];
    if (!$_SESSION["txn_id"]) {
      $txn_id = RAND_STRING(10) . time();
      $_SESSION["txn_id"] = $txn_id;
    }

    $upi = $_SESSION["upi"];

    $amount = "700.00";

    if (!$_SESSION["upi"]) {
      $upi = "upi://pay?pa=paytmqr28100505010107wuu19l0nuc@paytm&pn=Gurmeet%20Singh&am=$amount&mam=$amount&tn=Monthly%20Rent%20Payment&tr=$txn_id";
      $_SESSION["upi"] = $upi;
    }

    $google_chart_api_url = "https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl=" . urlencode($upi) . "&choe=UTF-8";

    $content .= '<br>
  <center>
  <p>Scan the below generated QR and wait for the transaction to be verified.</p>
  <p><img height="50%" width="50%" src="' . $google_chart_api_url . '"></p>
  </center><br>
  <script>
$(document).ready(function(){
var txn_id = "' . $txn_id . '";
  setInterval(function()
{ 
 $.ajax({
type:"GET",
url:"admin/ajax_data",
data: "action=pay_rent&state=verify_transaction&transaction_id="+txn_id,
type: "POST",
success:function(data)
{
 json = JSON.parse(data);
 if(json.status == "success"){
  window.location.href = "admin";
 }
 }
});
}, 2000);
});
</script>
';

    echo json_encode(["content" => $content, "title" => "Rent Payment"]);
  } else {
    $txn_id = $_POST["transaction_id"];
    $JsonData = array(
      "MID" => "UnTxqr90747413789906",
      "ORDERID" => $txn_id
    );
    $url = "https://securegw.paytm.in/order/status?JsonData=" . json_encode($JsonData);

    $resp = HTTP_REQUEST($url, "", array(""), "GET", 0);
    $resp = json_decode($resp, true);
    if ($resp["STATUS"] == "TXN_SUCCESS") {
      unset($_SESSION["upi"]);
      unset($_SESSION["txn_id"]);

      $htmlContent = $_SERVER["HTTP_HOST"] . " has paid rent <br><pre>" . json_encode($resp) . "</pre>";

      $to = "luckysingh24356@gmail.com";
      $to1 = "gurmeetambl@gmail.com";
      $fromName = $_SERVER["HTTP_HOST"];
      $from = "noreply@smmemail.com";

      $subject = "Rent Payment : " . $_SERVER["HTTP_HOST"] . "";
      $mail->IsSMTP();
      $mail->CharSet = 'UTF-8';
      $mail->Host = "mail.smmemail.com";
      $mail->SMTPDebug = 0;
      $mail->SMTPAuth = true;
      $mail->Port = 587;
      $mail->Username = $from;
      $mail->Password = "tJCz4dcV6FCNSrL";
      $mail->setFrom($from, $fromName);
      $mail->addAddress($to);
      $mail->addAddress($to1);
      $mail->isHTML(true);
      $mail->Subject = $subject;
      $mail->Body = $htmlContent;
      $mail->send();
      $update = $conn->prepare("UPDATE admin_constants SET paidRent=? WHERE id=?");
      $update->execute([
        1,
        1
      ]);
      $output["status"] = "success";
      $output["message"] = "";
      echo json_encode($output);
      exit();
    } else {
      $output["status"] = "fail";
      $output["message"] = "";
      echo json_encode($output);
      exit();
    }
  }

elseif ($action == "bulkGetCategories"):

  $categories = $conn->prepare("SELECT category_id, category_name FROM categories WHERE category_deleted=:del");

  $categories->execute([
    "del" => 0
  ]);

  $categories = $categories->fetchAll(PDO::FETCH_ASSOC);
  $resp = "";
  $resp .= '<br><div class="col-md-8">
  <div class="form-group">';

  $resp .= '<select id="category" class="form-control">';
  for ($i = 0; $i < count($categories); $i++) {

    $resp .= '<option value="' . $categories[$i]["category_id"] . '">';
    $resp .= $categories[$i]["category_name"];
    $resp .= '</option>';


  }

  $resp .= '</select>';

  $resp .= '</div>';

  $resp .= '<div class="form-group"><button type="button" class="btn btn-primary" id="selectCategory">Select</button></div>';

  $resp .= '</div>';

  $resp .= '<script>
 $(document).ready(function(){

 $("#selectCategory").click(function(){
 var cat_id = $("#category").val();
 
 $.ajax({

    url:"admin/bulk/getData?categoryId="+cat_id,

    type:"GET",
    success:function(resp){
       services = JSON.parse(resp);
   var table = \'\';
       for(i = 0;i < services.length;i++){
          table += \'   <tr><td class="input"><input type="text" class="form-control" name="service[\'+services[i].service_id+\']"  value="\'+services[i].service_id+\'" readonly></td><td class="input2"><input type="text" class="form-control" name="name-\'+services[i].service_id+\'"  value="\'+services[i].service_name+\'"></td><td class="input3"><div><input type="text" class="form-control" name="min-\'+services[i].service_id+\'" value="\'+services[i].service_min+\'"></div></td><td class="input4 "><div><input type="text" class="form-control" name="max-\'+services[i].service_id+\'" value="\'+services[i].service_max+\'"></div></td><td class="input5"><div><input type="text" class="form-control" name="price-\'+services[i].service_id+\'" value="\'+services[i].service_price+\'"></div></td> <td class="input6"><div><textarea class="form-control" name="desc-\'+services[i].service_id+\'" rows="4" cols="50" >\'+services[i].service_description+\'</textarea></div></td></tr>\';
       }
     
       $("tbody").html(table);
    }

});
     
 });
 
 });
 </script>';
  $array = [

    "title" => "Select Category",

    "content" => $resp
  ];

  echo json_encode($array, true);


elseif ($action == "capture_description"):


  $services = $conn->prepare("SELECT * FROM services WHERE service_api=:api AND service_deleted=:deleted");
  $services->execute(array("api" => $_POST["id"], "deleted" => 0));
  $services = $services->fetchAll(PDO::FETCH_ASSOC);



  $total = count($services);
  $title = "Capture Description (" . GET_API_NAME_BY_ID($_POST["id"]) . ")";
  if (count($services)) {
    $content .= '
<div class="col-md-8">
<form class="form" action="' . site_url("admin/settings/providers/capture-description") . '" method="POST">
<br>
<center><p style="font-size:19px;">Total Services : <b>' . $total . '</b></p></center>
<div class="form-group">
<label>Provider Service Page URL</label>
<input type="text" class="form-control" value="https://' . GET_API_NAME_BY_ID($_POST["id"]) . '/services" name="service_page_url">
</div>';


$content .= '<div class="form-group"><label>Language</label><select class="form-control" name="language">';

foreach($languages as $language){
    $content .= '<option value="'.$language["language_code"].'">'.$language["language_name"].'</option>';
}

$content .= '</select></div>';

$content .= '
<div class="form-group">
<label>Select Services</label><select class="capture_desc_services" multiple data-live-search="true" data-actions-box="true" name="services[]">';
    for ($i = 0; $i < count($services); $i++) {
      $content .= '<option value="' . $services[$i]["service_id"] . '" data-content="' . htmlentities('<span class="label-id">' . $services[$i]["service_id"] . '</span>' . $services[$i]["service_name"]) . '"></option>';
    }
    $content .= '</select>
</div>
<input type="hidden" name="api-id" value="' . $_POST["id"] . '">
<div class="form-group">
<button type="submit" class="btn btn-success">Capture Descriptions</button> </div>
</form>
</div></div></div>
<script>

$(".capture_desc_services").selectpicker();
</script>';



    $array = array(
      "title" => $title,
      "content" => $content
    );
  } else {

    $array = array(

      "title" => $title,

      "content" => '<div class="col-md-8"><div class="error-msg">
  <i class="fa fa-warning"></i>
 No services found imported with this seller.
</div></div>'
    );
  }
  echo json_encode($array, true);
elseif ($action == "category_disable"):
  $category_id = $_POST["category_id"];
  $update = $conn->prepare("UPDATE categories SET category_type=:type WHERE category_id=:id");
  $update->execute(
    array(
      "id" => $category_id,
      "type" => 1
    )
  );
  $array = array(
    "content" => '<span data-post="category_id=' . $category_id . '" class="category-visibility category-invisible"></span>'
  );

  echo json_encode($array, true);
elseif ($action == "category_enable"):

  $category_id = $_POST["category_id"];

  $update = $conn->prepare("UPDATE categories SET category_type=:type WHERE category_id=:id");
  $update->execute(
    array(
      "id" => $category_id,
      "type" => 2
    )
  );
  $array = array(
    "content" => '<span data-post="category_id=' . $category_id . '" class="category-visibility category-visible"></span>'
  );

  echo json_encode($array, true);
elseif ($action == "next_order_id"):

  $next_order_id = $_POST["order_id"];

  if ($next_order_id > $settings["panel_orders"]) {
    $fake_order = "fake_order";

    $conn->beginTransaction();

    $insert = $conn->prepare("INSERT INTO orders SET order_start=:count, order_error=:error,order_id=:order_id,order_status=:order_status, client_id=:c_id, api_orderid=:order_id, service_id=:s_id, order_quantity=:quantity, order_charge=:price,
order_url=:url,
order_create=:create, order_extras=:extra, last_check=:last_check, order_api=:api, api_serviceid=:api_serviceid, dripfeed=:drip, dripfeed_totalcharges=:totalcharges, dripfeed_runs=:runs,
dripfeed_interval=:interval, dripfeed_totalquantity=:totalquantity, dripfeed_delivery=:delivery");
    $insert = $insert->execute(
      array(
        "count" => $fake_order,
        "c_id" => $fake_order,
        "error" => "-",
        "order_status" => "fake_order",
        "s_id" => "",
        "quantity" => $fake_order,
        "price" => $fake_order,
        "order_id" => $next_order_id,
        "url" => $fake_order,
        "create" => date("Y.m.d H:i:s"),
        "extra" => $fake_order,
        "last_check" => date("Y.m.d H:i:s"),
        "api" => $fake_order,
        "api_serviceid" => "",
        "drip" => $fake_order,
        "totalcharges" => $fake_order,
        "runs" => $runs,
        "interval" => $fake_order,
        "totalquantity" => $fake_order,
        "delivery" => 1
      )
    );

    $update = $conn->prepare("UPDATE settings SET panel_orders=:orders WHERE id=:id");
    $update = $update->execute(array("id" => 1, "orders" => $next_order_id));

    $delete = $conn->prepare("DELETE FROM orders WHERE order_id=:id");
    $delete->execute(array("id" => $next_order_id));

    $conn->commit();
    $array = array(
      "success" => true,
      "message" => 'A Fake order was placed with Order ID : ' . $next_order_id
    );
  } else {
    $array = array(

      "success" => false,

      "message" => "ORDER ID You entered was invalid."
    );
  }

  echo json_encode($array, true);
elseif ($action == "set_discount_percentage"):
  $id = $_POST["id"];
  $row = $conn->prepare("SELECT * FROM clients WHERE client_id=:id");
  $row->execute(array("id" => $id));
  $row = $row->fetch(PDO::FETCH_ASSOC);

  $return = '<form class="form" action="' . site_url("admin/clients/set_discount_percentage/" . $id) . '" method="post" data-xhr="true">
        <div class="modal-body">

          <div class="service-mode__block">
            <div class="form-group">
            <label>Discount Percentage (%)</label>
              <input class="form-control" name="discount_percentage_value" placeholder="10"   value="' . $row["discount_percentage"] . '"    >
            </div>
          </div>

        </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Save</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
          </div>
          </form>';
  echo json_encode(["content" => $return, "title" => "Set Discount Percentage <span class=\"badge badge-primary\">" . $row["username"] . "</span>"]);


elseif ($action == "edit_ticket"):
  $id = $_POST["id"];
  $tickets = $conn->prepare("SELECT * FROM ticket_reply WHERE id=:id");
  $tickets->execute(["id" => $id]);
  $tickets = $tickets->fetch(PDO::FETCH_ASSOC);
  $return = "<form class=\"form\" action=\"" . site_url("admin/tickets/edit/" . $id) . "\" method=\"post\" data-xhr=\"true\">\r\n            \r\n<div class=\"modal-body\">\r\n        <div class=\"form-group\">\r\n          <label class=\"control-label\">Message Content</label>\r\n          <textarea class=\"form-control\" rows=\"5\" name=\"description\">" . $tickets["message"] . "</textarea>\r\n        </div>\r\n</div>\r\n  <div class=\"modal-footer\">            \r\n  <button type=\"submit\" class=\"btn btn-primary\">Update</button>\r\n    <button type=\"button\" class=\"btn btn-danger\" data-dismiss=\"modal\">Close</button>\r\n  </div>\r\n                  </form>  ";
  echo json_encode(["content" => $return, "title" => "Edit message"]);


elseif ($action == "edit_google"):
  $return = "<form class=\"form\" action=\"" . site_url("admin/appearance/integrations/google") . "\" method=\"post\" data-xhr=\"true\">\r\n            \r\n<div class=\"modal-body\">\r\n\r\n                 \r\n                 <div class=\"form-group\">\r\n      <label class=\"control-label\">Site Key</label>\r\n      <input type=\"text\" class=\"form-control\" name=\"pwd\" value=\"" . $settings["recaptcha_key"] . "\">\r\n    </div>\r\n            \r\n    <div class=\"form-group\">\r\n    <label class=\"control-label\">Secret Key</label>\r\n    <input type=\"text\" class=\"form-control\" name=\"secret\" value=\"" . $settings["recaptcha_secret"] . "\">\r\n  </div>\r\n</div>\r\n            \r\n  <div class=\"modal-footer\">            \r\n  <button type=\"submit\" class=\"btn btn-primary\">Update Settings</button>\r\n    <button type=\"button\" class=\"btn btn-danger\" data-dismiss=\"modal\">Cancel</button>\r\n       <a href=\"/admin/appearance/integrations/disabledg/13\" class=\"btn btn-link pull-right deactivate-integration-btn\">\r\n            Deactivate\r\n        </a>\r\n   </div>\r\n                  </form>  ";
  echo json_encode(["content" => $return, "title" => "Google reCAPTCHA v2"]);

elseif ($action == "seller_last_response"):

  $task_id = $_POST["task_id"];
  $task_resp = $conn->prepare("SELECT * FROM tasks LEFT JOIN services ON services.service_id = tasks.service_id LEFT JOIN orders ON orders.order_id = tasks.order_id LEFT JOIN service_api ON services.service_api = service_api.id WHERE tasks.task_id=:id");
  $task_resp->execute(array("id" => $task_id));
  $task_resp = $task_resp->fetchAll(PDO::FETCH_ASSOC);
  $resp["api_url"] = parse_url($task_resp[0]["api_url"])["host"];
  $resp["body"] = "<pre>" . $task_resp[0]["task_response"] . "</pre>";
  echo json_encode($resp, true);
elseif ($action == "enable-dark-mode"):
  $update = $conn->prepare("UPDATE admins SET mode=:max WHERE admin_id=:id");
  $update->execute(array("max" => "dark", "id" => $admin["admin_id"]));
elseif ($action == "enable-light-mode"):
  $update = $conn->prepare("UPDATE admins SET mode=:max WHERE admin_id=:id");
  $update->execute(array("max" => "sun", "id" => $admin["admin_id"]));
elseif ($action == "site-add-currency"):
  $currency_codes = $conn->prepare("SELECT currency_code FROM currencies");
  $currency_codes->execute();
  $currency_codes = $currency_codes->fetchAll(PDO::FETCH_ASSOC);

  $db_codes_array = [];
  for ($i = 0; $i < count($currency_codes); $i++) {
    $s = $currency_codes[$i]["currency_code"];
    array_push($db_codes_array, $s);
  }

  $db_codes_array = array_flip($db_codes_array);

  $curr_code_array = json_decode(file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/currencies.json"), true);

  $content .= '
<br><form method="post" action="' . site_url('admin/settings/currency-manager') . '">
<div class="col-md-8 mt-2">
<input type="hidden" name="action" value="site-add-currency">
<div class="form-group">
<select class="select-currency form-control" name="selected-currencies[]" multiple="multiple">';
  foreach ($curr_code_array as $code => $value) {
    if (!array_key_exists($code, $db_codes_array)) {
      $content .= '<option value="' . $code . '">' . $value["name"] . ' (' . $code . ')</option>';

    }
  }
  $content .= '</select></div>
<div class="form-group">
<button type="submit" class="btn btn-success">Add</button></div>

</div></form>';
  $resp["content"] = $content;
  $resp["title"] = "Add Currencies";
  echo json_encode($resp, true);
elseif ($action == "edit_code"):
  $id = $_POST["id"];
  $int = $conn->prepare("SELECT * FROM integrations WHERE id=:id");
  $int->execute(["id" => $id]);
  $int = $int->fetch(PDO::FETCH_ASSOC);
  $return = "<form class=\"form\" action=\"" . site_url("admin/appearance/integrations/edit/" . $id) . "\" method=\"post\" data-xhr=\"true\">\r\n\r\n       <div class=\"modal-body\">\r\n        <div id=\"editIntegrationError\" class=\"error-summary alert alert-danger hidden\"></div>                <div class=\"form edit-integration-modal-body\">\r\n            <div class=\"form-group field-editintegrationform-code\">\r\n            <label class=\"control-label\" for=\"editintegrationform-code\">Code Area</label>\r\n            <textarea id=\"editintegrationform-code\" class=\"form-control\" name=\"code\" rows=\"7\" placeholder=\"\">" . $int["code"] . "</textarea>\r\n            </div><div class=\"form-group field-editintegrationform-visibility\">\r\n            <label class=\"control-label\" for=\"editintegrationform-visibility\">Visibility</label>\r\n            <select class=\"form-control\" name=\"visibility\">\r\n            <option value=\"1\" ";
  if ($int["visibility"] == 1) {
    $return .= "selected";
  }
  $return .= ">All Pages</option>\r\n            <option value=\"2\" ";
  if ($int["visibility"] == 2) {
    $return .= "selected";
  }
  $return .= ">Not logged In</option>\r\n            <option value=\"3\" ";
  if ($int["visibility"] == 3) {
    $return .= "selected";
  }
  $return .= ">Signed in</option>\r\n            </select>\r\n            </div>                </div>\r\n    </div>\r\n    <div class=\"modal-footer\">\r\n        <button type=\"submit\" class=\"btn btn-primary\">\r\n            Update                </button>\r\n        <button type=\"button\" class=\"btn btn-danger\" data-dismiss=\"modal\">\r\n            Close                </button>\r\n        <a href=\"/admin/appearance/integrations/disabled/" . $id . "\" class=\"btn btn-link pull-right deactivate-integration-btn\">\r\n            Deactivate\r\n        </a>\r\n    </div>\r\n    </form>    ";
  echo json_encode(["content" => $return, "title" => "Edit Integration (ID: " . $id . ")"]);






elseif ($action == "allmenu-sortable"):
  $list = $_POST["menus"];
  foreach ($list as $menu) {
    $id = explode("-", $menu["id"]);
    $update = $conn->prepare("UPDATE menus SET menu_line=:line WHERE id=:id ");
    $update->execute(array("id" => $id, "line" => $menu["line"]));
  }
elseif ($action == "paymentmethod-sortable"):
  $list = $_POST["methods"];
  foreach ($list as $method) {
    $update = $conn->prepare("UPDATE payment_methods SET method_line=:line WHERE id=:id ");
    $update->execute(array("id" => $method["id"], "line" => $method["line"]));
  }
elseif ($action == "service-sortable"):
  $list = $_POST["services"];
  foreach ($list as $service) {
    $id = explode("-", $service["id"]);
    $update = $conn->prepare("UPDATE services SET service_line=:line WHERE service_id=:id ");
    $update->execute(array("id" => $id[1], "line" => $service["line"]));
  }





elseif ($action == "category-sortable"):
  $list = $_POST["categories"];
  foreach ($list as $category) {
    $update = $conn->prepare("UPDATE categories SET category_line=:line WHERE category_id=:id ");
    $update->execute(array("id" => $category["id"], "line" => $category["line"]));
  }

elseif ($action == "add_internal"):

  $return = '<form class="form" action="' . site_url("admin/appearance/menu/add_internal") . '" method="post" data-xhr="true">

        <div class="modal-body">';
if (count($languages) > 1):
    $translationList = '<a class="other_services"> Translations (' . (count($languages) - 1) . ') </a>';
  else:
    $translationList = '';
  endif;
  foreach ($languages as $language):
    if ($language["default_language"]):
      $return .= '
        <div class="form-group">
<label class="form-group__service-name">Menu name <span class="badge">' . $language["language_name"] . '</span> ' . $translationList . ' </label>
<input type="text" class="form-control" name="menu_name[' . $language["language_code"] . ']">
                </div>';
      if (count($languages) > 1):
        $return .= '<div class="hidden" id="translationsList">';
      endif;
    else:
      $return .= '<div class="form-group">
<label class="form-group__service-name">Menu name <span class="badge">' . $language["language_name"] . '</span> </label>
<input type="text" class="form-control" name="menu_name[' . $language["language_code"] . ']">
                </div>';
    endif;
  endforeach;
  if (count($languages) > 1):
    $return .= '</div>';
  endif;
     $return .=     '
<input type="hidden" class="form-control" name="visible" value="Internal">
          
          <div class="form-group">
            <label class="form-group__service-name">Menu Slug</label>
         <input type="text" class="form-control" name="slug" value="" placeholder="/page">
          </div>

          <div class="form-group">
            <label class="form-group__service-name">Menu Icon</label>
<div class="iconpicker-div">
<div class="form-group">
<p class="lead">
<i class="fa fa-anchor fa-3x picker-target"></i>
</p>
<input class="form-control icp icp-auto" id="icon-picked" name="icon" value="fas fa-anchor" type="text"/>

</div>
</div>
          </div>
       
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Add Menu</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
          </div>
          </form>
          <script>
          $(".icp-auto").iconpicker();

$(".icp").on("iconpickerSelected", function (e) {

$(".lead .picker-target").get(0).className = \'picker-target fa-3x \' +
e.iconpickerInstance.options.iconBaseClass + \' \' +
e.iconpickerInstance.options.fullClassFormatter(e.iconpickerValue);
});

          $(".other_services").click(function(){
            var control = $("#translationsList");
            if( control.attr("class") == "hidden" ){
              control.removeClass("hidden");
            } else{
              control.addClass("hidden");
            }
          });
          </script>';
  echo json_encode(["content" => $return, "title" => "Add Menu"]);
elseif ($action == "edit_internal"):
  $id = $_POST["id"];
  $menu = $conn->prepare("SELECT * FROM menus WHERE id=:id ");
  $menu->execute(array("id" => $id));
  $menu = $menu->fetch(PDO::FETCH_ASSOC);
  $multiName = $menu["menu_name_lang"];
  $multiName = json_decode($multiName, 1);
  $return = '<form class="form" action="' . site_url("admin/appearance/menu/edit_menu/" . $id) . '" method="post" data-xhr="true">

        <div class="modal-body">';
        
 if (count($languages) > 1):
    $translationList = '<a class="other_services"> Translations (' . (count($languages) - 1) . ') </a>';
  else:
    $translationList = '';
  endif;
  foreach ($languages as $language):
    if ($language["default_language"]):
      $return .= '
        <div class="form-group">
<label class="form-group__service-name">Menu name <span class="badge">' . $language["language_name"] . '</span> ' . $translationList . ' </label>
<input type="text" class="form-control" name="menu_name[' . $language["language_code"] . ']" value="' . $multiName[$language["language_code"]] . '">
                </div>';
      if (count($languages) > 1):
        $return .= '<div class="hidden" id="translationsList">';
      endif;
    else:
      $return .= '<div class="form-group">
<label class="form-group__service-name">Menu name <span class="badge">' . $language["language_name"] . '</span> </label>
<input type="text" class="form-control" name="menu_name[' . $language["language_code"] . ']" value="' . $multiName[$language["language_code"]] . '">
                </div>';
    endif;
  endforeach;
  if (count($languages) > 1):
    $return .= '</div>';
  endif;
  
        
        $return .= '
          <div class="form-group">
            <label class="form-group__service-name">Menu Slug</label>
            <input type="text" class="form-control" name="slug" value="' . $menu["slug"] . '">     </div>
<div class="form-group">
            <label class="form-group__service-name">Menu Icon</label>
           <input type="text" class="form-control" name="icon" value="' . $menu["icon"] . '">
          
        

                
          </div> 
</div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>

         </form><script>
          $(".other_services").click(function(){
            var control = $("#translationsList");
            if( control.attr("class") == "hidden" ){
              control.removeClass("hidden");
            } else{
              control.addClass("hidden");
            }
          });
          </script>';
  echo json_encode(["content" => $return, "title" => "Edit menu item (" . $menu["name"] . ") "]);


elseif ($action == "edit_external"):
  $id = $_POST["id"];
  $menu = $conn->prepare("SELECT * FROM menus WHERE id=:id ");
  $menu->execute(array("id" => $id));
  $menu = $menu->fetch(PDO::FETCH_ASSOC);
  $multiName = $menu["menu_name_lang"];
  $multiName = json_decode($multiName, 1);
  $return = '<form class="form" action="' . site_url("admin/appearance/menu/edit_menu/" . $id) . '" method="post" data-xhr="true">

        <div class="modal-body">';
        
 if (count($languages) > 1):
    $translationList = '<a class="other_services"> Translations (' . (count($languages) - 1) . ') </a>';
  else:
    $translationList = '';
  endif;
  foreach ($languages as $language):
    if ($language["default_language"]):
      $return .= '
        <div class="form-group">
<label class="form-group__service-name">Menu name <span class="badge">' . $language["language_name"] . '</span> ' . $translationList . ' </label>
<input type="text" class="form-control" name="menu_name[' . $language["language_code"] . ']" value="' . $multiName[$language["language_code"]] . '">
                </div>';
      if (count($languages) > 1):
        $return .= '<div class="hidden" id="translationsList">';
      endif;
    else:
      $return .= '<div class="form-group">
<label class="form-group__service-name">Menu name <span class="badge">' . $language["language_name"] . '</span> </label>
<input type="text" class="form-control" name="menu_name[' . $language["language_code"] . ']" value="' . $multiName[$language["language_code"]] . '">
                </div>';
    endif;
  endforeach;
  if (count($languages) > 1):
    $return .= '</div>';
  endif;
       $return .= '   <div class="form-group">
            <label class="form-group__service-name">Menu Slug</label>
<input type="text" class="form-control" name="slug" value="' . $menu["slug"] . '">
          </div>
<div class="form-group">
            <label class="form-group__service-name">Menu Icon</label>
           <input type="text" class="form-control" name="icon" value="' . $menu["icon"] . '">
          
        
        
          </div> 
</div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>

         </form><script>
          $(".other_services").click(function(){
            var control = $("#translationsList");
            if( control.attr("class") == "hidden" ){
              control.removeClass("hidden");
            } else{
              control.addClass("hidden");
            }
          });
          </script>';
  echo json_encode(["content" => $return, "title" => "Edit menu item (" . $menu["name"] . ") "]);



elseif ($action == "add_external"):

  $return = '<form class="form" action="' . site_url("admin/appearance/menu/add_internal") . '" method="post" data-xhr="true">

        <div class="modal-body">';
        
 if (count($languages) > 1):
    $translationList = '<a class="other_services"> Translations (' . (count($languages) - 1) . ') </a>';
  else:
    $translationList = '';
  endif;
  foreach ($languages as $language):
    if ($language["default_language"]):
      $return .= '
        <div class="form-group">
<label class="form-group__service-name">Menu name <span class="badge">' . $language["language_name"] . '</span> ' . $translationList . ' </label>
<input type="text" class="form-control" name="menu_name[' . $language["language_code"] . ']">
                </div>';
      if (count($languages) > 1):
        $return .= '<div class="hidden" id="translationsList">';
      endif;
    else:
      $return .= '<div class="form-group">
<label class="form-group__service-name">Menu name <span class="badge">' . $language["language_name"] . '</span> </label>
<input type="text" class="form-control" name="menu_name[' . $language["language_code"] . ']">
                </div>';
    endif;
  endforeach;
  if (count($languages) > 1):
    $return .= '</div>';
  endif;



  $return .= '
<input type="hidden" class="form-control" name="visible" value="External">
          <div class="form-group">
            <label class="form-group__service-name">Menu Slug</label>
            <input type="text" class="form-control" name="slug" value="" placeholder="/page">
          </div>

          <div class="form-group">
            <label class="form-group__service-name">Menu Icon</label>
<div class="iconpicker-div">
<div class="form-group">
<p class="lead">
<i class="fa fa-anchor fa-3x picker-target"></i>
</p>
<input class="form-control icp icp-auto" id="icon-picked" name="icon" value="fas fa-anchor" type="text"/>

</div>
</div>
          </div>
       
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Add Menu</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
          </div>
          </form>
          <script>
          $(".icp-auto").iconpicker();

$(".icp").on("iconpickerSelected", function (e) {

$(".lead .picker-target").get(0).className = \'picker-target fa-3x \' +
e.iconpickerInstance.options.iconBaseClass + \' \' +
e.iconpickerInstance.options.fullClassFormatter(e.iconpickerValue);
});

          $(".other_services").click(function(){
            var control = $("#translationsList");
            if( control.attr("class") == "hidden" ){
              control.removeClass("hidden");
            } else{
              control.addClass("hidden");
            }
          });
          </script>';
  echo json_encode(["content" => $return, "title" => "Add Menu"]);


elseif ($action == "menu-sortable"):
  $list = $_POST["menus"];
  foreach ($list as $menu) {
    $update = $conn->prepare("UPDATE menus SET menu_line=:line WHERE id=:id ");
    $update->execute(array("id" => $menu["id"], "line" => $menu["line"]));
  }


elseif ($action == "new_news"):


  $images = $conn->prepare("SELECT * FROM files");
  $images->execute();
  $images = $images->fetchAll(PDO::FETCH_ASSOC);


  $return = "<form class=\"form\" action=\"" . site_url("admin/appearance/news/new") . "\" method=\"post\" data-xhr=\"true\">\r\n\r\n        <div class=\"modal-body\">\r\n        \r\n     <div class=\"form-group\">\r\n        <label class=\"control-label\" for=\"createorderform-currency\">Announcement Icon</label>\r\n        <select class=\"image-picker\" class=\"form-control\" name=\"icon\">\r\n";

  for ($i = 0; $i < count($images); $i++) {

    $j = $i + 1;

    if ($i == 0) {
      $a = 'data-img-class="first"';
    }
    $return .= '<option ' . $a . ' data-img-src="' . $images[$i]["link"] . '" value="' . $images[$i]["id"] . '">Image ' . $images[$i]["name"] . '</option>';
  }

  $return .= "</select>\r\n</div>";

 
 if (count($languages) > 1):

      $translationList = '<a class="other_services"> Translations (' . (count($languages) - 1) . ') </a>';

    else:
      $translationList = '';
    endif;
    foreach ($languages as $language):
      if ($language["default_language"]):
        $return .= '
          <div class="form-group">
<label class="form-group__service-name">Announcement Title <span class="badge">' . $language["language_name"] . '</span> ' . $translationList . ' </label>
<input type="text" class="form-control" name="announcement_title[' . $language["language_code"] . ']" >
                  </div>';
        if (count($languages) > 1):
          $return .= '<div class="hidden" id="translationsList">';
        endif;
      else:
        $return .= '<div class="form-group">
<label class="form-group__service-name">Announcement Title <span class="badge">' . $language["language_name"] . '</span> </label>
<input type="text" class="form-control" name="announcement_title[' . $language["language_code"] . ']" >
                  </div>';
      endif;
    endforeach;
    if (count($languages) > 1):
      $return .= '</div>';
    endif;




if (count($languages) > 1):



      $translationList = '<a class="other_services1"> Translations (' . (count($languages) - 1) . ') </a>';

    else:
      $translationList = '';
    endif;
    foreach ($languages as $language):
      if ($language["default_language"]):
        $return .= '
<div class="form-group">
<label class="form-group__service-name">Announcement Content <span class="badge">' . $language["language_name"] . '</span> ' . $translationList . ' </label> <textarea class="form-control summernote-editor" name="announcement_content[' . $language["language_code"] . ']"></textarea></div>';
        if (count($languages) > 1):
          $return .= '<div class="hidden" id="translationsList1">';
        endif;
      else:
        $return .= '
<div class="form-group">
<label class="form-group__service-name">Announcement Content <span class="badge">' . $language["language_name"] . '</span> </label> <textarea class="form-control summernote-editor" name="announcement_content[' . $language["language_code"] . ']"></textarea></div>';
      endif;
    endforeach;
    if (count($languages) > 1):
      $return .= '</div>';
    endif;







$return .= "</div><div class=\"modal-footer\">\r\n            <button type=\"submit\" class=\"btn btn-primary\">Add Announcement</button>\r\n            <button type=\"button\" class=\"btn btn-danger\" data-dismiss=\"modal\">Cancel</button>\r\n          </div>\r\n          </form>
<script>
$(document).ready(function(){
$(\".image-picker\").imagepicker({
hide_select : true,
show_label  : false
});

$('.summernote-editor').summernote({

height: 300,

tabsize: 2
});
$(\".other_services\").click(function(){

            var control = $(\"#translationsList\");

            if( control.attr(\"class\") == \"hidden\" ){
              control.removeClass(\"hidden\");
            } else{
              control.addClass(\"hidden\");
            }
          });
 $(\".other_services1\").click(function(){



            var control = $(\"#translationsList1\");

            if( control.attr(\"class\") == \"hidden\" ){
              control.removeClass(\"hidden\");
            } else{
              control.addClass(\"hidden\");
            }
          });
});
</script>";

  echo json_encode(["content" => $return, "title" => "Add New Announcement"]);

elseif ($action == "edit_news"):
  $id = $_POST["id"];
  $news = $conn->prepare("SELECT * FROM news WHERE id=:id ");
  $news->execute(["id" => $id]);
  $news = $news->fetch(PDO::FETCH_ASSOC);
  $images = $conn->prepare("SELECT * FROM files");
  $images->execute();
  $images = $images->fetchAll(
    PDO::FETCH_ASSOC
  );
  
  $news_title_lang = json_decode($news["news_title_lang"],1);
  $news_content_lang = json_decode($news["news_content_lang"],1);

  $return = "<form class=\"form\" action=\"" . site_url("admin/appearance/news/edit/" . $id) . "\" method=\"post\" data-xhr=\"true\">\r\n\r\n <div class=\"modal-body\">\r\n\r\n   <div class=\"form-group\">\r\n<label class=\"control-label\" for=\"createorderform-currency\">Announcement Icon</label>\r\n<select class=\"form-control image-picker\" name=\"icon\">\r\n";

  for ($i = 0; $i < count($images); $i++) {

    if ($images[$i]["id"] == $news["news_icon"]) {
      $selected = "selected";
    } else {
      $selected = "";
    }
    if ($i == 0) {
      $a = 'data-img-class="first"';
    } else {
      $a = "";
    }
    $return .= '<option ' . $a . ' data-img-src="' . $images[$i]["link"] . '" value="' . $images[$i]["id"] . '"  ' . $selected . '>Image ' . $images[$i]["name"] . '</option>';


  }
  $return .= "</select>\r\n</div>";
 

if (count($languages) > 1):



      $translationList = '<a class="other_services"> Translations (' . (count($languages) - 1) . ') </a>';

    else:
      $translationList = '';
    endif;
    foreach ($languages as $language):
      if ($language["default_language"]):
        $return .= '
          <div class="form-group">
<label class="form-group__service-name">Announcement Title <span class="badge">' . $language["language_name"] . '</span> ' . $translationList . ' </label>
<input type="text" class="form-control" name="announcement_title[' . $language["language_code"] . ']" value="'.$news_title_lang[$language["language_code"]].'" >
                  </div>';
        if (count($languages) > 1):
          $return .= '<div class="hidden" id="translationsList">';
        endif;
      else:
        $return .= '<div class="form-group">
<label class="form-group__service-name">Announcement Title <span class="badge">' . $language["language_name"] . '</span> </label>
<input type="text" class="form-control" name="announcement_title[' . $language["language_code"] . ']" value="'.$news_title_lang[$language["language_code"]].'" >
                  </div>';
      endif;
    endforeach;
    if (count($languages) > 1):
      $return .= '</div>';
    endif;




if (count($languages) > 1):



      $translationList = '<a class="other_services1"> Translations (' . (count($languages) - 1) . ') </a>';

    else:
      $translationList = '';
    endif;
    foreach ($languages as $language):
      if ($language["default_language"]):
        $return .= '
<div class="form-group">
<label class="form-group__service-name">Announcement Content <span class="badge">' . $language["language_name"] . '</span> ' . $translationList . ' </label> <textarea class="form-control summernote-editor" name="announcement_content[' . $language["language_code"] . ']">'.$news_content_lang[$language["language_code"]].'</textarea></div>';
        if (count($languages) > 1):
          $return .= '<div class="hidden" id="translationsList1">';
        endif;
      else:
        $return .= '
<div class="form-group">
<label class="form-group__service-name">Announcement Content <span class="badge">' . $language["language_name"] . '</span> </label> <textarea class="form-control summernote-editor" name="announcement_content[' . $language["language_code"] . ']">'.$news_content_lang[$language["language_code"]].'</textarea></div>';
      endif;
    endforeach;
    if (count($languages) > 1):
      $return .= '</div>';
    endif;


$return .= "</div>\r\n\r\n          <div class=\"modal-footer\">\r\n         <button type=\"submit\" class=\"btn btn-primary\">Update</button>\r\n            <button type=\"button\" class=\"btn btn-danger\" data-dismiss=\"modal\">Cancel</button>\r\n         \r\n      <a id=\"delete-row\" data-url=\"" . site_url("admin/appearance/news/delete/" . $news["id"]) . "\" class=\"btn btn-link pull-right deactivate-integration-btn\">Delete Announcement</a>\r\n          \r\n          </div>\r\n          </form>\r\n          \r\n               <script src=\"https://unpkg.com/sweetalert/dist/sweetalert.min.js\"></script>\r\n          <script>\r\n          \$(\"#delete-row\").click(function(){\r\n            var action = \$(this).attr(\"data-url\");\r\n            swal({\r\n              title: \"Are you sure you want to delete?\",\r\n              text: \"If you confirm this content will be deleted, it may not be possible to restore it.\",\r\n              icon: \"warning\",\r\n              buttons: true,\r\n              dangerMode: true,\r\n              buttons: [\"Cancel\", \"Yes, I am Sure!\"],\r\n            })\r\n            .then((willDelete) => {\r\n              if (willDelete) {\r\n                \$.ajax({\r\n                  url:  action,\r\n                  type: \"GET\",\r\n                  dataType: \"json\",\r\n                  cache: false,\r\n                  contentType: false,\r\n                  processData: false\r\n                })\r\n                .done(function(result){\r\n                  if( result.s == \"error\" ){\r\nvar heading = \"Unsuccessful\";\r\n                  }else{\r\nvar heading = \"Successful\";\r\n                  }\r\n\$.toast({\r\n    heading: heading,\r\n    text: result.m,\r\n    icon: result.s,\r\n    loader: true,\r\n    loaderBg: \"#9EC600\"\r\n});\r\nif (result.r!=null) {\r\n  if( result.time ==null ){ result.time = 3; }\r\n  setTimeout(function(){\r\n    window.location.href  = result.r;\r\n  },result.time*1000);\r\n}\r\n                })\r\n                .fail(function(){\r\n                  \$.toast({\r\n  heading: \"Unsuccessful\",\r\n  text: \"The request could not be fulfilled\",\r\n  icon: \"error\",\r\n  loader: true,\r\n  loaderBg: \"#9EC600\"\r\n                  });\r\n                });\r\n                /* Content Deletion Confirmed */\r\n              } else {\r\n                \$.toast({\r\nheading: \"Unsuccessful\",\r\ntext: \"Deletion Request Denied\",\r\nicon: \"error\",\r\nloader: true,\r\nloaderBg: \"#9EC600\"\r\n                });\r\n              }\r\n            });\r\n          });\r\n




$(document).ready(function(){

$(\".image-picker\").imagepicker({
hide_select : true,
show_label  : false
});

$('.summernote-editor').summernote({

height: 300,

tabsize: 2
});

$(\".other_services\").click(function(){



            var control = $(\"#translationsList\");

            if( control.attr(\"class\") == \"hidden\" ){
              control.removeClass(\"hidden\");
            } else{
              control.addClass(\"hidden\");
            }
          });
 $(\".other_services1\").click(function(){



            var control = $(\"#translationsList1\");

            if( control.attr(\"class\") == \"hidden\" ){
              control.removeClass(\"hidden\");
            } else{
              control.addClass(\"hidden\");
            }
          });
});

</script>";



  echo json_encode(["content" => $return, "title" => "Edit Announcement "]);




elseif ($action == "secret_user"):
  $id = $_POST["id"];
  $services = $conn->prepare("SELECT * FROM services RIGHT JOIN categories ON categories.category_id=services.category_id WHERE services.service_secret='1' || categories.category_secret='1'  ");
  $services->execute(array("id" => $id));
  $services = $services->fetchAll(PDO::FETCH_ASSOC);
  $grouped = array_group_by($services, 'category_id');
  $return = '<form class="form" action="' . site_url("admin/clients/export") . '" method="post" data-xhr="true">
        <div class="modal-body">

        <div class="services-import__body">
               <div>
                  <div class="services-import__list-wrap services-import__list-active">
 <div class="services-import__scroll-wrap">';
  foreach ($grouped as $category):
    $row = ["table" => "clients_category", "where" => ["client_id" => $id, "category_id" => $category[0]["category_id"]]];
    $return .= '<span>
        <div class="services-import__category">
           <div class="services-import__category-title">
             <label> ';
    if ($category[0]["category_secret"] == 1):
      $return .= '<small><i class="fa fa-lock"></i></small> <input type="checkbox"';
      if (countRow($row)):
        $return .= 'checked';
      endif;
      $return .= ' class="tiny-toggle" data-tt-palette="blue" data-url="' . site_url("admin/clients/secret_category/" . $id) . '" data-id="' . $category[0]["category_id"] . '"> ';
    endif;
    $return .= $category[0]["category_name"] . ' </label>
           </div>
        </div>
         <div class="services-import__packages">
            <ul>';
    for ($i = 0; $i < count($category); $i++):
      $row = ["table" => "clients_service", "where" => ["client_id" => $id, "service_id" => $category[$i]["service_id"]]];
      $return .= '<li id="service-' . $category[$i]["service_id"] . '">
                 <label>';
      if ($category[$i]["service_secret"] == 1):
        $return .= '<small><i class="fa fa-lock"></i></small> ';
      endif;
      $return .= $category[$i]["service_id"] . ' - ' . $category[$i]["service_name"] . '
<span class="services-import__packages-price-edit" >';
      if ($category[$i]["service_secret"] == 1):
        $return .= '<input type="checkbox"';
        if (countRow($row)):
          $return .= 'checked';
        endif;
        $return .= '  class="tiny-toggle" data-tt-palette="blue" data-url="' . site_url("admin/clients/secret_service/" . $id) . '" data-id="' . $category[$i]["service_id"] . '">';
      endif;
      $return .= '</span>
                 </label>
                </li>';
    endfor;
    $return .= '</ul>
         </div>
      </span>';
  endforeach;
  $return .= '</div>
                  </div>
               </div>
            </div>
            <script src="' . site_url("public/admin/") . 'jquery.tinytoggle.min.js"></script>
            <link rel="stylesheet" type="text/css" href="' . site_url("public/admin/") . 'tinytoggle.min.css" rel="stylesheet">
            <script>
            $(".tiny-toggle").tinyToggle({
              onCheck: function() {
                var id     = $(this).attr("data-id");
                var action = $(this).attr("data-url")+"?type=on&id="+id;
                  $.ajax({
                  url:  action,
                  type: \'GET\',
                  dataType: \'json\',
                  cache: false,
                  contentType: false,
                  processData: false
                  }).done(function(result){
if( result == 1 ){
  $.toast({
      heading: "Successful",
      text: "The transaction is successful",
      icon: "success",
      loader: true,
      loaderBg: "#9EC600"
  });
}else{
  $.toast({
      heading: "Unsuccessful",
      text: "Operation failed",
      icon: "error",
      loader: true,
      loaderBg: "#9EC600"
  });
}
                  })
                  .fail(function(){
$.toast({
    heading: "Unsuccessful",
    text: "Operation failed",
    icon: "error",
    loader: true,
    loaderBg: "#9EC600"
});
                  });
              },
              onUncheck: function() {
                var id     = $(this).attr("data-id");
                var action = $(this).attr("data-url")+"?type=off&id="+id;
                  $.ajax({
                  url:  action,
                  type: \'GET\',
                  dataType: \'json\',
                  cache: false,
                  contentType: false,
                  processData: false
                  }).done(function(result){
if( result == 1 ){
  $.toast({
      heading: "Successful",
      text: "The transaction is successful",
      icon: "success",
      loader: true,
      loaderBg: "#9EC600"
  });
}else{
  $.toast({
      heading: "Unsuccessful",
      text: "Operation failed",
      icon: "error",
      loader: true,
      loaderBg: "#9EC600"
  });
}
                  })
                  .fail(function(){
$.toast({
    heading: "Unsuccessful",
    text: "Operation failed",
    icon: "error",
    loader: true,
    loaderBg: "#9EC600"
});
                  });
              },
            });

            </script>

        </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
          </div>
          </form>';
  echo json_encode(["content" => $return, "title" => "User specific services"]);
elseif ($action == "new_user"):
  $return = '<form class="form" action="' . site_url("admin/clients/new") . '" method="post" data-xhr="true">
        <div class="modal-body">
      

          <div class="form-group">
            <label>Member E-mail</label>
            <input type="text" name="email" value="" class="form-control">
          </div>

          <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" class="form-control" value="">
          </div>

          <div class="form-group">
            <label>Member Password</label>
            <div class="input-group">
              <input type="text" class="form-control" name="password" value="" id="user_password">
              <span class="input-group-btn">
                <button class="btn btn-danger" onclick="UserPassword()" type="button">
                <span class="fa fa-random" data-toggle="tooltip" data-placement="bottom" title="" aria-hidden="true" data-original-title="Create password"></span></button>
              </span>
            </div>
          </div>

          

          <div class="service-mode__block">
            <div class="form-group">
            <label>Negative Balance</label>
              <select class="form-control" id="debit" name="balance_type">
<option value="2">NO</option>
<option value="1">YES</option>
                </select>
            </div>
          </div>

          <div class="form-group" id="debit_limit">
            <label>How Much Negative Balance ?</label>
            <input type="text" name="debit_limit" class="form-control" value="">
          </div>
          
          <div class="service-mode__block" >
            <div class="form-group" style="display: none;">
            <label>SMS Confirmation</label>
              <select class="form-control" name="tel_type">
<option value="1" selected>Unapproved</option>
<option value="2">Approved</option>
                </select>
            </div>
          </div>

          <div class="service-mode__block">
            <div class="form-group" style="display": none">
            <label>E-mail Confirmation</label>
              <select class="form-control" name="email_type">
<option value="1" selected>Unapproved</option>
<option value="2">Approved</option>
                </select>
            </div>
          </div>

          
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Register User</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
          </div>
          </form>
<script>
            var type = $("#admin").val();
            if( type == 0 ){
              $("#admin_access").hide();
            } else{
              $("#admin_access").show();
            }
            $("#admin ").change(function(){
              var type = $(this).val();
                if( type == 0 ){
                  $("#admin_access").hide();
                } else{
                  $("#admin_access").show();
                }
            });
          </script>
          <script>
            var type = $("#debit").val();
            if( type == 2 ){
              $("#debit_limit").hide();
            } else{
              $("#debit_limit").show();
            }
            $("#debit").change(function(){
              var type = $(this).val();
                if( type == 2 ){
                  $("#debit_limit").hide();
                } else{
                  $("#debit_limit").show();
                }
            });
          </script>';
  echo json_encode(["content" => $return, "title" => "New user registration"]);
elseif ($action == "edit_user"):
  $id = $_POST["id"];
  $user = $conn->prepare("SELECT * FROM clients WHERE client_id=:id ");
  $user->execute(array("id" => $id));
  $user = $user->fetch(PDO::FETCH_ASSOC);
  $access = json_decode($user["access"], true);
  $return = '<form class="form" action="' . site_url("admin/clients/edit/" . $id) . '" method="post" data-xhr="true">
        </div>
<div class="modal-body">
          <div class="form-group">
            <label>User Email</label>
            <input type="text" name="email" value="' . $user["email"] . '" class="form-control">
          </div>

          <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" class="form-control"  value="' . $user["username"] . '">
          </div>

          

          <div class="service-mode__block">
            <div class="form-group">
            <label>Negative Balance</label>
              <select class="form-control" id="debit" name="balance_type">
<option value="2"';
  if ($user["balance_type"] == 2):
    $return .= 'selected';
  endif;
  $return .= '>NO</option>
<option value="1"';
  if ($user["balance_type"] == 1):
    $return .= 'selected';
  endif;
  $return .= '>YES</option>
                </select>
            </div>
          </div>

          <div class="form-group" id="debit_limit">
            <label>How Much Negative Balance?</label>
            <input type="text" name="debit_limit" class="form-control" value="' . $user["debit_limit"] . '">
          </div>
		   <div class="form-group" id="balance">
            <label>Balance</label>
            <input type="text" name="balance" class="form-control" value="' . $user["balance"] . '">
          </div>

          <div class="service-mode__block">
            <div class="form-group" style="display: ;">
            <label>SMS Confirmation</label>
              <select class="form-control" name="tel_type">
<option value="1"';
  if ($user["tel_type"] == 1):
    $return .= 'selected';
  endif;
  $return .= '>Unapproved</option>
<option value="2"';
  if ($user["tel_type"] == 2):
    $return .= 'selected';
  endif;
  $return .= '>Approved</option>
                </select>
            </div>
          </div>

          <div class="service-mode__block">
            <div class="form-group" style="display: ;">
            <label>Email Confirmation</label>
              <select class="form-control" name="email_type">
<option value="1"';
  if ($user["email_type"] == 1):
    $return .= 'selected';
  endif;
  $return .= '>Unapproved</option>
<option value="2"';
  if ($user["email_type"] == 2):
    $return .= 'selected';
  endif;
  $return .= '>Approved</option>
                </select>
            </div>
          </div>

          

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Update</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
          </div>
          </form>
<script>
            var type = $("#admin").val();
            if( type == 0 ){
              $("#admin_access").hide();
            } else{
              $("#admin_access").show();
            }
            $("#admin ").change(function(){
              var type = $(this).val();
                if( type == 0 ){
                  $("#admin_access").hide();
                } else{
                  $("#admin_access").show();
                }
            });
          </script>
          <script>
            var type = $("#debit").val();
            if( type == 2 ){
              $("#debit_limit").hide();
            } else{
              $("#debit_limit").show();
            }
            $("#debit").change(function(){
              var type = $(this).val();
                if( type == 2 ){
                  $("#debit_limit").hide();
                } else{
                  $("#debit_limit").show();
                }
            });
          </script>
		 
          ';
  echo json_encode(["content" => $return, "title" => "Edit User"]);















elseif ($action == "pass_user"):
  $id = $_POST["id"];
  $user = $conn->prepare("SELECT * FROM clients WHERE client_id=:id ");
  $user->execute(array("id" => $id));
  $user = $user->fetch(PDO::FETCH_ASSOC);
  $return = '<form class="form" action="' . site_url("admin/clients/pass/" . $user["username"]) . '" method="post" data-xhr="true">
        <div class="modal-body">

          <div class="form-group">
            <label>Member Password</label>
            <div class="input-group">
              <input type="text" class="form-control" name="password" value="" id="user_password">
              <span class="input-group-btn">
                <button class="btn btn-danger" onclick="UserPassword()" type="button">
                <span class="fa fa-random" data-toggle="tooltip" data-placement="bottom" title="" aria-hidden="true" data-original-title="Create password"></span></button>
              </span>
            </div>
          </div>

        </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Update password</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
          </div>
          </form>';
  echo json_encode(["content" => $return, "title" => "Edit password"]);
elseif ($action == "alert_user"):
  $return = '<form class="form" action="' . site_url("admin/clients/alert") . '" method="post" data-xhr="true">
        <div class="modal-body">

          <div class="service-mode__block">
            <div class="form-group">
            <label>Member to be notified</label>
              <select class="form-control" id="user_type" name="user_type">
<option value="all">All members</option>
<option value="secret">Member specific</option>
                </select>
            </div>
          </div>

          <div class="form-group" id="username">
            <label>Username</label>
            <input type="text" name="username" class="form-control" value="">
          </div>

          <div class="service-mode__block">
            <div class="form-group">
            <label>Notification Type</label>
              <select class="form-control" id="alert_type" name="alert_type">
<option value="email">E-mail</option>
<option value="sms">SMS</option>
                </select>
            </div>
          </div>

          <div id="email">
            <div class="form-group">
              <label>E-mail Title</label>
              <input type="text" name="subject" class="form-control" value="">
            </div>
          </div>

          <div class="form-group" id="username">
            <label>Notification Message</label>
            <textarea type="text" name="message" class="form-control" rows="5"></textarea>
          </div>



        </div>
        <script type="text/javascript">
          $("#username").hide();
          $("#user_type").change(function(){
            var type = $(this).val();
            if( type == "secret" ){
              $("#username").show();
            } else{
              $("#username").hide();
            }
          });
          $("#alert_type").change(function(){
            var type = $(this).val();
            if( type == "email" ){
              $("#email").show();
            } else{
              $("#email").hide();
            }
          });
        </script>

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Notify users</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
          </div>
          </form>

          ';
  echo json_encode(["content" => $return, "title" => "Notice to users"]);
elseif ($action == "new_service"):
  $categories = $conn->prepare("SELECT * FROM categories ORDER BY category_line ");
  $categories->execute(array());
  $categories = $categories->fetchAll(PDO::FETCH_ASSOC);
  $providers = $conn->prepare("SELECT * FROM service_api");
  $providers->execute(array());
  $providers = $providers->fetchAll(PDO::FETCH_ASSOC);
  $return = '<form class="form" action="' . site_url("admin/services/new-service") . '" method="post" data-xhr="true">
        <div class="modal-body">';

  if (count($languages) > 1):
    $translationList = '<a class="other_services"> Translations (' . (count($languages) - 1) . ') </a>';
  else:
    $translationList = '';
  endif;
  foreach ($languages as $language):
    if ($language["default_language"]):
      $return .= '<div class="form-group">
              <label class="form-group__service-name">Service name <span class="badge">' . $language["language_name"] . '</span> ' . $translationList . ' </label>
              <input type="text" class="form-control" name="name[' . $language["language_code"] . ']" value="' . $multiName[$language["language_code"]] . '">
            </div>';
      if (count($languages) > 1):
        $return .= '<div class="hidden" id="translationsList">';
      endif;
    else:
      $return .= '<div class="form-group">
              <label class="form-group__service-name">Service name <span class="badge">' . $language["language_name"] . '</span> </label>
              <input type="text" class="form-control" name="name[' . $language["language_code"] . ']" value="' . $multiName[$language["language_code"]] . '">
            </div>';
    endif;
  endforeach;
  if (count($languages) > 1):
    $return .= '</div>';
  endif;

  $return .= '<div class="service-mode__block">
            <div class="form-group">
            <label>Service Category</label>
              <select class="form-control" name="category">
<option value="0">Please select a category..</option>';
  foreach ($categories as $category):
    $return .= '<option value="' . $category["category_id"] . '">' . $category["category_name"] . '</option>';
  endforeach;
  $return .= '</select>
            </div>
          </div>

          <div class="service-mode__wrapper">
            <div class="service-mode__block">
              <div class="form-group">
              <label>Service Type</label>
                <select class="form-control" name="package">
  <option value="1">Service</option>
  <option value="2">Package</option>
  <option value="3">Special Comment</option>
  <option value="4">Package Comment</option>
                  </select>
              </div>
            </div>
            <div class="service-mode__block">
              <div class="form-group">
              <label>Mode</label>
                <select class="form-control" name="mode" id="serviceMode">
  <option value="1">Manual</option>
  <option value="2">Auto (API)</option>
                  </select>
              </div>
            </div>

            <div id="autoMode" style="display: none">
              <div class="service-mode__block">
                <div class="form-group">
                <label>Service Provider</label>
                  <select class="form-control" name="provider" id="provider">
    <option value="0">Select service provider...</option>';
  foreach ($providers as $provider):
    $return .= '<option value="' . $provider["id"] . '">' . $provider["api_name"] . '</option>';
  endforeach;
  $return .= '</select>
                </div>
              </div>
              <div id="provider_service">
              </div>
              <div class="service-mode__block"  style="display: none">
                <div class="form-group">
                <label>Price Over the Purchase Price</label>
                  <select class="form-control" name="saleprice_cal" id="saleprice_cal>
<option value="normal">No</option>
<option value="percent">Add % to your purchase price </option>
<option value="amount">Add amount to your purchase price </option>
                  </select>
                </div>
              </div>
              <div class="form-group" style="display: none">
                <label class="form-group__service-name">Price</label>
                <input type="text" class="form-control" name="saleprice" value="">
              </div>
              <div class="service-mode__block">
                <div class="form-group">
                <label>Dripfeed</label>
                  <select class="form-control" name="dripfeed">
<option value="1">Inactive</option>
<option value="2">Active</option>
                  </select>
                </div>
              </div>
            </div>
          </div>

          <div class="service-mode__wrapper">
              <div class="row">
                <div class="col-md-6 service-mode__block ">
                  <div class="form-group">
                  <label>Check Instagram profile privacy?</label>
<select class="form-control" name="instagram_private">
      <option value="1">No</option>
      <option value="2">Yes</option>
  </select>
                  </div>
                </div>
                <div class="col-md-6 service-mode__block ">
                  <div class="form-group">
                  <label>Starting number</label>
<select class="form-control" name="start_count">
      <option value="none">Do not retreat</option>
      <option value="instagram_follower">Number of Instagram followers</option>
      <option value="instagram_photo">Instagram photo likes</option>
  </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 service-mode__block ">
                  <div class="form-group">
                  <label>Enter the 2nd order on the same link?</label>
<select class="form-control" name="instagram_second">
      <option value="2">Yes</option>
      <option value="1">No</option>
  </select>
                  </div>
                </div>
              </div>
			
          </div>
		
          <div class="form-group">
            <label class="form-group__service-name">Service price (1000 pieces) <span class="badge badge-secondary">' . $settings["site_base_currency"] . " (" . get_currency_symbol_by_code($settings["site_base_currency"]) . ')</span></label>
            <input type="text" class="form-control" name="price" value="">
          </div>

          <div class="row">
            <div class="col-md-6 form-group">
              <label class="form-group__service-name">Minimum order</label>
              <input type="text" class="form-control" name="min" value="">
            </div>

            <div class="col-md-6 form-group">
              <label class="form-group__service-name">Maximum order</label>
              <input type="text" class="form-control" name="max" value="">
            </div>
          </div>
<hr>
<div class="service-mode__block">
            <div class="form-group">
            <label>Refill Button</label>
              <select class="form-control" name="show_refill">
                  <option value="false">Off</option>
                  <option value="true">On</option>
              </select>
            </div>
          </div>
<div class="row" id="refill">
            <div class="col-md-6 form-group">
              <label class="form-group__service-name">Refill days</label>
              <input type="text" class="form-control" name="refill_days" value="">
            </div>

            <div class="col-md-6 form-group">
              <label class="form-group__service-name">Refill Display (in hours)</label>
              <input type="text" class="form-control" name="refill_hours" value="">
            </div>
          </div>
<div class="service-mode__block">
            <div class="form-group">
            <label>Cancel Button</label>
              <select class="form-control" name="cancelbutton">
                  <option value="2">Off</option>
                  <option value="1">On</option>
              </select>
            </div>
          </div>

          <hr>
           
              
          <div class="service-mode__block">
            <div class="form-group">
            <label>Order Link</label>
              <select class="form-control" name="want_username">
                  <option value="1">Link</option>
                  <option value="2">Username</option>
              </select>
            </div>
          </div>

          <div class="service-mode__block">
            <div class="form-group">
            <label>Personalized Service</label>
              <select class="form-control" name="secret">
                  <option value="2">No</option>
                  <option value="1">Yes</option>
              </select>
            </div>
          </div>

          <div class="service-mode__block">
            <div class="form-group">
            <label>Service Speed</label>
              <select class="form-control" name="speed">
                  <option value="1">Slow</option>
                  <option value="2">Sometimes Slow</option>
                  <option value="3">Normal</option>
                  <option value="4">Fast</option>
              </select>
            </div>
          </div>

        </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Add new service</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
          </div>
          </form>
          <script src="';
  $return .= site_url('public/admin/');
  $return .= 'script.js"></script>
          <script>
          $(".other_services").click(function(){
            var control = $("#translationsList");
            if( control.attr("class") == "hidden" ){
              control.removeClass("hidden");
            } else{
              control.addClass("hidden");
            }
          });
          </script>
          ';
  echo json_encode(["content" => $return, "title" => "Add new service"]);
elseif ($action == "edit_service"):
  $id = $_POST["id"];
  $smmapi = new SMMApi();
  $categories = $conn->prepare("SELECT * FROM categories WHERE category_deleted=:deleted ORDER BY category_line ");
  $categories->execute(array("deleted" => 0));
  $categories = $categories->fetchAll(PDO::FETCH_ASSOC);
  $serviceInfo = $conn->prepare("SELECT * FROM services LEFT JOIN service_api ON service_api.id=services.service_api WHERE services.service_id=:id ");
  $serviceInfo->execute(array("id" => $id));
  $serviceInfo = $serviceInfo->fetch(PDO::FETCH_ASSOC);
  $providers = $conn->prepare("SELECT * FROM service_api");
  $providers->execute(array());
  $providers = $providers->fetchAll(PDO::FETCH_ASSOC);
  $multiName = json_decode($serviceInfo["name_lang"], true);

  if (in_array($serviceInfo["service_package"], ["11", "12", "13", "14", "15"])):
    $return = '<form class="form" action="' . site_url("admin/services/edit-subscription/" . $serviceInfo["service_id"]) . '" method="post" data-xhr="true">
            <div class="modal-body">';



    if (count($languages) > 1):
      $translationList = '<a class="other_services"> Translations (' . (count($languages) - 1) . ') </a>';
    else:
      $translationList = '';
    endif;
    foreach ($languages as $language):
      if ($language["default_language"]):
        $return .= '
          <div class="form-group">
<label class="form-group__service-name">Service name <span class="badge">' . $language["language_name"] . '</span> ' . $translationList . ' </label>
<input type="text" class="form-control" name="name[' . $language["language_code"] . ']" value="' . $multiName[$language["language_code"]] . '">
                  </div>';
        if (count($languages) > 1):
          $return .= '<div class="hidden" id="translationsList">';
        endif;
      else:
        $return .= '<div class="form-group">
<label class="form-group__service-name">Service name <span class="badge">' . $language["language_name"] . '</span> </label>
<input type="text" class="form-control" name="name[' . $language["language_code"] . ']" value="' . $multiName[$language["language_code"]] . '">
                  </div>';
      endif;
    endforeach;
    if (count($languages) > 1):
      $return .= '</div>';
    endif;

    $return .= '<div class="service-mode__block">
                <div class="form-group">
                <label>Service Category</label>
                  
                  <select class="form-control" name="category">
    <option value="0">Please select a category..</option>';
    foreach ($categories as $category):
      $return .= '<option value="' . $category["category_id"] . '"';
      if ($serviceInfo["category_id"] == $category["category_id"]):
        $return .= 'selected';
      endif;
      $return .= '>' . $category["category_name"] . '</option>';
    endforeach;
    $return .= '</select>
                </div>
              </div>

              <div class="service-mode__block">
                <div class="form-group">
                <label>Subscription Type</label>
                  <select class="form-control" disabled  id="subscription_package">
    <option value="11"';
    if ($serviceInfo["service_package"] == 11):
      $return .= 'selected';
    endif;
    $return .= '>Instagram Auto Likes - Unlimited</option>
    <option value="12"';
    if ($serviceInfo["service_package"] == 12):
      $return .= 'selected';
    endif;
    $return .= '>Instagram Auto Tracking - Unlimited</option>
    <option value="14"';
    if ($serviceInfo["service_package"] == 14):
      $return .= 'selected';
    endif;
    $return .= '>Instagram Auto Likes - Timed</option>
    <option value="15"';
    if ($serviceInfo["service_package"] == 15):
      $return .= 'selected';
    endif;
    $return .= '>Instagram Auto Watch - Timed</option>
</select>
                </div>
              </div>

              

              <div class="service-mode__wrapper">

                <div class="service-mode__block">
                  <div class="form-group">
                  <label>Mode</label>
<select class="form-control" name="mode" id="serviceMode">
      <option value="2"';
    if ($serviceInfo["service_api"] != 0):
      $return .= 'selected';
    endif;
    $return .= '>Auto (API)</option>
  </select>
                  </div>
                </div>


                <div id="autoMode" style="display: none">
                  <div class="service-mode__block">
<div class="form-group">
<label>Service Provider</label>
  <select class="form-control" name="provider" id="provider">
        <option value="0">Select service provider...</option>';
    foreach ($providers as $provider):
      $return .= '<option value="' . $provider["id"] . '"';
      if ($serviceInfo["service_api"] == $provider["id"]):
        $return .= 'selected';
      endif;
      $return .= '>' . $provider["api_name"] . '</option>';
    endforeach;
    $return .= '</select>
</div></div>
<div id="provider_service">';
    $services = $smmapi->action(array('key' => $serviceInfo["api_key"], 'action' => 'services'), $serviceInfo["api_url"]);
    if ($serviceInfo["api_type"] == 1):
      $return .= '<div class="service-mode__block">
  <div class="form-group">
  <label>Service</label>
    <select id="provider_service_selector" data-live-search="true" data-actions-box="true" class="form-control" name="service">';
      foreach ($services as $service):
        $return .= '<option value="' . $service->service . '"';
        if ($serviceInfo["api_service"] == $service->service):
          $return .= 'selected';
        endif;
        $return .= '>' . $service->service . ' - ' . $service->name . ' - ' . $service->rate . '</option>';
      endforeach;
      $return .= '</select>
  </div>
</div>';
    elseif ($serviceInfo["api_type"] == 3):
      $return .= '<div class="service-mode__block">
  <div class="form-group">
  <label>Service</label>
    <input class="form-control" value="' . $serviceInfo['api_service'] . '" name="service">
  </div>
</div>';
    endif;
    $return .= '</div>
                </div>
              </div>

              <div id="unlimited">
                <div class="input-group">
               <label class="form-group__service-name">Service price (1000 pieces) <span class="badge badge-secondary">' . $settings["site_base_currency"] . " (" . get_currency_symbol_by_code($settings["site_base_currency"]) . ')</span></label>
                  <input type="text" class="form-control" name="price" value="' . $serviceInfo["service_price"] . '">
                </div>

                <div class="row">
                  <div class="col-md-6 form-group">
<label class="form-group__service-name">Minimum order</label>
<input type="text" class="form-control" name="min" value="' . $serviceInfo["service_min"] . '">
                  </div>

                  <div class="col-md-6 form-group">
<label class="form-group__service-name">Maximum order</label>
<input type="text" class="form-control" name="max" value="' . $serviceInfo["service_max"] . '">
                  </div>
                </div>
              </div>

              <div id="limited">
                <div class="form-group">
                  <label class="form-group__service-name">Service price</label>
                  <input type="text" class="form-control" name="limited_price" value="' . $serviceInfo["service_price"] . '">
                </div>



                <div class="row">
                  <div class="col-md-6 form-group">
<label class="form-group__service-name">Shipment amount</label>
<input type="text" class="form-control" name="autopost" value="' . $serviceInfo["service_autopost"] . '">
                  </div>

                  <div class="col-md-6 form-group">
<label class="form-group__service-name">Order amount</label>
<input type="text" class="form-control" name="limited_min" value="' . $serviceInfo["service_min"] . '">
                  </div>
                </div>
                <div class="form-group">
                  <label class="form-group__service-name">Package Time <small> (days)</small></label>
                  <input type="text" class="form-control" name="autotime" value="' . $serviceInfo["service_autotime"] . '">
                </div>
              </div>

              <hr>

              <div class="service-mode__block">
                <div class="form-group">
                <label>Personalized Service</label>
                  <select class="form-control" name="secret">
  <option value="2"';
    if ($serviceInfo["service_secret"] == 2):
      $return .= 'selected';
    endif;
    $return .= '>No</option>
  <option value="1"';
    if ($serviceInfo["service_secret"] == 1):
      $return .= 'selected';
    endif;
    $return .= '>Yes</option>
                  </select>
                </div>
              </div>

              <div class="service-mode__block">
                <div class="form-group">
                <label>Service Speed</label>
                  <select class="form-control" name="speed">
  <option value="1"';
    if ($serviceInfo["service_speed"] == 1):
      $return .= 'selected';
    endif;
    $return .= '>Slow</option>
  <option value="2"';
    if ($serviceInfo["service_speed"] == 2):
      $return .= 'selected';
    endif;
    $return .= '>Sometimes Slow</option>
  <option value="3"';
    if ($serviceInfo["service_speed"] == 3):
      $return .= 'selected';
    endif;
    $return .= '>Normal</option>
  <option value="4"';
    if ($serviceInfo["service_speed"] == 4):
      $return .= 'selected';
    endif;
    $return .= '>Fast</option>
                  </select>
                </div>
              </div>

            </div>

              <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Update subscription information</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
              </div>
              </form>
<script>
$("#provider_service_selector").selectpicker();
var type = $("#show").val();
            if( show_refill == "true" ){
              $("#refill").hide();
            } else{
              $("#refill").show();
            }
            $("#admin ").change(function(){
              var type = $(this).val();
                if( show_refill == "false" ){
                  $("#refill").hide();
                } else{
                  $("#refill").show();
                }
            });
          </script>


              <script type="text/javascript">
              $(".other_services").click(function(){
                var control = $("#translationsList");
                if( control.attr("class") == "hidden" ){
                  control.removeClass("hidden");
                } else{
                  control.addClass("hidden");
                }
              });
              var site_url  = $("head base").attr("href");
                $("#provider").change(function(){
                  var provider = $(this).val();
                  getProviderServices(provider,site_url);
                });

                getProvider();
                $("#serviceMode").change(function(){
                  getProvider();
                });

                getSalePrice();
                $("#saleprice_cal").change(function(){
                  getSalePrice();
                });

                getSubscription();
                $("#subscription_package").change(function(){
                  getSubscription();
                });
                function getProviderServices(provider,site_url){
                  if( provider == 0 ){
$("#provider_service").hide();
                  }else{
$.post(site_url+"admin/ajax_data",{action:"providers_list",provider:provider}).done(function( data ) {
  $("#provider_service").show();
  $("#provider_service").html(data);
}).fail(function(){
  alert("Hata oluştu!");
});
                  }
                }

                function getProvider(){
                  var mode = $("#serviceMode").val();
if( mode == 1 ){
  $("#autoMode").hide();
}else{
  $("#autoMode").show();
}
                }

                function getSalePrice(){
                  var type = $("#saleprice_cal").val();
if( type == "normal" ){
  $("#saleprice").hide();
  $("#servicePrice").show();
}else{
  $("#saleprice").show();
  $("#servicePrice").hide();
}
                }

                function getSubscription(){
                  var type = $("#subscription_package").val();
if( type == "11" || type == "12" ){
  $("#unlimited").show();
  $("#limited").hide();
}else{
  $("#unlimited").hide();
  $("#limited").show();
}
                }
              </script>
              ';


    echo json_encode(["content" => $return, "title" => "Edit subscription (ID: " . $serviceInfo["service_id"] . ")"]);


  else:
    $return = '

        <form class="form" action="' . site_url("admin/services/edit-service/" . $serviceInfo["service_id"]) . '" method="post" data-xhr="true">
            <div class="modal-body">';

    if (count($languages) > 1):
      $translationList = '<a class="other_services"> Translations (' . (count($languages) - 1) . ') </a>';
    else:
      $translationList = '';
    endif;
    foreach ($languages as $language):
      if ($language["default_language"]):
        $return .= '
				  <div class="form-group">
<label class="form-group__service-name">Service name <span class="badge">' . $language["language_name"] . '</span> ' . $translationList . ' </label>
<input type="text" class="form-control" name="name[' . $language["language_code"] . ']" value="' . $multiName[$language["language_code"]] . '">
                  </div>';
        if (count($languages) > 1):
          $return .= '<div class="hidden" id="translationsList">';
        endif;
      else:
        $return .= '<div class="form-group">
<label class="form-group__service-name">Service name <span class="badge">' . $language["language_name"] . '</span> </label>
<input type="text" class="form-control" name="name[' . $language["language_code"] . ']" value="' . $multiName[$language["language_code"]] . '">
                  </div>';
      endif;
    endforeach;
    if (count($languages) > 1):
      $return .= '</div>';
    endif;

    $return .= '<div class="service-mode__block">
                <div class="form-group">
                <label>Service Category</label>
<select id="service_category_selector" data-live-search="true" data-actions-box="true" class="form-control" name="category">
    <option value="0">Please select a category..</option>';
    foreach ($categories as $category):
      $return .= '<option value="' . $category["category_id"] . '"';
      if ($serviceInfo["category_id"] == $category["category_id"]):
        $return .= 'selected';
      endif;
      $return .= '>' . $category["category_name"] . '</option>';
    endforeach;
    $return .= '</select>
</div>
</div>

<div class="service-mode__wrapper">
                <div class="service-mode__block">
                  <div class="form-group">
                  <label>Service Type</label>
<select class="form-control" name="package">
      <option value="1"';
    if ($serviceInfo["service_package"] == 1):
      $return .= 'selected';
    endif;
    $return .= '>Service</option>
      <option value="2"';
    if ($serviceInfo["service_package"] == 2):
      $return .= 'selected';
    endif;
    $return .= '>Package</option>
      <option value="3"';
    if ($serviceInfo["service_package"] == 3):
      $return .= 'selected';
    endif;
    $return .= '>Special Comment</option>
      <option value="4"';
    if ($serviceInfo["service_package"] == 4):
      $return .= 'selected';
    endif;
    $return .= '>Package Comment</option>
  </select>
                  </div>
                </div>
                <div class="service-mode__block">
                  <div class="form-group">
                  <label>Mode</label>
<select class="form-control" name="mode" id="serviceMode">
      <option value="1"';
    if ($serviceInfo["service_api"] == 0):
      $return .= 'selected';
    endif;
    $return .= '>Manual</option>
      <option value="2"';
    if ($serviceInfo["service_api"] != 0):
      $return .= 'selected';
    endif;
    $return .= '>Auto (API)</option>
  </select>
                  </div>
                </div>

                <div id="autoMode" style="display: none">
                  <div class="service-mode__block">
<div class="form-group">
<label>Service Provider</label>
  <select class="form-control" name="provider" id="provider">
        <option value="0">Select service provider...</option>';
    foreach ($providers as $provider):
      $return .= '<option value="' . $provider["id"] . '"';
      if ($serviceInfo["service_api"] == $provider["id"]):
        $return .= 'selected';
      endif;
      $return .= '>' . $provider["api_name"] . '</option>';
    endforeach;
    $return .= '</select>
</div>
</div>
<div id="provider_service">';
    $services = $smmapi->action(array('key' => $serviceInfo["api_key"], 'action' => 'services'), $serviceInfo["api_url"]);
    if ($serviceInfo["api_type"] == 1):
      $return .= '<div class="service-mode__block">
    <div class="form-group">
    <label>Service</label>
      <select id="provider_service_selector" data-live-search="true" data-actions-box="true" class="form-control" name="service">';
      foreach ($services as $service):
        $return .= '<option value="' . $service->service . '"';
        if ($serviceInfo["api_service"] == $service->service):
          $return .= 'selected';
        endif;
        $return .= '>' . $service->service . ' - ' . $service->name . ' - ' . $service->rate . '</option>';
      endforeach;
      $return .= '</select>
    </div>
  </div>';
    elseif ($serviceInfo["api_type"] == 3):
      $return .= '<div class="service-mode__block">
    <div class="form-group">
    <label>Service</label>
      <input class="form-control" value="' . $serviceInfo['api_service'] . '" name="service">
    </div>
  </div>';
    endif;
    $return .= '</div>
<div class="service-mode__block"  style="display: none">
<div class="form-group">
<label>Price Over the Purchase Price</label>
  <select class="form-control" name="saleprice_cal" id="saleprice_cal>
    <option value="normal">No</option>
    <option value="percent">Add % to your purchase price</option>
    <option value="amount">Add amount to your purchase price </option>
  </select>
</div>
                  </div>
                  <div class="form-group" style="display: none">
<label class="form-group__service-name">Price</label>
<input type="text" class="form-control" name="saleprice" value="">
                  </div>
                  <div class="service-mode__block">
<div class="form-group">
<label>Dripfeed</label>
  <select class="form-control" name="dripfeed">
    <option value="1"';
    if ($serviceInfo["service_dripfeed"] == 1):
      $return .= 'selected';
    endif;
    $return .= '>Inactive</option>
    <option value="2"';
    if ($serviceInfo["service_dripfeed"] == 2):
      $return .= 'selected';
    endif;
    $return .= '>Active</option>
  </select>
</div>
                  </div>
                </div>
              </div>

              <div class="service-mode__wrapper">
                  <div class="row">
<div class="col-md-6 service-mode__block ">
  <div class="form-group">
  <label>Check Instagram profile privacy?</label>
    <select class="form-control" name="instagram_private">
          <option value="1"';
    if ($serviceInfo["instagram_private"] == 1):
      $return .= 'selected';
    endif;
    $return .= '>No</option>
          <option value="2"';
    if ($serviceInfo["instagram_private"] == 2):
      $return .= 'selected';
    endif;
    $return .= '>Yes</option>
      </select>
  </div>
</div>
<div class="col-md-6 service-mode__block ">
  <div class="form-group">
  <label>Starting number</label>
    <select class="form-control" name="start_count">
          <option value="none"';
    if ($serviceInfo["start_count"] == "none"):
      $return .= 'selected';
    endif;
    $return .= '>Starting number</option>
          <option value="instagram_follower"';
    if ($serviceInfo["start_count"] == "instagram_follower"):
      $return .= 'selected';
    endif;
    $return .= '>Number of Instagram followers</option>
          <option value="instagram_photo"';
    if ($serviceInfo["start_count"] == "instagram_photo"):
      $return .= 'selected';
    endif;
    $return .= '>Number of Instagram followers</option>
      </select>
  </div>
</div>
                  </div>
                  <div class="row">
<div class="col-md-6 service-mode__block ">
  <div class="form-group">
  <label>Enter the 2nd order on the same link?</label>
    <select class="form-control" name="instagram_second">
          <option value="2"';
    if ($serviceInfo["instagram_second"] == 2):
      $return .= 'selected';
    endif;
    $return .= '>Yes</option>
          <option value="1"';
    if ($serviceInfo["instagram_second"] == 1):
      $return .= 'selected';
    endif;
    $return .= '>No</option>
      </select>
  </div>
</div>
                  </div>
              </div>

              <div class="form-group">
                <label class="form-group__service-name">Service price (1000 pieces) <span class="badge badge-secondary">' . $settings["site_base_currency"] . " (" . get_currency_symbol_by_code($settings["site_base_currency"]) . ')</span></label>

<input type="text" class="form-control" name="price" value="' . $serviceInfo["service_price"] . '">
              </div>

              <div class="row">
                <div class="col-md-6 form-group">
                  <label class="form-group__service-name">Minimum order</label>
                  <input type="text" class="form-control" name="min" value="' . $serviceInfo["service_min"] . '">
                </div>

                <div class="col-md-6 form-group">
                  <label class="form-group__service-name">Minimum order</label>
                  <input type="text" class="form-control" name="max" value="' . $serviceInfo["service_max"] . '">
                </div>
              </div>

              <hr>
    <div class="service-mode__block">
                <div class="form-group">
                <label>Refill Button</label>
                  <select id="show" class="form-control" name="show_refill">
  <option value="false"';
    if ($serviceInfo["show_refill"] == "false"):
      $return .= 'selected';
    endif;
    $return .= '>Off</option>
  <option value="true"';
    if ($serviceInfo["show_refill"] == "true"):
      $return .= 'selected';
    endif;
    $return .= '>On</option>
                  </select>
                </div>
    </div><div class="row" id="refill">

<div class="col-md-6 form-group" id="refill"> 
<label class="form-group__service-name">Refill days</label>
<input type="text" class="form-control" name="refill_days" value="' . $serviceInfo["refill_days"] . '">
</div>
<div class="col-md-6 form-group" id="refill">
<label class="form-group__service-name">Refill Display (in hours)</label>
<input type="text" class="form-control" name="refill_hours" value="' . $serviceInfo["refill_hours"] . '">
</div>
</div>

<div class="service-mode__block">
<div class="form-group">
                <label>Cancel Button</label>
                  <select class="form-control" name="cancelbutton">
  <option value="2"';
    if ($serviceInfo["cancelbutton"] == 2):
      $return .= 'selected';
    endif;
    $return .= '>Off</option>
  <option value="1"';
    if ($serviceInfo["cancelbutton"] == 1):
      $return .= 'selected';
    endif;
    $return .= '>On</option>
</select>
</div>
</div>
<hr>
<div class="form-group" id="refill"> 
<label class="form-group__service-name">Overflow (%)</label>
<input type="text" class="form-control" name="service_overflow" value="' . $serviceInfo["service_overflow"] . '">
</div>

<div class="form-group" id="refill"> 

<label class="form-group__service-name">Disable Sync</label>
<select name="service_sync" class="form-control">
  <option value="0"';
    if ($serviceInfo["service_sync"] == 0):
      $return .= 'selected';
    endif;
    $return .= '>Yes</option>
<option value="1"';
    if ($serviceInfo["service_sync"] == 1):
      $return .= 'selected';
    endif;
    $return .= '>No</option>
</select>
</div>

<div class="service-mode__block">

<div class="form-group">

<label>Order Link</label>
                  <select class="form-control" name="want_username">
  <option value="1"';
    if ($serviceInfo["want_username"] == 1):
      $return .= 'selected';
    endif;
    $return .= '>Link</option>
  <option value="2"';
    if ($serviceInfo["want_username"] == 2):
      $return .= 'selected';
    endif;
    $return .= '>Username</option>
                  </select>
                </div>
              </div>

              <div class="service-mode__block">
                <div class="form-group">
                <label>Personalized Service</label>
                  <select class="form-control" name="secret">
  <option value="2"';
    if ($serviceInfo["service_secret"] == 2):
      $return .= 'selected';
    endif;
    $return .= '>No</option>
  <option value="1"';
    if ($serviceInfo["service_secret"] == 1):
      $return .= 'selected';
    endif;
    $return .= '>Yes</option>
                  </select>
                </div>
              </div>

              <div class="service-mode__block">
                <div class="form-group">
                <label>Service Speed</label>
                  <select class="form-control" name="speed">
  <option value="1"';
    if ($serviceInfo["service_speed"] == 1):
      $return .= 'selected';
    endif;
    $return .= '>Slow</option>
  <option value="2"';
    if ($serviceInfo["service_speed"] == 2):
      $return .= 'selected';
    endif;
    $return .= '>Sometimes Slow</option>
  <option value="3"';
    if ($serviceInfo["service_speed"] == 3):
      $return .= 'selected';
    endif;
    $return .= '>Normal</option>
  <option value="4"';
    if ($serviceInfo["service_speed"] == 4):
      $return .= 'selected';
    endif;
    $return .= '>Fast</option>
                  </select>
                </div>
              </div>

            </div>

              <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Update service information</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
              </div>
        </form>
<script>
$("#provider_service_selector").selectpicker();
$("#service_category_selector").selectpicker();
            var type = $("#show").val();
            if( $serviceInfo["show_refill"]   == "false" ){
              $("#refill").hide();
            } else{
              $("#refill").show();
            }
            $("#show ").change(function(){
              var type = $(this).val();
                if( $serviceInfo["show_refill"]  == "false" ){
                  $("#refill").hide();
                } else{
                  $("#refill").show();
                }
            });
          </script>
              <script type="text/javascript">

               $(".other_services").click(function(){
                 var control = $("#translationsList");
                 if( control.attr("class") == "hidden" ){
                   control.removeClass("hidden");
                 } else{
                   control.addClass("hidden");
                 }
               });
              var site_url  = $("head base").attr("href");
                $("#provider").change(function(){
                  var provider = $(this).val();
                  getProviderServices(provider,site_url);
                });

                getProvider();
                $("#serviceMode").change(function(){
                  getProvider();
                });

                getSalePrice();
                $("#saleprice_cal").change(function(){
                  getSalePrice();
                });

                getSubscription();
                $("#subscription_package").change(function(){
                  getSubscription();
                });
                function getProviderServices(provider,site_url){
                  if( provider == 0 ){
$("#provider_service").hide();
                  }else{
$.post(site_url+"admin/ajax_data",{action:"providers_list",provider:provider}).done(function( data ) {
  $("#provider_service").show();
  $("#provider_service").html(data);
}).fail(function(){
  alert("Hata oluştu!");
});
                  }
                }

                function getProvider(){
                  var mode = $("#serviceMode").val();
if( mode == 1 ){
  $("#autoMode").hide();
}else{
  $("#autoMode").show();
}
                }

                function getSalePrice(){
                  var type = $("#saleprice_cal").val();
if( type == "normal" ){
  $("#saleprice").hide();
  $("#servicePrice").show();
}else{
  $("#saleprice").show();
  $("#servicePrice").hide();
}
                }

                function getSubscription(){
                  var type = $("#subscription_package").val();
if( type == "11" || type == "12" ){
  $("#unlimited").show();
  $("#limited").hide();
}else{
  $("#unlimited").hide();
  $("#limited").show();
}
                }
              </script>
              ';
    echo json_encode(["content" => $return, "title" => "Edit service (ID: " . $serviceInfo["service_id"] . ")"]);
  endif;
  elseif ($action == "edit_service_name"):
    $id = $_POST["id"];
    $smmapi = new SMMApi();
    $serviceInfo = $conn->prepare("SELECT service_id,service_name,name_lang FROM services WHERE service_id=:id ");
    $serviceInfo->execute(array("id" => $id));
    $serviceInfo = $serviceInfo->fetch(PDO::FETCH_ASSOC);
    $multiName = $serviceInfo["name_lang"];
    $multiName = json_decode($multiName, 1);
  
    $return .= '<form class="form" action="' . site_url("admin/services/edit-service-name/" . $serviceInfo["service_id"]) . '" method="post" data-xhr="true">
  <div class="modal-body">';
  
  if (count($languages) > 1):
    $translationList = '<a class="other_services"> Translations (' . (count($languages) - 1) . ') </a>';
  else:
    $translationList = '';
  endif;
  foreach ($languages as $language):
    if ($language["default_language"]):
      $return .= '
        <div class="form-group">
<label class="form-group__service-name">Service name <span class="badge">' . $language["language_name"] . '</span> ' . $translationList . ' </label>
<input type="text" class="form-control" name="service_name[' . $language["language_code"] . ']" value="' . $multiName[$language["language_code"]] . '">
                </div>';
      if (count($languages) > 1):
        $return .= '<div class="hidden" id="translationsList">';
      endif;
    else:
      $return .= '<div class="form-group">
<label class="form-group__service-name">Service name <span class="badge">' . $language["language_name"] . '</span> </label>
<input type="text" class="form-control" name="service_name[' . $language["language_code"] . ']" value="' . $multiName[$language["language_code"]] . '">
                </div>';
    endif;
  endforeach;
  if (count($languages) > 1):
    $return .= '</div>';
  endif;
  
  
  
    $return .= '
  </div>
  <div class="modal-footer">
  <button type="submit" class="btn btn-primary">Update service name</button>
  <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
  </div>
  </form>
  <script>
  $(".other_services").click(function(){
    var control = $("#translationsList");
    if( control.attr("class") == "hidden" ){
      control.removeClass("hidden");
    } else{
      control.addClass("hidden");
    }
  });
  
  </script>
  ';
    echo json_encode(["content" => $return, "title" => "Edit description (ID: " . $serviceInfo["service_id"] . ")"]);
elseif ($action == "edit_description"):
  $id = $_POST["id"];
  $smmapi = new SMMApi();
  $serviceInfo = $conn->prepare("SELECT service_id,service_description,description_lang FROM services WHERE service_id=:id ");
  $serviceInfo->execute(array("id" => $id));
  $serviceInfo = $serviceInfo->fetch(PDO::FETCH_ASSOC);
  $multiDesc = $serviceInfo["description_lang"];
  $multiDesc = json_decode($multiDesc, 1);

  $return .= '<form class="form" action="' . site_url("admin/services/edit-description/" . $serviceInfo["service_id"]) . '" method="post" data-xhr="true">
<div class="modal-body">';

  if (count($languages) > 1):
    $translationList = '<a class="other_services"> Translations (' . (count($languages) - 1) . ') </a>';
  else:
    $translationList = '';
  endif;
  foreach ($languages as $language):
    if ($language["default_language"]):
      $return .= '
      <div class="form-group">
<label class="form-group__service-name">Service Description <span class="badge">' . $language["language_name"] . '</span> ' . $translationList . ' </label>
<textarea rows="10" class="form-control" name="service_description[' . $language["language_code"] . ']" >' . rtrim(str_replace(["<br>", "<br/>", "<br />"], "\n", $multiDesc[$language["language_code"]])) . '</textarea>
              </div>';
      if (count($languages) > 1):
        $return .= '<div class="hidden" id="translationsList">';
      endif;
    else:
      $return .= '
      <div class="form-group">
      <label class="form-group__service-name">Service Description <span class="badge">' . $language["language_name"] . '</span></label>
<textarea rows="8" class="form-control" name="service_description[' . $language["language_code"] . ']" >' . rtrim(str_replace(["<br>", "<br/>", "<br />"], "\n", $multiDesc[$language["language_code"]])) . '</textarea>
              </div>';
    endif;
  endforeach;
  if (count($languages) > 1):
    $return .= '</div>';
  endif;



  $return .= '
</div>
<div class="modal-footer">
<button type="submit" class="btn btn-primary">Update description</button>
<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
</div>
</form>
<script>
$(".other_services").click(function(){
  var control = $("#translationsList");
  if( control.attr("class") == "hidden" ){
    control.removeClass("hidden");
  } else{
    control.addClass("hidden");
  }
});

</script>
';
  echo json_encode(["content" => $return, "title" => "Edit description (ID: " . $serviceInfo["service_id"] . ")"]);
elseif ($action == "edit_time"):
  $id = $_POST["id"];
  $smmapi = new SMMApi();
  $serviceInfo = $conn->prepare("SELECT * FROM services WHERE service_id=:id ");
  $serviceInfo->execute(array("id" => $id));
  $serviceInfo = $serviceInfo->fetch(PDO::FETCH_ASSOC);
  $multiDesc = json_decode($serviceInfo["time_lang"], true);

  $return = '<form class="form" action="' . site_url("admin/services/edit-time/" . $serviceInfo["service_id"]) . '" method="post" data-xhr="true">
            <div class="modal-body">';

  if (count($languages) > 1):
    $translationList = '<a class="other_services"> Translations (' . (count($languages) - 1) . ') </a>';
  else:
    $translationList = '';
  endif;
  foreach ($languages as $language):
    if ($language["default_language"]):
      $return .= '<div class="form-group">
<label class="form-group__service-name">Explanation <span class="badge">' . $language["language_name"] . '</span> ' . $translationList . ' </label>
<textarea class="form-control" rows="5" name="description[' . $language["language_code"] . ']">' . $multiDesc[$language["language_code"]] . '</textarea>
                  </div>';
      if (count($languages) > 1):
        $return .= '<div class="hidden" id="translationsList">';
      endif;
    else:
      $return .= '<div class="form-group">
<label class="form-group__service-name">Explanation <span class="badge">' . $language["language_name"] . '</span> </label>
<textarea class="form-control" rows="5"  name="description[' . $language["language_code"] . ']">' . $multiDesc[$language["language_code"]] . '</textarea>
                  </div>';
    endif;
  endforeach;
  if (count($languages) > 1):
    $return .= '</div>';
  endif;

  $return .= '

            </div>

              <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Update Time</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
              </div>
              </form>
              <script type="text/javascript">

              $(".other_services").click(function(){
                var control = $("#translationsList");
                if( control.attr("class") == "hidden" ){
                  control.removeClass("hidden");
                } else{
                  control.addClass("hidden");
                }
              });

              </script>
              ';
  echo json_encode(["content" => $return, "title" => "Edit Average Time (ID: " . $serviceInfo["service_id"] . ")"]);





elseif ($action == "new_subscriptions"):
  $categories = $conn->prepare("SELECT * FROM categories ORDER BY category_line ");
  $categories->execute(array());
  $categories = $categories->fetchAll(PDO::FETCH_ASSOC);
  $providers = $conn->prepare("SELECT * FROM service_api");
  $providers->execute(array());
  $providers = $providers->fetchAll(PDO::FETCH_ASSOC);
  $return = '<form class="form" action="' . site_url("admin/services/new-subscription") . '" method="post" data-xhr="true">
        <div class="modal-body">';

  if (count($languages) > 1):
    $translationList = '<a class="other_services"> Translations (' . (count($languages) - 1) . ') </a>';
  else:
    $translationList = '';
  endif;
  foreach ($languages as $language):
    if ($language["default_language"]):
      $return .= '<div class="form-group">
              <label class="form-group__service-name">Service name <span class="badge">' . $language["language_name"] . '</span> ' . $translationList . ' </label>
              <input type="text" class="form-control" name="name[' . $language["language_code"] . ']" value="' . $multiName[$language["language_code"]] . '">
            </div>';
      if (count($languages) > 1):
        $return .= '<div class="hidden" id="translationsList">';
      endif;
    else:
      $return .= '<div class="form-group">
              <label class="form-group__service-name">Service name <span class="badge">' . $language["language_name"] . '</span> </label>
              <input type="text" class="form-control" name="name[' . $language["language_code"] . ']" value="' . $multiName[$language["language_code"]] . '">
            </div>';
    endif;
  endforeach;
  if (count($languages) > 1):
    $return .= '</div>';
  endif;

  $return .= '<div class="service-mode__block">
            <div class="form-group">
            <label>Service Category</label>
              <select class="form-control" name="category">
<option value="0">Please select a category..</option>';
  foreach ($categories as $category):
    $return .= '<option value="' . $category["category_id"] . '">' . $category["category_name"] . '</option>';
  endforeach;
  $return .= '</select>
            </div>
          </div>

          <div class="service-mode__block">
            <div class="form-group">
            <label>Subscription Type</label>
              <select class="form-control" name="package" id="subscription_package">
<option value="11">Instagram Auto Likes - Unlimited</option>
<option value="12">Instagram Auto Tracking - Unlimited</option>
<option value="14">Instagram Auto Likes - Timed</option>
<option value="15">Instagram Auto Watch - Timed</option>
                </select>
            </div>
          </div>

          <div class="service-mode__wrapper">

            <div class="service-mode__block">
              <div class="form-group">
              <label>Mode</label>
                <select class="form-control" name="mode" id="serviceMode">
  <option value="2">Auto (API)</option>
                  </select>
              </div>
            </div>

            <div id="autoMode" style="display: none">
              <div class="service-mode__block">
                <div class="form-group">
                <label>Service Provider</label>
                  <select class="form-control" name="provider" id="provider">
    <option value="0">Select service provider...</option>';
  foreach ($providers as $provider):
    $return .= '<option value="' . $provider["id"] . '">' . $provider["api_name"] . '</option>';
  endforeach;
  $return .= '</select>
                </div>
              </div>
              <div id="provider_service">
              </div>
            </div>
          </div>

          <div id="unlimited">
            <div class="form-group">
              <label class="form-group__service-name">Service price (1000 pieces) <span class="badge badge-secondary">' . $settings["site_base_currency"] . " (" . get_currency_symbol_by_code($settings["site_base_currency"]) . ')</span></label>
              <input type="text" class="form-control" name="price" value="">
            </div>

            <div class="row">
              <div class="col-md-6 form-group">
                <label class="form-group__service-name">Minimum order</label>
                <input type="text" class="form-control" name="min" value="">
              </div>

              <div class="col-md-6 form-group">
                <label class="form-group__service-name">Maximum order</label>
                <input type="text" class="form-control" name="max" value="">
              </div>
            </div>
          </div>

          <div id="limited">
            <div class="form-group">
              <label class="form-group__service-name">Service price</label>
              <input type="text" class="form-control" name="limited_price" value="">
            </div>



            <div class="row">
              <div class="col-md-6 form-group">
                <label class="form-group__service-name">Shipment amount</label>
                <input type="text" class="form-control" name="autopost" value="">
              </div>

              <div class="col-md-6 form-group">
                <label class="form-group__service-name">Order amount</label>
                <input type="text" class="form-control" name="limited_min" value="">
              </div>
            </div>
            <div class="form-group">
              <label class="form-group__service-name">Package Time <small> (days)</small></label>
              <input type="text" class="form-control" name="autotime" value="">
            </div>
          </div>

          <hr>


          <div class="service-mode__block">
            <div class="form-group">
            <label>Personalized Service</label>
              <select class="form-control" name="secret">
                  <option value="2">No</option>
                  <option value="1">Yes</option>
              </select>
            </div>
          </div>

          <div class="service-mode__block">
            <div class="form-group">
            <label>Service Speed</label>
              <select class="form-control" name="speed">
                  <option value="1">Slow</option>
                  <option value="2">Sometimes Slow</option>
                  <option value="3">Normal</option>
                  <option value="4">Fast</option>
              </select>
            </div>
          </div>

        </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Add new subscription</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
          </div>
          </form>
          <script type="text/javascript">

          $(".other_services").click(function(){
            var control = $("#translationsList");
            if( control.attr("class") == "hidden" ){
              control.removeClass("hidden");
            } else{
              control.addClass("hidden");
            }
          });

          var site_url  = $("head base").attr("href");
            $("#provider").change(function(){
              var provider = $(this).val();
              getProviderServices(provider,site_url);
            });

            getProvider();
            $("#serviceMode").change(function(){
              getProvider();
            });

            getSalePrice();
            $("#saleprice_cal").change(function(){
              getSalePrice();
            });

            getSubscription();
            $("#subscription_package").change(function(){
              getSubscription();
            });
            function getProviderServices(provider,site_url){
              if( provider == 0 ){
                $("#provider_service").hide();
              }else{
                $.post(site_url+"admin/ajax_data",{action:"providers_list",provider:provider}).done(function( data ) {
                  $("#provider_service").show();
                  $("#provider_service").html(data);
                }).fail(function(){
                  alert("Hata oluştu!");
                });
              }
            }

            function getProvider(){
              var mode = $("#serviceMode").val();
                if( mode == 1 ){
                  $("#autoMode").hide();
                }else{
                  $("#autoMode").show();
                }
            }

            function getSalePrice(){
              var type = $("#saleprice_cal").val();
                if( type == "normal" ){
                  $("#saleprice").hide();
                  $("#servicePrice").show();
                }else{
                  $("#saleprice").show();
                  $("#servicePrice").hide();
                }
            }

            function getSubscription(){
              var type = $("#subscription_package").val();
                if( type == "11" || type == "12" ){
                  $("#unlimited").show();
                  $("#limited").hide();
                }else{
                  $("#unlimited").hide();
                  $("#limited").show();
                }
            }
          </script>
          ';
  echo json_encode(["content" => $return, "title" => "Add new subscription"]);


elseif ($action == "new_category"):
  $return = '<form class="form" action="' . site_url('admin/services/new-category') . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Category name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="">' . "\r\n" . ' </div>' . "\r\n\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-refill">Position  <div class="tooltip5">  <span class="fas fa-info-circle"></span><span class="tooltiptext5">The position of a category after adding it</span></div> </label>' . "\r\n" . ' <select name="position" class="form-control"><option value="top">Top</option><option value="bottom">Bottom</option></select> ' . "\r\n" . ' </div>' . "\r\n\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Hidden Category</label>' . "\r\n" . '<select class="form-control" name="secret">' . "\r\n" . '  <option value="2">No</option>' . "\r\n" . '  <option value="1">Yes</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n\r\n" . '</div>' . "\r\n\r\n" . ' <div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Create category</button>' . "\r\n" . '  <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';
  echo json_encode(['content' => $return, 'title' => '']);



elseif ($action == "edit_category"):
  $id = $_POST["id"];
  $category = $conn->prepare("SELECT * FROM categories WHERE category_id=:id ");
  $category->execute(array("id" => $id));
  $category = $category->fetch(PDO::FETCH_ASSOC);
  
  $multiName = json_decode($category["category_name_lang"],1);
  
  

  $images = $conn->prepare("SELECT * FROM files");
  $images->execute();
  $images = $images->fetchAll(PDO::FETCH_ASSOC);

  $return .= '<div class="modal-body">';
  
  
  if (count($languages) > 1):

    $translationList = '<a class="other_services"> Translations (' . (count($languages) - 1) . ') </a>';

  else:
    $translationList = '';
  endif;
  foreach ($languages as $language):
    if ($language["default_language"]):
      $return .= '
        <div class="form-group">
<label class="form-group__service-name">Category name <span class="badge">' . $language["language_name"] . '</span> ' . $translationList . ' </label>
<input type="text" class="form-control" name="category_name[' . $language["language_code"] . ']" value="' . $multiName[$language["language_code"]] . '">
                </div>';
      if (count($languages) > 1):
        $return .= '<div class="hidden" id="translationsList">';
      endif;
    else:
      $return .= '<div class="form-group">
<label class="form-group__service-name">Category name <span class="badge">' . $language["language_name"] . '</span> </label>
<input type="text" class="form-control" name="category_name[' . $language["language_code"] . ']" value="' . $multiName[$language["language_code"]] . '">
                </div>';
    endif;
  endforeach;
  if (count($languages) > 1):
    $return .= '</div>';
  endif;

  $return .= '
<ul class="list-group list-group-horizontal">
<li  class="list-group-item active enable-icon-picker">Icon Picker</li>
<li  class="list-group-item enable-image-picker">Image Picker</li>
</ul>

<div class="iconpicker-div">
<div class="form-group">
<label>Category Icon</label>
<p class="lead">
<i class="fa fa-anchor fa-3x picker-target"></i>
</p>
<input class="form-control icp icp-auto" id="icon-picked" value="fas fa-anchor" type="text"/>
<input type="hidden" id="cat_id" value="' . $id . '">
</div>
</div>
<div style="display:none;" class="picker imagepicker-div load-images"></div>';


  $return .= '<hr color="#2176FF"><button type="button" id="update-category" class="btn btn-primary">Update category</button>' . "\r\n" . '  <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>';
  $return .= '<script>

$(document).ready(function(){
  

  $(".other_services").click(function(){

    var control = $("#translationsList");
    if( control.attr("class") == "hidden" ){
      control.removeClass("hidden");
    } else{
      control.addClass("hidden");
    }
  });
  
  
$(".icp-auto").iconpicker();
$(".icp").on("iconpickerSelected", function (e) {
$(".lead .picker-target").get(0).className = \'picker-target fa-3x \' +
e.iconpickerInstance.options.iconBaseClass + \' \' +
e.iconpickerInstance.options.fullClassFormatter(e.iconpickerValue);
});


$(".enable-icon-picker").click(function(){
$(".enable-image-picker").removeClass("active");
$(this).addClass("active");
$(".iconpicker-div").show();
$(".imagepicker-div").hide();
});

$(".enable-image-picker").click(function(){
$(".enable-icon-picker").removeClass("active");
$(this).addClass("active");
$(".iconpicker-div").hide();
$(".imagepicker-div").show();
var imgpicker = $(".imagepicker-div");

if(imgpicker.hasClass("load-images")){
imgpicker.html(\'<center><svg class="spinner_2 medium" viewBox="0 0 48 48"><circle class="path_2" cx="24" cy="24" r="20" fill="none" stroke-width="3"></circle></svg></center><br><br>\');

$.ajax({
url:"admin/ajax_data",
data:"action=download_category_icon_images",
type:"POST",
success:function(resp){
var resp = JSON.parse(resp);
imgpicker.html(resp.content);
imgpicker.removeClass("load-images");
}
});

}


});

$("#update-category").click(function(){
$(".list-group").addClass("disabledDiv");
var cat_id = $("#cat_id").val();
var cat_name = encodeURIComponent($("#cat_name").val());

var cat_name = $("input[name^=\'category_name\']").serialize();

var image_id = $("#image-picker").val();
if($(".enable-icon-picker").hasClass("active")){
 var icon_data = "&icon_type=icon";
}
if($(".enable-image-picker").hasClass("active")){

 var icon_data = "&icon_type=image";

}
var icon_class = $("#icon-picked").val();
$.ajax({
url:"admin/services/edit-category",
data:"cat_id="+cat_id+"&"+cat_name+icon_data+"&image_id="+image_id+"&icon_class="+icon_class,
type:"POST",
success:function(resp){
var resp = JSON.parse(resp);
if(resp.success == 1){
iziToast.show({
    icon:\'fa fa-check\',
    title: resp.message,
    message: \'\',
    color:\'green\',
    position:\'topCenter\'
});
} else {
iziToast.show({
    icon:\'fa fa-times\',
    title: resp.message,
    message: \'\',
    color:\'red\',
    position:\'topCenter\'
});
}
$(".modal .close").trigger("click");
}
});
});

});
</script>';
  echo json_encode(['content' => $return, 'title' => 'Edit Category <span class="badge badge-primary">' . $id . '</span>']);


elseif ($action == "download_category_icon_images"):

  $already_downloaded = $conn->prepare("SELECT downloaded_category_icons FROM settings WHERE id=1");
  $already_downloaded->execute();
  $already_downloaded = $already_downloaded->fetch(PDO::FETCH_ASSOC)["downloaded_category_icons"];
  if ($already_downloaded == 0) {
    $check_if_table_exists = $conn->prepare("DESCRIBE files");
    if ($check_if_table_exists->execute()) {
      $delete_table = $conn->prepare("DROP TABLE files");
      $delete_table->execute();
    }

    $create_table = $conn->prepare("CREATE TABLE files (
  id int(11) NOT NULL,
  name varchar(100) DEFAULT NULL,
  link text DEFAULT NULL,
  date datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");
    $create_table->execute();
    $add_primary_key = $conn->prepare("ALTER TABLE files ADD PRIMARY KEY (id)");
    $add_primary_key->execute();

    $add_autoincrement = $conn->prepare("ALTER TABLE files
  MODIFY id int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1");
    $add_autoincrement->execute();
  }

  $check_if_column_exists = $conn->prepare("SELECT downloaded_category_icons FROM settings WHERE id=1");

  $check_if_column_exists->execute();

  $check_if_column_exists = $check_if_column_exists->fetch(PDO::FETCH_ASSOC);
  if (is_array($check_if_column_exists) && count($check_if_column_exists)) {

  } else {

    $create_column = $conn->prepare("ALTER TABLE settings ADD downloaded_category_icons BOOLEAN NOT NULL DEFAULT FALSE AFTER panel_orders");

    $create_column->execute();

  }

  $is_images_downloaded = $conn->prepare("SELECT downloaded_category_icons FROM settings WHERE id=1");

  $is_images_downloaded->execute();
  $is_images_downloaded = $is_images_downloaded->fetch(PDO::FETCH_ASSOC)["downloaded_category_icons"];

  if ($is_images_downloaded == 0) {

    $images_json = '{
  "instagram": "https://i.postimg.cc/ZncWBLrK/icons8-instagram-48.png",
  "facebook": "https://i.postimg.cc/MGCMhH6x/icons8-facebook-48.png",
  "discord": "https://i.postimg.cc/KztkFbjT/icons8-discord-48.png",
  "pinterest": "https://i.postimg.cc/vmgxqfd3/icons8-pinterest-48.png",
  "telegram": "https://i.postimg.cc/L5Rq8Wgb/icons8-telegram-app-48.png",
  "reddit": "https://i.postimg.cc/FFgdBh25/reddit.png",
  "snapchat": "https://i.postimg.cc/90GzK09z/icons8-snapchat-a-multimedia-messaging-app-used-globally-48.png",
  "spotify": "https://i.postimg.cc/prNpszpg/icons8-spotify-48.png",
  "youtube": "https://i.postimg.cc/fbr306ZW/icons8-youtube-48.png",
  "tiktok": "https://i.postimg.cc/9FJkHdsB/tik-tok.png",
  "twitter": "https://i.postimg.cc/VLYhhGZ6/twitter-1.png",
  "twitch": "https://i.postimg.cc/fRyrz3NQ/twitch-1.png",
  "star": "https://i.postimg.cc/pT5mQqXf/icons8-star-filled-48.png",
  "pin": "https://i.postimg.cc/05G3cRfF/pin.png"
}';



    $array_of_images = json_decode($images_json, true);

    foreach ($array_of_images as $name => $link) {

      $binary_image = HTTP_REQUEST($link, "", array(""), "GET", 0);
      $save_name = md5(random_bytes(10));
      $db_link = site_url("img/files/" . $save_name . ".png");
      file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/img/files/" . $save_name . ".png", $binary_image);

      $insert = $conn->prepare("INSERT INTO files SET name=:name,link=:link,date=:date");
      $insert->execute(
        array(
          "name" => $name,
          "link" => $db_link,
          "date" => date('Y-m-d H:i:s')
        )
      );

    }
    $images = $conn->prepare("SELECT * FROM files");
    $images->execute();
    $images = $images->fetchAll(PDO::FETCH_ASSOC);

    $return .= '<label>Select Category Icon</label>
<select id="image-picker" class="image-picker">';

    for ($i = 0; $i < count($images); $i++) {
      $j = $i + 1;
      if ($i == 0) {
        $a = 'data-img-class="first"';
      }
      $return .= '<option ' . $a . ' data-img-src="' . $images[$i]["link"] . '" value="' . $images[$i]["id"] . '">Image ' . $images[$i]["name"] . '</option>';

    }
    $return .= '</select>
<script>
$(document).ready(function(){
$(".image-picker").imagepicker({

hide_select : true,

show_label  : false
});
});
</script>';


    $update = $conn->prepare("UPDATE settings SET downloaded_category_icons=:downloaded WHERE id=:id");
    $update->execute(
      array(
        "downloaded" => 1,
        "id" => 1
      )
    );

  }


  if ($is_images_downloaded == 1) {

    $images = $conn->prepare("SELECT * FROM files");
    $images->execute();
    $images = $images->fetchAll(PDO::FETCH_ASSOC);

    $return .= '<label>Select Category Icon</label>
<select id="image-picker" class="image-picker">';

    for ($i = 0; $i < count($images); $i++) {
      $j = $i + 1;
      if ($i == 0) {
        $a = 'data-img-class="first"';
      }
      $return .= '<option ' . $a . ' data-img-src="' . $images[$i]["link"] . '" value="' . $images[$i]["id"] . '">Image ' . $images[$i]["name"] . '</option>';

    }
    $return .= '</select>
<script>
$(document).ready(function(){
$(".image-picker").imagepicker({

hide_select : true,

show_label  : false
});
});
</script>';

  }

  $return .= '
<form id="image-upload" enctype="multipart/form-data">
<input type="file" name="logo" id="image-input" style="display:none;"/>

</form><div class="form-group"><button type="button" id="upload_an_image" class="btn btn-primary">Upload an image</button></div>
<script>
$("#upload_an_image").click(function(){

$("#image-input").click();

});
</script>';


  echo json_encode(['content' => $return], true);
elseif ($action == "import_services"):

  $providers = $conn->prepare("SELECT * FROM service_api   WHERE status=:status    ");
  $providers->execute(array("status" => 1));
  $providers = $providers->fetchAll(PDO::FETCH_ASSOC);
  $category = $conn->prepare("SELECT * FROM categories");
  $category->execute(array());
  $category = $category->fetchAll(PDO::FETCH_ASSOC);
  $return = '<form class="form" action="' . site_url("admin/services/get_services_add/") . '" method="post" data-xhr="true">
    
        <div class="modal-body">

          <div id="firstStep">
            <div class="service-mode__block">
              <div class="form-group">
              <label>Service Provider</label>
                <select class="form-control" name="provider" id="provider">
  <option value="0">Select service provider...</option>';
  foreach ($providers as $provider):
    $return .= '<option value="' . $provider["id"] . '">' . $provider["api_name"] . '</option>';
  endforeach;
  $return .= '</select>
              </div>
            </div><div class="service-mode__block">
              <div class="form-group">
              <label>Select the Category to Add Services</label>
                <select class="form-control" name="selector" id="selector">
  <option value="0">Select category...</option>';
  foreach ($category as $cat):
    $return .= '<option value="' . ($cat["category_id"]) . '">' . $cat["category_name"] . '</option>';
  endforeach;
  $return .= '</select>
              </div>
            </div>
          </div>

          
          <div id="secondStep">
          </div>

          <div id="thirdStep">
          </div>


        </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" id="nextStep" data-step="first">Next step</button>
            <button type="submit" class="btn btn-primary" id="submitStep">Add services</button>
          </div>

        </form>
           <script>
            $("#submitStep").hide();
            $("#nextStep").click(function(){
              var now_step = $(this).attr("data-step");
              var provider = $("#provider").val();
              var category = $("#selector").val();
              $("#secondStep").hide();
                if( now_step == "first" ){
                  if( provider == 0 ){
$.toast({
    heading: "Unsuccessful",
    text: "Please select service provider",
    icon: "error",
    loader: true,
    loaderBg: "#9EC600"
});
                  }else{
$("#firstStep").hide();
$("#secondStep").show();
$.post("admin/ajax_data", {provider:provider,category:category,action:"import_services_list" }, function(data){
  $("#secondStep").html(data);
});
$("#nextStep").attr("data-step","second");
                  }
                }else if( now_step == "second" ){
var array     = [];
   $(\'[class^="selectServices-"]\').each(function () {
        var id    = $(this).val();
        var check = $(this).prop("checked");
        var provider  =  $(this).attr("data-provider");
          if( check == true ){
            var params = {};
            params["id"]            = id;
            params["category"]      = $(this).attr("data-category");
            array.push(params);
          }
   });
   var count = array.length;
 if( count ){
   $.post("admin/ajax_data", {provider:provider,action:"import_services_last",services:array }, function(data){
     $("#thirdStep").html(data);
   });
   $("#nextStep").hide();
   $("#submitStep").show();
 }else{
   $("#nextStep").attr("data-step","second");
   $("#firstStep").hide();
   $("#secondStep").show();
   $("#nextStep").show();
   $("#submitStep").hide();
   $.toast({
       heading: "Unsuccessful",
       text: "Please select at least 1 service you want to add",
       icon: "error",
       loader: true,
       loaderBg: "#9EC600"
   });
 }

                }
            });
          </script>
          ';
  echo json_encode(["content" => $return, "title" => "Pull out services from provider"]);
elseif ($action == "import_services_list"):
  $provider_id = $_POST["provider"];
  $category_id2 = $_POST["category"];
  $smmapi = new SMMApi();
  $provider = $conn->prepare("SELECT * FROM service_api WHERE id=:id");
  $provider->execute(array("id" => $provider_id));
  $provider = $provider->fetch(PDO::FETCH_ASSOC);
  if ($provider["api_type"] == 1):
    $services = $smmapi->action(array('key' => $provider["api_key"], 'action' => 'services'), $provider["api_url"]);
    if ($services):
      $grouped = array_group_by($services, 'category');
      echo '<div class="">
             <div class="services-import__body">
                 <div>
<div class="services-import__list-wrap">
   <div class="services-import__scroll-wrap">
					   <label class="btn btn-primary"> <input id="checkk" type="checkbox"> Select All</label>';
      foreach ($grouped as $category):
        $category_id++;
        echo '
      <span>
         <div class="services-import__category">
            <div class="services-import__category-title">
              <label><input type="checkbox" data-id="' . $category_id . '" id="checkAll-' . $category_id . '">' . $category[0]->category . '</label>
    <input type="hidden" name="category" value="' . $category_id2 . '">
            </div>
         </div>
         <div class="services-import__packages">
            <ul>';
        for ($i = 0; $i < count($category); $i++):
          echo '<li><label><input data-service="' . $category[$i]->name . '" data-provider="' . $provider["id"] . '"  data-category="' . $category_id . '"  class="selectServices-' . $category_id . '" type="checkbox" value="' . $category[$i]->service . '" name="services[]">' . $category[$i]->service . ' - ' . $category[$i]->name . '<span class="services-import__packages-price">' . priceFormat($category[$i]->rate) . '</span></label></li>';
        endfor;
        echo '</ul>
         </div>
      </span>';
      endforeach;
      echo '
   </div>
</div>
                 </div>
              </div>
			   <script> $("#checkk").click(function () {$("#secondStep :checkbox").not(this).prop("checked", this.checked);});</script>
              <script>
              $(\'[id^="checkAll-"]\').click(function () {
                var id = $(this).attr("data-id");
                 if ( $(this).prop("checked") == true ) {
                   $(".selectServices-"+id).not(this).prop("checked", true);
                 }else{
                   $(".selectServices-"+id).not(this).prop("checked", false);
                 }
               });
              </script>
              </div>';
    else:
      echo "An error occurred, please try later.";
    endif;
  endif;
elseif ($action == "import_services_last"):
  $provider_id = $_POST["provider"];
  $services = json_decode(json_encode($_POST["services"]));
  $smmapi = new SMMApi();
  $provider = $conn->prepare("SELECT * FROM service_api WHERE id=:id");
  $provider->execute(array("id" => $provider_id));
  $provider = $provider->fetch(PDO::FETCH_ASSOC);
  $apiServices = $smmapi->action(array('key' => $provider["api_key"], 'action' => 'services'), $provider["api_url"]);
  $grouped = array_group_by($services, 'category');
  echo '
      <div class="services-import__body">
             <div>
                <div class="services-import__fields">
                   
                   <div class="services-import__step3-field">
  <div class="services-import__placeholder-title">Select Currency</div><br>
					  <select id="raise-currency" name="currency">
        <option value="" disabled selected>Choose Provider Currency</option>
        <option value="0.0139">INR</option>
        <option value="1">USD</option>
    </select>
 
                   </div>
                   <div class="services-import__step3-plus">+</div>
                   <div class="services-import__step3-field">
  <div class="services-import__placeholder-title">Percent (%)</div>
  <input type="number" placeholder="0" id="raise-percent" name="percent" value="">
                   </div>
				   
                   <div class="services-import__step3-actions"><span class="btn btn-danger">Reset calculations</span></div>
                </div>
                <div class="services-import__list-wrap services-import__list-active">
                   <div class="services-import__scroll-wrap">';
  $category_id = 0;
  $c = 0;
  foreach ($grouped as $category):
    foreach ($apiServices as $key => $value):
      if ($category[$category_id]->id == $value->service):
        $categoryName = $value->category;
      endif;
    endforeach;
    $category_id = $category_id++;
    $c++;
    echo '<span class="providerCategory" id="providerCategory-' . $c . '">
       <div class="services-import__category">
          <div class="services-import__category-title"><label>' . $categoryName . '</label></div>
       </div>
       <div class="services-import__packages">
          <ul>';
    for ($i = 0; $i < count($category); $i++):
      foreach ($apiServices as $apiService):
        if ($apiService->service == $category[$i]->id):
          echo '<li id="providerService-' . $apiService->service . '">
 <label>
    ' . $apiService->service . ' - ' . $apiService->name . '
    <span class="services-import__packages-price-edit" >
       <div class="services-import__packages-price-lock" data-category="' . $c . '"  data-id="servicedelete-' . $apiService->service . '" data-service="' . $apiService->service . '">
         <span class="fa fa-trash"></span>
       </div>
       <div class="services-import__packages-price-lock"  data-id="servicelock-' . $apiService->service . '" data-service="' . $apiService->service . '">
         <span class="fa fa-unlock"></span>
       </div>
       <input id="servicePriceCal' . $apiService->service . '" type="text" class="services-import__price" data-rate="' . priceFormat($apiService->rate) . '" data-service="' . $apiService->service . '" name="servicesList[' . $apiService->service . ']" value="' . priceFormat
          ($apiService->rate) . '">
       <span class="services-import__provider-price">' . priceFormat($apiService->rate) . '</span>
    </span>
 </label>
                  </li>';
        endif;
      endforeach;
    endfor;
    echo '</ul>
       </div>
    </span>';
  endforeach;
  echo '</div>
                </div>
             </div>
          </div>
          <script>
          function formatCurrency(total) {
              var neg = false;
              if(total < 0) {
                  neg = true;
                  total = Math.abs(total);
              }
              return parseFloat(total, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString();
          }
          function sum(input){
           if (toString.call(input) !== "[object Array]")
              return false;

  var total =  0;
  for(var i=0;i<input.length;i++)
    {
      if(isNaN(input[i])){
      continue;
       }
  total += Number(input[i]);
                   }
             return total;
            }
          function chargeService(){
            var add_fixed       = $("#raise-fixed").val();
            var add_percent     = $("#raise-percent").val();
			var add_currency     = $("#raise-currency").val();
            $(".services-import__price").each(function(){
              if( $(this).attr("readonly") != "readonly" ){
                var rate        = $(this).attr("data-rate");
                var service     = $(this).attr("data-service");
                var total = sum([rate,(rate*add_percent/100)])*(add_currency);
                $("#servicePriceCal"+service).val(total);

              }
            });
          }
            $(\'[data-id^="servicedelete-"]\').click(function(){
              var id        = $(this).attr("data-service");
              var category  = $(this).attr("data-category");
              $("li#providerService-"+id).remove();
                if( $("#providerCategory-"+category+" > .services-import__packages > ul > li").length == 0 ){
                  $("#providerCategory-"+category).remove();
                }
            });
            $(\'[data-id^="servicelock-"]\').click(function(){
              var service_id  = $(this).attr("data-service");
              var lock        = $(this).find("span").attr("class");
              if( lock == "fa fa-unlock" ){
                $(this).find("span").removeClass("fa fa-unlock");
                $(this).find("span").addClass("fa fa-lock");
                $(\'[data-service="\'+service_id+\'"]\').attr("readonly",true);
              } else{
                $(this).find("span").removeClass("fa fa-lock");
                $(this).find("span").addClass("fa fa-unlock");
                $(\'[data-service="\'+service_id+\'"]\').attr("readonly",false);
              }
            });

            $(".services-import__step3-actions").click(function(){
              var add_fixed       = $("#raise-fixed").val("");
              var add_percent     = $("#raise-percent").val("");
			  var add_currency     = $("#raise-currency").val("");
              $(".services-import__price").each(function(){
                if( $(this).attr("readonly") != "readonly" ){
                  var rate        = $(this).attr("data-rate");
                  var service     = $(this).attr("data-service");
$("#servicePriceCal"+service).val(rate);
                }
              });
            });

            $("#raise-fixed").on("keyup", function(){
              chargeService();
            });

            $("#raise-percent").on("keyup", function(){
              chargeService();
            });
			 $("#raise-currency").on("keyup", function(){
              chargeService();
            });

          </script>
          ';
elseif ($action == "price_providerCal"):
  $fixed = $_POST["fixed"];
  $percent = $_POST["percent"];
  $rate = $_POST["rate"];
  $total = $rate;
  if (is_numeric($percent) && $percent > 0):
    $total = $total + ($rate * $percent / 100);
  endif;
  if (is_numeric($fixed) && $fixed > 0):
    $total = $total + $fixed;
  endif;
  echo $total;


elseif ($action == "import_service"):
  $providers = $conn->prepare("SELECT * FROM service_api   WHERE status=:status    ");
  $providers->execute(array("status" => 1));
  $providers = $providers->fetchAll(PDO::FETCH_ASSOC);

  $category = $conn->prepare("SELECT * FROM categories");
  $category->execute(array());
  $category = $category->fetchAll(PDO::FETCH_ASSOC);
  $return = '<form class="form" action="' . site_url("admin/services/get_service_add/") . '" method="post" data-xhr="true">
    
        <div class="modal-body">
          <div id="firstStep">
            <div class="service-mode__block">
              <div class="form-group">
              <label>Service Provider</label>
                <select class="form-control" name="provider" id="provider">
  <option value="0">Select service provider...</option>';
  foreach ($providers as $provider):
    $return .= '<option value="' . $provider["id"] . '">' . $provider["api_name"] . '</option>';
  endforeach;
  $return .= '</select>
              </div>
            </div>
          </div>

          
          <div id="secondStep">
          </div>

          <div id="thirdStep">
          </div>


        </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" id="nextStep" data-step="first">Next step</button>
            <button type="submit" class="btn btn-primary" id="submitStep">Add services</button>
          </div>

        </form>
           <script>
            $("#submitStep").hide();
            $("#nextStep").click(function(){
              var now_step = $(this).attr("data-step");
              var provider = $("#provider").val();
              var category = $("#selector").val();
              $("#secondStep").hide();
                if( now_step == "first" ){
                  if( provider == 0 ){
$.toast({
    heading: "Unsuccessful",
    text: "Please select service provider",
    icon: "error",
    loader: true,
    loaderBg: "#9EC600"
});
                  }else{
$("#firstStep").hide();
$("#secondStep").show();
$.post("admin/ajax_data", {provider:provider,category:category,action:"import_services_list" }, function(data){
  $("#secondStep").html(data);
});
$("#nextStep").attr("data-step","second");
                  }
                }else if( now_step == "second" ){
var array     = [];
   $(\'[class^="selectServices-"]\').each(function () {
        var id    = $(this).val();
        var check = $(this).prop("checked");
        var provider  =  $(this).attr("data-provider");
          if( check == true ){
            var params = {};
            params["id"]            = id;
            params["category"]      = $(this).attr("data-category");
            array.push(params);
          }
   });
   var count = array.length;
 if( count ){
   $.post("admin/ajax_data", {provider:provider,action:"import_services_last",services:array }, function(data){
     $("#thirdStep").html(data);
   });
   $("#nextStep").hide();
   $("#submitStep").show();
 }else{
   $("#nextStep").attr("data-step","second");
   $("#firstStep").hide();
   $("#secondStep").show();
   $("#nextStep").show();
   $("#submitStep").hide();
   $.toast({
       heading: "Unsuccessful",
       text: "Please select at least 1 service you want to add",
       icon: "error",
       loader: true,
       loaderBg: "#9EC600"
   });
 }

                }
            });
          </script>
          ';
  echo json_encode(["content" => $return, "title" => "Pull out services from provider"]);
elseif ($action == "import_services_list"):
  $provider_id = $_POST["provider"];
  $category_id2 = $_POST["category"];
  $smmapi = new SMMApi();
  $provider = $conn->prepare("SELECT * FROM service_api WHERE id=:id");
  $provider->execute(array("id" => $provider_id));
  $provider = $provider->fetch(PDO::FETCH_ASSOC);
  if ($provider["api_type"] == 1):
    $services = $smmapi->action(array('key' => $provider["api_key"], 'action' => 'services'), $provider["api_url"]);
    if ($services):
      $grouped = array_group_by($services, 'category');
      echo '<div class="">
            <div class="services-import__body">
                 <div>
<div class="services-import__list-wrap">
   <div class="services-import__scroll-wrap">';
      foreach ($grouped as $category):
        $category_id++;
        echo '
      <span>
         <div class="services-import__category">
            <div class="services-import__category-title">
              <label><input type="checkbox" data-id="' . $category_id . '" id="checkAll-' . $category_id . '">' . $category[0]->category . '</label>
    <input type="hidden" name="category" value="' . $category_id2 . '">
            </div>
         </div>
         <div class="services-import__packages">
            <ul>';
        for ($i = 0; $i < count($category); $i++):
          echo '<li><label><input data-service="' . $category[$i]->name . '" data-provider="' . $provider["id"] . '"  data-category="' . $category_id . '"  class="selectServices-' . $category_id . '" type="checkbox" value="' . $category[$i]->service . '" name="services[]">' . $category[$i]->service . ' - ' . $category[$i]->name . '<span class="services-import__packages-price">' . priceFormat($category[$i]->rate) . '</span></label></li>';
        endfor;
        echo '</ul>
         </div>
      </span>';
      endforeach;
      echo '
   </div>
</div>
                 </div>
              </div>
              <script>
              $(\'[id^="checkAll-"]\').click(function () {
                var id = $(this).attr("data-id");
                 if ( $(this).prop("checked") == true ) {
                   $(".selectServices-"+id).not(this).prop("checked", true);
                 }else{
                   $(".selectServices-"+id).not(this).prop("checked", false);
                 }
               });
              </script>
              </div>';
    else:
      echo "An error occurred, please try later.";
    endif;
  endif;
elseif ($action == "import_services_last"):
  $provider_id = $_POST["provider"];
  $services = json_decode(json_encode($_POST["services"]));
  $smmapi = new SMMApi();
  $provider = $conn->prepare("SELECT * FROM service_api WHERE id=:id");
  $provider->execute(array("id" => $provider_id));
  $provider = $provider->fetch(PDO::FETCH_ASSOC);
  $apiServices = $smmapi->action(array('key' => $provider["api_key"], 'action' => 'services'), $provider["api_url"]);
  $grouped = array_group_by($services, 'category');
  echo '
      <div class="services-import__body">
             <div>
                <div class="services-import__fields">
                   
                   <div class="services-import__step3-field">
  <div class="services-import__placeholder-title">Select Currency</div><br>
					  <select id="raise-currency" name="currency">
        <option value="" disabled selected>Choose Provider Currency</option>
        <option value="0.0139">INR</option>
        <option value="1">USD</option>
    </select>
 
                   </div>
                   <div class="services-import__step3-plus">+</div>
                   <div class="services-import__step3-field">
  <div class="services-import__placeholder-title">Percent (%)</div>
  <input type="number" placeholder="0" id="raise-percent" name="percent" value="">
                   </div>
				   
                   <div class="services-import__step3-actions"><span class="btn btn-danger">Reset calculations</span></div>
                </div>
                <div class="services-import__list-wrap services-import__list-active">
                   <div class="services-import__scroll-wrap">';
  $category_id = 0;
  $c = 0;
  foreach ($grouped as $category):
    foreach ($apiServices as $key => $value):
      if ($category[$category_id]->id == $value->service):
        $categoryName = $value->category;
      endif;
    endforeach;
    $category_id = $category_id++;
    $c++;
    echo '<span class="providerCategory" id="providerCategory-' . $c . '">
       <div class="services-import__category">
          <div class="services-import__category-title"><label>' . $categoryName . '</label></div>
       </div>
       <div class="services-import__packages">
          <ul>';
    for ($i = 0; $i < count($category); $i++):
      foreach ($apiServices as $apiService):
        if ($apiService->service == $category[$i]->id):
          echo '<li id="providerService-' . $apiService->service . '">
 <label>
    ' . $apiService->service . ' - ' . $apiService->name . '
    <span class="services-import__packages-price-edit" >
       <div class="services-import__packages-price-lock" data-category="' . $c . '"  data-id="servicedelete-' . $apiService->service . '" data-service="' . $apiService->service . '">
         <span class="fa fa-trash"></span>
       </div>
       <div class="services-import__packages-price-lock"  data-id="servicelock-' . $apiService->service . '" data-service="' . $apiService->service . '">
         <span class="fa fa-unlock"></span>
       </div>
       <input id="servicePriceCal' . $apiService->service . '" type="text" class="services-import__price" data-rate="' . priceFormat($apiService->rate) . '" data-service="' . $apiService->service . '" name="servicesList[' . $apiService->service . ']" value="' . priceFormat
          ($apiService->rate) . '">
       <span class="services-import__provider-price">' . priceFormat($apiService->rate) . '</span>
    </span>
 </label>
                  </li>';
        endif;
      endforeach;
    endfor;
    echo '</ul>
       </div>
    </span>';
  endforeach;
  echo '</div>
                </div>
             </div>
          </div>
          <script>
          function formatCurrency(total) {
              var neg = false;
              if(total < 0) {
                  neg = true;
                  total = Math.abs(total);
              }
              return parseFloat(total, 10).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString();
          }
          function sum(input){
           if (toString.call(input) !== "[object Array]")
              return false;

  var total =  0;
  for(var i=0;i<input.length;i++)
    {
      if(isNaN(input[i])){
      continue;
       }
  total += Number(input[i]);
                   }
             return total;
            }
          function chargeService(){
            var add_fixed       = $("#raise-fixed").val();
            var add_percent     = $("#raise-percent").val();
			var add_currency     = $("#raise-currency").val();
            $(".services-import__price").each(function(){
              if( $(this).attr("readonly") != "readonly" ){
                var rate        = $(this).attr("data-rate");
                var service     = $(this).attr("data-service");
                var total = sum([rate,(rate*add_percent/100)])*(add_currency);
                $("#servicePriceCal"+service).val(total);

              }
            });
          }
            $(\'[data-id^="servicedelete-"]\').click(function(){
              var id        = $(this).attr("data-service");
              var category  = $(this).attr("data-category");
              $("li#providerService-"+id).remove();
                if( $("#providerCategory-"+category+" > .services-import__packages > ul > li").length == 0 ){
                  $("#providerCategory-"+category).remove();
                }
            });
            $(\'[data-id^="servicelock-"]\').click(function(){
              var service_id  = $(this).attr("data-service");
              var lock        = $(this).find("span").attr("class");
              if( lock == "fa fa-unlock" ){
                $(this).find("span").removeClass("fa fa-unlock");
                $(this).find("span").addClass("fa fa-lock");
                $(\'[data-service="\'+service_id+\'"]\').attr("readonly",true);
              } else{
                $(this).find("span").removeClass("fa fa-lock");
                $(this).find("span").addClass("fa fa-unlock");
                $(\'[data-service="\'+service_id+\'"]\').attr("readonly",false);
              }
            });

            $(".services-import__step3-actions").click(function(){
              var add_fixed       = $("#raise-fixed").val("");
              var add_percent     = $("#raise-percent").val("");
			  var add_currency     = $("#raise-currency").val("");
              $(".services-import__price").each(function(){
                if( $(this).attr("readonly") != "readonly" ){
                  var rate        = $(this).attr("data-rate");
                  var service     = $(this).attr("data-service");
$("#servicePriceCal"+service).val(rate);
                }
              });
            });

            $("#raise-fixed").on("keyup", function(){
              chargeService();
            });

            $("#raise-percent").on("keyup", function(){
              chargeService();
            });
			 $("#raise-currency").on("keyup", function(){
              chargeService();
            });

          </script>
          ';
elseif ($action == "price_providerCal"):
  $fixed = $_POST["fixed"];
  $percent = $_POST["percent"];
  $rate = $_POST["rate"];
  $total = $rate;
  if (is_numeric($percent) && $percent > 0):
    $total = $total + ($rate * $percent / 100);
  endif;
  if (is_numeric($fixed) && $fixed > 0):
    $total = $total + $fixed;
  endif;
  echo $total;



elseif ($action == "update_inr_rate"):
  update_inr_rate();
  $rate = get_inr_rate();
  echo json_encode(array("rate" => $rate), true);
elseif ($action == "update_inr_rate_manual"):
  $rate = $conn->prepare("UPDATE settings SET dolar_charge=:rate WHERE id=1");
  $rate_fetched = $_POST["rate"];
  $rate->execute(
    array(
      "rate" => $rate_fetched
    )
  );
  $rate = get_inr_rate();

  echo json_encode(array("rate" => $rate), true);
elseif ($action == "new_ticket"):
  $return = '<form class="form" action="' . site_url("admin/tickets/new") . '" method="post" data-xhr="true">
        <div class="modal-body">

          <div class="form-group">
            <label class="form-group__service-name">Username</label>
            <input type="text" class="form-control" name="username" value="">
          </div>

          <div class="form-group">
            <label class="form-group__service-name">Topic</label>
            <input type="text" class="form-control" name="subject" value="">
          </div>

          <div class="form-group">
            <label class="form-group__service-name">Message</label>
            <textarea class="form-control" name="message" rows="4"></textarea>
          </div>


        </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Create new request</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
          </div>
          </form>';
  echo json_encode(["content" => $return, "title" => "New support request"]);
elseif ($action == "yeni_kupon"):
  $return = '<form class="form" action="' . site_url("admin/kuponlar/new") . '" method="post" data-xhr="true">
        <div class="modal-body">

          <div class="form-group">
            <label class="form-group__service-name">Coupon Code</label>
            <input type="text" class="form-control" name="kuponadi" value="">
          </div>

          <div class="form-group">
            <label class="form-group__service-name">Piece</label>
            <input type="text" class="form-control" name="adet" value="">
          </div>

          <div class="form-group">
            <label class="form-group__service-name">Amount</label>
            <input type="text" class="form-control" name="tutar" value="">
          </div>


        </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Create new coupon</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
          </div>
          </form>
          ';
  echo json_encode(["content" => $return, "title" => "Create new coupon"]);

elseif ($action == "edit_integration" && $_POST["id"] == "whatsapp"):
  $id = $_POST["id"];
  $method = $conn->prepare("SELECT * FROM integrations WHERE method_get=:id ");
  $method->execute(array("id" => $id));
  $method = $method->fetch(PDO::FETCH_ASSOC);
  $extra = json_decode($method["method_extras"], true);
  $return = '<form class="form" action="' . site_url('admin/settings/integrations/edit/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Whatsapp Number</label>' . "\r\n" . '  <input type="text" class="form-control" name="number" value="' . $extra['number'] . '">' . "\r\n" . ' Omit any zeroes, brackets, or dashes when adding the phone number in international format. Example: 1XXXXXXXXXX</div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Position</label>' . "\r\n" . '<select class="form-control" name="position">' . "\r\n" . '  <option value="right"';

  if ($extra['position'] == "right") {
    $return .= 'selected';
  }

  $return .= '>Right</option>' . "\r\n" . '  <option value="left"';

  if (extra['position'] == "left") {
    $return .= 'selected';
  }

  $return .= '>Left</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Status</label>' . "\r\n" . '<select class="form-control" name="method_type">' . "\r\n" . '  <option value="2"';

  if ($method['method_type'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Enabled</option>' . "\r\n" . '  <option value="1"';

  if ($method['method_type'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Disabled</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Visibility</label>' . "\r\n" . '<select class="form-control" name="visibility">' . "\r\n" . '  <option value="2"';

  if (extra['visibility'] == 2) {
    $return .= 'selected';
  }

  $return .= '>All</option>' . "\r\n" . ' <option value="2"';

  if (extra['visibility'] == 2) {
    $return .= 'selected';
  }

  $return .= '>External</option>' . "\r\n" . '  <option value="1"';

  if ($extra['visibility'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Internal</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' </div>' . "\r\n\r\n" . ' </div>' . "\r\n\r\n\r\n" . '</div>' . "\r\n\r\n" . ' <div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update</button>' . "\r\n" . '  <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';
  echo json_encode(['content' => $return, 'title' => 'Whatsapp Button']);



elseif ($action == "edit_paymentmethod" && $_POST["id"] == "paypal"):
  $id = $_POST['id'];
  $method = $conn->prepare('SELECT * FROM payment_methods WHERE method_get=:id ');
  $method->execute(['id' => $id]);
  $method = $method->fetch(PDO::FETCH_ASSOC);
  $extra = json_decode($method['method_extras'], true);
  $return = '<form class="form" action="' . site_url('admin/settings/payment-methods/edit/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Method name</label>' . "\r\n" . '  <input type="text" class="form-control" readonly value="' . $method['method_name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Visibility</label>' . "\r\n" . '<select class="form-control" name="method_type">' . "\r\n" . '  <option value="2"';

  if ($method['method_type'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Active</option>' . "\r\n" . '  <option value="1"';

  if ($method['method_type'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Inactive</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Visible name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="' . $extra['name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Minimum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="min" value="' . $extra['min'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Maximum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="max" value="' . $extra['max'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <hr>' . "\r\n" . '  <p class="card-description">' . "\r\n" . '<ul>' . "\r\n" . '<li>' . "\r\n" . ' API callback address: <code>';
  $return .= site_url('payment/' . $method['method_get']);
  $return .= '</code>' . "\r\n" . '</li>' . "\r\n" . '</ul>' . "\r\n" . '  </p>' . "\r\n" . ' <hr>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Client ID</label>' . "\r\n" . '  <input type="text" class="form-control" name="client_id" value="' . $extra['client_id'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Client Secret</label>' . "\r\n" . '  <input type="text" class="form-control" name="client_secret" value="' . $extra['client_secret'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Commission, %</label>' . "\r\n" . '  <input type="text" class="form-control" name="fee" value="' . $extra['fee'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n\r\n" . '</div>' . "\r\n\r\n" . ' <div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update</button>' . "\r\n" . '  <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';
  echo json_encode(["content" => $return, "title" => "Arrange payment method (Method: " . $method["method_name"] . ")"]);


elseif ($action == "edit_paymentmethod" && $_POST["id"] == "phonepe"):

  $id = $_POST['id'];
  $method = $conn->prepare('SELECT * FROM payment_methods WHERE method_get=:id ');
  $method->execute(['id' => $id]);
  $method = $method->fetch(PDO::FETCH_ASSOC);
  $extra = json_decode($method['method_extras'], true);
  $return = '<form class="form" action="' . site_url('admin/settings/payment-methods/edit/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Method name</label>' . "\r\n" . '  <input type="text" class="form-control" readonly value="' . $method['method_name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Visibility</label>' . "\r\n" . '<select class="form-control" name="method_type">' . "\r\n" . '  <option value="2"';

  if ($method['method_type'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Active</option>' . "\r\n" . '  <option value="1"';

  if ($method['method_type'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Inactive</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Visible name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="' . $extra['name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Minimum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="min" value="' . $extra['min'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Maximum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="max" value="' . $extra['max'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <hr>' . "\r\n" . '  <p class="card-description">' . "\r\n" . '<ul>' . "\r\n" . '<li>' . "\r\n" . ' API callback address: <code>';
  $return .= site_url('payment/' . $method['method_get']);
  $return .= '</code>' . "\r\n" . '</li>' . "\r\n" . '</ul>' . "\r\n" . '  </p>' . "\r\n" . ' <hr>' . "\r\n\r\n  <div class=\"form-group\"><label class=\"form-group__service-name\">Phonepe QR Image Link</label><input type=\"text\" class=\"form-control\" name=\"phonepe_qr_link\" value=\"" . $extra["phonepe_qr_link"] . "\"/></div>" . '<div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Email</label>' . "\r\n" . '  <input id="gmail" type="text" class="form-control" name="email" value="' . $extra['email'] . '">' . "\r\n" . ' </div>' . '

<div style="padding:20px;box-shadow: rgba(0, 0, 0, 0.15) 1.95px 1.95px 2.6px;" class="form-group">
<p style="font-weight:bold;">To read PhonePe Transactions from Gmail, we need access to your Gmail Account. Click the below button to allow access for the same. You may see a message <span class="text-primary">"Google hasn\'t verified this app."</span> Click <span class="text-primary">"Advanced"</span> and click <span class="text-primary">"Proceed".</span></p>

<div id="gmail_access"></div>
<br>
<p style="font-weight:bold;">After, you will get a <span class="text-primary">"Access Key".</span> Copy that access key and paste in below field.</p>
</div>
<script>
function verify_gmail(email_add){
var thisRegex = new RegExp(/[a-zA-Z0-9._%+-]+@gmail\.com/);
var email = email_add;
if(thisRegex.test(email)){
$("#gmail_access").html(\'<a class="btn btn-primary" target="_blank" href="https://mails.dukesmm.com/oauth?domain=' . $_SERVER["HTTP_HOST"] . '&email=\'+email+\'">Allow Access for \'+email+\' </a>\');
} else {
  $("#gmail_access").html(\'<button type="button" class="btn btn-danger" disabled>The email you entered is not a Gmail Address</button>\');
}
}
$(document).ready(function(){
verify_gmail("' . $extra["email"] . '");

$("#gmail").keyup(function(){
verify_gmail($(this).val());
});
});
</script>
<div class="form-group">
<label class="form-group__service-name">Access Key</label>
<textarea rows="10" class="form-control" name="access_key">' . $extra["access_key"] . '</textarea>
</div>
' . '<div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update</button>' . "\r\n" . '  <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';

  echo json_encode(['content' => $return, 'title' => '']);

elseif ($action == "edit_paymentmethod" && $_POST["id"] == "easypaisa"):

  $id = $_POST['id'];
  $method = $conn->prepare('SELECT * FROM payment_methods WHERE method_get=:id ');
  $method->execute(['id' => $id]);
  $method = $method->fetch(PDO::FETCH_ASSOC);
  $extra = json_decode($method['method_extras'], true);
  $return = '<form class="form" action="' . site_url('admin/settings/payment-methods/edit/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Method name</label>' . "\r\n" . '  <input type="text" class="form-control" readonly value="' . $method['method_name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Visibility</label>' . "\r\n" . '<select class="form-control" name="method_type">' . "\r\n" . '  <option value="2"';

  if ($method['method_type'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Active</option>' . "\r\n" . '  <option value="1"';

  if ($method['method_type'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Inactive</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Visible name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="' . $extra['name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Minimum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="min" value="' . $extra['min'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Maximum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="max" value="' . $extra['max'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <hr>' . "\r\n" . '  <p class="card-description">' . "\r\n" . '<ul>' . "\r\n" . '<li>' . "\r\n" . ' API callback address: <code>';
  $return .= site_url('payment/' . $method['method_get']);
  $return .= '</code>' . "\r\n" . '</li>' . "\r\n" . '</ul>' . "\r\n" . '  </p>' . "\r\n" . ' <hr>' . "\r\n\r\n  <div class=\"form-group\"><label class=\"form-group__service-name\">Instruction</label><textarea  class=\"form-control\" name=\"content\" id=\"custom-payment-content\">" . $extra['content'] . '</textarea></div>' . '<div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Email</label>' . "\r\n" . '  <input id="gmail" type="text" class="form-control" name="email" value="' . $extra['email'] . '">' . "\r\n" . ' </div>' . '


<div id="gmail_access"></div>
<br>


<script>
function verify_gmail(email_add){
var thisRegex = new RegExp(/[a-zA-Z0-9._%+-]+@gmail\.com/);
var email = email_add;
if(thisRegex.test(email)){
$("#gmail_access").html(\'<a class="btn btn-primary" target="_blank" href="https://mails.scriptlux.com/oauth?domain=' . $_SERVER["HTTP_HOST"] . '&email=\'+email+\'">Allow Access for \'+email+\' </a>\');
} else {
  $("#gmail_access").html(\'<button type="button" class="btn btn-danger" disabled>The email you entered is not a Gmail Address</button>\');
}
}
$(document).ready(function(){
verify_gmail("' . $extra["email"] . '");

$("#gmail").keyup(function(){
verify_gmail($(this).val());
});
$(\'#custom-payment-content\').summernote({
height: 300,
tabsize: 2,
dialogsInBody: true
});
});
</script>
<div class="form-group">
<label class="form-group__service-name">Access Key</label>
<textarea rows="10" class="form-control" name="access_key">' . $extra["access_key"] . '</textarea>
</div>
</div>
' . '<div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update</button>' . "\r\n" . '  <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';

  echo json_encode(['content' => $return, 'title' => '']);

elseif ($action == "edit_paymentmethod" && $_POST["id"] == "payfast"):

  $id = $_POST['id'];
  $method = $conn->prepare('SELECT * FROM payment_methods WHERE method_get=:id ');
  $method->execute(['id' => $id]);
  $method = $method->fetch(PDO::FETCH_ASSOC);
  $extra = json_decode($method['method_extras'], true);
  $return = '<form class="form" action="' . site_url('admin/settings/payment-methods/edit/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Method name</label>' . "\r\n" . '  <input type="text" class="form-control" readonly value="' . $method['method_name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Visibility</label>' . "\r\n" . '<select class="form-control" name="method_type">' . "\r\n" . '  <option value="2"';

  if ($method['method_type'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Active</option>' . "\r\n" . '  <option value="1"';

  if ($method['method_type'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Inactive</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Visible name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="' . $extra['name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Minimum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="min" value="' . $extra['min'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Maximum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="max" value="' . $extra['max'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <hr>' . "\r\n" . '  <p class="card-description">' . "\r\n" . '<ul>' . "\r\n" . '<li>' . "\r\n" . ' API callback address: <code>';
  $return .= site_url('payment/' . $method['method_get']);
  $return .= '</code>' . "\r\n" . '</li>' . "\r\n" . '</ul>' . "\r\n" . '  </p>' . "\r\n" . ' <hr>' . "\r\n\r\n 
  <div class=\"form-group\"><label class=\"form-group__service-name\">GoPayFast Merchant Name</label><input type=\"text\" class=\"form-control\" name=\"merchant_name\" value=\"" . $extra["merchant_name"] . "\"/></div><div class=\"form-group\"><label class=\"form-group__service-name\">GoPayFast Merchant ID</label><input type=\"text\" class=\"form-control\" name=\"merchant_id\" value=\"" . $extra["merchant_id"] . "\"/></div><div class=\"form-group\"> <label class=\"form-group__service-name\">GoPayFast Secured Key</label>" . "\r\n" . '  <input type="text" class="form-control" name="secured_key" value="' . $extra['secured_key'] . '">' . "</div>" . '<div class="form-group">
  <label class="form-group__service-name">Currency</label>
 <input type="text" class="form-control" name="currency" value="' . $extra['currency'] . '" readonly></div><div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update</button>' . "\r\n" . '  <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';

  echo json_encode(['content' => $return, 'title' => 'Edit Payment Method']);

elseif ($action == "edit_paymentmethod" && $_POST["id"] == "kashier"):

  $id = $_POST['id'];
  $method = $conn->prepare('SELECT * FROM payment_methods WHERE method_get=:id ');
  $method->execute(['id' => $id]);
  $method = $method->fetch(PDO::FETCH_ASSOC);
  $extra = json_decode($method['method_extras'], true);
  $return = '<form class="form" action="' . site_url('admin/settings/payment-methods/edit/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Method name</label>' . "\r\n" . '  <input type="text" class="form-control" readonly value="' . $method['method_name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Visibility</label>' . "\r\n" . '<select class="form-control" name="method_type">' . "\r\n" . '  <option value="2"';

  if ($method['method_type'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Active</option>' . "\r\n" . '  <option value="1"';

  if ($method['method_type'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Inactive</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Visible name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="' . $extra['name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Minimum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="min" value="' . $extra['min'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Maximum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="max" value="' . $extra['max'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <hr>' . "\r\n" . '  <p class="card-description">' . "\r\n" . '<ul>' . "\r\n" . '<li>' . "\r\n" . ' API callback address: <code>';
  $return .= site_url('payment/' . $method['method_get']);
  $return .= '</code>' . "\r\n" . '</li>' . "\r\n" . '</ul>' . "\r\n" . '  </p>' . "\r\n" . ' <hr>' . "\r\n\r\n  <div class=\"form-group\"><label class=\"form-group__service-name\">Kashier MID</label><input type=\"text\" class=\"form-control\" name=\"mid\" value=\"" . $extra["mid"] . "\"/><div class=\"form-group\"> <label class=\"form-group__service-name\">Kashier API Key</label>" . "\r\n" . '  <input type="text" class="form-control" name="apikey" value="' . $extra['apikey'] . '">' . "</div><div class=\"form-group\">" . "<label class=\"form-group__service-name\">Kashier Secret Key</label><input type=\"text\" class=\"form-control\" name=\"secret_key\" value=\"" . $extra["secret_key"] . "\"/></div>" . "\r\n" . ' </div>
  <div class="form-group">
  <label class="form-group__service-name">Currency</label>
 <input type="text" class="form-control" name="currency" value="' . $extra['currency'] . '" readonly></div><div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update</button>' . "\r\n" . '  <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';

  echo json_encode(['content' => $return, 'title' => '']);
elseif ($action == "edit_paymentmethod" && $_POST["id"] == "flutterwave"):

  $id = $_POST['id'];
  $method = $conn->prepare('SELECT * FROM payment_methods WHERE method_get=:id ');
  $method->execute(['id' => $id]);
  $method = $method->fetch(PDO::FETCH_ASSOC);
  $extra = json_decode($method['method_extras'], true);
  $return = '<form class="form" action="' . site_url('admin/settings/payment-methods/edit/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Method name</label>' . "\r\n" . '  <input type="text" class="form-control" readonly value="' . $method['method_name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Visibility</label>' . "\r\n" . '<select class="form-control" name="method_type">' . "\r\n" . '  <option value="2"';

  if ($method['method_type'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Active</option>' . "\r\n" . '  <option value="1"';

  if ($method['method_type'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Inactive</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Visible name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="' . $extra['name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Minimum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="min" value="' . $extra['min'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Maximum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="max" value="' . $extra['max'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <hr>' . "\r\n" . '  <p class="card-description">' . "\r\n" . '<ul>' . "\r\n" . '<li>' . "\r\n" . ' API callback address: <code>';
  $return .= site_url('payment/' . $method['method_get']);
  $return .= '</code>' . "\r\n" . '</li>' . "\r\n" . '</ul>' . "\r\n" . '  </p>' . "\r\n" . ' <hr>' . "\r\n\r\n  <div class=\"form-group\"><label class=\"form-group__service-name\">Flutterwave Secret Key</label><input type=\"text\" class=\"form-control\" name=\"secret_key\" value=\"" . $extra["secret_key"] . "\"/>" . '</div></div><div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update</button>' . "\r\n" . '  <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';

  echo json_encode(['content' => $return, 'title' => '']);
elseif ($action == "edit_paymentmethod" && $_POST["id"] == "stripe"):
  $id = $_POST['id'];
  $method = $conn->prepare('SELECT * FROM payment_methods WHERE method_get=:id ');
  $method->execute(['id' => $id]);
  $method = $method->fetch(PDO::FETCH_ASSOC);
  $extra = json_decode($method['method_extras'], true);
  $return = '<form class="form" action="' . site_url('admin/settings/payment-methods/edit/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Method name</label>' . "\r\n" . '  <input type="text" class="form-control" readonly value="' . $method['method_name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Visibility</label>' . "\r\n" . '<select class="form-control" name="method_type">' . "\r\n" . '  <option value="2"';

  if ($method['method_type'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Active</option>' . "\r\n" . '  <option value="1"';

  if ($method['method_type'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Inactive</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Visible name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="' . $extra['name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Minimum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="min" value="' . $extra['min'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Maximum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="max" value="' . $extra['max'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <hr>' . "\r\n" . '  <p class="card-description">' . "\r\n" . '<ul>' . "\r\n" . '<li>' . "\r\n" . ' API callback address: <code>';
  $return .= site_url('payment/' . $method['method_get']);
  $return .= '</code>' . "\r\n" . '</li>' . "\r\n" . '</ul>' . "\r\n" . '  </p>' . "\r\n" . ' <hr>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Stripe Publishable Key</label>' . "\r\n" . '  <input type="text" class="form-control" name="stripe_publishable_key" value="' . $extra['stripe_publishable_key'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Stripe Secret Key</label>' . "\r\n" . '  <input type="text" class="form-control" name="stripe_secret_key" value="' . $extra['stripe_secret_key'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Stripe Webhooks Secret</label>' . "\r\n" . '  <input type="text" class="form-control" name="stripe_webhooks_secret" value="' . $extra['stripe_webhooks_secret'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Commission, %</label>' . "\r\n" . '  <input type="text" class="form-control" name="fee" value="' . $extra['fee'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Currency</label>' . "\r\n" . '  <input type="text" class="form-control" name="currency" value="' . $extra['currency'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n\r\n" . '</div>' . "\r\n\r\n" . ' <div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update</button>' . "\r\n" . '  <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';
  echo json_encode(['content' => $return, 'title' => '']);

elseif ($action == "edit_paymentmethod" && $_POST["id"] == "manualOne" || $_POST["id"] == "manualTwo" || $_POST["id"] == "manualThree" || $_POST["id"] == "manualFour" || $_POST["id"] == "manualFive" || $_POST["id"] == "manualSix" || $_POST["id"] == "manualSeven" || $_POST["id"] == "manualEight" || $_POST["id"] == "manualNine" || $_POST["id"] == "manualTen"):
  $id = $_POST['id'];
  $method = $conn->prepare('SELECT * FROM payment_methods WHERE method_get=:id ');
  $method->execute(['id' => $id]);
  $method = $method->fetch(PDO::FETCH_ASSOC);
  $extra = json_decode($method['method_extras'], true);
  $return = '<form class="form" action="' . site_url('admin/settings/payment-methods/edit/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Method name</label>' . "\r\n" . '  <input type="text" class="form-control" readonly value="' . $method['method_name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Visibility</label>' . "\r\n" . '<select class="form-control" name="method_type">' . "\r\n" . '  <option value="2"';

  if ($method['method_type'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Active</option>' . "\r\n" . '  <option value="1"';

  if ($method['method_type'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Inactive</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Visible name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="' . $extra['name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n";
  $return .= '' . "\r\n" . '  </p>' . "\r\n" . ' <hr>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Content</label>' . "\r\n" . '  <textarea  class="form-control" name="content" id="custom-payment-content">' . $extra['content'] . '</textarea>' . "\r\n" . ' </div>' . "\r\n\r\n\r\n" . '</div>' . "\r\n\r\n" . ' <div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update</button>' . "\r\n" . '  <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form><script>
 $(\'#custom-payment-content\').summernote({
height: 300,
tabsize: 2,
dialogsInBody: true
});
 </script>';
  echo json_encode(['content' => $return, 'title' => '']);




elseif ($action == "edit_paymentmethod" && $_POST["id"] == "payeer"):
  $id = $_POST['id'];
  $method = $conn->prepare('SELECT * FROM payment_methods WHERE method_get=:id ');
  $method->execute(['id' => $id]);
  $method = $method->fetch(PDO::FETCH_ASSOC);
  $extra = json_decode($method['method_extras'], true);
  $return = '<form class="form" action="' . site_url('admin/settings/payment-methods/edit/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Method name</label>' . "\r\n" . '  <input type="text" class="form-control" readonly value="' . $method['method_name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Visibility</label>' . "\r\n" . '<select class="form-control" name="method_type">' . "\r\n" . '  <option value="2"';

  if ($method['method_type'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Active</option>' . "\r\n" . '  <option value="1"';

  if ($method['method_type'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Inactive</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Visible name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="' . $extra['name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Minimum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="min" value="' . $extra['min'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Maximum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="max" value="' . $extra['max'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <hr>' . "\r\n" . '  <p class="card-description">' . "\r\n" . '<ul>' . "\r\n" . '<li>' . "\r\n" . ' API callback address: <code>';
  $return .= site_url('payment/' . $method['method_get']);
  $return .= '</code>' . "\r\n" . '</li>' . "\r\n" . '</ul>' . "\r\n" . '  </p>' . "\r\n" . ' <hr>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Account</label>' . "\r\n" . '  <input type="text" class="form-control" name="account" value="' . $extra['account'] . '">' . '  <label class="form-group__service-name">Client Secret</label>' . "\r\n" . '  <input type="text" class="form-control" name="client_secret" value="' . $extra['client_secret'] . '">' . '  <label class="form-group__service-name">User id</label>' . "\r\n" . '  <input type="text" class="form-control" name="user_id" value="' . $extra['user_id'] . '">' . '  <label class="form-group__service-name">User pass</label>' . "\r\n" . '  <input type="text" class="form-control" name="user_pass" value="' . $extra['user_pass'] . '">' . '<label class="form-group__service-name">M Shop</label>' . "\r\n" . '  <input type="text" class="form-control" name="m_shop" value="' . $extra['m_shop'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n\r\n" . '</div>' . "\r\n\r\n" . ' <div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update</button>' . "\r\n" . '  <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';
  echo json_encode(['content' => $return, 'title' => '']);


elseif ($action == "edit_paymentmethod" && $_POST["id"] == "opay"):
  $id = $_POST['id'];
  $method = $conn->prepare('SELECT * FROM payment_methods WHERE method_get=:id ');
  $method->execute(['id' => $id]);
  $method = $method->fetch(PDO::FETCH_ASSOC);
  $extra = json_decode($method['method_extras'], true);
  $return = '<form class="form" action="' . site_url('admin/settings/payment-methods/edit/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Method name</label>' . "\r\n" . '  <input type="text" class="form-control" readonly value="' . $method['method_name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Visibility</label>' . "\r\n" . '<select class="form-control" name="method_type">' . "\r\n" . '  <option value="2"';

  if ($method['method_type'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Active</option>' . "\r\n" . '  <option value="1"';

  if ($method['method_type'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Inactive</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Mode</label>' . "\r\n" . '<select class="form-control" name="is_demo">' . "\r\n" . '  <option value="1"';

  if ($method['is_demo'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Demo</option>' . "\r\n" . '  <option value="0"';

  if ($method['is_demo'] == 0) {
    $return .= 'selected';
  }

  $return .= '>Live</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Visible name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="' . $extra['name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Minimum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="min" value="' . $extra['min'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Maximum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="max" value="' . $extra['max'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <hr>' . "\r\n" . '  <p class="card-description">' . "\r\n" . '<ul>' . "\r\n" . '<li>' . "\r\n" . ' API callback address: <code>';
  $return .= site_url('payment/' . $method['method_get']);
  $return .= '</code>' . "\r\n" . '</li>' . "\r\n" . '</ul>' . "\r\n" . '  </p>' . "\r\n" . ' <hr>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Merchant id</label>' . "\r\n" . '  <input type="text" class="form-control" name="merchant_id" value="' . $extra['merchant_id'] . '">' . '  <label class="form-group__service-name"> Secret Key</label>' . "\r\n" . '  <input type="text" class="form-control" name="secret_key" value="' . $extra['secret_key'] . '">' . '  <label class="form-group__service-name">Public key</label>' . "\r\n" . '  <input type="text" class="form-control" name="public_key" value="' . $extra['public_key'] . '">' . '  <label class="form-group__service-name">Dollar rate</label>' . "\r\n" . '  <input type="number" step="0.01" min="1" class="form-control" name="dollar_rate" value="' . $extra['dollar_rate'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n\r\n" . '</div>' . "\r\n\r\n" . ' <div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update</button>' . "\r\n" . '  <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';
  echo json_encode(['content' => $return, 'title' => '']);




elseif (($action == 'edit_paymentmethod') && ($_POST['id'] == 'perfectmoney')):
  $id = $_POST['id'];
  $method = $conn->prepare('SELECT * FROM payment_methods WHERE method_get=:id ');
  $method->execute(['id' => $id]);
  $method = $method->fetch(PDO::FETCH_ASSOC);
  $extra = json_decode($method['method_extras'], true);
  $return = '<form class="form" action="' . site_url('admin/settings/payment-methods/edit/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Method name</label>' . "\r\n" . '  <input type="text" class="form-control" readonly value="' . $method['method_name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Visibility</label>' . "\r\n" . '<select class="form-control" name="method_type">' . "\r\n" . '  <option value="2"';

  if ($method['method_type'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Active</option>' . "\r\n" . '  <option value="1"';

  if ($method['method_type'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Inactive</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Visible name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="' . $extra['name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Minimum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="min" value="' . $extra['min'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Maximum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="max" value="' . $extra['max'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <hr>' . "\r\n" . '  <p class="card-description">' . "\r\n" . '<ul>' . "\r\n" . '<li>' . "\r\n" . ' API callback address: <code>';
  $return .= site_url('payment/' . $method['method_get']);
  $return .= '</code>' . "\r\n" . '</li>' . "\r\n" . '</ul>' . "\r\n" . '  </p>' . "\r\n" . ' <hr>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Alternate Passphrase</label>' . "\r\n" . '  <input type="text" class="form-control" name="passphrase" value="' . $extra['passphrase'] . '">' . "\r\n" . ' </div>' . "\r\n" . '<div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">USD ID</label>' . "\r\n" . '  <input type="text" class="form-control" name="usd" value="' . $extra['usd'] . '">' . "\r\n" . ' </div>' . "\r\n" . '<div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Merchant Website Name</label>' . "\r\n" . '  <input type="text" class="form-control" name="merchant_website" value="' . $extra['merchant_website'] . '">' . "\r\n" . ' </div>' . "\r\n" . '<div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Commission, %</label>' . "\r\n" . '  <input type="text" class="form-control" name="fee" value="' . $extra['fee'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n\r\n" . '</div>' . "\r\n\r\n" . ' <div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update</button>' . "\r\n" . '  <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';
  echo json_encode(['content' => $return, 'title' => '']);


elseif ($action == "edit_paymentmethod" && $_POST["id"] == "payeer"):
  $id = $_POST['id'];
  $method = $conn->prepare('SELECT * FROM payment_methods WHERE method_get=:id ');
  $method->execute(['id' => $id]);
  $method = $method->fetch(PDO::FETCH_ASSOC);
  $extra = json_decode($method['method_extras'], true);
  $return = '<form class="form" action="' . site_url('admin/settings/payment-methods/edit/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Method name</label>' . "\r\n" . '  <input type="text" class="form-control" readonly value="' . $method['method_name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Visibility</label>' . "\r\n" . '<select class="form-control" name="method_type">' . "\r\n" . '  <option value="2"';

  if ($method['method_type'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Active</option>' . "\r\n" . '  <option value="1"';

  if ($method['method_type'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Inactive</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Visible name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="' . $extra['name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Minimum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="min" value="' . $extra['min'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Maximum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="max" value="' . $extra['max'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <hr>' . "\r\n" . '  <p class="card-description">' . "\r\n" . '<ul>' . "\r\n" . '<li>' . "\r\n" . ' API callback address: <code>';
  $return .= site_url('payment/' . $method['method_get']);
  $return .= '</code>' . "\r\n" . '</li>' . "\r\n" . '</ul>' . "\r\n" . '  </p>' . "\r\n" . ' <hr>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Client Secret</label>' . "\r\n" . '  <input type="text" class="form-control" name="client_secret" value="' . $extra['client_secret'] . '">' . '<label class="form-group__service-name">M Shop</label>' . "\r\n" . '  <input type="text" class="form-control" name="m_shop" value="' . $extra['m_shop'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n\r\n" . '</div>' . "\r\n\r\n" . ' <div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update</button>' . "\r\n" . '  <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';
  echo json_encode(['content' => $return, 'title' => '']);



elseif ($action == "edit_paymentmethod" && $_POST["id"] == "Coinbase"):
  $id = $_POST['id'];
  $method = $conn->prepare('SELECT * FROM payment_methods WHERE method_get=:id ');
  $method->execute(['id' => $id]);
  $method = $method->fetch(PDO::FETCH_ASSOC);
  $extra = json_decode($method['method_extras'], true);
  $return = '<form class="form" action="' . site_url('admin/settings/payment-methods/edit/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Method name</label>' . "\r\n" . '  <input type="text" class="form-control" readonly value="' . $method['method_name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Visibility</label>' . "\r\n" . '<select class="form-control" name="method_type">' . "\r\n" . '  <option value="2"';

  if ($method['method_type'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Active</option>' . "\r\n" . '  <option value="1"';

  if ($method['method_type'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Inactive</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Visible name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="' . $extra['name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Minimum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="min" value="' . $extra['min'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Maximum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="max" value="' . $extra['max'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <hr>' . "\r\n" . '  <p class="card-description">' . "\r\n" . '<ul>' . "\r\n" . '<li>' . "\r\n" . ' API callback address: <code>';
  $return .= site_url('payment/' . $method['method_get']);
  $return .= '</code>' . "\r\n" . '</li>' . "\r\n" . '</ul>' . "\r\n" . '  </p>' . "\r\n" . ' <hr>' . "\r\n\r\n" . "\r\n" . '<div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">API KEY</label>' . "\r\n" . '  <input type="text" class="form-control" name="api_key" value="' . $extra['api_key'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n\r\n" . '</div>' . "\r\n\r\n" . '<div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">WEBHOOK SHARED API KEY</label>' . "\r\n" . '  <input type="text" class="form-control" name="webhook_api" value="' . $extra['webhook_api'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n\r\n" . '<div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">COMMISSION</label>' . "\r\n" . '  <input type="text" class="form-control" name="fee" value="' . $extra['fee'] . '">' . "\r\n" . ' </div>' . '</div>' . ' <div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update</button>' . "\r\n" . '  <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';
  echo json_encode(['content' => $return, 'title' => '']);




elseif ($action == "edit_paymentmethod" && $_POST["id"] == "Webmoney"):
  $id = $_POST['id'];
  $method = $conn->prepare('SELECT * FROM payment_methods WHERE method_get=:id ');
  $method->execute(['id' => $id]);
  $method = $method->fetch(PDO::FETCH_ASSOC);
  $extra = json_decode($method['method_extras'], true);
  $return = '<form class="form" action="' . site_url('admin/settings/payment-methods/edit/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Method name</label>' . "\r\n" . '  <input type="text" class="form-control" readonly value="' . $method['method_name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Visibility</label>' . "\r\n" . '<select class="form-control" name="method_type">' . "\r\n" . '  <option value="2"';

  if ($method['method_type'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Active</option>' . "\r\n" . '  <option value="1"';

  if ($method['method_type'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Inactive</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Visible name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="' . $extra['name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Minimum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="min" value="' . $extra['min'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Maximum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="max" value="' . $extra['max'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <hr>' . "\r\n" . '  <p class="card-description">' . "\r\n" . '<ul>' . "\r\n" . '<li>' . "\r\n" . ' API callback address: <code>';
  $return .= site_url('payment/' . $method['method_get']);
  $return .= '</code>' . "\r\n" . '</li>' . "\r\n" . '</ul>' . "\r\n" . '  </p>' . "\r\n" . ' <hr>' . "\r\n\r\n" . "\r\n" . '<div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">WMID</label>' . "\r\n" . '  <input type="text" class="form-control" name="wmid" value="' . $extra['wmid'] . '">' . "\r\n" . '<div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">MERCHANT PURSE</label>' . "\r\n" . '  <input type="text" class="form-control" name="purse" value="' . $extra['purse'] . '">' . "\r\n" . ' </div>'
    . '<div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">COMMISSION</label>' . "\r\n" . '  <input type="text" class="form-control" name="fee" value="' . $extra['fee'] . '">' . "\r\n" . ' </div>' . ' </div>' . "\r\n\r\n\r\n" . '</div>' . ' <div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update</button>' . "\r\n" . '  <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';
  echo json_encode(['content' => $return, 'title' => '']);


elseif ($action == "edit_paymentmethod" && $_POST["id"] == "UnityPay"):
  $id = $_POST['id'];
  $method = $conn->prepare('SELECT * FROM payment_methods WHERE method_get=:id ');
  $method->execute(['id' => $id]);
  $method = $method->fetch(PDO::FETCH_ASSOC);
  $extra = json_decode($method['method_extras'], true);
  $return = '<form class="form" action="' . site_url('admin/settings/payment-methods/edit/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Method name</label>' . "\r\n" . '  <input type="text" class="form-control" readonly value="' . $method['method_name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Visibility</label>' . "\r\n" . '<select class="form-control" name="method_type">' . "\r\n" . '  <option value="2"';

  if ($method['method_type'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Active</option>' . "\r\n" . '  <option value="1"';

  if ($method['method_type'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Inactive</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Visible name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="' . $extra['name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Minimum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="min" value="' . $extra['min'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Maximum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="max" value="' . $extra['max'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <hr>' . "\r\n" . '  <p class="card-description">' . "\r\n" . '<ul>' . "\r\n" . '<li>' . "\r\n" . ' API callback address: <code>';
  $return .= site_url('payment/' . $method['method_get']);
  $return .= '</code>' . "\r\n" . '</li>' . "\r\n" . '</ul>' . "\r\n" . '  </p>' . "\r\n" . ' <hr>' . "\r\n\r\n" . "\r\n" . '<div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">SECRET KEY</label>' . "\r\n" . '  <input type="text" class="form-control" name="secret_key" value="' . $extra['secret_key'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n\r\n" . '</div>' . '<div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">PUBLIC ID</label>' . "\r\n" . '  <input type="text" class="form-control" name="reg_email" value="' . $extra['reg_email'] . '">' . "\r\n" . ' </div>' . '<div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">COMMISSION</label>' . "\r\n" . '  <input type="text" class="form-control" name="fee" value="' . $extra['fee'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n\r\n" . '</div>' . ' <div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update</button>' . "\r\n" . '  <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';
  echo json_encode(['content' => $return, 'title' => '']);

elseif ($action == "edit_paymentmethod" && $_POST["id"] == "coinpayments"):
  $id = $_POST['id'];
  $method = $conn->prepare('SELECT * FROM payment_methods WHERE method_get=:id ');
  $method->execute(['id' => $id]);
  $method = $method->fetch(PDO::FETCH_ASSOC);
  $extra = json_decode($method['method_extras'], true);
  $return = '<form class="form" action="' . site_url('admin/settings/payment-methods/edit/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Method name</label>' . "\r\n" . '  <input type="text" class="form-control" readonly value="' . $method['method_name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Visibility</label>' . "\r\n" . '<select class="form-control" name="method_type">' . "\r\n" . '  <option value="2"';

  if ($method['method_type'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Active</option>' . "\r\n" . '  <option value="1"';

  if ($method['method_type'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Inactive</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Visible name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="' . $extra['name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Minimum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="min" value="' . $extra['min'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Maximum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="max" value="' . $extra['max'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <hr>' . "\r\n" . '  <p class="card-description">' . "\r\n" . '<ul>' . "\r\n" . '<li>' . "\r\n" . ' API callback address: <code>';
  $return .= site_url('payment/' . $method['method_get']);
  $return .= '</code>' . "\r\n" . '</li>' . "\r\n" . '</ul>' . "\r\n" . '  </p>' . "\r\n" . ' <hr>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Coinpayments Public Key</label>' . "\r\n" . '  <input type="text" class="form-control" name="coinpayments_public_key" value="' . $extra['coinpayments_public_key'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Coinpayments Private Key</label>' . "\r\n" . '  <input type="text" class="form-control" name="coinpayments_private_key" value="' . $extra['coinpayments_private_key'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Coinpayments Crypto Currency</label>' . "\r\n" . '  <input type="text" class="form-control" name="coinpayments_currency" value="' . $extra['coinpayments_currency'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Merchant ID</label>' . "\r\n" . '  <input type="text" class="form-control" name="merchant_id" value="' . $extra['merchant_id'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">IPN Secret</label>' . "\r\n" . '  <input type="text" class="form-control" name="ipn_secret" value="' . $extra['ipn_secret'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Commission, %</label>' . "\r\n" . '  <input type="text" class="form-control" name="fee" value="' . $extra['fee'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Currency</label>' . "\r\n" . '  <input type="text" class="form-control" name="currency" value="' . $extra['currency'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n\r\n" . '</div>' . "\r\n\r\n" . ' <div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update</button>' . "\r\n" . '  <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';
  echo json_encode(['content' => $return, 'title' => '']);
elseif ($action == "edit_paymentmethod" && $_POST["id"] == "2checkout"):
  $id = $_POST['id'];
  $method = $conn->prepare('SELECT * FROM payment_methods WHERE method_get=:id ');
  $method->execute(['id' => $id]);
  $method = $method->fetch(PDO::FETCH_ASSOC);
  $extra = json_decode($method['method_extras'], true);
  $return = '<form class="form" action="' . site_url('admin/settings/payment-methods/edit/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Method name</label>' . "\r\n" . '  <input type="text" class="form-control" readonly value="' . $method['method_name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Visibility</label>' . "\r\n" . '<select class="form-control" name="method_type">' . "\r\n" . '  <option value="2"';

  if ($method['method_type'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Active</option>' . "\r\n" . '  <option value="1"';

  if ($method['method_type'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Inactive</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Visible name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="' . $extra['name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Minimum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="min" value="' . $extra['min'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Maximum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="max" value="' . $extra['max'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <hr>' . "\r\n" . '  <p class="card-description">' . "\r\n" . '<ul>' . "\r\n" . '<li>' . "\r\n" . ' API callback address: <code>';
  $return .= site_url('payment/' . $method['method_get']);
  $return .= '</code>' . "\r\n" . '</li>' . "\r\n" . '</ul>' . "\r\n" . '  </p>' . "\r\n" . ' <hr>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Seller ID</label>' . "\r\n" . '  <input type="text" class="form-control" name="seller_id" value="' . $extra['seller_id'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Private Key</label>' . "\r\n" . '  <input type="text" class="form-control" name="private_key" value="' . $extra['private_key'] . '">' . "\r\n" . ' </div>' . "\r\n" . '<div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Commission, %</label>' . "\r\n" . '  <input type="text" class="form-control" name="fee" value="' . $extra['fee'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Currency</label>' . "\r\n" . '  <input type="text" class="form-control" name="currency" value="' . $extra['currency'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n\r\n" . '</div>' . "\r\n\r\n" . ' <div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update</button>' . "\r\n" . '  <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';
  echo json_encode(['content' => $return, 'title' => '']);
elseif ($action == "edit_paymentmethod" && $_POST["id"] == "payoneer"):
  $id = $_POST['id'];
  $method = $conn->prepare('SELECT * FROM payment_methods WHERE method_get=:id ');
  $method->execute(['id' => $id]);
  $method = $method->fetch(PDO::FETCH_ASSOC);
  $extra = json_decode($method['method_extras'], true);
  $return = '<form class="form" action="' . site_url('admin/settings/payment-methods/edit/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Method name</label>' . "\r\n" . '  <input type="text" class="form-control" readonly value="' . $method['method_name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Visibility</label>' . "\r\n" . '<select class="form-control" name="method_type">' . "\r\n" . '  <option value="2"';

  if ($method['method_type'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Active</option>' . "\r\n" . '  <option value="1"';

  if ($method['method_type'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Inactive</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Visible name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="' . $extra['name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . '<div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Email</label>' . "\r\n" . '  <input type="text" class="form-control" name="email" value="' . $extra['email'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n\r\n" . '</div>' . "\r\n\r\n" . ' <div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update</button>' . "\r\n" . '  <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';
  echo json_encode(['content' => $return, 'title' => '']);

elseif ($action == "edit_paymentmethod" && $_POST["id"] == "mollie"):
  $id = $_POST['id'];
  $method = $conn->prepare('SELECT * FROM payment_methods WHERE method_get=:id ');
  $method->execute(['id' => $id]);
  $method = $method->fetch(PDO::FETCH_ASSOC);
  $extra = json_decode($method['method_extras'], true);
  $return = '<form class="form" action="' . site_url('admin/settings/payment-methods/edit/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Method name</label>' . "\r\n" . '  <input type="text" class="form-control" readonly value="' . $method['method_name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Visibility</label>' . "\r\n" . '<select class="form-control" name="method_type">' . "\r\n" . '  <option value="2"';

  if ($method['method_type'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Active</option>' . "\r\n" . '  <option value="1"';

  if ($method['method_type'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Inactive</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Visible name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="' . $extra['name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Minimum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="min" value="' . $extra['min'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Maximum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="max" value="' . $extra['max'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <hr>' . "\r\n" . '  <p class="card-description">' . "\r\n" . '<ul>' . "\r\n" . '<li>' . "\r\n" . ' API callback address: <code>';
  $return .= site_url('payment/' . $method['method_get']);
  $return .= '</code>' . "\r\n" . '</li>' . "\r\n" . '</ul>' . "\r\n" . '  </p>' . "\r\n" . ' <hr>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Live API key</label>' . "\r\n" . '  <input type="text" class="form-control" name="live_api_key" value="' . $extra['live_api_key'] . '">' . "\r\n" . ' </div>' . "\r\n" . '<div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Commission, %</label>' . "\r\n" . '  <input type="text" class="form-control" name="fee" value="' . $extra['fee'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Currency</label>' . "\r\n" . '  <input type="text" class="form-control" name="currency" value="' . $extra['currency'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n\r\n" . '</div>' . "\r\n\r\n" . ' <div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update</button>' . "\r\n" . '  <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';
  echo json_encode(['content' => $return, 'title' => '']);


elseif ($action == "edit_paymentmethod" && $_POST["id"] == "paytm"):
  $id = $_POST['id'];
  $method = $conn->prepare('SELECT * FROM payment_methods WHERE method_get=:id ');
  $method->execute(['id' => $id]);
  $method = $method->fetch(PDO::FETCH_ASSOC);
  $extra = json_decode($method['method_extras'], true);
  $return = '<form class="form" action="' . site_url('admin/settings/payment-methods/edit/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Method name</label>' . "\r\n" . '  <input type="text" class="form-control" readonly value="' . $method['method_name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Visibility</label>' . "\r\n" . '<select class="form-control" name="method_type">' . "\r\n" . '  <option value="2"';

  if ($method['method_type'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Active</option>' . "\r\n" . '  <option value="1"';

  if ($method['method_type'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Inactive</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Visible name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="' . $extra['name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Minimum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="min" value="' . $extra['min'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Maximum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="max" value="' . $extra['max'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <hr>' . "\r\n" . '  <p class="card-description">' . "\r\n" . '<ul>' . "\r\n" . '<li>' . "\r\n" . ' API callback address: <code>';
  $return .= site_url('payment/' . $method['method_get']);
  $return .= '</code>' . "\r\n" . '</li>' . "\r\n" . '</ul>' . "\r\n" . '  </p>' . "\r\n" . ' <hr>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Merchant Key</label>' . "\r\n" . '  <input type="text" class="form-control" name="merchant_key" value="' . $extra['merchant_key'] . '">' . "\r\n" . ' </div>' . "\r\n" . '<div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Merchant MID</label>' . "\r\n" . '  <input type="text" class="form-control" name="merchant_mid" value="' . $extra['merchant_mid'] . '">' . "\r\n" . ' </div>' . "\r\n" . '<div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Merchant Website</label>' . "\r\n" . '  <input type="text" class="form-control" name="merchant_website" value="' . $extra['merchant_website'] . '">' . "\r\n" . ' </div>' . "\r\n" . '<div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Commission, %</label>' . "\r\n" . '  <input type="text" class="form-control" name="fee" value="' . $extra['fee'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Currency</label>' . "\r\n" . '  <input type="text" class="form-control" name="currency" value="' . $extra['currency'] . '" readonly>' . "\r\n" . ' </div>' . "\r\n\r\n\r\n" . '</div>' . "\r\n\r\n" . ' <div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update</button>' . "\r\n" . '  <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';
  echo json_encode(['content' => $return, 'title' => '']);


elseif (($action == 'edit_paymentmethod') && ($_POST['id'] == 'Cashmaal')):
  $id = $_POST['id'];
  $method = $conn->prepare('SELECT * FROM payment_methods WHERE method_get=:id ');
  $method->execute(['id' => $id]);
  $method = $method->fetch(PDO::FETCH_ASSOC);
  $extra = json_decode($method['method_extras'], true);
  $return = '<form class="form" action="' . site_url('admin/settings/payment-methods/edit/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Method name</label>' . "\r\n" . '  <input type="text" class="form-control" readonly value="' . $method['method_name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Visibility</label>' . "\r\n" . '<select class="form-control" name="method_type">' . "\r\n" . '  <option value="2"';

  if ($method['method_type'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Active</option>' . "\r\n" . '  <option value="1"';

  if ($method['method_type'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Inactive</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Visible name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="' . $extra['name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Minimum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="min" value="' . $extra['min'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Maximum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="max" value="' . $extra['max'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <hr>' . "\r\n" . '  <p class="card-description">' . "\r\n" . '<ul>' . "\r\n" . '<li>' . "\r\n" . ' API callback address: <code>';
  $return .= site_url('payment/' . $method['method_get']);
  $return .= '</code>' . "\r\n" . '</li>' . "\r\n" . '</ul>' . "\r\n" . '  </p>' . "\r\n" . ' <hr>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Web ID</label>' . "\r\n" . '  <input type="text" class="form-control" name="web_id" value="' . $extra['web_id'] . '">' . "\r\n" . ' </div>' . "\r\n" . '<div class="form-group">' . "\r\n" . '<div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Commission, %</label>' . "\r\n" . '  <input type="text" class="form-control" name="fee" value="' . $extra['fee'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Currency</label>' . "\r\n" . '  <input type="text" class="form-control" name="currency" value="' . $extra['currency'] . '" readonly>' . "\r\n" . ' </div>' . "\r\n\r\n\r\n" . '</div>' . "\r\n\r\n" . ' <div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update</button>' . "\r\n" . '  <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';
  echo json_encode(['content' => $return, 'title' => '']);

elseif ($action == "edit_paymentmethod" && $_POST["id"] == "instamojo"):
  $id = $_POST['id'];
  $method = $conn->prepare('SELECT * FROM payment_methods WHERE method_get=:id ');
  $method->execute(['id' => $id]);
  $method = $method->fetch(PDO::FETCH_ASSOC);
  $extra = json_decode($method['method_extras'], true);
  $return = '<form class="form" action="' . site_url('admin/settings/payment-methods/edit/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Method name</label>' . "\r\n" . '  <input type="text" class="form-control" readonly value="' . $method['method_name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Visibility</label>' . "\r\n" . '<select class="form-control" name="method_type">' . "\r\n" . '  <option value="2"';

  if ($method['method_type'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Active</option>' . "\r\n" . '  <option value="1"';

  if ($method['method_type'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Inactive</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Visible name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="' . $extra['name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Minimum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="min" value="' . $extra['min'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Maximum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="max" value="' . $extra['max'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <hr>' . "\r\n" . '  <p class="card-description">' . "\r\n" . '<ul>' . "\r\n" . '<li>' . "\r\n" . ' API callback address: <code>';
  $return .= site_url('payment/' . $method['method_get']);
  $return .= '</code>' . "\r\n" . '</li>' . "\r\n" . '</ul>' . "\r\n" . '  </p>' . "\r\n" . ' <hr>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Live API Key</label>' . "\r\n" . '  <input type="text" class="form-control" name="api_key" value="' . $extra['api_key'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Live Auth Token Key</label>' . "\r\n" . '  <input type="text" class="form-control" name="live_auth_token_key" value="' . $extra['live_auth_token_key'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Commission, %</label>' . "\r\n" . '  <input type="text" class="form-control" name="fee" value="' . $extra['fee'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Currency</label>' . "\r\n" . '  <input type="text" class="form-control" name="currency" value="' . $extra['currency'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n\r\n" . '</div>' . "\r\n\r\n" . ' <div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update</button>' . "\r\n" . '  <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';
  echo json_encode(['content' => $return, 'title' => '']);



elseif ($action == "edit_paymentmethod" && $_POST["id"] == "paystack"):
  $id = $_POST['id'];
  $method = $conn->prepare('SELECT * FROM payment_methods WHERE method_get=:id ');
  $method->execute(['id' => $id]);
  $method = $method->fetch(PDO::FETCH_ASSOC);
  $extra = json_decode($method['method_extras'], true);
  $return = '<form class="form" action="' . site_url('admin/settings/payment-methods/edit/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Method name</label>' . "\r\n" . '  <input type="text" class="form-control" readonly value="' . $method['method_name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Visibility</label>' . "\r\n" . '<select class="form-control" name="method_type">' . "\r\n" . '  <option value="2"';

  if ($method['method_type'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Active</option>' . "\r\n" . '  <option value="1"';

  if ($method['method_type'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Inactive</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Visible name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="' . $extra['name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Minimum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="min" value="' . $extra['min'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Maximum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="max" value="' . $extra['max'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <hr>' . "\r\n" . '  <p class="card-description">' . "\r\n" . '<ul>' . "\r\n" . '<li>' . "\r\n" . ' API callback address: <code>';
  $return .= site_url('payment/' . $method['method_get']);
  $return .= '</code>' . "\r\n" . '</li>' . "\r\n" . '</ul>' . "\r\n" . '  </p>' . "\r\n" . ' <hr>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">API Secret Key</label>' . "\r\n" . '  <input type="text" class="form-control" name="api_secret_key" value="' . $extra['api_secret_key'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">API Publish Key</label>' . "\r\n" . '  <input type="text" class="form-control" name="api_publish_key" value="' . $extra['api_publish_key'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Commission, %</label>' . "\r\n" . '  <input type="text" class="form-control" name="fee" value="' . $extra['fee'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Currency</label>' . "\r\n" . '  <input type="text" class="form-control" name="currency" value="' . $extra['currency'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n\r\n" . '</div>' . "\r\n\r\n" . ' <div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update</button>' . "\r\n" . '  <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';
  echo json_encode(['content' => $return, 'title' => '']);




elseif ($action == "edit_paymentmethod" && $_POST["id"] == "razorpay"):
  $id = $_POST['id'];
  $method = $conn->prepare('SELECT * FROM payment_methods WHERE method_get=:id ');
  $method->execute(['id' => $id]);
  $method = $method->fetch(PDO::FETCH_ASSOC);
  $extra = json_decode($method['method_extras'], true);
  $return = '<form class="form" action="' . site_url('admin/settings/payment-methods/edit/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Method name</label>' . "\r\n" . '  <input type="text" class="form-control" readonly value="' . $method['method_name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Visibility</label>' . "\r\n" . '<select class="form-control" name="method_type">' . "\r\n" . '  <option value="2"';

  if ($method['method_type'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Active</option>' . "\r\n" . '  <option value="1"';

  if ($method['method_type'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Inactive</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Visible name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="' . $extra['name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Minimum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="min" value="' . $extra['min'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Maximum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="max" value="' . $extra['max'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <hr>' . "\r\n" . '  <p class="card-description">' . "\r\n" . '<ul>' . "\r\n" . '<li>' . "\r\n" . ' API callback address: <code>';
  $return .= site_url('payment/' . $method['method_get']);
  $return .= '</code>' . "\r\n" . '</li>' . "\r\n" . '</ul>' . "\r\n" . '  </p>' . "\r\n" . ' <hr>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">API Key</label>' . "\r\n" . '  <input type="text" class="form-control" name="api_key" value="' . $extra['api_key'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">API Secret Key</label>' . "\r\n" . '  <input type="text" class="form-control" name="api_secret_key" value="' . $extra['api_secret_key'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Commission, %</label>' . "\r\n" . '  <input type="text" class="form-control" name="fee" value="' . $extra['fee'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Currency</label>' . "\r\n" . '  <input type="text" class="form-control" name="currency" value="' . $extra['currency'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n\r\n" . '</div>' . "\r\n\r\n" . ' <div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update</button>' . "\r\n" . '  <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';
  echo json_encode(['content' => $return, 'title' => '']);


elseif ($action == "edit_paymentmethod" && $_POST["id"] == "iyzico"):
  $id = $_POST['id'];
  $method = $conn->prepare('SELECT * FROM payment_methods WHERE method_get=:id ');
  $method->execute(['id' => $id]);
  $method = $method->fetch(PDO::FETCH_ASSOC);
  $extra = json_decode($method['method_extras'], true);
  $return = '<form class="form" action="' . site_url('admin/settings/payment-methods/edit/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Method name</label>' . "\r\n" . '  <input type="text" class="form-control" readonly value="' . $method['method_name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Visibility</label>' . "\r\n" . '<select class="form-control" name="method_type">' . "\r\n" . '  <option value="2"';

  if ($method['method_type'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Active</option>' . "\r\n" . '  <option value="1"';

  if ($method['method_type'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Inactive</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Visible name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="' . $extra['name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Minimum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="min" value="' . $extra['min'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Maximum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="max" value="' . $extra['max'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <hr>' . "\r\n" . '  <p class="card-description">' . "\r\n" . '<ul>' . "\r\n" . '<li>' . "\r\n" . ' API callback address: <code>';
  $return .= site_url('payment/' . $method['method_get']);
  $return .= '</code>' . "\r\n" . '</li>' . "\r\n" . '</ul>' . "\r\n" . '  </p>' . "\r\n" . ' <hr>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">API Key</label>' . "\r\n" . '  <input type="text" class="form-control" name="api_key" value="' . $extra['api_key'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">API Secret Key</label>' . "\r\n" . '  <input type="text" class="form-control" name="api_secret_key" value="' . $extra['api_secret_key'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Commission, %</label>' . "\r\n" . '  <input type="text" class="form-control" name="fee" value="' . $extra['fee'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Currency</label>' . "\r\n" . '  <input type="text" class="form-control" name="currency" value="' . $extra['currency'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n\r\n" . '</div>' . "\r\n\r\n" . ' <div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update</button>' . "\r\n" . '  <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';
  echo json_encode(['content' => $return, 'title' => '']);


elseif ($action == "edit_paymentmethod" && $_POST["id"] == "authorize-net"):
  $id = $_POST['id'];
  $method = $conn->prepare('SELECT * FROM payment_methods WHERE method_get=:id ');
  $method->execute(['id' => $id]);
  $method = $method->fetch(PDO::FETCH_ASSOC);
  $extra = json_decode($method['method_extras'], true);
  $return = '<form class="form" action="' . site_url('admin/settings/payment-methods/edit/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Method name</label>' . "\r\n" . '  <input type="text" class="form-control" readonly value="' . $method['method_name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Visibility</label>' . "\r\n" . '<select class="form-control" name="method_type">' . "\r\n" . '  <option value="2"';

  if ($method['method_type'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Active</option>' . "\r\n" . '  <option value="1"';

  if ($method['method_type'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Inactive</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Visible name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="' . $extra['name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Minimum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="min" value="' . $extra['min'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Maximum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="max" value="' . $extra['max'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <hr>' . "\r\n" . '  <p class="card-description">' . "\r\n" . '<ul>' . "\r\n" . '<li>' . "\r\n" . ' API callback address: <code>';
  $return .= site_url('payment/' . $method['method_get']);
  $return .= '</code>' . "\r\n" . '</li>' . "\r\n" . '</ul>' . "\r\n" . '  </p>' . "\r\n" . ' <hr>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">API Login Id</label>' . "\r\n" . '  <input type="text" class="form-control" name="api_login_id" value="' . $extra['api_login_id'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Secret Transaction Key</label>' . "\r\n" . '  <input type="text" class="form-control" name="secret_transaction_key" value="' . $extra['secret_transaction_key'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Commission, %</label>' . "\r\n" . '  <input type="text" class="form-control" name="fee" value="' . $extra['fee'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Currency</label>' . "\r\n" . '  <input type="text" class="form-control" name="currency" value="' . $extra['currency'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n\r\n" . '</div>' . "\r\n\r\n" . ' <div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update</button>' . "\r\n" . '  <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';
  echo json_encode(['content' => $return, 'title' => '']);




elseif ($action == "edit_paymentmethod" && $_POST["id"] == "mercadopago"):
  $id = $_POST['id'];
  $method = $conn->prepare('SELECT * FROM payment_methods WHERE method_get=:id ');
  $method->execute(['id' => $id]);
  $method = $method->fetch(PDO::FETCH_ASSOC);
  $extra = json_decode($method['method_extras'], true);
  $return = '<form class="form" action="' . site_url('admin/settings/payment-methods/edit/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Method name</label>' . "\r\n" . '  <input type="text" class="form-control" readonly value="' . $method['method_name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Visibility</label>' . "\r\n" . '<select class="form-control" name="method_type">' . "\r\n" . '  <option value="2"';

  if ($method['method_type'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Active</option>' . "\r\n" . '  <option value="1"';

  if ($method['method_type'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Inactive</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Visible name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="' . $extra['name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Minimum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="min" value="' . $extra['min'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Maximum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="max" value="' . $extra['max'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <hr>' . "\r\n" . '  <p class="card-description">' . "\r\n" . '<ul>' . "\r\n" . '<li>' . "\r\n" . ' API callback address: <code>';
  $return .= site_url('payment/' . $method['method_get']);
  $return .= '</code>' . "\r\n" . '</li>' . "\r\n" . '</ul>' . "\r\n" . '  </p>' . "\r\n" . ' <hr>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Live Access Token</label>' . "\r\n" . '  <input type="text" class="form-control" name="live_access_token" value="' . $extra['live_access_token'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Commission, %</label>' . "\r\n" . '  <input type="text" class="form-control" name="fee" value="' . $extra['fee'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Currency</label>' . "\r\n" . '  <input type="text" class="form-control" name="currency" value="' . $extra['currency'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n\r\n" . '</div>' . "\r\n\r\n" . ' <div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update</button>' . "\r\n" . '  <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';
  echo json_encode(['content' => $return, 'title' => '']);

elseif ($action == "edit_paymentmethod" && $_POST["id"] == "payumoney"):
  $id = $_POST['id'];
  $method = $conn->prepare('SELECT * FROM payment_methods WHERE method_get=:id ');
  $method->execute(['id' => $id]);
  $method = $method->fetch(PDO::FETCH_ASSOC);
  $extra = json_decode($method['method_extras'], true);
  $return = '<form class="form" action="' . site_url('admin/settings/payment-methods/edit/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Method name</label>' . "\r\n" . '  <input type="text" class="form-control" readonly value="' . $method['method_name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Visibility</label>' . "\r\n" . '<select class="form-control" name="method_type">' . "\r\n" . '  <option value="2"';

  if ($method['method_type'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Active</option>' . "\r\n" . '  <option value="1"';

  if ($method['method_type'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Inactive</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Visible name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="' . $extra['name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Minimum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="min" value="' . $extra['min'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Maximum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="max" value="' . $extra['max'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <hr>' . "\r\n" . '  <p class="card-description">' . "\r\n" . '<ul>' . "\r\n" . '<li>' . "\r\n" . ' API callback address: <code>';
  $return .= site_url('payment/' . $method['method_get']);
  $return .= '</code>' . "\r\n" . '</li>' . "\r\n" . '</ul>' . "\r\n" . '  </p>' . "\r\n" . ' <hr>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Merchant Key</label>' . "\r\n" . '  <input type="text" class="form-control" name="merchant_key" value="' . $extra['merchant_key'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Salt Key</label>' . "\r\n" . '  <input type="text" class="form-control" name="salt_key" value="' . $extra['salt_key'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Commission, %</label>' . "\r\n" . '  <input type="text" class="form-control" name="fee" value="' . $extra['fee'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Currency</label>' . "\r\n" . '  <input type="text" class="form-control" name="currency" value="' . $extra['currency'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n\r\n" . '</div>' . "\r\n\r\n" . ' <div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update</button>' . "\r\n" . '  <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';
  echo json_encode(['content' => $return, 'title' => '']);



elseif ($action == "edit_paymentmethod" && $_POST["id"] == "ravepay"):
  $id = $_POST['id'];
  $method = $conn->prepare('SELECT * FROM payment_methods WHERE method_get=:id ');
  $method->execute(['id' => $id]);
  $method = $method->fetch(PDO::FETCH_ASSOC);
  $extra = json_decode($method['method_extras'], true);
  $return = '<form class="form" action="' . site_url('admin/settings/payment-methods/edit/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Method name</label>' . "\r\n" . '  <input type="text" class="form-control" readonly value="' . $method['method_name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Visibility</label>' . "\r\n" . '<select class="form-control" name="method_type">' . "\r\n" . '  <option value="2"';

  if ($method['method_type'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Active</option>' . "\r\n" . '  <option value="1"';

  if ($method['method_type'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Inactive</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Visible name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="' . $extra['name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Minimum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="min" value="' . $extra['min'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Maximum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="max" value="' . $extra['max'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <hr>' . "\r\n" . '  <p class="card-description">' . "\r\n" . '<ul>' . "\r\n" . '<li>' . "\r\n" . ' API callback address: <code>';
  $return .= site_url('payment/' . $method['method_get']);
  $return .= '</code>' . "\r\n" . '</li>' . "\r\n" . '</ul>' . "\r\n" . '  </p>' . "\r\n" . ' <hr>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Public API Key</label>' . "\r\n" . '  <input type="text" class="form-control" name="public_api_key" value="' . $extra['public_api_key'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Secret API Key</label>' . "\r\n" . '  <input type="text" class="form-control" name="secret_api_key" value="' . $extra['secret_api_key'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Commission, %</label>' . "\r\n" . '  <input type="text" class="form-control" name="fee" value="' . $extra['fee'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Currency</label>' . "\r\n" . '  <input type="text" class="form-control" name="currency" value="' . $extra['currency'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n\r\n" . '</div>' . "\r\n\r\n" . ' <div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update</button>' . "\r\n" . '  <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';
  echo json_encode(['content' => $return, 'title' => '']);


elseif ($action == "edit_paymentmethod" && $_POST["id"] == "pagseguro"):
  $id = $_POST['id'];
  $method = $conn->prepare('SELECT * FROM payment_methods WHERE method_get=:id ');
  $method->execute(['id' => $id]);
  $method = $method->fetch(PDO::FETCH_ASSOC);
  $extra = json_decode($method['method_extras'], true);
  $return = '<form class="form" action="' . site_url('admin/settings/payment-methods/edit/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Method name</label>' . "\r\n" . '  <input type="text" class="form-control" readonly value="' . $method['method_name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Visibility</label>' . "\r\n" . '<select class="form-control" name="method_type">' . "\r\n" . '  <option value="2"';

  if ($method['method_type'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Active</option>' . "\r\n" . '  <option value="1"';

  if ($method['method_type'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Inactive</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Visible name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="' . $extra['name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Minimum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="min" value="' . $extra['min'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Maximum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="max" value="' . $extra['max'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <hr>' . "\r\n" . '  <p class="card-description">' . "\r\n" . '<ul>' . "\r\n" . '<li>' . "\r\n" . ' API callback address: <code>';
  $return .= site_url('payment/' . $method['method_get']);
  $return .= '</code>' . "\r\n" . '</li>' . "\r\n" . '</ul>' . "\r\n" . '  </p>' . "\r\n" . ' <hr>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">PagSeguro Email id</label>' . "\r\n" . '  <input type="text" class="form-control" name="email_id" value="' . $extra['email_id'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Live Production Token</label>' . "\r\n" . '  <input type="text" class="form-control" name="live_production_token" value="' . $extra['live_production_token'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Commission, %</label>' . "\r\n" . '  <input type="text" class="form-control" name="fee" value="' . $extra['fee'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Currency</label>' . "\r\n" . '  <input type="text" class="form-control" name="currency" value="' . $extra['currency'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n\r\n" . '</div>' . "\r\n\r\n" . ' <div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update</button>' . "\r\n" . '  <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';
  echo json_encode(['content' => $return, 'title' => '']);



elseif ($action == "edit_paymentmethod" && $_POST["id"] == "shopier"):
  $id = $_POST['id'];
  $method = $conn->prepare('SELECT * FROM payment_methods WHERE method_get=:id ');
  $method->execute(['id' => $id]);
  $method = $method->fetch(PDO::FETCH_ASSOC);
  $extra = json_decode($method['method_extras'], true);
  $return = '<form class="form" action="' . site_url('admin/settings/payment-methods/edit/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Method name</label>' . "\r\n" . '  <input type="text" class="form-control" readonly value="' . $method['method_name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Visibility</label>' . "\r\n" . '<select class="form-control" name="method_type">' . "\r\n" . '  <option value="2"';

  if ($method['method_type'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Active</option>' . "\r\n" . '  <option value="1"';

  if ($method['method_type'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Inactive</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Visible name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="' . $extra['name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Minimum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="min" value="' . $extra['min'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Maximum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="max" value="' . $extra['max'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <hr>' . "\r\n" . '  <p class="card-description">' . "\r\n" . '<ul>' . "\r\n" . '<li>' . "\r\n" . ' API callback address: <code>';
  $return .= site_url('payment/' . $method['method_get']);
  $return .= '</code>' . "\r\n" . '</li>' . "\r\n" . '</ul>' . "\r\n" . '  </p>' . "\r\n" . ' <hr>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">apiKey</label>' . "\r\n" . '  <input type="text" class="form-control" name="apiKey" value="' . $extra['apiKey'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">apiSecret</label>' . "\r\n" . '  <input type="text" class="form-control" name="apiSecret" value="' . $extra['apiSecret'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . ' <label>Callbacks</label>' . "\r\n" . '  <select class="form-control" name="website_index">' . "\r\n" . ' <option value="1"';

  if ($extra['website_index'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Callback URL (1)</option>' . "\r\n" . ' <option value="2"';

  if ($extra['website_index'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Callback URL (2)</option>' . "\r\n" . ' <option value="3"';

  if ($extra['website_index'] == 3) {
    $return .= 'selected';
  }

  $return .= '>Callback URL (3)</option>' . "\r\n" . ' <option value="4"';

  if ($extra['website_index'] == 4) {
    $return .= 'selected';
  }

  $return .= '>Callback URL (4)</option>' . "\r\n" . ' <option value="5"';

  if ($extra['website_index'] == 5) {
    $return .= 'selected';
  }

  $return .= '>Callback URL (5)</option>' . "\r\n" . '</select>' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . ' <label>Processing fee (0,49 TL)</label>' . "\r\n" . '  <select class="form-control" name="processing_fee">' . "\r\n" . ' <option value="1"';

  if ($extra['processing_fee'] == 1) {
    $return .= 'selected';
  }

  $return .= '>User should pay this commission</option>' . "\r\n" . ' <option value="0"';

  if ($extra['processing_fee'] == 0) {
    $return .= 'selected';
  }

  $return .= '>User should not pay this commission</option>' . "\r\n" . '</select>' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Commission, %</label>' . "\r\n" . '  <input type="text" class="form-control" name="fee" value="' . $extra['fee'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Currency</label>' . "\r\n" . '  <input type="text" class="form-control" name="currency" value="' . $extra['currency'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n\r\n" . '</div>' . "\r\n\r\n" . ' <div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update</button>' . "\r\n" . '  <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';
  echo json_encode(['content' => $return, 'title' => '']);

elseif ($action == "edit_paymentmethod" && $_POST["id"] == "paytr"):
  $id = $_POST['id'];
  $method = $conn->prepare('SELECT * FROM payment_methods WHERE method_get=:id ');
  $method->execute(['id' => $id]);
  $method = $method->fetch(PDO::FETCH_ASSOC);
  $extra = json_decode($method['method_extras'], true);
  $return = '<form class="form" action="' . site_url('admin/settings/payment-methods/edit/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Method name</label>' . "\r\n" . '  <input type="text" class="form-control" readonly value="' . $method['method_name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Visibility</label>' . "\r\n" . '<select class="form-control" name="method_type">' . "\r\n" . '  <option value="2"';

  if ($method['method_type'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Active</option>' . "\r\n" . '  <option value="1"';

  if ($method['method_type'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Inactive</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Visible name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="' . $extra['name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Minimum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="min" value="' . $extra['min'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Maximum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="max" value="' . $extra['max'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <hr>' . "\r\n" . '  <p class="card-description">' . "\r\n" . '<ul>' . "\r\n" . '<li>' . "\r\n" . ' API Callback address: <code>';
  $return .= site_url('payment/' . $method['method_get']);
  $return .= '</code>' . "\r\n" . '</li>' . "\r\n" . '</ul>' . "\r\n" . '  </p>' . "\r\n" . ' <hr>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Merchant id</label>' . "\r\n" . '  <input type="text" class="form-control" name="merchant_id" value="' . $extra['merchant_id'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Merchant key</label>' . "\r\n" . '  <input type="text" class="form-control" name="merchant_key" value="' . $extra['merchant_key'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Merchant salt</label>' . "\r\n" . '  <input type="text" class="form-control" name="merchant_salt" value="' . $extra['merchant_salt'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Commission, %</label>' . "\r\n" . '  <input type="text" class="form-control" name="fee" value="' . $extra['fee'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Currency</label>' . "\r\n" . '  <input type="text" class="form-control" name="currency" value="' . $extra['currency'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n\r\n" . '</div>' . "\r\n\r\n" . ' <div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update</button>' . "\r\n" . '  <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';
  echo json_encode(['content' => $return, 'title' => '']);

elseif (($action == 'edit_paymentmethod') && ($_POST['id'] == 'paytmqr')):
  $id = $_POST['id'];
  $method = $conn->prepare('SELECT * FROM payment_methods WHERE method_get=:id ');
  $method->execute(['id' => $id]);
  $method = $method->fetch(PDO::FETCH_ASSOC);
  $extra = json_decode($method['method_extras'], true);
  $return = '<form class="form" action="' . site_url('admin/settings/payment-methods/edit/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Method name</label>' . "\r\n" . '  <input type="text" class="form-control" readonly value="' . $method['method_name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Visibility</label>' . "\r\n" . '<select class="form-control" name="method_type">' . "\r\n" . '  <option value="2"';

  if ($method['method_type'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Active</option>' . "\r\n" . '  <option value="1"';

  if ($method['method_type'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Inactive</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Visible name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="' . $extra['name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Minimum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="min" value="' . $extra['min'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Maximum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="max" value="' . $extra['max'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <hr>' . "\r\n" . '  <p class="card-description">' . "\r\n" . '<ul>' . "\r\n" . '<li>' . "\r\n" . ' API callback address: <code>';
  $return .= site_url('payment/' . $method['method_get']);
  $return .= '</code>' . "\r\n" . '</li>' . "\r\n" . '</ul>' . "\r\n" . '  </p>' . "\r\n" . ' <hr>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Paytm QR Image Link</label>' . "\r\n" . '  <input type="text" class="form-control" name="merchant_key" value="' . $extra['merchant_key'] . '">' . "\r\n" . ' </div>' . "\r\n" . '<div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Merchant MID</label>' . "\r\n" . '  <input type="text" class="form-control" name="merchant_mid" value="' . $extra['merchant_mid'] . '">' . "\r\n" . ' </div>' . "\r\n" . '<div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Merchant Website</label>' . "\r\n" . '  <input type="text" class="form-control" name="merchant_website" value="' . $extra['merchant_website'] . '">' . "\r\n" . ' </div>' . "\r\n" . '<div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Commission, %</label>' . "\r\n" . '  <input type="text" class="form-control" name="fee" value="' . $extra['fee'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n\r\n" . '</div>' . "\r\n\r\n" . ' <div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update</button>' . "\r\n" . '  <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';
  echo json_encode(['content' => $return, 'title' => '']);


elseif ($action == "edit_paymentmethod" && $_POST["id"] == "paytr_havale"):
  $id = $_POST['id'];
  $method = $conn->prepare('SELECT * FROM payment_methods WHERE method_get=:id ');
  $method->execute(['id' => $id]);
  $method = $method->fetch(PDO::FETCH_ASSOC);
  $extra = json_decode($method['method_extras'], true);
  $return = '<form class="form" action="' . site_url('admin/settings/payment-methods/edit/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Method name</label>' . "\r\n" . '  <input type="text" class="form-control" readonly value="' . $method['method_name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Visibility</label>' . "\r\n" . '<select class="form-control" name="method_type">' . "\r\n" . '  <option value="2"';

  if ($method['method_type'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Active</option>' . "\r\n" . '  <option value="1"';

  if ($method['method_type'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Inactive</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Visible name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="' . $extra['name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Minimum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="min" value="' . $extra['min'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Maximum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="max" value="' . $extra['max'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <hr>' . "\r\n" . '  <p class="card-description">' . "\r\n" . '<ul>' . "\r\n" . '<li>' . "\r\n" . ' API callback address: <code>';
  $return .= site_url('payment/paytr');
  $return .= '</code>' . "\r\n" . '</li>' . "\r\n" . '</ul>' . "\r\n" . '  </p>' . "\r\n" . ' <hr>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Merchant id</label>' . "\r\n" . '  <input type="text" class="form-control" name="merchant_id" value="' . $extra['merchant_id'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Merchant key</label>' . "\r\n" . '  <input type="text" class="form-control" name="merchant_key" value="' . $extra['merchant_key'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Merchant salt</label>' . "\r\n" . '  <input type="text" class="form-control" name="merchant_salt" value="' . $extra['merchant_salt'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Commission, %</label>' . "\r\n" . '  <input type="text" class="form-control" name="fee" value="' . $extra['fee'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n\r\n" . '</div>' . "\r\n\r\n" . ' <div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update</button>' . "\r\n" . '  <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';
  echo json_encode(['content' => $return, 'title' => '']);

elseif ($action == "edit_paymentmethod" && $_POST["id"] == "paywant"):
  $id = $_POST['id'];
  $method = $conn->prepare('SELECT * FROM payment_methods WHERE method_get=:id ');
  $method->execute(['id' => $id]);
  $method = $method->fetch(PDO::FETCH_ASSOC);
  $extra = json_decode($method['method_extras'], true);
  $return = '<form class="form" action="' . site_url('admin/settings/payment-methods/edit/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Method name</label>' . "\r\n" . '  <input type="text" class="form-control" readonly value="' . $method['method_name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Visibility</label>' . "\r\n" . '<select class="form-control" name="method_type">' . "\r\n" . '  <option value="2"';

  if ($method['method_type'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Active</option>' . "\r\n" . '  <option value="1"';

  if ($method['method_type'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Inactive</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Visible name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="' . $extra['name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Minimum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="min" value="' . $extra['min'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Maximum Payment</label>' . "\r\n" . '  <input type="text" class="form-control" name="max" value="' . $extra['max'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <hr>' . "\r\n" . '  <p class="card-description">' . "\r\n" . '<ul>' . "\r\n" . '<li>' . "\r\n" . ' API callback address: <code>';
  $return .= site_url('payment/' . $method['method_get']);
  $return .= '</code>' . "\r\n" . '</li>' . "\r\n" . '</ul>' . "\r\n" . '  </p>' . "\r\n" . ' <hr>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">apiKey</label>' . "\r\n" . '  <input type="text" class="form-control" name="apiKey" value="' . $extra['apiKey'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">apiSecret</label>' . "\r\n" . '  <input type="text" class="form-control" name="apiSecret" value="' . $extra['apiSecret'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Commission, %</label>' . "\r\n" . '  <input type="text" class="form-control" name="fee" value="' . $extra['fee'] . '">' . "\r\n" . ' </div>' . "\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Currency</label>' . "\r\n" . '  <input type="text" class="form-control" name="currency" value="' . $extra['currency'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Paywant Commission</label>' . "\r\n" . '<select class="form-control" name="commissionType">' . "\r\n" . '  <option value="2"';

  if ($extra['commissionType'] == 2) {
    $return .= 'selected';
  }

  $return .= '>User should pay this commission</option>' . "\r\n" . '  <option value="1"';

  if ($extra['commissionType'] == 1) {
    $return .= 'selected';
  }

  $return .= '>User should not pay this commission</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label>Payment Methods</label>' . "\r\n" . '<div class="form-group col-md-12">' . "\r\n" . ' <div class="row">' . "\r\n" . '  <label class="checkbox-inline col-md-3">' . "\r\n" . '<input type="checkbox" class="access" name="payment_type[]" value="1"';

  if (in_array(1, $extra['payment_type'])) {
    $return .= ' checked';
  }

  $return .= '> Mobile Payment' . "\r\n" . '  </label>' . "\r\n" . '  <label class="checkbox-inline col-md-3">' . "\r\n" . '<input type="checkbox" class="access" name="payment_type[]" value="2"';

  if (in_array(2, $extra['payment_type'])) {
    $return .= ' checked';
  }

  $return .= '> Credit/Bank Card' . "\r\n" . '  </label>' . "\r\n" . '  <label class="checkbox-inline col-md-3">' . "\r\n" . '<input type="checkbox" class="access" name="payment_type[]" value="3"';

  if (in_array(3, $extra['payment_type'])) {
    $return .= ' checked';
  }

  $return .= '> Money Order / EFT' . "\r\n" . '  </label>' . "\r\n" . ' </div>' . "\r\n" . '</div>' . "\r\n" . '  </div>' . "\r\n\r\n\r\n" . '</div>' . "\r\n\r\n" . ' <div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update</button>' . "\r\n" . '  <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';
  echo json_encode(['content' => $return, 'title' => '']);

elseif ($action == "edit_paymentmethod" && $_POST["id"] == "havale-eft"):
  $id = $_POST['id'];
  $method = $conn->prepare('SELECT * FROM payment_methods WHERE method_get=:id ');
  $method->execute(['id' => $id]);
  $method = $method->fetch(PDO::FETCH_ASSOC);
  $extra = json_decode($method['method_extras'], true);
  $return = '<form class="form" action="' . site_url('admin/settings/payment-methods/edit/' . $id) . '" method="post" data-xhr="true">' . "\r\n\r\n" . '<div class="modal-body">' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Method name</label>' . "\r\n" . '  <input type="text" class="form-control" readonly value="' . $method['method_name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="service-mode__block">' . "\r\n" . '  <div class="form-group">' . "\r\n" . '  <label>Visibility</label>' . "\r\n" . '<select class="form-control" name="method_type">' . "\r\n" . '  <option value="2"';

  if ($method['method_type'] == 2) {
    $return .= 'selected';
  }

  $return .= '>Active</option>' . "\r\n" . '  <option value="1"';

  if ($method['method_type'] == 1) {
    $return .= 'selected';
  }

  $return .= '>Inactive</option>' . "\r\n" . '</select>' . "\r\n" . '  </div>' . "\r\n" . ' </div>' . "\r\n\r\n" . ' <div class="form-group">' . "\r\n" . '  <label class="form-group__service-name">Visible name</label>' . "\r\n" . '  <input type="text" class="form-control" name="name" value="' . $extra['name'] . '">' . "\r\n" . ' </div>' . "\r\n\r\n\r\n" . '</div>' . "\r\n\r\n" . ' <div class="modal-footer">' . "\r\n" . '  <button type="submit" class="btn btn-primary">Update</button>' . "\r\n" . '  <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>' . "\r\n" . ' </div>' . "\r\n" . ' </form>';
  echo json_encode(['content' => $return, 'title' => '']);






elseif ($action == "new_bankaccount"):
  $return = '<form class="form" action="' . site_url("admin/settings/bank-accounts/new") . '" method="post" data-xhr="true">
        <div class="modal-body">

          <div class="form-group">
            <label class="form-group">The name of the Bank</label>
            <input type="text" name="bank_name" class="form-control" value="">
          </div>

          <div class="form-group">
            <label class="form-group">Recipient name</label>
            <input type="text" name="bank_alici" class="form-control" value="">
          </div>

          <div class="form-group">
            <label class="form-group">Branch number</label>
            <input type="text" name="bank_sube" class="form-control" value="">
          </div>

          <div class="form-group">
            <label class="form-group">Account number</label>
            <input type="text" name="bank_hesap" class="form-control" value="">
          </div>

          <div class="form-group">
            <label class="form-group">IBAN</label>
            <input type="text" name="bank_iban" class="form-control" value="">
          </div>


        </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Add new bank account</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
          </div>
          </form>';
  echo json_encode(["content" => $return, "title" => "New bank account"]);
elseif ($action == "edit_bankaccount"):
  $id = $_POST["id"];
  $bank = $conn->prepare("SELECT * FROM bank_accounts WHERE id=:id ");
  $bank->execute(array("id" => $id));
  $bank = $bank->fetch(PDO::FETCH_ASSOC);
  $return = '<form class="form" action="' . site_url("admin/settings/bank-accounts/edit/" . $id) . '" method="post" data-xhr="true">
        <div class="modal-body">

          <div class="form-group">
            <label class="form-group">The name of the Bank</label>
            <input type="text" name="bank_name" class="form-control" value="' . $bank["bank_name"] . '">
          </div>

          <div class="form-group">
            <label class="form-group">Recipient name</label>
            <input type="text" name="bank_alici" class="form-control" value="' . $bank["bank_alici"] . '">
          </div>

          <div class="form-group">
            <label class="form-group">Branch number</label>
            <input type="text" name="bank_sube" class="form-control" value="' . $bank["bank_sube"] . '">
          </div>

          <div class="form-group">
            <label class="form-group">Account number</label>
            <input type="text" name="bank_hesap" class="form-control" value="' . $bank["bank_hesap"] . '">
          </div>

          <div class="form-group">
            <label class="form-group">IBAN</label>
            <input type="text" name="bank_iban" class="form-control" value="' . $bank["bank_iban"] . '">
          </div>


        </div>

        <div class="modal-footer">
          <a id="delete-row" data-url="' . site_url("admin/settings/bank-accounts/delete/" . $bank["id"]) . '" class="btn btn-danger pull-left">Remove account</a>
          <button type="submit" class="btn btn-primary">Update bank account</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
        </div>
        </form>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script>
        $("#delete-row").click(function(){
          var action = $(this).attr("data-url");
          swal({
            title: "Are you sure you want to delete?",
            text: "If you confirm, this content will be deleted, it may not be possible to restore it.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
            buttons: ["Cancel", "Yes, I am sure!"],
          })
          .then((willDelete) => {
            if (willDelete) {
              $.ajax({
                url:  action,
                type: "GET",
                dataType: "json",
                cache: false,
                contentType: false,
                processData: false
              })
              .done(function(result){
                if( result.s == "error" ){
                  var heading = "Unsuccessful";
                }else{
                  var heading = "Successful";
                }
                  $.toast({
  heading: heading,
  text: result.m,
  icon: result.s,
  loader: true,
  loaderBg: "#9EC600"
                  });
                  if (result.r!=null) {
if( result.time ==null ){ result.time = 3; }
setTimeout(function(){
  window.location.href  = result.r;
},result.time*);
                  }
              })
              .fail(function(){
                $.toast({
heading: "Unsuccessful",
text: "The request could not be fulfilled",
icon: "error",
loader: true,
loaderBg: "#9EC600"
                });
              });
              /* İçerik silinmesi onaylandı */
            } else {
              $.toast({
                  heading: "Unsuccessful",
                  text: "Request for deletion denied",
                  icon: "error",
                  loader: true,
                  loaderBg: "#9EC600"
              });
            }
          });
        });
        </script>
          </form>';
  echo json_encode(["content" => $return, "title" => "Update bank account"]);
elseif ($action == "new_paymentbonus"):
  $methodList = $conn->prepare("SELECT * FROM payment_methods WHERE id!='4' ");
  $methodList->execute(array());
  $methodList = $methodList->fetchAll(PDO::FETCH_ASSOC);
  $return = '<form class="form" action="' . site_url("admin/settings/payment-bonuses/new") . '" method="post" data-xhr="true">
        <div class="modal-body">

          <div class="form-group">
          <label>Method</label>
            <select class="form-control" name="method_type">';
  foreach ($methodList as $method):
    $return .= '<option value="' . $method["id"] . '">' . $method["method_name"] . '</option>';
  endforeach;
  $return .= '</select>
          </div>

          <div class="form-group">
            <label class="form-group">Bonus amount (%)</label>
            <input type="text" name="amount" class="form-control" value="">
          </div>

          <div class="form-group">
            <label class="form-group">Starts From Amount</label>
            <input type="text" name="from" class="form-control" value="">
          </div>

        </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Add new bonus</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
          </div>
          </form>';
  echo json_encode(["content" => $return, "title" => "Add new bonus"]);
elseif ($action == "edit_paymentbonus"):
  $id = $_POST["id"];
  $bonus = $conn->prepare("SELECT * FROM payments_bonus WHERE bonus_id=:id ");
  $bonus->execute(array("id" => $id));
  $bonus = $bonus->fetch(PDO::FETCH_ASSOC);
  $methodList = $conn->prepare("SELECT * FROM payment_methods  WHERE id!='4' ");
  $methodList->execute(array());
  $methodList = $methodList->fetchAll(PDO::FETCH_ASSOC);
  $return = '<form class="form" action="' . site_url("admin/settings/payment-bonuses/edit/" . $id) . '" method="post" data-xhr="true">
        <div class="modal-body">

          <div class="form-group">
          <label>Method</label>
            <select class="form-control" name="method_type">';
  foreach ($methodList as $method):
    $return .= '<option value="' . $method["id"] . '"';
    if ($bonus["bonus_method"] == $method["id"]):
      $return .= 'selected';
    endif;
    $return .= '>' . $method["method_name"] . '</option>';
  endforeach;
  $return .= '</select>
          </div>

          <div class="form-group">
            <label class="form-group">Bonus amount (%)</label>
            <input type="text" name="amount" class="form-control" value="' . $bonus["bonus_amount"] . '">
          </div>

          <div class="form-group">
            <label class="form-group">Starts From Amount</label>
            <input type="text" name="from" class="form-control" value="' . $bonus["bonus_from"] . '">
          </div>

        </div>

          <div class="modal-footer">
            <a id="delete-row" data-url="' . site_url("admin/settings/payment-bonuses/delete/" . $bonus["bonus_id"]) . '" class="btn btn-danger pull-left">Remove bonus</a>
            <button type="submit" class="btn btn-primary">Update bonus</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
          </div>
          </form>
          <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
          <script>
          $("#delete-row").click(function(){
            var action = $(this).attr("data-url");
            swal({
              title: "Are you sure you want to delete?",
              text: "If you confirm this content will be deleted, it may not be possible to restore it.",
              icon: "warning",
              buttons: true,
              dangerMode: true,
              buttons: ["Cancel", "Yes, I am sure!"],
            })
            .then((willDelete) => {
              if (willDelete) {
                $.ajax({
                  url:  action,
                  type: "GET",
                  dataType: "json",
                  cache: false,
                  contentType: false,
                  processData: false
                })
                .done(function(result){
                  if( result.s == "error" ){
var heading = "Unsuccessful";
                  }else{
var heading = "Successful";
                  }
$.toast({
    heading: heading,
    text: result.m,
    icon: result.s,
    loader: true,
    loaderBg: "#9EC600"
});
if (result.r!=null) {
  if( result.time ==null ){ result.time = 3; }
  setTimeout(function(){
    window.location.href  = result.r;
  },result.time*1000);
}
                })
                .fail(function(){
                  $.toast({
  heading: "Unsuccessful",
  text: "The request could not be fulfilled",
  icon: "error",
  loader: true,
  loaderBg: "#9EC600"
                  });
                });
                /* İçerik silinmesi onaylandı */
              } else {
                $.toast({
heading: "Unsuccessful",
text: "Request for deletion denied",
icon: "error",
loader: true,
loaderBg: "#9EC600"
                });
              }
            });
          });
          </script>
          ';
  echo json_encode(["content" => $return, "title" => "Update payment bonus"]);
elseif ($action == "new_provider"):
  $return .= "<form class=\"form\" action=\"" . site_url("admin/settings/providers/new") . "\" method=\"post\" data-xhr=\"true\">\r\n\r\n        <div class=\"modal-body\">\r\n\r\n          <div class=\"form-group\">\r\n            <label class=\"form-group__service-name\">API URL</label>\r\n            <input type=\"text\" class=\"form-control\" name=\"url\" value=\"\">\r\n          </div>\r\n          \r\n<div class=\"form-group\">\r\n            <label class=\"form-group__service-name\">API Key</label>\r\n            <input type=\"text\" class=\"form-control\" name=\"apikey\" value=\"\">\r\n          </div>\r\n\r\n ";
  $return .= '<div class="form-group">
<label class="form-group__service-name">Disable Sync</label>
<select name="api_sync" class="form-control">
<option value="0">Yes</option>
<option value="1" selected>No</option>
</select>
 </div>';
  $return .= '<hr><h2><center><span>Login Credentials</span></center></h2>

<div class="alert alert-info">

This will be used to fetch Refill Status from provider (/refill). Works only with Rental Panel.
</div>
<div class="form-group">
 <label class="form-group__service-name">Username</label>
<input type="text" class="form-control" name="credential_username">
</div>
<div class="form-group">
 <label class="form-group__service-name">Password</label>
<input type="password" class="form-control" name="credential_password">
</div>
';
  $return .= "</div>\r\n\r\n          <div class=\"modal-footer\">\r\n            <button type=\"submit\" class=\"btn btn-primary\">Add Provider</button>\r\n            <button type=\"button\" class=\"btn btn-danger\" data-dismiss=\"modal\">Cancel</button>\r\n          </div>\r\n          </form>";
  echo json_encode(["content" => $return, "title" => "Add New Provider"]);

elseif ($action == "edit_provider"):
  $id = $_POST["id"];
  $provider = $conn->prepare("SELECT * FROM service_api WHERE id=:id ");
  $provider->execute(array("id" => $id));
  $provider = $provider->fetch(PDO::FETCH_ASSOC);
  $login_credentials = json_decode($provider["api_login_credentials"], true);
  $return = '<form class="form" action="' . site_url("admin/settings/providers/edit/" . $id) . '" method="post" data-xhr="true">

        <div class="modal-body">

          <div class="form-group">
            <label class="form-group__service-name">Provider Name</label>
            <input type="text" class="form-control" name="name" value="' . $provider["api_name"] . '" readonly  >
          </div>

          

          <div class="form-group">
            <label class="form-group__service-name">API Key</label>
            <input type="text" class="form-control" name="apikey" value="' . obfuscate_provider_key($provider["api_key"]) . '">
          </div>';
  $return .= '<div class="form-group">

<label class="form-group__service-name">Disable Sync</label>

<select name="api_sync" class="form-control">
<option value="0"';
  if ($provider["api_sync"] == 0) {
    $return .= " selected";
  }
  $return .= '>Yes</option>
<option value="1"';
  if ($provider["api_sync"] == 1) {
    $return .= " selected";
  }
  $return .= '>No</option>
</select>
 </div>';

  $return .= '<hr><h2><center><span>Login Credentials</span></center></h2>
<div class="alert alert-info">
This will be used to fetch Refill Status from provider (/refill). Works only with Rental Panel.
</div>
<div class="form-group">
 <label class="form-group__service-name">Username</label>
<input type="text" class="form-control" name="credential_username" value="' . str_repeat("*", strlen($login_credentials["username"])) . '">
</div>
<div class="form-group">
 <label class="form-group__service-name">Password</label>
<input type="password" class="form-control" name="credential_password" value="' . $login_credentials["password"] . '">
</div>
';

  $return .= '<div class="modal-footer">
<button type="submit" class="btn btn-primary">Edit provider</button>
<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
</div>
</form>
<script>

$("#admin ").change(function(){
var type = $(this).val();
if( $panel["panel_type"] != "Child" ){
$("#admin_access").hide();
                } else{
                  $("#admin_access").show();
                }
            });
          </script>

                  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
          <script>
          $("#delete-row").click(function(){
            var action = $(this).attr("data-url");
            swal({
              title: "Are you sure you want to delete?",
              text: "If you confirm this content will be deleted, it may not be possible to restore it.",
              icon: "warning",
              buttons: true,
              dangerMode: true,
              buttons: ["Cancel", "Yes, I am sure!"],
            })
            .then((willDelete) => {
              if (willDelete) {
                $.ajax({
                  url:  action,
                  type: "GET",
                  dataType: "json",
                  cache: false,
                  contentType: false,
                  processData: false
                })
                .done(function(result){
if( result.s == "error" ){
var heading = "Unsuccessful";
}else{
var heading = "Successful";
}
$.toast({
    heading: heading,
    text: result.m,
    icon: result.s,
    loader: true,
    loaderBg: "#9EC600"
});
if (result.r!=null) {
  if( result.time ==null ){ result.time = 3; }
  setTimeout(function(){
    window.location.href  = result.r;
  },result.time*1000);
}
                })
                .fail(function(){
                  $.toast({
  heading: "Unsuccessful",
  text: "The request could not be fulfilled",
  icon: "error",
  loader: true,
  loaderBg: "#9EC600"
                  });
                });
                /* İçerik silinmesi onaylandı */
              } else {
                $.toast({
heading: "Unsuccessful",
text: "Request for deletion denied",
icon: "error",
loader: true,
loaderBg: "#9EC600"
                });
              }
            });
          });
          </script>
         ';
  echo json_encode(["content" => $return, "title" => "Edit provider (" . $provider["api_name"] . ") "]);

elseif ($action == "export_user"):
  $return = '<form class="form" action="' . site_url("admin/clients/export") . '" method="post">
        <div class="modal-body">

          <div class="service-mode__block">
            <div class="form-group">
            <label>Membership Status</label>
              <select class="form-control" name="client_status">
<option value="all">All members</option>
<option value="1">Inactive</option>
<option value="2">Active</option>
                </select>
            </div>
          </div>

          <div class="service-mode__block">
            <div class="form-group">
            <label>Email Status</label>
              <select class="form-control" name="email_status">
<option value="all">All members</option>
<option value="1">Unapproved</option>
<option value="2">Approved</option>
                </select>
            </div>
          </div>

          <div class="service-mode__block">
            <div class="form-group">
            <label>Format</label>
              <select class="form-control" name="format">
<option value="json">JSON</option>
                </select>
            </div>
          </div>

          <div class="service-mode__block">
            <div class="form-group">
            <label>Member information</label>
              <div class="form-group">
                  <label class="checkbox-inline">
<input type="checkbox" class="access" name="exportcolumn[client_id]" checked value="1"> ID
                  </label>
                  <label class="checkbox-inline">
<input type="checkbox" class="access" name="exportcolumn[email]" checked value="1"> Email
                  </label>
                  <label class="checkbox-inline">
<input type="checkbox" class="access" name="exportcolumn[name]" checked value="1"> Name surname
                  </label>
                  <label class="checkbox-inline">
<input type="checkbox" class="access" name="exportcolumn[username]" checked value="1"> Username
                  </label>
                  <label class="checkbox-inline">
<input type="checkbox" class="access" name="exportcolumn[telephone]" checked value="1"> Phone number
                  </label>
                  <label class="checkbox-inline">
<input type="checkbox" class="access" name="exportcolumn[balance]" checked value="1"> Balance
                  </label>
                  <label class="checkbox-inline">
<input type="checkbox" class="access" name="exportcolumn[spent]" checked value="1"> Spending
                  </label>
                  <label class="checkbox-inline">
<input type="checkbox" class="access" name="exportcolumn[register_date]" checked value="1"> Date of registration
                  </label>
                  <label class="checkbox-inline">
<input type="checkbox" class="access" name="exportcolumn[login_date]" checked value="1"> Last entry date
                  </label>
              </div>
            </div>
          </div>


        </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Backup users</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
          </div>
          </form>';
  echo json_encode(["content" => $return, "title" => "Backup users"]);
elseif ($action == "all_numbers"):
  $rows = $conn->prepare("SELECT * FROM clients");
  $rows->execute(array());
  $rows = $rows->fetchAll(PDO::FETCH_ASSOC);
  $numbers = "";
  $emails = "";
  foreach ($rows as $row):
    if ($row["telephone"]):
      $numbers .= $row["telephone"] . "\n";
    endif;
    $emails .= $row["email"] . "\n";
  endforeach;
  $return = '<form>
        <div class="modal-body">

          <div class="service-mode__block">
            <div class="form-group">
            <label>Member Phone Numbers</label>
              <textarea class="form-control" rows="8" readonly>' . $numbers . '</textarea>
            </div>
          </div>

          <div class="service-mode__block">
            <div class="form-group">
            <label>Member E-mail Addresses</label>
              <textarea class="form-control" rows="8" readonly>' . $emails . '</textarea>
            </div>
          </div>


        </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
          </div>
          </form>';
  echo json_encode(["content" => $return, "title" => "User information"]);


elseif ($action == "details"):

  $toplamkullanici = $conn->prepare("SELECT * FROM clients");
  $toplamkullanici->execute();
  $toplamkullanici = $toplamkullanici->rowCount();

  //Toplam Kullanılabilir Bakiye
  $query = $conn->query("SELECT sum(balance) as toplambakiye FROM clients")->fetch(PDO::FETCH_ASSOC);

  //Toplam Harcanan Bakiye
  $query2 = $conn->query("SELECT sum(order_charge) as order_charge FROM orders")->fetch(PDO::FETCH_ASSOC);

  //Negatif Bakiyeli Kullanıcılar
  $negatifbakiye = $conn->prepare("SELECT * FROM clients where balance < 0");
  $negatifbakiye->execute();
  $negatifbakiye = $negatifbakiye->rowCount();

  //Bakiyesi Olmayan
  $bakiyesiz = $conn->prepare("SELECT * FROM clients where balance = 0");
  $bakiyesiz->execute();
  $bakiyesiz = $bakiyesiz->rowCount();


  $return = '<form>
        <div class="modal-body">
		
          <div class="service-mode__block">
            <div class="form-group">
            <label>Total Users : ' . $toplamkullanici . '</label>
            </div>
          </div>
		  
		  <div class="service-mode__block">
            <div class="form-group">
            <label>Total Available Balance : ' . $query['toplambakiye'] . '</label>
            </div>
          </div>
		  
		  <div class="service-mode__block">
            <div class="form-group">
            <label>Total Spent Balance : ' . $query2['order_charge'] . '</label>
            </div>
          </div>
		  
		  <div class="service-mode__block">
            <div class="form-group">
            <label>Negative Balance User : ' . $negatifbakiye . '</label>
            </div>
          </div>
		  
		  <div class="service-mode__block">
            <div class="form-group">
            <label>Zero Balance User : ' . $bakiyesiz . '</label>
            </div>
          </div>
		  

        </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
          </div>
          </form>';
  echo json_encode(["content" => $return, "title" => "Detail"]);
elseif ($action == "price_user"):
  $id = $_POST["id"];
  $price = $conn->prepare("SELECT *,services.service_id as serviceid,services.service_price as price,clients_price.service_price as clientprice FROM services LEFT JOIN clients_price ON clients_price.service_id=services.service_id && clients_price.client_id=:id ");
  $price->execute(array("id" => $id));
  $price = $price->fetchAll(PDO::FETCH_ASSOC);
  $return = '<form class="form" action="' . site_url("admin/clients/price/" . $id) . '" method="post" data-xhr="true">
        <div class="modal-body">

        <div class="services-import__body">
               <div>
                  <div class="services-import__list-wrap services-import__list-active">
 <div class="services-import__scroll-wrap">
    <span>
         <div class="services-import__packages">
            <ul>';
  foreach ($price as $row):
    $return .= '<li id="service-' . $row["serviceid"] . '">
                 <label>
' . $row["serviceid"] . ' - ' . $row["service_name"] . '
<span class="services-import__packages-price-edit" >
   <div class="services-import__packages-price-lock"  data-id="servicedelete-' . $row["serviceid"] . '" data-service="' . $row["serviceid"] . '">
     <span class="fa fa-trash"></span>
   </div>
   <input type="text" class="services-import__price" name="price[' . $row["serviceid"] . ']" value="' . $row["clientprice"] . '">
   <span class="services-import__provider-price">' . $row["price"] . '</span>
</span>
                 </label>
                </li>';
  endforeach;
  $return .= '</ul>
         </div>
      </span></div>
                  </div>
               </div>
            </div>
            <script>

              $(\'[data-id^="servicedelete-"]\').click(function(){
                var id        = $(this).attr("data-service");
                $("[name=\'price["+id+"]\']").val("");
                //$("ul > li#service-"+id).remove();
              });

            </script>

        </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Update settings</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
          </div>
          </form>';
  echo json_encode(["content" => $return, "title" => "Special Pricing"]);
elseif ($action == "order_errors"):
  $id = $_POST["id"];
  $row = $conn->prepare("SELECT * FROM orders INNER JOIN service_api ON service_api.id=orders.order_api WHERE order_id=:id ");
  $row->execute(array("id" => $id));
  $row = $row->fetch(PDO::FETCH_ASSOC);
  $errors = json_decode($row["order_error"]);
  $return = '<form>
        <div class="modal-body">

          <div class="service-mode__block">
            <div class="form-group">
            <h4>' . $row["api_name"] . '</h4>
              <textarea class="form-control" rows="8" readonly>';
  $return .= print_r($errors, true);
  $return .= '</textarea>
            </div>
          </div>


        </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
          </div>
          </form>';
  echo json_encode(["content" => $return, "title" => "Error details (ID: " . $row["order_id"] . ") "]);


elseif ($action == "order_details"):
  $id = $_POST["id"];
  $row = $conn->prepare("SELECT * FROM orders INNER JOIN service_api ON service_api.id=orders.order_api WHERE order_id=:id ");
  $row->execute(array("id" => $id));
  $row = $row->fetch(PDO::FETCH_ASSOC);
  $detail = json_decode($row["order_detail"]);
  $return = '<form>
        <div class="modal-body">

          <div class="service-mode__block">
            <div class="form-group">
            <h4>' . $row["api_name"] . '</h4>
              <textarea class="form-control" rows="8" readonly>';
  $return .= print_r($detail, true);
  $return .= '</textarea>
            </div>
          </div>

          <div class="service-mode__block">
            <div class="form-group">
            <label>Order ID</label>
              <input class="form-control" value="' . $row["api_orderid"] . '" readonly>
            </div>
          </div>

          <div class="service-mode__block">
            <div class="form-group">
            <label>Last update</label>
              <input class="form-control" value="' . $row["last_check"] . '" readonly>
            </div>
          </div>


        </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
          </div>
          </form>';
  echo json_encode(["content" => $return, "title" => "Order Details (ID: " . $row["order_id"] . ") "]);

elseif ($action == "earn_note"):
  $id = $_POST["id"];
  $earn = $conn->prepare("SELECT * FROM earn WHERE earn_id=:id ");
  $earn->execute(array("id" => $id));
  $earn = $earn->fetch(PDO::FETCH_ASSOC);
  $earn_note = json_decode($earn["earn_note"]);
  $return = '<form class="form" action="' . site_url("admin/earn/set_earnnote/" . $id) . '" method="post">
        <div class="modal-body">

          <div class="service-mode__block">
            <div class="form-group">
            <label>Promotion Note(ex:-20rs funds granted)</label>
              <input class="form-control" value="' . $earn["earn_note"] . '" name="note">
            </div>
          </div>


        </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Update settings</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
          </div>
          </form>';
  echo json_encode(["content" => $return, "title" => "Promotion details (ID: " . $earn["earn_id"] . ") "]);



elseif ($action == "order_orderurl"):
  $id = $_POST["id"];
  $row = $conn->prepare("SELECT * FROM orders WHERE order_id=:id ");
  $row->execute(array("id" => $id));
  $row = $row->fetch(PDO::FETCH_ASSOC);
  $detail = json_decode($row["order_detail"]);
  $return = '<form class="form" action="' . site_url("admin/orders/set_orderurl/" . $id) . '" method="post">
        <div class="modal-body">

          <div class="service-mode__block">
            <div class="form-group">
            <label>Order Link</label>
              <input class="form-control" value="' . $row["order_url"] . '" name="url">
            </div>
          </div>


        </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Update settings</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
          </div>
          </form>';
  echo json_encode(["content" => $return, "title" => "Order details (ID: " . $row["order_id"] . ") "]);
elseif ($action == "order_startcount"):
  $id = $_POST["id"];
  $row = $conn->prepare("SELECT * FROM orders WHERE order_id=:id ");
  $row->execute(array("id" => $id));
  $row = $row->fetch(PDO::FETCH_ASSOC);
  $detail = json_decode($row["order_detail"]);
  $return = '<form class="form" action="' . site_url("admin/orders/set_startcount/" . $id) . '" method="post">
        <div class="modal-body">

          <div class="service-mode__block">
            <div class="form-group">
            <label>Starting number</label>
              <input class="form-control" value="' . $row["order_start"] . '" name="start">
            </div>
          </div>


        </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Update settings</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
          </div>
          </form>';
  echo json_encode(["content" => $return, "title" => "Order details (ID: " . $row["order_id"] . ") "]);
elseif ($action == "order_partial"):
  $id = $_POST["id"];
  $row = $conn->prepare("SELECT * FROM orders WHERE order_id=:id ");
  $row->execute(array("id" => $id));
  $row = $row->fetch(PDO::FETCH_ASSOC);
  $detail = json_decode($row["order_detail"]);
  $return = '<form class="form" action="' . site_url("admin/orders/set_partial/" . $id) . '" method="post" data-xhr="true">
        <div class="modal-body">

          <div class="service-mode__block">
            <div class="form-group">
            <label>Not going amount</label>
              <input class="form-control" name="remains">
            </div>
          </div>

        </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Update settings</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
          </div>
          </form>';
  echo json_encode(["content" => $return, "title" => "Order details (ID: " . $row["order_id"] . ") "]);
elseif ($action == "subscriptions_expiry"):
  $id = $_POST["id"];
  $row = $conn->prepare("SELECT * FROM orders WHERE order_id=:id ");
  $row->execute(array("id" => $id));
  $row = $row->fetch(PDO::FETCH_ASSOC);
  $detail = json_decode($row["order_detail"]);
  $return = '<form class="form" action="' . site_url("admin/subscriptions/set_expiry/" . $id) . '" method="post">
        <div class="modal-body">

          <div class="service-mode__block">
            <div class="form-group">
            <label>Starting number</label>
              <input class="form-control datetime" value="';
  if ($row["subscriptions_expiry"] != "1970-01-01"):
    $return .= date("d/m/Y", strtotime($row["subscriptions_expiry"]));
  endif;
  $return .= '" name="expiry">
            </div>
          </div>


        </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Update settings</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
          </div>
          </form>
          <link rel="stylesheet" type="text/css" href="' . site_url("public/") . 'datepicker/css/bootstrap-datepicker3.min.css">
          <script type="text/javascript" src="' . site_url("public/") . 'datepicker/js/bootstrap-datepicker.min.js"></script>
          <script type="text/javascript" src="' . site_url("public/") . 'datepicker/locales/bootstrap-datepicker.tr.min.js"></script>
          ';
  echo json_encode(["content" => $return, "title" => "Subscription end date (ID: " . $row["order_id"] . ") "]);
elseif ($action == "payment_bankedit"):
  $id = $_POST["id"];
  $payment = $conn->prepare("SELECT * FROM payments INNER JOIN bank_accounts ON bank_accounts.id=payments.payment_bank INNER JOIN clients ON clients.client_id=payments.client_id WHERE payments.payment_id=:id");
  $payment->execute(array("id" => $id));
  $payment = $payment->fetch(PDO::FETCH_ASSOC);
  $bank = $conn->prepare("SELECT * FROM bank_accounts ");
  $bank->execute();
  $bank = $bank->fetchAll(PDO::FETCH_ASSOC);
  $return = '<form class="form" action="' . site_url("admin/payments/edit-bank/" . $id) . '" method="post" data-xhr="true">

        <div class="modal-body">

          <div class="service-mode__block">
            <div class="form-group">
            <label>The paid bank</label>
              <select class="form-control" name="bank">';
  foreach ($bank as $banka):
    $return .= '<option value="' . $banka["id"] . '"';
    if ($payment["payment_bank"] == $banka["id"]):
      $return .= 'selected';
    endif;
    $return .= '>' . $banka["bank_name"] . '</option>';
  endforeach;
  $return .= '</select>
            </div>
          </div>

          <div class="service-mode__block">
            <div class="form-group">
            <label>Payment status</label>
              <select class="form-control" ';
  if ($payment["payment_status"] == 3):
    $return .= 'disabled';
  endif;
  $return .= ' name="status">
<option value="1"';
  if ($payment["payment_status"] == 1):
    $return .= 'selected';
  endif;
  $return .= '>Pending</option>
<option value="2"';
  if ($payment["payment_status"] == 2):
    $return .= 'selected';
  endif;
  $return .= '>Cancel</option>
<option value="3"';
  if ($payment["payment_status"] == 3):
    $return .= 'selected';
  endif;
  $return .= '>Approved</option>
                </select>
            </div>
          </div>

          <div class="form-group">
            <label class="form-group__service-name">Note</label>
            <input type="text" class="form-control" name="note" value="' . $payment["payment_note"] . '">
          </div>


        </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Update settings</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
          </div>
          </form>';
  echo json_encode(["content" => $return, "title" => "Arrange a bank payment (ID: " . $id . ") "]);
elseif ($action == "payment_banknew"):
  $bank = $conn->prepare("SELECT * FROM bank_accounts ");
  $bank->execute();
  $bank = $bank->fetchAll(PDO::FETCH_ASSOC);
  $return = '<form class="form" action="' . site_url("admin/payments/new-bank/") . '" method="post" data-xhr="true">

        <div class="modal-body">


          <div class="form-group">
            <label class="form-group__service-name">Username</label>
            <input type="text" class="form-control" name="username" value="">
          </div>

          <div class="form-group">
            <label class="form-group__service-name">Amount</label>
            <input type="text" class="form-control" name="amount" value="">
          </div>

          <div class="service-mode__block">
            <div class="form-group">
            <label>The paid bank</label>
              <select class="form-control" name="bank">';
  foreach ($bank as $banka):
    $return .= '<option value="' . $banka["id"] . '">' . $banka["bank_name"] . '</option>';
  endforeach;
  $return .= '</select>
            </div>
          </div>


          <div class="form-group">
            <label class="form-group__service-name">Note</label>
            <input type="text" class="form-control" name="note" value="">
          </div>


        </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Add payment</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
          </div>
          </form>';
  echo json_encode(["content" => $return, "title" => "Add bank payment "]);

elseif ($action == "edit_w"):
  $id = $_POST["id"];
  $integration = $conn->prepare("SELECT * FROM integrations WHERE id=:id ");
  $integration->execute(array("id" => "1"));
  $integration = $integration->fetch(PDO::FETCH_ASSOC);
  $return = '<form class="form" action="' . site_url("admin/settings/integrations/edit/1") . '" method="post" data-xhr="true">
        <div class="modal-body">

          <div class="form-group">
            <label class="form-group__service-name">WhatsApp Number</label>
          <input class="form-control" value="' . $integration["w_num"] . '" name="w_num">
Omit any zeroes, brackets, or dashes when adding the phone number in international format. Example: 1XXXXXXXXXX
          </div> 

<div class="service-mode__block">
            <div class="form-group">
            <label>Position</label>
              <select class="form-control" ';
  if ($integration["w_position"] == 1):
    $return .= 'Right';
  endif;
  $return .= ' name="w_position">
<option value="1"';
  if ($integration["w_position"] == 1):
    $return .= 'selected';
  endif;
  $return .= '>Right</option>
<option value="2"';
  if ($integration["w_position"] == 2):
    $return .= 'selected';
  endif;
  $return .= '>Left</option>
                </select>
            </div>
          </div>

          <div class="service-mode__block">
            <div class="form-group">
            <label>Status</label>
              <select class="form-control" ';
  if ($integration["w_status"] == 1):
    $return .= 'Enabled';
  endif;
  $return .= ' name="w_status">
<option value="1"';
  if ($integration["w_status"] == 1):
    $return .= 'selected';
  endif;
  $return .= '>Enabled</option>
<option value="2"';
  if ($integration["w_status"] == 2):
    $return .= 'selected';
  endif;
  $return .= '>Disabled</option>
                </select>
            </div>
          </div>

          <div class="service-mode__block">
            <div class="form-group">
            <label>Visibility</label>
              <select class="form-control" ';
  if ($integration["w_type"] == 1):
    $return .= 'All';
  endif;
  $return .= ' name="w_type">
<option value="1"';
  if ($integration["w_type"] == 1):
    $return .= 'selected';
  endif;
  $return .= '>All</option>
<option value="2"';
  if ($integration["w_type"] == 2):
    $return .= 'selected';
  endif;
  $return .= '>Public</option>
<option value="3"';
  if ($integration["w_type"] == 3):
    $return .= 'selected';
  endif;
  $return .= '>Internal</option>
                </select>
            </div>
          </div>

          


        </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
          </div>
          </form>';
  echo json_encode(["content" => $return, "title" => "Button"]);


elseif ($action == "payment_edit"):
  $id = $_POST["id"];
  $payment = $conn->prepare("SELECT * FROM payments INNER JOIN clients ON clients.client_id=payments.client_id WHERE payments.payment_id=:id");
  $payment->execute(array("id" => $id));
  $payment = $payment->fetch(PDO::FETCH_ASSOC);
  $methods = $conn->prepare("SELECT * FROM payment_methods WHERE id!='4' ");
  $methods->execute();
  $methods = $methods->fetchAll(PDO::FETCH_ASSOC);
  $return = '<form class="form" action="' . site_url("admin/payments/edit-online/" . $id) . '" method="post" data-xhr="true">

        <div class="modal-body">

          <div class="service-mode__block">
            <div class="form-group">
            <label>Payment method</label>
              <select class="form-control" name="method">';
  foreach ($methods as $method):
    $return .= '<option value="' . $method["id"] . '"';
    if ($payment["payment_method"] == $method["id"]):
      $return .= 'selected';
    endif;
    $return .= '>' . $method["method_name"] . '</option>';
  endforeach;
  $return .= '</select>
            </div>
          </div>

          <div class="form-group">
            <label class="form-group__service-name">Note</label>
            <input type="text" class="form-control" name="note" value="' . $payment["payment_note"] . '">
          </div>


        </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Update settings</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
          </div>
          </form>';
  echo json_encode(["content" => $return, "title" => "Edit online payment (ID: " . $id . ") "]);
elseif ($action == "reffered_users"):
  $ref_code = $_POST["id"];

  $clients = $conn->prepare("SELECT * FROM clients WHERE ref_by=:ref_by");
  $clients->execute(array("ref_by" => $ref_code));
  $clients = $clients->fetchAll(PDO::FETCH_ASSOC);
  $return = '<form>
      <div class="modal-body">

        <div class="service-mode__block">
          <div class="form-group">

            <table  class="table" id="table1" style="overflow:auto;"> <thead>
            <th>Username</th><th>Balance</th><th>Spent</th><th>Actions </th>
            </thead>';
  foreach ($clients as $client):
    // $return.=  $client['username'] .' , ';
    $return .= '<tr>
                <td>' . $client['username'] . '</td>
                <td>' . $client['balance'] . '</td>
                <td>' . $client['spent'] . '</td>
                <td><a href="admin/referrals?ref_code=' . $ref_code . '&remove=' . $client['client_id'] . '">Remove</a></td>
              </tr>';
  endforeach;

  // <textarea class="form-control" rows="8" readonly> Usernames :
  $return .= '</table>
          </div>
        </div>
      </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
        </div>
        </form>';

  echo json_encode(["content" => $return, "title" => "Reffered Users by " . $ref_code . " Code"]);


elseif ($action == "payment_new"):
  $methods = $conn->prepare("SELECT * FROM payment_methods WHERE id!='4' ");
  $methods->execute();
  $methods = $methods->fetchAll(PDO::FETCH_ASSOC);
  $return = '<form class="form" action="' . site_url("admin/payments/new-online") . '" method="post" data-xhr="true">

        <div class="modal-body">

          <div class="form-group">
            <label class="form-group__service-name">Username</label>
            <input type="text" class="form-control" name="username" value="">
          </div>

          <div class="form-group">
            <label class="form-group__service-name">Amount</label>
            <input type="text" class="form-control" name="amount" value="">
          </div>

          <div class="service-mode__block">
            <div class="form-group">
            <label>Add/Remove</label>
              <select class="form-control" name="add-remove">
                <option value="add">Add</option>
                <option value="remove">Remove</option>
            </select>
            </div>
          </div>

          <div class="service-mode__block">
            <div class="form-group">
            <label>Payment method</label>
              <select class="form-control" name="method">';
  foreach ($methods as $method):
    $return .= '<option value="' . $method["id"] . '">' . $method["method_name"] . '</option>';
  endforeach;
  $return .= '</select>
            </div>
          </div>

          <div class="form-group">
            <label class="form-group__service-name">Note</label>
            <input type="text" class="form-control" name="note" value="No">
          </div>


        </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Update</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
          </div>
          </form>';
  echo json_encode(["content" => $return, "title" => " Add payment"]);
elseif ($action == "payment_detail"):
  $id = $_POST["id"];
  $row = $conn->prepare("SELECT * FROM payments WHERE payment_id=:id ");
  $row->execute(array("id" => $id));
  $row = $row->fetch(PDO::FETCH_ASSOC);
  $detail = json_decode($row["payment_extra"]);
  $return = '<form>
        <div class="modal-body">

          <div class="service-mode__block">
            <div class="form-group">
            <label>Payment info</label>
              <textarea class="form-control" rows="8" readonly>';
  $return .= print_r($detail, true);
  $return .= '</textarea>
            </div>
          </div>

          <div class="service-mode__block">
            <div class="form-group">
            <label>Last update</label>
              <input class="form-control" value="' . $row["payment_update_date"] . '" readonly>
            </div>
          </div>


        </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
          </div>
          </form>';
  echo json_encode(["content" => $return, "title" => "Payment details (ID: " . $row["payment_id"] . ") "]);
elseif ($action == "add_currency"):

  $return = '<form class="form" action="' . site_url("admin/settings/currency/add") . '" method="post" data-xhr="true">

        <div class="modal-body">

          <div class="form-group">
            <label class="form-group__service-name">Currency symbol</label>
            <input type="text" class="form-control" name="symbol" value="">
          </div>

          
          <div class="form-group">
            <label class="form-group__service-name">Currency Name</label>
            <input type="text" class="form-control" name="name" value="">
          </div>

          <div class="form-group">
            <label class="form-group__service-name">1 Usd = </label>
            <input type="text" class="form-control" name="value" value="">
          </div>
       
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Add Currency</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
          </div>
          </form>';
  echo json_encode(["content" => $return, "title" => "Add Currency"]);
elseif ($action == "edit_admin_password"):
  $id = $_POST["id"];

  if ($id == 1 && $admin["admin_id"] != 1):

    $id = $id + 1;
  endif;
  $adminData = $conn->prepare("SELECT * FROM admins WHERE admin_id=:id");
  $adminData->execute(array("id" => $id));
  $adminData = $adminData->fetch(PDO::FETCH_ASSOC);

  $return = '<form class="form" action="' . site_url("admin/manager/admins/password/" . $adminData["admin_id"]) . '

  " method="post" data-xhr="true">
  <div class="modal-body">
<div class="form-group">
<label class="form-group__service-name">Admin Password</label>
<input type="text" class="form-control" name="password">
</div>
<div class="modal-footer">
<button type="submit" class="btn btn-primary">Update</button>
<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
</div></form>';

  echo json_encode(["content" => $return, "title" => "Edit Admin Password"]);

elseif ($action == "edit_admin"):
  $id = $_POST["id"];
  if ($id == 1 && $admin["admin_id"] != 1):
    $id = $id + 1;
  endif;
  $adminData = $conn->prepare("SELECT * FROM admins WHERE admin_id=:id");
  $adminData->execute(array("id" => $id));
  $adminData = $adminData->fetch(PDO::FETCH_ASSOC);
  $access = json_decode($adminData["access"], true);
  $permission_array = json_decode($settings["permissions"], true);



  $return = '<form class="form" action="' . site_url("admin/manager/admins/edit/" . $adminData["admin_id"]) . '
  " method="post" data-xhr="true">
            <div class="modal-body">
              <div class="form-group">
                <label class="form-group__service-name">Name</label>
                <input type="text" class="form-control" name="name" value="' . $adminData["admin_name"] . '">
              </div>
    
              <div class="form-group">
                <label>Email</label>
                <input type="text" name="email" value="' . $adminData["admin_email"] . '" class="form-control">
              </div>
    
              <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="' . $adminData["username"] . '">
              </div>
    
              <div class="form-group">
                <label>Telephone</label>
                <input type="text" name="telephone" class="form-control" value="' . $adminData["telephone"] . '">
              </div>

              <div class="service-mode__block">
              <div class="form-group">
              <label>Account Status</label>
                <select class="form-control" name="client_type">
  <option value="1"';
  if ($adminData["client_type"] == 1):
    $return .= 'selected';
  endif;
  $return .= '>Inactive</option>
  <option value="2"';
  if ($adminData["client_type"] == 2):
    $return .= 'selected';
  endif;
  $return .= '>Active</option>
                  </select>
              </div>
            </div>

              <div class="service-mode__block">
              <div class="form-group">
              <label>Permissions</label>
              <select  id="selectpicker" data-live-search="true" multiple class="form-control" name="admin_access[]">
            ';
  foreach ($permission_array as $group => $array):
    $return .= ' <optgroup label="' . strtoupper($group) . '">';
    foreach ($array as $perm):
      $return .= '<option';
      $perm_value = $perm["value"];
      if ($access[$perm_value] == 1):
        $return .= ' selected ';
      endif;
      $return .= ' value="' . $perm["value"] . '">' . $perm["name"] . '</option>';
    endforeach;
  endforeach;
  $return .= '</optgroup> </select>
               </div>
            </div>
    
            </div>
    
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Update</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
              </div>
              </form>
     
              <script>
  
  
              $("#selectpicker").selectpicker({
                "actionsBox" : true,
              });
              $("#selectpicker1").selectpicker();
  
          
              </script>
              ';

  echo json_encode(["content" => $return, "title" => "Edit Admin"]);


elseif ($action == "add_admin" && $admin["admin_type"] == 3):

  $permission_array = json_decode($settings["permissions"], true);

  $return = '<form class="form" action="' . site_url("admin/manager/admins/new/") . '" 
  method="post" data-xhr="true">
              <div class="modal-body">
                <div class="form-group">
                  <label class="form-group__service-name">Name</label>
                  <input type="text" class="form-control" name="name" >
                </div>
      
                <div class="form-group">
                  <label>Email</label>
                  <input type="text" name="email" class="form-control">
                </div>
      
                <div class="form-group">
                  <label>Username</label>
                  <input type="text" name="username" class="form-control" >
                </div>
      

                <div class="form-group">
                <label>Password</label>
                <input type="text" name="password" class="form-control" >
              </div>
    
                <div class="form-group">
                  <label>Telephone</label>
                  <input type="text" name="telephone" class="form-control" >
                </div>
  
                <div class="service-mode__block">
                <div class="form-group">
                <label>Account Status</label>
                  <select class="form-control" name="client_type">
                  <option value="2">Active</option>
    <option value="1">Inactive</option>
   
</select>
                </div>
              </div>
  
                <div class="service-mode__block">
                <div class="form-group">
                <label>Permissions</label>
                <select  id="selectpicker" data-live-search="true" multiple class="form-control" name="admin_access[]">
              ';
  foreach ($permission_array as $group => $array):
    $return .= ' <optgroup label="' . strtoupper($group) . '">';
    foreach ($array as $perm):
      $return .= '<option value="' . $perm["value"] . '">' . $perm["name"] . '</option>';
    endforeach;
  endforeach;
  $return .= '</optgroup> </select>
                 </div>
              </div>
      
              </div>
      
                <div class="modal-footer">
                  <button type="submit" class="btn btn-primary">Add</button>
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                </div>
                </form>
       
                <script>
    
    
                $("#selectpicker").selectpicker({
                  "actionsBox" : true,
                });
                $("#selectpicker1").selectpicker();
    
            
                </script>
                ';
  echo json_encode(["content" => $return, "title" => "Add Admin"]);

elseif ($action == "edit_currency"):
  $id = $_POST["id"];
  $provider = $conn->prepare("SELECT * FROM currency WHERE id=:id ");
  $provider->execute(array("id" => $id));
  $provider = $provider->fetch(PDO::FETCH_ASSOC);
  $return = '<form class="form" action="' . site_url("admin/settings/currency/edit/" . $id) . '" method="post" data-xhr="true">

        <div class="modal-body">

          <div class="form-group">
            <label class="form-group__service-name">Currency Name</label>
            <input type="text" class="form-control" name="name" value="' . $provider["name"] . '">
          </div>
          <div class="form-group">
            <label class="form-group__service-name">Currency Symbol</label>
            <input type="text" class="form-control" name="symbol" value="' . $provider["symbol"] . '">
          </div>
<div class="form-group">
            <label class="form-group__service-name">Exchange Rates</label>
            <input type="text" class="form-control" name="currencyvalue" value="' . $provider["value"] . '">
          </div> 


<div class="service-mode__block">
                <div class="form-group">
                <label>Currency Status</label>
                  <select class="form-control" name="status">
  <option value="1"';
  if ($provider["status"] == 1):
    $return .= 'selected';
  endif;
  $return .= '>Enabled</option>
  <option value="2"';
  if ($provider["status"] == 2):
    $return .= 'selected';
  endif;
  $return .= '>Disabled</option>
                  </select>
                </div>
              </div>


          </div>
          
          

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>

         </form>';
  echo json_encode(["content" => $return, "title" => "Edit currency (" . $provider["name"] . ") "]);



endif;
