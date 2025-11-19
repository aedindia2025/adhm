<?php include 'header.php'; ?>
<?php include 'footer.php'; ?>
<?php include 'config/dbconfig.php'; ?>

<style>
    .comm-btn {
        background: linear-gradient(to right, #25bff9, #0890c3);
        border: 0px;
        padding: 10px;
        color: #fff;
        border-radius: 5px;
        font-weight: 600;
        font-size: 15px;
        outline: 0;
        margin-left: 30px;
    }

    .comm-btn:hover {
        background: linear-gradient(to right, #0890c3, #25bff9);
    }
</style>
<br><br>

<div class="row">
    <div class="col-md-12 mt-3 align-self-center text-center">
        <input type="button" class="comm-btn" id="get_aadhar_number" onclick="getRecord();" value="Generate OTP">
    </div>
</div>

<!-- <div class="row">
    <div class="col-md-12 mt-3 align-self-center text-center">
        <input type="button" class="comm-btn" onclick="get_aadhar();" value="Get Aadhar Number">
    </div>
</div> -->


<script>

function getRecord() {
    var ajax_url = "crud_uid.php";
    var data = {
        "action": "getRecord"
    };

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        dataType: 'json',
        success: function (response) {
            if (response.status) {
                alert(response.msg);
            } else {
                console.error("Error processing records:", response.error);
                alert("Error: " + response.error);
            }
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error:", xhr.responseText);
            alert("An error occurred: " + xhr.responseText);
        }
    });
}

    function get_aadhar() {
        var uuid = '149465619725';
        alert(uuid);
        var ajax_url = "crud_uuid.php";
        var data = {
            "uuid": uuid,
            "action": "check_aadhar"
        };

        $.ajax({
            type: "POST",
            url: ajax_url,
            data: data,
            dataType: 'json', // Parse response as JSON

            success: function (response) {

                if (response.data) {
                    // Handle success
                    //console.log(response.data.RESPONSE);

                    var status = response.data.RESPONSE.STATUS;
                    var uuid = response.data.RESPONSE.UID;
                    alert(uuid);
                }
            }

        });
    }

</script>