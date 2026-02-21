<?php
header_remove("X-Powered-By");
define("BASEPATH", TRUE);
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php';
$mail = new PHPMailer(true);
require __DIR__ . '/app/init.php';
$first_route = explode('?', $_SERVER["REQUEST_URI"]);
$gets = explode('&', $first_route[1]);
foreach ($gets as $get) {
  $get = explode('=', $get);
  $_GET[$get[0]] = $get[1];
}

//require __DIR__.'/logger.php';


$routes = array_filter(explode('/', $first_route[0]));

if (SUBFOLDER === true) {
  array_shift($routes);
  $route = $routes;
} else {
  foreach ($routes as $index => $value):
    $route[$index - 1] = $value;
  endforeach;
}
$panel_orders = countRow(["table" => "orders"]);

if ($_GET["lang"] && $user['auth'] != 1):
  include 'app/language/list.php';
  if (countRow(["table" => "languages", "where" => ["language_type" => 2, "language_code" => $_GET["lang"]]])):
    unset($_SESSION["lang"]);
    $_SESSION["lang"] = $_GET["lang"];
    include 'app/language/' . $_GET["lang"] . '.php';
  else:
    $_SESSION["lang"] = $_GET["lang"];
    include 'app/language/' . $_GET["lang"] . '.php';
  endif;
  $selectedLang = $_SESSION["lang"];
  header("Location:" . site_url());
else:
  if ($_SESSION["lang"] && $user['auth'] != 1):
    $language = $_SESSION["lang"];
  elseif ($user['auth'] != 1):
    $language = $conn->prepare("SELECT * FROM languages WHERE default_language=:default ");
    $language->execute(array("default" => 1));
    $language = $language->fetch(PDO::FETCH_ASSOC);
    $language = $language["language_code"];
  else:
    if (getRow(["table" => "languages", "where" => ["language_code" => $user["lang"]]])):
      $language = $user["lang"];
    else:
      $language = $conn->prepare("SELECT * FROM languages WHERE default_language=:default ");
      $language->execute(array("default" => 1));
      $language = $language->fetch(PDO::FETCH_ASSOC);
      $language = $language["language_code"];
    endif;
  endif;
  include 'app/language/' . $language . '.php';
  $selectedLang = $language;
endif;

if($selectedLang == "ar"){
  $body_rtl = "body-rtl";
  $table_rtl = "table-rtl";
} else {
  $body_rtl = "";
  $table_rtl = "";
}

$selectedLangName = $conn->prepare("SELECT language_name FROM languages WHERE language_code=:code");
$selectedLangName->execute([
  "code" => $selectedLang
]);
$selectedLangName = $selectedLangName->fetchColumn();

if (!isset($route[0]) && $_SESSION["msmbilisim_userlogin"] == true) {
  $route[0] = "neworder";
  $routeType = 0;
} elseif (!isset($route[0]) && $_SESSION["msmbilisim_userlogin"] == false) {
  $route[0] = "auth";
  $routeType = 1;
} elseif ($route[0] == "auth" && $_SESSION["msmbilisim_userlogin"] == false) {
  $routeType = 1;
} else {
  $routeType = 0;
}



$pageshh = $_SERVER["REQUEST_URI"];
$pageshh2 = $_SERVER['HTTP_HOST'];

$link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ?

  "https" : "http") . "://" . $_SERVER['HTTP_HOST'] .

  $_SERVER['REQUEST_URI'];



$pageurl = $link;




if (!file_exists(controller($route[0]))) {
  $route[0] = "404";
}
if (route(0) != "frozen" && route(0) != "dreampanelworkall" && $panel["panel_status"] == "Suspended"):
  header("Location:" . site_url('frozen'));
endif;
if (route(0) == "frozen" && $panel["panel_status"] == "Active"):
  header("Location:" . site_url(''));
endif;
if (route(0) != "frozen" && route(0) != "dreampanelworkall" && $panel["panel_status"] == "Frozen"):
  header("Location:" . site_url('frozen'));
endif;




if (route(0) != "admin" && $settings["site_maintenance"] == 1):
  include 'app/views/maintenance.php';
  exit();
endif;

if (route(0) != "admin" && $settings["site_maintenance"] == 1):
  include 'app/views/maintenance.php';
  exit();
endif;


