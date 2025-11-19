<?php include 'header.php' ?>
<style>
    .otp-text h4 {
        text-align: center;
        margin-top: 4px;
        margin-bottom: 22px;
        color: #000000;
    }

    .otp-text p {
        text-align: center;
        margin-bottom: 0px;
    }

    ul.l-one li {
        width: 20%;
        padding: 15px;
        border-right: 1px solid #cfc2e7;
    }

    ul.l-one li:last-child {
        border-right: 0px solid #cfc2e7;
    }

    ul.l-one {
        list-style: none;
        display: -webkit-inline-box;
        background: #fff;
        box-shadow: 0px 10px 10px 0px rgba(100, 200, 255, 0.1);
        border-style: solid;
        border-width: 15px 0px 6px 0px;
        border-color: #c3a9f2;

    }

    .box {
        padding: 15px 0px;
        background: #fff;

    }

    .box img {

        margin: 0px auto;
        text-align: center;
        display: block;

    }

    a.a-btn:hover {
        background: #1c6c30;
    }

    .main-img img {
        text-align: center;
        /* display: block; */
        margin: 0px auto;
        padding: 5px 27px;
    }

    .box h5 {
        font-weight: 700;
        text-align: center;
        font-size: 16px;
        margin: 20px 0px 7px;
    }

    .hole-part {

        padding: 100px 0px;
    }

    .hole-part {
        padding: 0px 100px;
        margin-top: -190px;
    }

    a.a-btn {
        text-align: center;
        background: #00afef;
        margin: 0px auto;
        display: table;
        padding: 6px 22px;
        border-radius: 50px;
        z-index: 999999999999999;
        position: relative;
        text-decoration: unset;
        color: #fff;
        text-transform: uppercase;
        font-weight: 900;
        font-size: 13px;
        margin-top: 17px;
    }

    .box span {
        font-size: 14px;
        color: #555;
        text-align: center;
        margin: 0px auto;
        display: block;
    }

    .hole-unit {
        background: #00afef;
        padding: 0px 27px;
        position: fixed;
        left: 0;
        bottom: 0;
        width: 101%;
        text-align: center;
        border-bottom: 24px solid #ffff;
    }

    .hole-unit h3 {
        color: #ffff;
        margin: 0px;
        font-size: 19px;
        padding: 15px 0px;
    }

    .br-right {
        border-right: 3px solid #fff;
    }

    .logo {
        background: #fff;
        padding: 10px;
    }

    .hole-unit h3 img {
        width: 25px;
    }





    .img-top {
        position: absolute;
        background-color: #00afef;
        width: 100%;
        max-height: 350px;
        top: 0;
        left: 0;
        overflow: hidden;
        z-index: -1;
        height: 330px;
    }

    .d-grid.gap-10.text-center {
        padding-top: 30px !important;
        padding-bottom: 30px !important;
    }

    h4.heading-section-4.text-white.mb-0 {
        font-size: 28px;
    }

    .curv-box {
        background: #fff;
        box-shadow: 0px 7px 22px rgba(143, 134, 196, 0.07) !important;
        padding: 45px;
        border-radius: 0px;
        border: 1px solid #ccc;
    }

    h2.hedline {
        font-size: 23px;
        margin-bottom: 37px;
        font-weight: 800;
        color: #00afef;
        margin-top: 0px;
    }

    form.row.new-form label {
        font-size: 1rem;
        font-weight: 500;
        color: #363848;
        margin-bottom: 10px;
    }

    form.row.new-form input {
        font-weight: 400;
        background-color: #FBFBFB;
        color: #363848;
        padding: 11px 24px;
        border: 1px solid #C2C2C2;
        border-radius: 5px !important;
    }

    form.row.new-form Select {
        font-weight: 400;
        background-color: #FBFBFB;
        color: #363848;
        padding: 11px 24px;
        border: 1px solid #C2C2C2;
        border-radius: 5px !important;
        width: 100%;
    }

    .mb-r {
        margin-bottom: 21px;
    }

    button.btn.btn-primary.fw-semiBold {
        color: #fff;
        background-color: #00afef;
        border-color: #00afef;
        padding: 9px 24px;
    }

    a.btn.btn-cultured.text-philippine-gray.fw-semiBold {
        background-color: #00afef;
        border-color: #00afef;
    }

    a.btn.btn-cultured.text-philippine-gray.fw-semiBold {
        background-color: #00afef;
        border-color: #00afef;
        margin-right: 28px;
        padding: 9px 24px;
    }

    a.abck {

        background: #00afef;
        padding: 10px;
        border-radius: 50px;
        color: #fff;
        font-size: 20px;
    }

    .ad-logo {
        text-align: justify;
    }

    .otp-btn {
        text-align: center;
        margin-top: 18px;
    }

    div#inputs {
        text-align: center;
    }

    div#inputs input {
        border: 1px solid #bcb0b080;
        margin: 0 5px;
        text-align: center;
        font-size: 32px;
        cursor: not-allowed;
        pointer-events: none;
        width: 40px;
        height: 40px;
        border-radius: 5px;
    }

    div#inputs input:focus {
        border-bottom: 3px solid #00afef;
        outline: none;
    }

    div#inputs input:nth-child(1) {
        cursor: pointer;
        pointer-events: all;
    }

    a.btn.rounded-pill.btn-primary.btn-colour {
        background: #00afef;
        border-color: #00afef;
        padding: 10px 29px;
    }

    .modal-body.outline-manual {
        border: 1px solid #9275c9;
    }

    a.back_home {
        background-color: #00afef;
        padding: 8px 12px;
        border-radius: 8px;
        color: #fff;
    }

                h3 {
  display: flex;
  flex-direction: row;
}
</style>


