<style>
    .card .card-header, .card .card-body, .card .card-footer {
    background-color: transparent;
    padding: 20px 20px;
}

</style>

<!--  <div class="loader"></div>
-->  <div id="app">
    <div class="main-wrapper main-wrapper-1">
      <!-- Main Content -->
      <div class="main-content">
        <?php 
          if (isset($_GET['file'])) { 

            $folder_name_dec = str_replace(" ", "+",$_GET['file']);
            $get_dec_file     = openssl_decrypt(base64_decode($folder_name_dec), $enc_method, $enc_password,OPENSSL_RAW_DATA, $enc_iv);
             $file_str        = $get_dec_file; 

            // $file_str        = $_GET['file'];
            $file_arr        = explode("/",$file_str);
            $folder_name_org = $file_arr[0];
            $file_name_org   = $file_arr[1];

            $menu_screen            = $folder_name_org."/model";
            $file_name_create       = base64_encode(openssl_encrypt($menu_screen, $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv));
            $add_btn_link           = "index.php?file=".$file_name_create;

            $menu_screen         = $folder_name_org."/list";
            $file_name_list      = base64_encode(openssl_encrypt($menu_screen, $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv));
            $cancel_btn_link     = "index.php?file=".$file_name_list;
            
            $folder_crud_link= "folders/".$folder_name_org."/crud.php";

            $menu_screen            = $folder_name_org."/model";
            $file_name_create       = base64_encode(openssl_encrypt($menu_screen, $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv));
            $btn_add                = "index.php?file=".$file_name_create;

            $menu_screen            = $folder_name_org."/list";
            $file_name_list      = base64_encode(openssl_encrypt($menu_screen, $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv));
            $btn_cancel      = "index.php?file=".$file_name_list;

            $folder_crud_link= "folders/".$folder_name_org."/crud.php";

            $folder_name     = disname($folder_name_org);
            $file_name       = disname($file_name_org);
            $file_name_create       = base64_encode(openssl_encrypt($menu_screen, $enc_method, $enc_password, OPENSSL_RAW_DATA, $enc_iv));
            $btn_save      = "index.php?file=".$folder_name_org."/model";
          ?>
            <script>
              sessionStorage.setItem("folder_crud_link","<?php echo $folder_crud_link; ?>");
              sessionStorage.setItem("list_link","<?php echo $btn_cancel; ?>");
              // sessionStorage.setItem("save_link","<?php //echo $btn_save; ?>");
              // sessionStorage.setItem("back_link","<?php //echo $btn_back; ?>");
              // sessionStorage.setItem("company_name","<?php //echo "Ascent Urban"; ?>");
              document.addEventListener('contextmenu', function(event) {
    event.preventDefault();
              });

              document.onkeydown = function(e)
    {
        if(event.keyCode == 123)
        {
            return false;
        }
        if(e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0))
        {
            return false;
        }
        if(e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0))
        {
            return false;
        }
        if(e.ctrlKey && e.keyCode == 'U'.charCodeAt(0))
        {
            return false;
        }
    if(e.ctrlKey && e.shiftKey && e.keyCode == 'C'.charCodeAt(0))
    {
      return false;
    }
    }
            </script>
            <section class="section">
              <?php include 'folders/'.$file_str.'.php'; ?>
            </section>
          <?php }else{?>
        <section class="section">
          <?php include 'folders/dashboard/form.php' ?>
        </section>
      <?php } ?>
       
    </div>
    
  </div>
      <?php include 'inc/footer.php'?>