$paidRent = $conn->prepare("SELECT paidRent FROM admin_constants WHERE id=?");
$paidRent->execute([
  1
]);
$paidRent = $paidRent->fetchColumn();

if (!$paidRent && route(0) != "admin") {
  include 'app/views/suspended.php';
  exit();
}


if ($settings["service_list"] == 2):
  $serviceList = 1;
endif;

require controller($route[0]);
if ($settings["snow_effect"] == 1) {
  $snow_effect = true;
}
if ($settings["recaptcha"] == 1) {
  $captcha = false;
} elseif (($settings["recaptcha"] == 2 && (route(0) == "signup" || route(0) == "resetpassword")) || $_SESSION["recaptcha"]) {
  $captcha = true;
}


if ($settings["resetpass_page"] == 2) {
  $resetPage = true;
}

if ($settings["register_page"] == 2) {
  $Signup = true;
}

if ($settings["childpanel_selling"] == 2) {
  $ChildPanel = true;
}

if ($settings["referral_status"] == 2) {
  $affiliatessystem = true;
}
if ($settings["promotion"] == 2) {
  $Promotion = true;
}
if ($general["updates_show"] == 2) {
  $updates = true;
}
if (route(0) == "auth"):
  $active_menu = route(1);
else:
  $active_menu = route(0);