<div class=" logo">
    <div class="row">
        <div class="col-md-6">
            <div class="ad-logo">
                <img src="img/ad-logo.png">
            </div>
        </div>
        <div class="col-md-6 align-self-center" style="text-align: end;">
            <a href="index.php" class="back_home"><i class="mdi mdi-home"></i></a>
        </div>
    </div>
</div>
<div class="heder-top">
    <div class="img-top">
        <!--<img src="img/header-bg.png">-->
    </div>
    <div class="container">
        <div class="position-relative py-43 py-lg-80">
            <div class="d-grid gap-10 text-center">
                <h4 class="heading-section-4 text-white mb-0">Application Status</h4>
            </div>
            <!-- /.heading-header -->


        </div>
    </div>
</div>

<div class="container">
    <div class="curv-box">
        <form action="#" class="row new-form">

        <div class="row">
            <div class="col-md-4 ">
                <label for="inputMiddlename" class="form-label">Educational details/ கல்வி விவரங்கள் </label>
            </div>
            <div class="col-md-4 ">
                <label for="inputMiddlename" class="form-label">B.Sc</label>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 ">
                <label for="inputLastname" class="form-label">Last Study Details / கடைசி ஆய்வு விவரங்கள் </label>
            </div>
            <div class="col-md-4 ">
                <label for="inputLastname" class="form-label">B.Sc</label>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 ">
                <label for="inputPhonenumber" class="form-label">Address  /  முகவரி</label>
            </div>
            <div class="col-md-4 ">
                <label for="inputPhonenumber" class="form-label">Vani Street, kk nagar, thindel Erode   </label>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 ">
                    <label for="example-month" class="form-label">Identification Details /  அடையாள விவரங்கள்</label>
            </div>
            <div class="col-md-4 ">
                    <label for="example-month" class="form-label">7658 8796 9087 5674</label>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 ">
                  <label for="app-number" class="form-label"> Bank Details  / வங்கி விவரங்கள் </label>
            </div>
            <div class="col-md-4 ">
                  <label for="app-number" class="form-label">  </label>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 ">
                  <label for="app-number" class="form-label">  Family Details  / குடும்ப விவரங்கள் </label>
            </div>
            <div class="col-md-4 ">
                  <label for="app-number" class="form-label">  Family Details  / குடும்ப விவரங்கள் </label>
            </div>
        </div>

        </form>
    </div>
</div>





<?php include 'footer.php' ?>