<?php 

  $_ROOT_DIRECTORY = dirname(__FILE__) . "/../";
  
  foreach (glob($_ROOT_DIRECTORY . "includes/*.php") as $filename) {
    if ($filename !== ($_ROOT_DIRECTORY . "includes/core.php")) {
      require_once($filename);
    }
  }
  
  $page = $_GET['page'];
  $method = $_GET['method'];
  
  $errors = array();
  
  $backend = new Backend();
  
  if ($page != "logout") {
    $logged_in = $backend->check_login();
  } else {
    $logged_in = FALSE;
  }
  
  switch ($page) {
    case "login":
      
      if ($logged_in) {
        header('Location: /admin/pages/');
      } else {
      
        $authenticated = FALSE;
      
        if (isset($_POST['data']) && $method == "authenticate") {
          $backend->authenticate($_POST['data']['id'], $_POST['data']['password']);
        } else {
          //$backend->notices[] = "Welcome to Seamonster... Rrraaaarghh!";
        }
      
        if ($method == "failed") {
          $backend->notices[] = "Your attempt to log in failed. Maybe you mistyped something? Please try again, anyway.";
        }
    
        $page_content = $backend->login_form();
      }
      break;
      
    case "pages":
      if ($logged_in) {
        
        switch ($method) {
          
          case "save":
          
            $data = $_POST['data'];
            $data['Files'] = $_POST['existing-file'];
            if ($backend->save_changes($_POST['page'], $data)) {
              header('Location: /admin/pages/');
              exit;
            } else {
              $backend->notices[] = "There was a problem saving your page. Please try again.";
            }
            
            $page_content = $backend->menu();
            break;
          case "":
            $page_content = $backend->menu();
            break;
          default:
            $page_content = $backend->edit_page($method);
            break;
          
        }
      
      } else {
        header('Location: /admin/login/');
        exit;
      }
      break;
    
    case "files":
      $page_content = $backend->manage_files();
      break;
    
    case "upload":
      if ($backend->upload_file()) {
        header('Location: /admin/files/');
        exit;
      } else {
        $backend->notices[] = "There was a problem uploading your file. Please try again.";
      }
      $page_content = $backend->manage_files();
      break;
    
    case "logout":
      if ($backend->logout()) {
        header('Location: /admin/');
      }
      break;
  }
  
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

  <title><?= page_title("Collins Quarter", ucfirst($page)) ?></title>
  
  <?= stylesheet_tag('reset', '/admin/stylesheets'); ?>
  <?= stylesheet_tag('forms', '/admin/stylesheets'); ?>
  <?= stylesheet_tag('admin', '/admin/stylesheets'); ?>
  
  <?= javascript_tag('prototype', '/admin/javascripts'); ?>
  <?= javascript_tag('scriptaculous/scriptaculous', '/admin/javascripts', ".js?load=effects,dragdrop"); ?>
  <?= javascript_tag('admin', '/admin/javascripts'); ?>
  
  <!--[if lt IE 7]>
    <script src="http://ie7-js.googlecode.com/svn/version/2.0(beta3)/IE7.js" type="text/javascript"></script>
    <?= javascript_tag('unitpngfix', '/admin/javascripts'); ?>
  <![endif]-->
  
  
</head>

<body id="<?= $page ?>">
  <div id="wrapper">
  
  
    <div id="header">
      <h1><?= hyperlink("/admin/", page_title("Collins Quarter")) ?></h1>
    <?php if ($page != "login"): ?>
      <div id="logout"><?= hyperlink("/admin/pages/", "Manage pages") ?> <?= hyperlink("/admin/files/", "Manage files") ?> <?= hyperlink("/", "Collins Quarter site &rarr;") ?> <?= hyperlink("/admin/logout/", "Log Out") ?></div>
    <?php endif; ?>
      <br class="hurdle" />
    </div>
    
    
    <?= comment("Error list:") ?>
    <?= $backend->list_notices() ?>
    
    <div id="content">
    <?php 
      echo $page_content;
    ?>
    </div>
    
    <div id="footer">
      Built on Seamonster by <a href="http://www.barkingsparrows.com">Barking Sparrows</a>.
    </div>
    
  </div>
</body>
</html>