endif;
if (route(0) != "admin" && route(0) != "ajax_data") {
  $languages = $conn->prepare("SELECT * FROM languages WHERE language_type=:type");
  $languages->execute(array("type" => 2));
  $languages = $languages->fetchAll(PDO::FETCH_ASSOC);
  $languagesL = [];
  foreach ($languages as $language) {
    $l["name"] = $language["language_name"];
    $l["code"] = $language["language_code"];
    if ($_SESSION["lang"] && $language["language_code"] == $_SESSION["lang"]) {
      $l["active"] = 1;
    } elseif (!$_SESSION["lang"]) {
      $l["active"] = $language["default_language"];
    } else {
      $l["active"] = 0;
    }
    array_push($languagesL, $l);
  }

  // print_r($languagesL);exit;

  if (!$templateDir) {
    $templateDir = route($routeType);
  }
  if ($templateDir == "login" || $templateDir == "register"):
    $contentGet = "auth";
  else:
    $contentGet = $templateDir;
  endif;
  $content = $conn->prepare("SELECT * FROM pages WHERE page_get=:get ");
  $content->execute(array("get" => $contentGet));
  $content = $content->fetch(PDO::FETCH_ASSOC);
  $pageactive = $content["page_status"];
  $seo_description = $content["seo_description"];
  $seo_title = $content["seo_title"];
  $seo_keywords = $content["seo_keywords"];
  $content1 = $content["page_content"];
  $content2 = $content["page_content2"];

  if ($pageactive == 1) {
    $PageText = true;
  }


  $blogGet = $route[1];

  $blog = $conn->prepare("SELECT * FROM blogs WHERE blog_get=:get ");
  $blog->execute(array("get" => $blogGet));
  $blog = $blog->fetch(PDO::FETCH_ASSOC);
  $blogcontent = $blog["content"];
  $blogtitle = $blog["title"];
  $blogimage = $blog["image_file"];



  $blogs = $conn->prepare("SELECT * FROM blogs WHERE status=:status ");
  $blogs->execute(array("status" => 1));
  $blogs = $blogs->fetchAll();

  if (is_array($user) && $user["auth"]):

    $menus = $conn->prepare("SELECT * FROM menus WHERE visible=:Internal ORDER BY menu_line ASC ");
    $menus->execute(array("Internal" => "Internal"));
    $menus = $menus->fetchAll();
    $menuslug = $menus["slug"];
    
    for($i = 0; $i < count($menus); $i++){
        $menu_lang_name = json_decode($menus[$i]["menu_name_lang"],1)[$user["lang"]];
        
        if(!$menu_lang_name){
        $menus[$i]["menu_name"] = $menus[$i]["name"];
        } else {
            $menus[$i]["menu_name"] = $menu_lang_name;
        }
    }

    $ref_content = $conn->prepare("SELECT * FROM referral WHERE referral_code=:referral_code ");
    $ref_content->execute(array("referral_code" => $user['ref_code']));
    $ref_content = $ref_content->fetchAll();


    $panel_info = $conn->prepare("SELECT * FROM panel_info WHERE panel_id=:id ");
    $panel_info->execute(array("id" => 1));
    $panel_info = $panel_info->fetchAll();

    $ref_payouts = $conn->prepare("SELECT * FROM referral WHERE referral_code=:r_p_code ");
    $ref_payouts->execute(array("r_p_code" => $user['ref_code']));
    $ref_payouts = $ref_payouts->fetchAll();

  else:


    $menus = $conn->prepare("SELECT * FROM menus WHERE visible=:visible ORDER BY menu_line ASC ");
    $menus->execute(array("visible" => "External"));
    $menus = $menus->fetchAll();
    $menuslug = $menus["slug"];
    
    for($i = 0; $i < count($menus); $i++){
        $menu_lang_name = json_decode($menus[$i]["menu_name_lang"],1)[$selectedLang];
        if(!$menu_lang_name){
        $menus[$i]["menu_name"] = $menus[$i]["name"];
        } else {
            $menus[$i]["menu_name"] = $menu_lang_name;
        }
    }
    
  endif;
  $allorders = $conn->prepare("SELECT * FROM orders ORDER BY order_id DESC LIMIT 1");
  $allorders->execute();
  $allorders = $allorders->fetchAll();
  $totalorder = "1";
  $news = $conn->prepare("SELECT * FROM news ORDER BY news_date DESC");
  $news->execute(array());
  $news = $news->fetchAll();


  $panel_orders_pattern = json_decode($settings["panel_orders_pattern"], true);

  $order_count = $panel_orders_pattern["panel_orders_prefix"] . $settings["panel_orders"] . $panel_orders_pattern["panel_orders_suffix"];


  if (is_array($user) && $user["auth"]):

    $bid_from_db = $conn->prepare("SELECT broadcast_id FROM clients WHERE client_id=:cid");
    $bid_from_db->execute(array("cid" => $user["client_id"]));
    $bid_from_db = $bid_from_db->fetch(PDO::FETCH_ASSOC)["broadcast_id"];


    $user["balance"] = format_amount_string(
      $user["currency_type"],


      from_to($currencies_array, $settings["site_base_currency"], $user["currency_type"], $user["balance"])
    );
    $user["spent"] = format_amount_string($user["currency_type"], from_to($currencies_array, $settings["site_base_currency"], $user["currency_type"], $user["spent"]));

  endif;

  $startYear = 2021;
  $currentYear = date('Y');
  $copyright_year = $startYear . '-' . $currentYear;

  if ($_SESSION["msmbilisim_userlogin"] != 1 || $user["client_type"] == 1) {
    echo $twig->render(
      $templateDir . '.twig',
      array(
        'site' => [
          'url' => URL,
          "title" => $settings["site_title"],
          'favicon' => $settings['favicon'],
          "logo" => $settings["site_logo"],
          "site_name" => $settings["site_name"],
          'service_speed' => $settings["service_speed"],
          'allorders' => $allorders,
          "keywords" => $settings["site_keywords"],
          "description" => $settings["site_description"],
          'languages' => $languagesL,
          "childpanel_price" => $settings["childpanel_price"],
          "ns1" => $settings["ns1"],
          "ns2" => $settings["ns2"],
          "snow_colour" => $settings["snow_colour"],
          "snow_effect" => $settings["snow_effect"],
          "popup" => $settings["popup"],
          "active_lang" => $selectedLangName,
          "active_lang_code" => $selectedLang,
        ],
        'styleList' => $stylesheet["stylesheets"],
        'scriptList' => $stylesheet["scripts"],
        'captchaKey' => $settings["recaptcha_key"],
        'captcha' => $captcha,
        'resetPage' => $resetPage,
        'serviceCategory' => $categories,
        'categories' => $categories,
        'error' => $error,
        'errorText' => $errorText,
        'success' => $success,
        "servicesPage" => $serviceList,
        "resetType" => $resetType,
        'successText' => $successText,
        'title' => $title,
        "Signup" => $Signup,
        "ChildPanel" => $ChildPanel,
        'affiliatessystem' => $affiliatessystem,
        'Promotion' => $Promotion,
        'user' => $user,
        'data' => $_SESSION["data"],
        'settings' => $settings,
        'total_orders' => $totalorder,
        'search' => urldecode($_GET["search"]),
        "active_menu" => $active_menu,
        'ticketList' => $ticketList,
        'messageList' => $messageList,
        'paymentsList' => $methodList,
        'integrationsList' => $methoList,
        'chilpanel_logs' => $panel_logs,
        'PaytmQR' => $PaytmQR["method_type"],
        'PaytmQRimage' => $PaytmQRimage,
        "phonpe_qr_image" => $PhonepeQRimage,
        'WhatsApp' => $WhatsApp["method_type"],
        'whatsappnumber' => $whatsappnumber,
        'whatsappposition' => $whatsappposition,
        'whatsappstatus' => $whatsappstatus,
        'whatsappvisibility' => $whatsappvisibility,
        'bankPayment' => $bankPayment["method_type"],
        'bankList' => $bankList,
        'status' => $route[1],
        'earn' => $earnList,
        'orders' => $ordersList,
        'pagination' => $paginationArr,
        'contentText' => $content1,
        'contentText2' => $content2,
        'headerCode' => $settings["custom_header"],
        'footerCode' => $settings["custom_footer"],
        'lang' => $languageArray,
        'timezones' => $timezones,
        'AllOrders' => $panel_orders,
        'panel' => $panel,
        'general' => $general,
        'updates' => $updates,
        'blogs' => $blogs,
        'blogtitle' => $blogtitle,
        'blogcontent' => $blogcontent,
        'blogimage' => $blogimage,
        'currency' => $currency,
        'currencies' => $currencies,
        'menus' => $menus,
        'MenuSlug' => $menuslug,
        'pageurl' => $pageurl,
        'pagekeywords' => $seo_keywords,
        'pagetitle' => $seo_title,
        'pagedescription' => $seo_description,
        'PageText' => $PageText,
        "news" => $news,
        "totalorders" => $panel_orders,
        "decoration" => $decoration,
        "route_service_currency" => route(1),
        "route_0" => route(0),
        "site_base_currency" => $settings["site_base_currency"],
        "without_login_currencies_item" => $offline_currencies_item,
        "site_base_currency_symbol" => get_currency_symbol_by_code($settings["site_base_currency"]),
        "without_login_currencies_item_with_li" => $offline_currencies_item_with_li,
        "google_login_content" => $google_login_content,
        "body_rtl" => $body_rtl,
        "table_rtl" => $table_rtl
      )
    );


  } else {


    $uye_id = $user["client_id"];
    $dripfeedvarmi = $conn->prepare("SELECT * FROM orders WHERE client_id=$uye_id and dripfeed=2");
    if ($dripfeedvarmi->rowCount()) {
      $dripfeedcount = 1;
    } else {
      $dripfeedcount = 0;
    }


    $uye_id = $user["client_id"];
    $refillvarmi = $conn->prepare("SELECT * FROM refill_status WHERE client_id=$uye_id ");
    if ($refillvarmi->rowCount()) {
      $refillavailable = 1;
    } else {
      $refillavailable = 0;
    }
    $subscriptionsvarmi = $conn->prepare("SELECT * FROM orders WHERE client_id=$uye_id and subscriptions_type=2");
    if ($subscriptionsvarmi->rowCount()) {
      $subscriptionscount = 1;
    } else {
      $subscriptionscount = 0;
    }


    $statubul = $conn->prepare("SELECT SUM(payment_amount) as toplam FROM payments WHERE client_id=:client_id ");
    $statubul->execute(array("client_id" => $uye_id));
    $statubul = $statubul->fetch(PDO::FETCH_ASSOC);


    if ($statubul["toplam"] <= $bronz_statu):
      $statusu = "VIP";
    endif;

    if ($statubul["toplam"] > $bronz_statu and $statubul["toplam"] <= $silver_statu):
      $statusu = "JUNIOR";
    endif;

    if ($statubul["toplam"] > $silver_statu and $statubul["toplam"] <= $gold_statu):
      $statusu = "REGULAR";
    endif;

    if ($statubul["toplam"] > $gold_statu and $statubul["toplam"] <= $bayi_statu):
      $statusu = "NEW";
    endif;

    if ($statubul["toplam"] > $bayi_statu):
      $statusu = "NEW";
    endif;

    $ref_content = $conn->prepare("SELECT * FROM referral WHERE referral_code=:referral_code ");
    $ref_content->execute(array("referral_code" => $user['ref_code']));
    $ref_content = $ref_content->fetchAll();

    $ref_payouts = $conn->prepare("SELECT * FROM referral_payouts WHERE r_p_code=:r_p_code ORDER BY r_p_id DESC");
    $ref_payouts->execute(array("r_p_code" => $user['ref_code']));
    $ref_payouts = $ref_payouts->fetchAll();

    $panel_info = $conn->prepare("SELECT * FROM panel_info WHERE panel_id=:id ");
    $panel_info->execute(array("id" => 1));
    $panel_info = $panel_info->fetchAll();


    $allorders = $conn->prepare("SELECT * FROM orders ORDER BY order_id DESC LIMIT 1  ");
    $allorders->execute(array());
    $allorders = $allorders->fetchAll();




    $news = $conn->prepare("SELECT * FROM news ORDER BY news_date DESC");
    $news->execute(array());
    $news = $news->fetchAll(PDO::FETCH_ASSOC);


    for ($i = 0; $i < count($news); $i++) {

      $news[$i]["news_icon"] = GET_IMAGE_URL_BY_ID($news[$i]["news_icon"]);
      
      $news[$i]["news_date"] = date("jS M, Y g:i A", strtotime($news[$i]["news_date"]));
      
      $news_title_lang = $news[$i]["news_title_lang"];
      
      $news_title_lang = json_decode($news_title_lang, 1);
      
      $news_content_lang = $news[$i]["news_content_lang"];
      
      $news_content_lang = json_decode($news_content_lang, 1);
      
      if($news_title_lang[$user["lang"]]){
        
        $news[$i]["news_title"] = $news_title_lang[$user["lang"]];
          
      } else {
         $news[$i]["news_title"] = $news[$i]["news_title"];
      }
      
      if($news_content_lang[$user["lang"]]){
        
        $news[$i]["news_content"] = $news_content_lang[$user["lang"]];
          
      } else {
         $news[$i]["news_content"] = $news[$i]["news_content"];
      }
      
      
    }



    echo $twig->render(
      $templateDir . '.twig',
      [
        'site' => [
          'url' => URL,
          'favicon' => $settings['favicon'],
          "logo" => $settings["site_logo"],
          "site_name" => $settings["site_name"],
          'service_speed' => $settings["service_speed"],
          "keywords" => $settings["site_keywords"],
          "description" => $settings["site_description"],
          "allorders" => $allorders,
          'languages' => $languagesL,
          "refillavailable" => $refillavailable,
          "dripfeedcount" => $dripfeedcount,
          "subscriptionscount" => $subscriptionscount,
          "childpanel_price" => $settings["childpanel_price"],
          "inr_symbol" => $settings["inr_symbol"],
          "inr_value" => $settings["inr_value"],
          "ns1" => $childpanel_nameservers["ns1"],
          "ns2" => $childpanel_nameservers["ns2"],
          "snow_colour" => $settings["snow_colour"],
          "snow_effect" => $settings["snow_effect"],
          "popup" => $settings["popup"],
          "paymentMethods" => $methodsList,
          "paymentMethodsJSON" => $paymentMethodsJSON
        ],
        "category_html" => $category_html,
        "category_html_simplify" => $category_html_simplify,
        'styleList' => $stylesheet["stylesheets"],
        'scriptList' => $stylesheet["scripts"],
        'captchaKey' => $settings["recaptcha_key"],
        'captcha' => $captcha,
        'resetPage' => $resetPage,
        'serviceCategory' => $categories,
        'categories' => $categories,
        'error' => $error,
        'errorText' => $errorText,
        'success' => $success,
        "servicesPage" => $serviceList,
        "resetType" => $resetType,
        'successText' => $successText,
        'title' => $title,
        "Signup" => $Signup,
        "ChildPanel" => $ChildPanel,
        'affiliatessystem' => $affiliatessystem,
        'Promotion' => $Promotion,
        'user' => $user,
        'data' => $_SESSION["data"],
        'statu' => $statusu,
        'settings' => $settings,
        'total_orders' => $totalorder,
        'search' => urldecode($_GET["search"]),
        "active_menu" => $active_menu,
        'ticketList' => $ticketList,
        'messageList' => $messageList,
        'ticketCount' => new_ticket($user['client_id']),
        'paymentsList' => $methodList,
        'integrationsList' => $methoList,
        'payments' => $paymentHistory,
        'chilpanel_logs' => $panel_logs,
        'PaytmQRimage' => $PaytmQRimage,
        "phonpe_qr_image" => $PhonepeQRimage,
        'PaytmQR' => $PaytmQR["method_type"],
        'WhatsApp' => $WhatsApp["method_type"],
        'whatsappnumber' => $whatsappnumber,
        'whatsappposition' => $whatsappposition,
        'whatsappstatus' => $whatsappstatus,
        'whatsappvisibility' => $whatsappvisibility,
        'bankPayment' => $bankPayment["method_type"],
        'bankList' => $bankList,
        'status' => $route[1],
        'earn' => $earnList,
        'orders' => $ordersList,
        'pagination' => $paginationArr,
        'contentText' => $content1,
        'contentText2' => $content2,
        'headerCode' => $settings["custom_header"],
        'footerCode' => $settings["custom_footer"],
        'lang' => $languageArray,
        'timezones' => $timezones,
        'ref_content' => $ref_content,
        'ref_payouts' => $ref_payouts,
        'panels' => $panels,
        'invoices' => $invoices,
        "route" => $routes,
        "unpaidCount" => $unpaidCount,
        'panel' => $panel,
        'general' => $general,
        'updates' => $updates,
        'blogs' => $blogs,
        'blogtitle' => $blogtitle,
        'blogcontent' => $blogcontent,
        'blogimage' => $blogimage,
        'currency' => $currency,
        'currencies' => $currencies,
        'menus' => $menus,
        'MenuSlug' => $menuslug,
        'pageurl' => $pageurl,
        'pagekeywords' => $seo_keywords,
        'pagetitle' => $seo_title,
        'pagedescription' => $seo_description,
        'PageText' => $PageText,
        "news" => $news,
        "totalorders" => $order_count,
        "decoration" => $decoration,
        "broadcast_id" => $bid_from_db,
        "massorders" => $_SESSION["massorders"],
        "massorder_seen" => $_SESSION["massorder_seen"],
        "currencies_dropdown" => $currencies_item,
        "site_currency_converter" => $settings["site_currency_converter"],
        "site_base_currency" => $settings["site_base_currency"],
        "site_base_currency_symbol" => get_currency_symbol_by_code($settings["site_base_currency"]),
        "user_set_currency_symbol" => get_currency_symbol_by_code($user["currency_type"]),
        "child_panel_currencies" => $choose_currencies,
        "CopyrightYear" => $copyright_year,
        "masked_api_key" => obfuscate_provider_key($user["apikey"]),
        "body_rtl" => $body_rtl,
        "table_rtl" => $table_rtl
      ]
    );

  }

  if (route(0) != 'admin' && route(1) != 'v2' && route(0) != 'ajax_data') {


    $int1 = $conn->prepare('SELECT * FROM integrations WHERE status=:status && visibility=:visibility');
    $int1->execute(
      array(
        'status' => 2,
        'visibility' => 1
      )
    );

    if ($int1) {
      foreach ($int1 as $int) {
        echo $int['code'];
      }
    }

    if ($_SESSION['msmbilisim_userlogin'] != 1) {

      $int2 = $conn->prepare('SELECT * FROM integrations WHERE status=:status && visibility=:visibility');
      $int2->execute(
        array(
          'status' => 2,
          'visibility' => 2
        )
      );

      if ($int2) {
        foreach ($int2 as $int) {
          echo $int['code'];
        }
      }
    }

    if ($_SESSION['msmbilisim_userlogin'] == 1) {

      $int3 = $conn->prepare('SELECT * FROM integrations WHERE status=:status && visibility=:visibility');
      $int3->execute(
        array(
          'status' => 2,
          'visibility' => 3
        )
      );

      if ($int3) {
        foreach ($int3 as $int) {
          echo $int['code'];
        }
      }
    }
  }

}

if (route(0) != "neworder" && route(0) != "child-panels" && route(0) != "ajax_data" && (route(0) != "admin" && route(1) != "services")):
  unset($_SESSION["data"]);
endif;