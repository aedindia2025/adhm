
$(document).ready(function () {
    get_group();
    // alert();

    var dob = $('#std_dob').val();

    if (dob) {
        var today = new Date();
        var birthDate;

        // Check if the input is a full date (e.g., "23-06-2002")
        if (dob.includes('-')) {

            var parts = dob.split('-');
            if (parts.length === 3) {
                var day = parseInt(parts[0]);
                var month = parseInt(parts[1]) - 1; // Months are 0-based in JavaScript
                var year = parseInt(parts[2]);
                birthDate = new Date(year, month, day);
            } else {
                birthDate = new Date(dob); // Fallback if not in expected format
            }
        } else {

            // Assuming the input is a year only (e.g., "2002")
            var year = parseInt(dob);
            birthDate = new Date(year, 0); // January 1st of the given year
        }

        // Check if birthDate is a valid date
        if (!isNaN(birthDate.getTime())) {
            var age = today.getFullYear() - birthDate.getFullYear();
            var m = today.getMonth() - birthDate.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            $('#std_age').val(age);
        } else {
            $('#std_age').val('Invalid date');
        }
    }


    var age = $('#age').val();
    if (age == '') {
        var dob = $('#dob').val();
        var t_dob = $('#t_dob').val();
        if (dob || t_dob) {

            if (dob) {
                var formattedDate = dob;
                var [day, month, year] = formattedDate.split('-');
                var formattedDate = `${year}-${month}-${day}`;
            } else if (t_dob) {
                var formattedDate = t_dob;
            }

            var today = new Date();
            var birthDate = new Date(formattedDate);
            var age = today.getFullYear() - birthDate.getFullYear();
            var m = today.getMonth() - birthDate.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }

            $('#age').val(age);
        }
    }

    if (dob == '') {
        $("#t_dob").val('');
        $("#age").val('');
    }


    get_phy_div();
    get_emis_umis();

    get_gender();

    ready_get_cert_detail();


    var cert_detail = $("#cert_detail").val();
    if (cert_detail != '') {
        com_no();
    }

    var yr_stdy = $("#yr_stdy").val();
    if (yr_stdy != '') {
        ltl_div();
    }

    var lat_entry = $("#lat_entry").val();
    if (lat_entry != '') {
        hav_umis_div();
    }
    var umisSelect = $("#umisSelect").val();
    if (umisSelect != '') {
        get_umis_values();
    }



    var emis_no = $("#emis_no").val();
    if (emis_no != '') {
        emis_div();
        // toggleDiv_emis();
    }

    var caDistrictId = $("#caDistrictId").val();
    if (caDistrictId != '') {
        get_umis_district();
    } else {
        host_district();
    }

    var communityno = $("#communityno").val();
    if (communityno != '') {
        ready_toggle_cc();
    }

    var incomecerno = $("#incomecerno").val();
    if (incomecerno != '') {
        ready_toggle_inc();
    }



    var umis_no = $("#umis_no").val();
    if (umis_no != '') {
        umis_div();
        // toggleDiv_umis();
    }







});

function get_group() {

    var emis_class = $("#emis_class").val();
    if (emis_class == '11' || emis_class == '12') {

        document.getElementById('emis_group').style.display = 'block';
        document.getElementById('group_lbl').style.display = 'block';
    } else {
        document.getElementById('emis_group').style.display = 'none';
        document.getElementById('group_lbl').style.display = 'none';

    }
}





function cancel_app() {

    // var emis_name = $("#emis_name").val();
    var s1_unique_id = $("#s1_unique_id").val();

    var data = new FormData();

    // data.append("emis_name", emis_name);
    data.append("s1_unique_id", s1_unique_id);

    data.append("action", "cancel_app");


    $.ajax({
        type: "POST",
        url: "crud.php",
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        method: 'POST',

        success: function (data) {

            var obj = JSON.parse(data);
            var msg = obj.msg;

            var status = obj.status;
            var error = obj.error;


            if (msg == 'cancel_app') {
                url = "index.php";
                log_sweetalert("cancel_app", url);
            }
        },
        error: function (data) {
            alert("Network Error");
        }
    });

}

// document.addEventListener('contextmenu', function(event) {
//     event.preventDefault();
//               });

//               document.onkeydown = function(e)
//     {
//         if(event.keyCode == 123)
//         {
//             return false;
//         }
//         if(e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0))
//         {
//             return false;
//         }
//         if(e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0))
//         {
//             return false;
//         }
//         if(e.ctrlKey && e.keyCode == 'U'.charCodeAt(0))
//         {
//             return false;
//         }
//     if(e.ctrlKey && e.shiftKey && e.keyCode == 'C'.charCodeAt(0))
//     {
//       return false;
//     }
//     }

function com_no() {

    var cert_detail = $("#cert_detail").val();

    if (cert_detail == 'Yes') {

        document.getElementById('communityno_div').style.display = 'inline-flex';
        document.getElementById('upload_lbl_div').style.display = 'none';
        document.getElementById('upload_file_div').style.display = 'none';
        document.getElementById('com_file_lbl').style.display = 'block';
        document.getElementById('com_file_div').style.display = 'block';
        document.getElementById('togglecom').style.display = 'none';

    }
    else if (cert_detail == 'No') {

        document.getElementById('communityno_div').style.display = 'none';
        document.getElementById('togglecom').style.display = 'block';
        document.getElementById('upload_lbl_div').style.display = 'block';
        document.getElementById('upload_file_div').style.display = 'block';
        document.getElementById('com_file_lbl').style.display = 'none';
        document.getElementById('com_file_div').style.display = 'none';

    } else {

        document.getElementById('communityno_div').style.display = 'none';
        document.getElementById('togglecom').style.display = 'none';
        document.getElementById('upload_lbl_div').style.display = 'none';
        document.getElementById('upload_file_div').style.display = 'none';
        document.getElementById('com_file_lbl').style.display = 'none';
        document.getElementById('com_file_div').style.display = 'none';
    }

}



function onAadharKeyPress() {
    var aadhaarNo = document.getElementById("aadhar_no").value;
    var aadhaarError = document.getElementById("aadhaarError");



    // Validate using Verhoeff algorithm
    if (!verhoeffCheck(aadhaarNo)) {
        aadhaarError.textContent = "Invalid Aadhaar number.";
        return;
    }

    // If all checks pass, clear error message
    aadhaarError.textContent = "";
}

function verhoeffCheck(num) {
    var d = 0;
    var p = 1;
    var inv;

    var verhoeffD = [
        [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
        [1, 2, 3, 4, 0, 6, 7, 8, 9, 5],
        [2, 3, 4, 0, 1, 7, 8, 9, 5, 6],
        [3, 4, 0, 1, 2, 8, 9, 5, 6, 7],
        [4, 0, 1, 2, 3, 9, 5, 6, 7, 8],
        [5, 9, 8, 7, 6, 0, 4, 3, 2, 1],
        [6, 5, 9, 8, 7, 1, 0, 4, 3, 2],
        [7, 6, 5, 9, 8, 2, 1, 0, 4, 3],
        [8, 7, 6, 5, 9, 3, 2, 1, 0, 4],
        [9, 8, 7, 6, 5, 4, 3, 2, 1, 0]
    ];

    // Verhoeff P table
    var verhoeffP = [
        [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
        [1, 5, 7, 6, 2, 8, 3, 0, 9, 4],
        [5, 8, 0, 3, 7, 9, 6, 1, 4, 2],
        [8, 9, 1, 6, 0, 4, 3, 5, 2, 7],
        [9, 4, 5, 3, 1, 2, 6, 8, 7, 0],
        [4, 2, 8, 6, 5, 7, 3, 9, 0, 1],
        [2, 7, 9, 3, 8, 0, 6, 4, 1, 5],
        [7, 0, 4, 6, 9, 1, 3, 2, 5, 8]
    ];


    for (var i = num.length - 1; i >= 0; i--) {
        inv = p ^ parseInt(num.charAt(i));
        p = verhoeffD[inv][d];
        d = verhoeffP[inv][d];
    }

    return d === 0;
}


var today = new Date();
var maxDate = new Date(today);
maxDate.setFullYear(maxDate.getFullYear() - 5);

// Get the year part of maxDate in full format (e.g., "2019")
var maxYear = maxDate.getFullYear();

// Set maxDate to the last day of the same month, five years ago
maxDate.setFullYear(maxYear, 0, 1); // Set to January 1st of the target year
maxDate.setFullYear(maxYear, 11, 31); // Set to December 31st of the same year

// Format maxDate as yyyy-mm-dd
var maxDateString = maxYear + "-12-31"; // December 31st of the target year

// Set max attribute of the input date field
document.getElementById("std_dob").setAttribute("max", maxDateString);
document.getElementById("dob").setAttribute("max", maxDateString);



$('#std_dob').change(function () {
    var dob = $(this).val();
    if (dob) {

        var today = new Date();
        var birthDate = new Date(dob);
        var age = today.getFullYear() - birthDate.getFullYear();
        var m = today.getMonth() - birthDate.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }

        $('#std_age').val(age);

    }
});
$('#t_dob').change(function () {

    var dob = $(this).val();
    if (dob) {

        var today = new Date();
        var birthDate = new Date(dob);
        var age = today.getFullYear() - birthDate.getFullYear();
        var m = today.getMonth() - birthDate.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }


        $('#age').val(age);
    }
});

function get_age(dob) {
    // var dob = $('#dob').val();


    if (dob) {
        // alert(dob);
        // Split the date string into day, month, and year components
        // var parts = dob.split('/');
        // Format the date as "YYYY-MM-DD"
        // var formattedDate = parts[2] + '-' + parts[1] + '-' + parts[0];

        var today = new Date();

        var birthDate = new Date(dob);

        var age = today.getFullYear() - birthDate.getFullYear();
        var m = today.getMonth() - birthDate.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }

        $('#age').val(age);
    }
}

function back(value) {


    if (value == "2") {

        $('a[href="#tab12"]').tab('show');

    }

    else if (value == "3") {
        $('a[href="#tab22"]').tab('show');



    } else if (value == "4") {
        $('a[href="#tab33"]').tab('show');



    } else if (value == "5") {
        $('a[href="#tab34"]').tab('show');


    }
}

function save_and_continue() {
    $('#v-pills-level-tab').tab('show');
}
function next() {
    $('#v-pills-home-tab').tab('show');
}

// function togglecommunity() {
//     var communityno = $("#communityno").val();

//     if (communityno != '') {

//         var ajax_url = "crud.php";
//         var data = {
//             "communityno": communityno,
//             "action": "insert_communityno"
//         };
//         $.ajax({
//             type: "POST",
//             url: ajax_url,
//             data: data,
//             dataType: 'json', // Parse response as JSON
//             success: function (response) {

//                 var hiddenDivUmis = document.getElementById("togglecom");
//                 if (hiddenDivUmis.style.display === "none") {
//                     hiddenDivUmis.style.display = "inline-flex";
//                 } else {
//                     hiddenDivUmis.style.display = "none";
//                 }
//             }
//         });
//     } else {
//         log_sweetalert("valid_com");
//     }
// }

function ready_toggle_cc() {
    // if(){
    var hiddenDivUmis = document.getElementById("togglecom");
    hiddenDivUmis.style.display = "inline-flex";
    // }
}

function togglecommunity() {
    var communityno = $("#communityno").val();

    if (communityno != '') {

        showLoader();
        var ajax_url = "crud.php";
        var data = {
            "communityno": communityno,
            "action": "insert_community"
        };

        $.ajax({
            type: "POST",
            url: ajax_url,
            data: data,
            dataType: 'json',
            success: function (response) {
                hideLoader();
                //console.log(response.data);
                var xmlData = response.data;

                var startIndex = xmlData.indexOf('<?xml');
                if (startIndex > 0) {
                    var xmlDeclaration = xmlData.substring(startIndex, xmlData.indexOf('?>', startIndex) + 2);
                    xmlData = xmlData.replace(xmlDeclaration, '');
                    xmlData = xmlDeclaration + xmlData;
                }

                // Parse the XML string into an XML document
                var parser = new DOMParser();
                var xmlDoc = parser.parseFromString(xmlData, "text/xml");


                var applicantMsgElement = xmlDoc.querySelector("MSG");
                //console.log(applicantMsgElement);
                // var applicantMsg = applicantMsgElement.textContent;

                if (applicantMsgElement == null) {


                    // Find the APPLICANTNAME element
                    var applicantNameElement = xmlDoc.querySelector("APPLICANTNAME");
                    var applicantCasteElement = xmlDoc.querySelector("CAST");
                    var applicantFatherElement = xmlDoc.querySelector("FATHERHUSNAME");
                    var applicantCommunityElement = xmlDoc.querySelector("COMMUNITY");
                    var applicantOutputElement = xmlDoc.querySelector("OUTPUTPDF");
                    var applicantAddressElement = xmlDoc.querySelector("ADDRESS");
                    var applicantVillageElement = xmlDoc.querySelector("VILLTOWN");
                    var applicantTalukElement = xmlDoc.querySelector("TALUK");
                    var applicantDistrictElement = xmlDoc.querySelector("DISTRICT");
                    var applicantPincodeElement = xmlDoc.querySelector("PINCODE");
                    var applicantGenderElement = xmlDoc.querySelector("GENDER");
                    var applicantReligionElement = xmlDoc.querySelector("RELIGION");
                    var applicantSerialElement = xmlDoc.querySelector("SERIAL_NO");
                    var applicantAuthorityElement = xmlDoc.querySelector("ISSUINGAUTHORITY");
                    var applicantIssueDateElement = xmlDoc.querySelector("DATEOFISSUE");
                    var applicantExpiryDateElement = xmlDoc.querySelector("DATEOFEXPIRY");
                    var applicantCertificateNoElement = xmlDoc.querySelector("CERTIFICATENO");
                    var applicantAttachementElement = xmlDoc.querySelector("ATTACHEMENT");


                    var applicantName = base64Encode(applicantNameElement.textContent);
                    var applicantCaste = base64Encode(applicantCasteElement.textContent);
                    var applicantFather = base64Encode(applicantFatherElement.textContent);
                    var applicantCommunity = base64Encode(applicantCommunityElement.textContent);
                    var applicantattachment = base64Encode(applicantOutputElement.textContent);
                    var applicantAddress = base64Encode(applicantAddressElement.textContent);
                    var applicantVillage = base64Encode(applicantVillageElement.textContent);
                    var applicantTaluk = base64Encode(applicantTalukElement.textContent);
                    var applicantDistrict = base64Encode(applicantDistrictElement.textContent);
                    var applicantPincode = base64Encode(applicantPincodeElement.textContent);
                    var applicantGender = base64Encode(applicantGenderElement.textContent);
                    var applicantReligion = base64Encode(applicantReligionElement.textContent);
                    var applicantSerial = base64Encode(applicantSerialElement.textContent);
                    var applicantAuthority = base64Encode(applicantAuthorityElement.textContent);
                    var applicantIssueDate = base64Encode(applicantIssueDateElement.textContent);
                    var applicantExpiryDate = base64Encode(applicantExpiryDateElement.textContent);
                    var applicantCertificateNo = base64Encode(applicantCertificateNoElement.textContent);
                    var applicantAttachement = base64Encode(applicantAttachementElement.textContent);
                    // alert(applicantName + applicantCaste + applicantFather);

                    var downloadLink = document.getElementById('download_link');

                    downloadLink.href = applicantOutputElement.textContent;

                    document.getElementById('fullname1').value = applicantNameElement.textContent;
                    document.getElementById('subcastename').value = applicantCasteElement.textContent;
                    document.getElementById('fathername3').value = applicantFatherElement.textContent;
                    document.getElementById('castename').value = applicantCommunityElement.textContent;
                    document.getElementById('community_pdf').value = applicantOutputElement.textContent;

                    var hiddenDivUmis = document.getElementById("togglecom");
                    hiddenDivUmis.style.display = "inline-flex";

                    var s1_unique_id = $("#s1_unique_id").val();

                    var ajax_url_1 = "crud.php";
                    var c_data = {
                        "s1_unique_id": base64Encode(s1_unique_id),
                        "applicant_name": applicantName,
                        "father_name": applicantFather,
                        "address": applicantAddress,
                        "village_town": applicantVillage,
                        "taluk_name": applicantTaluk,
                        "district": applicantDistrict,
                        "pincode": applicantPincode,
                        "gender": applicantGender,
                        "religion": applicantReligion,
                        "community": applicantCommunity,
                        "caste": applicantCaste,
                        "serial_no": applicantSerial,
                        "issuing_authority": applicantAuthority,
                        "date_issue": applicantIssueDate,
                        "date_expiry": applicantExpiryDate,
                        "certificate_no": applicantCertificateNo,
                        "attachment": applicantAttachement,
                        "output_pdf": applicantattachment,
                        "action": "community_certificate"
                    };


                    $.ajax({
                        type: "POST",
                        url: ajax_url_1,
                        data: c_data,

                        success: function (data) {

                            if (response.status) {
                                // Handle success

                            } else {
                                // Handle error
                                console.log(response.data);
                            }
                        },
                        error: function (xhr, status, error) {
                            // Handle AJAX error
                            console.error(xhr.responseText);
                        }
                    });

                } else {
                    log_sweetalert('no_community_record');
                }

            },
            error: function (xhr, status, error) {
                hideLoader();
                console.error("AJAX error:", xhr.responseText);
                // Handle AJAX error
            }
        });

    } else {
        hideLoader();
        var hiddenDivUmis = document.getElementById("togglecom");
        hiddenDivUmis.style.display = "none";
        log_sweetalert('valid_emis');
    }
}

function get_emis_umis() {

    var student_type = $("#student_type").val();

    if (student_type == '65f00a259436412348') {

        document.getElementById('emis_div').style.display = 'block';
        document.getElementById('umis_div').style.display = 'none';
        document.getElementById('scl_dis').style.display = 'block';
        document.getElementById('clg_dis').style.display = 'none';
        document.getElementById('emis_hostel_district').style.display = 'block';
        document.getElementById('hostel_district').style.display = 'none';
        document.getElementById('fg_lbl').style.display = 'none';
        document.getElementById('fg_div').style.display = 'none';
        document.getElementById('adj_dis').style.display = 'block';
        document.getElementById('adj_dis_lbl').style.display = 'block';




    }
    else {

        document.getElementById('emis_div').style.display = 'none';
        document.getElementById('umis_div').style.display = 'block';
        document.getElementById('havingUmis').style.display = 'none';
        document.getElementById('scl_dis').style.display = 'none';
        document.getElementById('clg_dis').style.display = 'block';
        document.getElementById('emis_hostel_district').style.display = 'none';
        document.getElementById('hostel_district').style.display = 'block';
        document.getElementById('adj_dis').style.display = 'block';
        document.getElementById('adj_dis_lbl').style.display = 'block';


    }

}




function overall_submit() {

    var s1_unique_id = $("#s1_unique_id").val();

    var ajax_url = "crud.php";

    var data = {
        "s1_unique_id": s1_unique_id,
        "action": "get_host_cnt"
    }

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function (data) {
            if (data == '0') {
                // log_sweetalert("no_host_sel");
                log_sweetalert("no_priority_1");
            } else {
                $.ajax({
                    type: "POST",
                    url: ajax_url,
                    data: {
                        "s1_unique_id": s1_unique_id,
                        "action": "calculate_distances"
                    },
                    success: function (res) {
                    
                        var result = JSON.parse(res);
                        console.log(result.status);
                    },
                });

                log_sweetalert("submitted", "index.php");
            }
        }
    });

    // log_sweetalert("submitted", "index.php");
}

function focusOnEmptyField(input_id, error_id, message) {
    // Check if the input field is empty

    $('#' + error_id).text(message); // Display the error message
    $('#' + input_id).focus(); // Focus the input field
    // return false; // Return false to prevent form submission or further processing


}

function aadhar_confirmation_add() {

    var std_name = $("#std_name").val();
    var std_dob = $("#std_dob").val();
    var std_mobile_no = $("#std_mobile_no").val();

    var std_age = $("#std_age").val();
    var std_gender = $("#gender_id").val();
    var father_name = $("#father_name").val();
    var std_address = $("#std_address").val();
    var std_app_no = $("#std_app_no").val();

    var s1_unique_id = $("#s1_unique_id").val();

    var first_check = document.getElementById('first_check');




    $(".error-message").text('');
    if (!std_mobile_no) {
        focusOnEmptyField('std_mobile_no', 'error-std-mob-no', "Mobile Number is required");
        return false;
    }

    if (first_check.checked) {
        // alert('ff');
        if ((std_name && std_dob && std_age && std_gender && father_name && std_address && std_mobile_no) != '') {
            var ajax_url = "crud.php";
            // if (aadhar_no != '' && ration_card_no != '') {
            var data = new FormData();

            data.append("std_name", base64Encode(std_name));
            data.append("std_dob", base64Encode(std_dob));
            data.append("std_age", base64Encode(std_age));
            data.append("std_gender", base64Encode(std_gender));
            data.append("father_name", base64Encode(father_name));
            data.append("std_address", base64Encode(std_address));
            data.append("std_app_no", base64Encode(std_app_no));
            data.append("std_mobile_no", base64Encode(std_mobile_no));

            data.append("s1_unique_id", base64Encode(s1_unique_id));


            // data.append("p1_unique_id", p1_unique_id);
            data.append("action", "aadhar_confirmation_add");
            // data.append("unique_id", unique_id);

            $.ajax({
                type: "POST",
                url: "crud.php",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',

                success: function (data) {

                    var obj = JSON.parse(data);
                    var msg = obj.msg;
                    // var std_app_no = obj.std_app_no;
                    var status = obj.status;
                    var error = obj.error;


                    if (!status) {
                        url = '';
                        $(".createupdate_btn").text("Error");
                        // alert("Error");
                        console.log(error);
                    } else {
                        if (msg == "already") {
                            // Button Change Attribute
                            url = '';


                            log_sweetalert(msg);
                        } else {

                            log_sweetalert("create");
                            // $('#tab22').tab('show');
                            $('a[href="#tab22"]').tab('show');

                        }
                    }



                },
                error: function (data) {
                    alert("Network Error");
                }
            });


        } else {
            log_sweetalert("form_alert");
        }
    } else {
        alert('Please check the confirmation checkbox');
    }
}


function emis_details_add() {

    var emis_name = $("#emis_name").val();
    var emis_no = $("#emis_no").val();
    var emis_dob = $("#emis_dob").val();
    var emis_class = $("#emis_class").val();
    var emis_group = $("#emis_group").val();
    var emis_medium = $("#emis_medium").val();
    var emis_school_name = $("#emis_school_name").val();
    var emis_school_block = $("#emis_school_block").val();
    var emis_school_district = $("#emis_school_district").val();

    var s1_unique_id = $("#s1_unique_id").val();
    var emis_check = document.getElementById('emis_check');
    if (emis_check.checked) {
        // alert('ff');
        if ((emis_name && emis_no && emis_dob && emis_class && emis_medium && emis_school_name && emis_school_block && emis_school_district) != '') {
            var ajax_url = "crud.php";
            // if (aadhar_no != '' && ration_card_no != '') {
            var data = new FormData();

            data.append("emis_name", base64Encode(emis_name));
            data.append("emis_no", base64Encode(emis_no));
            data.append("emis_dob", base64Encode(emis_dob));
            data.append("emis_class", base64Encode(emis_class));
            data.append("emis_group", base64Encode(emis_group));
            data.append("emis_medium", base64Encode(emis_medium));
            data.append("emis_school_name", base64Encode(emis_school_name));
            data.append("emis_school_block", base64Encode(emis_school_block));
            data.append("emis_school_district", base64Encode(emis_school_district));

            data.append("s1_unique_id", base64Encode(s1_unique_id));


            // data.append("p1_unique_id", p1_unique_id);
            data.append("action", "emis_details_add");
            // data.append("unique_id", unique_id);

            $.ajax({
                type: "POST",
                url: "crud.php",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                // beforeSend 	: function() {
                // 	$(".createupdate_btn").attr("disabled","disabled");
                // 	$(".createupdate_btn").text("Loading...");
                // },
                success: function (data) {

                    var obj = JSON.parse(data);
                    var msg = obj.msg;
                    // var std_app_no = obj.std_app_no;
                    var status = obj.status;
                    var error = obj.error;

                    if (!status) {
                        url = '';
                        $(".createupdate_btn").text("Error");
                        // alert("Error");
                        console.log(error);
                    } else {
                        if (msg == "already") {

                            url = '';


                            log_sweetalert(msg);
                        } else {
                            //    $(".createupdate_btn").text("Add");
                            host_district();
                            store_emis();
                            log_sweetalert("create");
                            $('a[href="#tab33"]').tab('show');


                            // $("#status_description").val("");
                        }
                    }



                },
                error: function (data) {
                    alert("Network Error");
                }
            });


        } else {
            log_sweetalert("form_alert");
        }
    } else {
        alert('Please check the confirmation checkbox');
    }
}

function base64Encode(str) {
    return btoa(unescape(encodeURIComponent(str)));
}

function store_emis() {

    var emis_name = $("#emis_name").val();
    var emis_no = $("#emis_no").val();
    var emis_dob = $("#emis_dob").val();
    var emis_class = $("#emis_class").val();
    var emis_group = $("#emis_group").val();
    var emis_medium = $("#emis_medium").val();
    var emis_school_name = $("#emis_school_name").val();
    var emis_school_block = $("#emis_school_block").val();
    var emis_school_district = $("#emis_school_district").val();

    var emis_father_name = $("#emis_father_name").val();
    var emis_mother_name = $("#emis_mother_name").val();
    var emis_father_occupation = $("#emis_father_occupation").val();
    var emis_mother_occupation = $("#emis_mother_occupation").val();
    var group_code_id = $("#group_code_id").val();
    var community_name = $("#community_name").val();
    var class_section = $("#class_section").val();
    var udise_code = $("#udise_code").val();



    var s1_unique_id = $("#s1_unique_id").val();

    // alert('ff');

    var ajax_url = "crud.php";
    // if (aadhar_no != '' && ration_card_no != '') {
    var data = new FormData();

    data.append("emis_name", base64Encode(emis_name));
    data.append("emis_no", base64Encode(emis_no));
    data.append("emis_dob", base64Encode(emis_dob));
    data.append("emis_class", base64Encode(emis_class));
    data.append("emis_group", base64Encode(emis_group));
    data.append("emis_medium", base64Encode(emis_medium));
    data.append("emis_school_name", base64Encode(emis_school_name));
    data.append("emis_school_block", base64Encode(emis_school_block));
    data.append("emis_school_district", base64Encode(emis_school_district));

    data.append("udise_code", base64Encode(udise_code));
    data.append("class_section", base64Encode(class_section));
    data.append("community_name", base64Encode(community_name));
    data.append("group_code_id", base64Encode(group_code_id));
    data.append("emis_mother_occupation", base64Encode(emis_mother_occupation));
    data.append("emis_father_occupation", base64Encode(emis_father_occupation));
    data.append("emis_mother_name", base64Encode(emis_mother_name));
    data.append("emis_father_name", base64Encode(emis_father_name));
    data.append("s1_unique_id", base64Encode(s1_unique_id));
    data.append("action", "store_emis");


    $.ajax({
        type: "POST",
        url: "crud.php",
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        method: 'POST',

        success: function (data) {

            var obj = JSON.parse(data);
            var msg = obj.msg;

            var status = obj.status;
            var error = obj.error;
        },
        error: function (data) {
            alert("Network Error");
        }
    });

}

function get_course1() {
    alert();
    var course = $("#no_umis_course").val();
    alert(course);
}




function umis_details_add() {


    var yr_stdy = $("#yr_stdy").val();
    var lat_entry = $("#lat_entry").val();

    var umis_no = $("#umis_no").val();
    var umisSelect = $("#umisSelect").val();
    var umis_std_name = $("#umis_std_name").val();

    var umis_dob = $("#umis_dob").val();
    var umis_std_degree = $("#umis_std_degree").val();
    var umis_std_course = $("#umis_std_course").val();
    var umis_yoa = $("#umis_yoa").val();
    var umis_yos = $("#umis_yos").val();
    var umis_clg_name = $("#umis_clg_name").val();
    var umis_clg_add = $("#umis_clg_add").val();
    var no_umis_name = $("#no_umis_name").val();
    var no_umis_course = $("#no_umis_course").val();
    var no_umis_branch = $("#no_umis_branch").val();
    var no_umis_stream = $("#no_umis_stream").val();
    var no_umis_college = $("#no_umis_college").val();
    var no_umis_clg_district = $("#no_umis_clg_district").val();
    var no_umis_pincode = $("#no_umis_pincode").val();
    var no_umis_yoa = $("#no_umis_yoa").val();
    var no_umis_yos = $("#no_umis_yos").val();
    var caDistrictId = $("#caDistrictId").val();
    // alert(no_umis_course);
    var s1_unique_id = $("#s1_unique_id").val();
    var umis_check = document.getElementById('umis_check');

    
    var no_umis_check = document.getElementById('no_umis_check');

    if (umisSelect == 'No') {
        if (!no_umis_name) {
            focusOnEmptyField('no_umis_name', 'error_no_umis_name', "Name is required");
            return false;
        } else {
            $('#error_no_umis_name').text('');
        }

        if (!no_umis_yoa) {
            focusOnEmptyField('no_umis_yoa', 'error_no_umis_yoa', "Year Of Admission is required");
            return false;
        } else {
            $('#error_no_umis_yoa').text('');
        }
        if (!no_umis_stream) {
            focusOnEmptyField('no_umis_stream', 'error_no_umis_stream', "Stream is required");
            return false;
        } else {
            $('#error_no_umis_stream').text('');
        }
        if (!no_umis_course) {
            focusOnEmptyField('no_umis_course', 'error_no_umis_course', "Course is required");
            return false;
        } else {
            $('#error_no_umis_course').text('');
        }

        if (!no_umis_branch) {
            focusOnEmptyField('no_umis_branch', 'error_no_umis_branch', "Branch is required");
            return false;
        } else {
            $('#error_no_umis_branch').text('');
        }

        if (!no_umis_college) {
            focusOnEmptyField('no_umis_college', 'error_no_umis_college', "College Name is required");
            return false;
        } else {
            $('#error_no_umis_college').text('');
        }
        if (!no_umis_clg_district) {
            focusOnEmptyField('no_umis_clg_district', 'error_no_umis_clg_district', "District is required");
            return false;
        } else {
            $('#error_no_umis_clg_district').text('');
        }

        if (!no_umis_pincode) {
            focusOnEmptyField('no_umis_pincode', 'error_no_umis_pincode', "Pincode is required");
            return false;
        } else {
            $('#error_no_umis_pincode').text('');
        }
    }

    if (umis_check.checked || no_umis_check.checked) {
        if (((no_umis_name && no_umis_course && no_umis_stream && no_umis_college && no_umis_clg_district && no_umis_pincode && no_umis_yoa && no_umis_branch) != '') || ((umis_no && umis_std_name && umis_dob && umis_std_degree && umis_std_course && umis_yoa && umis_yos && umis_clg_name && umis_clg_add) != '')) {
            // alert('ff');

            var ajax_url = "crud.php";
            // if (aadhar_no != '' && ration_card_no != '') {
            var data = new FormData();

            data.append("umisSelect", base64Encode(umisSelect));
            data.append("no_umis_name", base64Encode(no_umis_name));
            data.append("no_umis_course", base64Encode(no_umis_course));
            data.append("no_umis_branch", base64Encode(no_umis_branch));
            data.append("no_umis_stream", base64Encode(no_umis_stream));
            data.append("no_umis_college", base64Encode(no_umis_college));
            data.append("no_umis_clg_district", base64Encode(no_umis_clg_district));
            data.append("no_umis_pincode", base64Encode(no_umis_pincode));
            data.append("caDistrictId", base64Encode(caDistrictId));
            data.append("no_umis_yoa", base64Encode(no_umis_yoa));
            data.append("no_umis_yos", base64Encode(no_umis_yos));
            data.append("umis_no", base64Encode(umis_no));
            data.append("umis_std_name", base64Encode(umis_std_name));
            data.append("umis_dob", base64Encode(umis_dob));
            data.append("umis_std_degree", base64Encode(umis_std_degree));
            data.append("umis_std_course", base64Encode(umis_std_course));
            data.append("umis_yoa", base64Encode(umis_yoa));
            data.append("umis_yos", base64Encode(umis_yos));
            data.append("umis_clg_name", base64Encode(umis_clg_name));
            data.append("umis_clg_add", base64Encode(umis_clg_add));
            data.append("yr_stdy", base64Encode(yr_stdy));
            data.append("lat_entry", base64Encode(lat_entry));

            data.append("s1_unique_id", base64Encode(s1_unique_id));


            // data.append("p1_unique_id", p1_unique_id);
            data.append("action", "umis_details_add");
            // data.append("unique_id", unique_id);

            $.ajax({
                type: "POST",
                url: "crud.php",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                // beforeSend 	: function() {
                // 	$(".createupdate_btn").attr("disabled","disabled");
                // 	$(".createupdate_btn").text("Loading...");
                // },
                success: function (data) {

                    var obj = JSON.parse(data);
                    var msg = obj.msg;
                    // var std_app_no = obj.std_app_no;
                    var status = obj.status;
                    var error = obj.error;

                    if (!status) {
                        url = '';
                        $(".createupdate_btn").text("Error");
                        // alert("Error");
                        console.log(error);
                    } else {
                        if (msg == "already") {
                            // Button Change Attribute
                            url = '';



                            log_sweetalert(msg);
                        } else {
                            //    $(".createupdate_btn").text("Add");
                            log_sweetalert("create");
                            $('a[href="#tab33"]').tab('show');


                            // $("#status_description").val("");
                        }
                    }



                },
                error: function (data) {
                    alert("Network Error");
                }
            });


        } else {
            log_sweetalert("form_alert");
        }
    } else {
        alert('Please check the confirmation checkbox');
    }
}


function hostel_sub_add_update(s1_unique_id, hostel_district, hostel_taluk, hostel_name, gender_type, hostel_type, priority) {
    // var priority = $("#priority").val();
    // var hostel_district = $("#hostel_district").val();
    // var hostel_taluk = $("#hostel_taluk").val();
    // var gender_type = $("#gender_id").val();
    // var hostel_type = $("#student_type").val();
    // var hostel_name = $("#hostel_name").val();
    // var s1_unique_id = $("#s1_unique_id").val();

    var ajax_url = "crud.php";
    if (hostel_district != '' && hostel_name != '') {
        var data = new FormData();


        data.append("priority", base64Encode(priority));
        data.append("hostel_district", base64Encode(hostel_district));
        data.append("hostel_taluk", base64Encode(hostel_taluk));
        data.append("gender_type", base64Encode(gender_type));
        data.append("hostel_type", base64Encode(hostel_type));
        data.append("hostel_name", base64Encode(hostel_name));
        data.append("s1_unique_id", base64Encode(s1_unique_id));
        data.append("action", "hostel_sub_add_update");
        // data.append("unique_id", unique_id);

        $.ajax({
            type: "POST",
            url: "crud.php",
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',

            success: function (data) {

                var obj = JSON.parse(data);
                var msg = obj.msg;



                // if (!status) {
                //     url = '';
                //     $(".createupdate_btn").text("Error");
                //     console.log(error);
                // } else {
                if (msg == "already") {

                    url = '';
                    log_sweetalert('priority_exceed');

                }
                if (msg == 'save') {

                    log_sweetalert("priority_added");

                }


                sub_list_datatable("hostel_sub_datatable");

            },
            error: function (data) {
                alert("Network Error");
            }
        });


    } else {
        log_sweetalert("form_alert");
    }
}

function get_priority_count() {

    var s1_unique_id = $("#s1_unique_id").val();

    var ajax_url = "crud.php";
    if (s1_unique_id) {
        var data = {
            "s1_unique_id": s1_unique_id,
            "action": "get_priority_count"
        }

        $.ajax({
            type: "POST",
            url: ajax_url,
            data: data,
            success: function (data) {

                if (data) {
                    $("#priority").html(data);
                }
            }
        });
    }
}

function get_hostel_list() {

    sub_list_datatable("hostel_sub_datatable", "hostel_sub_datatable");

}

function get_umis_district() {

    var caDistrictId = $("#caDistrictId").val();


    var ajax_url = "crud.php";
    // if (hostel_district) {
    var data = {
        "caDistrictId": caDistrictId,
        "action": "get_umis_district"
    }

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function (data) {

            $("#umis_district").val(data);
            // $("#umis_dist").text(data);
            host_district();

        }
    });

}


function sub_list_datatable(table_id = "", action = "") {
    // get_priority_count();

    var s1_unique_id = $("#s1_unique_id").val();
    var adjacent_district = $("#adjacent_district").val();
    var gender_type = $("#gender_id").val();
    var hostel_type = $("#student_type").val();
    var hostel_district = $("#hostel_district").val();
    var emis_hostel_district = $("#emis_hostel_district").val();
    var umis_district = $("#umis_district").val();


    var table = $("#" + table_id);
    var data = {
        "s1_unique_id": s1_unique_id,
        "adjacent_district": adjacent_district,
        "gender_type": gender_type,
        "hostel_type": hostel_type,
        "hostel_district": hostel_district,
        "emis_hostel_district": emis_hostel_district,
        "umis_district": umis_district,
        "action": table_id,
    };
    var ajax_url = sessionStorage.getItem("folder_crud_link");
    var datatable = new DataTable(table, {
        destroy: true,
        "searching": false,
        "paging": false,
        "ordering": false,
        "info": false,
        "ajax": {
            url: "crud.php",
            type: "POST",
            data: data
        }

    });

}


function sub_list_datatable_old(table_id = "", action = "") {
    get_priority_count();

    var s1_unique_id = $("#s1_unique_id").val();


    var table = $("#" + table_id);
    var data = {
        "s1_unique_id": s1_unique_id,

        "action": table_id,
    };
    var ajax_url = sessionStorage.getItem("folder_crud_link");
    var datatable = new DataTable(table, {
        destroy: true,
        "searching": false,
        "paging": false,
        "ordering": false,
        "info": false,
        "ajax": {
            url: "crud.php",
            type: "POST",
            data: data
        }

    });
}





function hostel_sub_delete(unique_id = "", priority = "") {

    var s1_unique_id = $("#s1_unique_id").val();
    if (unique_id) {

        var ajax_url = "crud.php"
        var url = sessionStorage.getItem("list_link");

        confirm_delete('delete')
            .then((result) => {
                if (result.isConfirmed) {

                    var data = {
                        "unique_id": unique_id,
                        "priority": priority,
                        "s1_unique_id": s1_unique_id,
                        "action": "hostel_sub_delete"
                    }

                    $.ajax({
                        type: "POST",
                        url: ajax_url,
                        data: data,
                        success: function (data) {
                            // alert(data);

                            var obj = JSON.parse(data);
                            var msg = obj.msg;
                            var status = obj.status;
                            var error = obj.error;

                            if (!status) {
                                url = '';
                            } else {
                                sub_list_datatable("hostel_sub_datatable");
                                // location.reload();
                            }

                            log_sweetalert(msg, url);
                        }
                    });

                } else {
                    // alert("cancel");
                }
            });
    }
}


function get_adjacent_district() {


    var hostel_district = $("#hostel_district").val();
    var emis_school_district = $("#emis_school_district").val();
    var umis_district = $("#umis_district").val();
    $("#emis_hostel_district").val(emis_school_district);
    $("#emis_dis").text(emis_school_district);


    var ajax_url = "crud.php";

    var data = {
        "hostel_district": hostel_district,
        "emis_school_district": emis_school_district,
        "umis_district": umis_district,
        "action": "get_adjacent_district"
    }

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function (data) {

            var obj = JSON.parse(data);
            var adjacent_district = obj.adjacent_district;
            var adjacent_district_name = obj.adjacent_district_name;
            var district_name = obj.district_name;

            if (data) {

                $("#adjacent_district").val(adjacent_district);

                $("#adj_dis").text(adjacent_district_name);
                $("#host_name").text(district_name);

                sub_list_datatable("hostel_sub_datatable", "hostel_sub_datatable");
            }
        }
    });
}


function get_institute_name() {

    var no_umis_clg_district = $("#no_umis_clg_district").val();


    var ajax_url = "crud.php";
    if (no_umis_clg_district) {
        var data = {
            "no_umis_clg_district": no_umis_clg_district,
            "action": "get_institute_name"
        }

        $.ajax({
            type: "POST",
            url: ajax_url,
            data: data,
            success: function (data) {

                if (data) {
                    $("#no_umis_college").html(data);
                }
            }
        });
    }
}

function get_taluk_name() {

    var hostel_district = $("#hostel_district").val();


    var ajax_url = "crud.php";
    if (hostel_district) {
        var data = {
            "hostel_district": hostel_district,
            "action": "get_taluk_name"
        }

        $.ajax({
            type: "POST",
            url: ajax_url,
            data: data,
            success: function (data) {

                if (data) {
                    $("#hostel_taluk").html(data);
                }
            }
        });
    }
}

function get_hostel_name() {

    var hostel_district = $("#hostel_district").val();
    var hostel_taluk = $("#hostel_taluk").val();
    var gender_id = $("#gender_id").val();
    var student_type = $("#student_type").val();
    var s1_unique_id = $("#s1_unique_id").val();

    // $("#host_type").val(hostel_type);




    var ajax_url = "crud.php";
    // if (hostel_district) {
    var data = {
        "hostel_district": hostel_district,
        "hostel_taluk": hostel_taluk,
        "hostel_gender_type": gender_id,
        "hostel_type": student_type,
        "s1_unique_id": s1_unique_id,
        "action": "get_hostel_name"
    }

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function (data) {
            // alert(data);
            if (data) {
                $("#hostel_name").html(data);
                // get_host_type();
            }
        }
    });

}

function get_blood_group() {

    var bloodgroup = $("#bloodgroup").val();


    // $("#host_type").val(hostel_type);




    var ajax_url = "crud.php";
    // if (hostel_district) {
    var data = {
        "bloodgroup": bloodgroup,
        "action": "get_blood_group"
    }

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function (data) {
            // alert(data);
            // if (data) {
            $("#bloodgroup").val(data);
            // get_host_type();
            // }
        }
    });

}
 
function get_degree() {

    var no_umis_stream = $("#no_umis_stream").val();

    var ajax_url = "crud.php";
    // if (hostel_district) {
    var data = {
        "no_umis_stream": no_umis_stream,
        "action": "get_degree"
    }

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function (data) {

            // if (data) {
            $("#no_umis_course").html(data);
            // get_host_type();
            // }
        }
    });

}


function get_course_branch() {

    // alert();
    var no_umis_course = $("#no_umis_course").val();

    var ajax_url = "crud.php";
    // if (hostel_district) {
    var data = {
        "no_umis_course": no_umis_course,
        "action": "get_course_branch"
    }

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function (data) {

            // if (data) {
            $("#no_umis_branch").html(data);
            // get_host_type();
            // }
        }
    });

}


function get_gender() {
    var gender_type = $("#gender_id").val();
    // $("#gender_id").val(gender_type);

    if (gender_type == "65584660e85afd2400") {

        // sub_list_datatable("hostel_sub_datatable", "hostel_sub_datatable");
    } else if (gender_type == "65584660e85afd2401") {

        // $("#host_gender").val('Female');
        // sub_list_datatable("hostel_sub_datatable", "hostel_sub_datatable");
    }

}

function host_district() {

    var district_name = $("#no_umis_clg_district").val();
    var emis_school_district = $("#emis_school_district").val();
    var umis_district = $("#umis_district").val();


    $("#hostel_district").val(district_name);
    // $("#hostel_district_name").val(district_name);
    if (district_name || emis_school_district || umis_district) {
        get_adjacent_district();
    }

}

function get_first_check(value) {

    var value = $("input[name='first_check']:checked").val();
    if (value == "YES") {

        document.getElementById('aadhar_btn').disabled = false;
    } else {
        document.getElementById('aadhar_btn').disabled = true;

    }

}

function get_cert_detail() {

    var com_name = $("#com_name").val();

    if (com_name == 'ST' || com_name == 'OC') {

        $("#communityno").val('');
        $("#fullname1").val('');
        $("#castename").val('');
        $("#subcastename").val('');
        $("#fathername3").val('');
        $("#mothername3").val('');
        $("#communitycer").val('');
        document.getElementById('cert_detail_lbl').style.display = 'block';
        document.getElementById('cert_detail_div').style.display = 'block';
        document.getElementById('com_file_lbl').style.display = 'none';
        document.getElementById('com_file_div').style.display = 'none';
        document.getElementById('upload_lbl_div').style.display = 'block';
        document.getElementById('upload_file_div').style.display = 'block';
        document.getElementById('togglecom').style.display = 'none';
        document.getElementById('communityno_div').style.display = 'none';

    }
    else if (com_name == 'BC' || com_name == 'MBC' || com_name == 'SC' || com_name == 'SCA') {

        $("#cert_detail").val('');
        $("#communityno").val('');
        $("#fullname1").val('');
        $("#castename").val('');
        $("#subcastename").val('');
        $("#fathername3").val('');
        $("#mothername3").val('');
        $("#communitycer").val('');
        document.getElementById('communityno_div').style.display = 'inline-flex';
        document.getElementById('cert_detail_div').style.display = 'none';
        document.getElementById('cert_detail_lbl').style.display = 'none';
        document.getElementById('upload_lbl_div').style.display = 'none';
        document.getElementById('upload_file_div').style.display = 'none';
        document.getElementById('com_file_lbl').style.display = 'block';
        document.getElementById('com_file_div').style.display = 'block';
        document.getElementById('togglecom').style.display = 'none';



    } else {
        $("#cert_detail").val('');
        document.getElementById('communityno_div').style.display = 'none';
        document.getElementById('cert_detail_div').style.display = 'none';
        document.getElementById('cert_detail_lbl').style.display = 'none';
        document.getElementById('upload_lbl_div').style.display = 'none';
        document.getElementById('upload_file_div').style.display = 'none';
        document.getElementById('com_file_lbl').style.display = 'none';
        document.getElementById('com_file_div').style.display = 'none';
    }

}

function ready_get_cert_detail() {

    var com_name = $("#com_name").val();

    if (com_name == 'ST' || com_name == 'OC') {

        document.getElementById('cert_detail_lbl').style.display = 'block';
        document.getElementById('cert_detail_div').style.display = 'block';
        document.getElementById('com_file_lbl').style.display = 'none';
        document.getElementById('com_file_div').style.display = 'none';
        document.getElementById('upload_lbl_div').style.display = 'block';
        document.getElementById('upload_file_div').style.display = 'block';
        document.getElementById('togglecom').style.display = 'none';
        document.getElementById('communityno_div').style.display = 'none';

    }
    else if (com_name == 'BC' || com_name == 'MBC' || com_name == 'SC' || com_name == 'SCA') {


        document.getElementById('communityno_div').style.display = 'inline-flex';
        document.getElementById('cert_detail_div').style.display = 'none';
        document.getElementById('cert_detail_lbl').style.display = 'none';
        document.getElementById('upload_lbl_div').style.display = 'none';
        document.getElementById('upload_file_div').style.display = 'none';
        document.getElementById('com_file_lbl').style.display = 'block';
        document.getElementById('com_file_div').style.display = 'block';
        document.getElementById('togglecom').style.display = 'none';



    } else {

        $("#cert_detail").val('');
        document.getElementById('communityno_div').style.display = 'none';
        document.getElementById('cert_detail_div').style.display = 'none';
        document.getElementById('cert_detail_lbl').style.display = 'none';
        document.getElementById('upload_lbl_div').style.display = 'none';
        document.getElementById('upload_file_div').style.display = 'none';
        document.getElementById('com_file_lbl').style.display = 'none';
        document.getElementById('com_file_div').style.display = 'none';
        document.getElementById('togglecom').style.display = 'none';
    }

}

function get_com_no() {

    var cert_detail = $("#cert_detail").val();

    if (cert_detail == 'Yes') {
        $("#communityno").val('');
        $("#fullname1").val('');
        $("#castename").val('');
        $("#subcastename").val('');
        $("#fathername3").val('');
        $("#communitycer").val('');
        $("#community_pdf").val('');
        document.getElementById('communityno_div').style.display = 'inline-flex';
        document.getElementById('upload_lbl_div').style.display = 'none';
        document.getElementById('upload_file_div').style.display = 'none';
        document.getElementById('com_file_lbl').style.display = 'block';
        document.getElementById('com_file_div').style.display = 'block';
        document.getElementById('togglecom').style.display = 'none';

    }
    else {
        $("#communityno").val('');
        $("#fullname1").val('');
        $("#castename").val('');
        $("#subcastename").val('');
        $("#fathername3").val('');
        $("#communitycer").val('');
        $("#community_pdf").val('');

        document.getElementById('communityno_div').style.display = 'none';
        document.getElementById('togglecom').style.display = 'block';
        document.getElementById('upload_lbl_div').style.display = 'block';
        document.getElementById('upload_file_div').style.display = 'block';
        document.getElementById('com_file_lbl').style.display = 'none';
        document.getElementById('com_file_div').style.display = 'none';

    }

}


function ltl_div(value) {

    var value = $("#yr_stdy").val();


    if (value == "2") {



        document.getElementById('lat_div').style.display = 'flex';
        document.getElementById('have_umis_div').style.display = 'none';
        document.getElementById('havingUmis').style.display = 'none';
        document.getElementById('Noumis').style.display = 'none';
        document.getElementById('toggleUmis').style.display = 'none';

    } else if (value == "1") {


        document.getElementById('havingUmis').style.display = 'none';
        document.getElementById('Noumis').style.display = 'none';

        document.getElementById('lat_div').style.display = 'none';
        document.getElementById('have_umis_div').style.display = 'flex';
        document.getElementById('toggleUmis').style.display = 'none';

    } else if (value == "3" || value == "4" || value == "5") {


        document.getElementById('lat_div').style.display = 'none';
        document.getElementById('have_umis_div').style.display = 'none';
        document.getElementById('havingUmis').style.display = 'block';
        document.getElementById('toggleUmis').style.display = 'none';
        document.getElementById('Noumis').style.display = 'none';
        document.getElementById('t_dob').style.display = 'none';
        document.getElementById('dob').style.display = 'block';
        document.getElementById('bloodgroup').style.display = 'block';
        document.getElementById('bloodgroup_opt').style.display = 'none';

    } else {
        document.getElementById('lat_div').style.display = 'none';
        document.getElementById('have_umis_div').style.display = 'none';
        document.getElementById('havingUmis').style.display = 'none';
        document.getElementById('toggleUmis').style.display = 'none';
        document.getElementById('Noumis').style.display = 'none';
        document.getElementById('t_dob').style.display = 'none';
        document.getElementById('dob').style.display = 'block';
    }
}

function get_lat_div(value) {
    var value = $("#yr_stdy").val();
    if (value == "2") {

        $("#lat_entry").val('');
        $("#umisSelect").val('');
        $("#umis_no").val('');
        $("#umis_std_name").val('');
        $("#umis_dob").val('');
        $("#umis_std_degree").val('');
        $("#umis_std_course").val('');
        $("#umis_yoa").val('');
        $("#umis_yos").val('');
        $("#umis_clg_name").val('');
        $("#umis_clg_add").val('');
        $("#caDistrictId").val('');
        $("#no_umis_name").val('');
        $("#no_umis_course").val('');
        $("#no_umis_stream").val('');
        $("#no_umis_college").val('');
        $("#no_umis_clg_district").val('');
        $("#no_umis_pincode").val('');
        $("#no_umis_yoa").val('');
        $("#no_umis_yos").val('');
        document.getElementById('dob').value = '';
        document.getElementById('bloodgroup').value = '';
        document.getElementById('mailid').value = '';
        document.getElementById('firstgraduate').value = '';
        document.getElementById('dadmobno').value = '';
        document.getElementById('dadOccupation').value = '';
        document.getElementById('momOccupation').value = '';
        document.getElementById('t_dob').value = '';
        document.getElementById('age').value = '';


        document.getElementById('lat_div').style.display = 'flex';
        document.getElementById('have_umis_div').style.display = 'none';
        document.getElementById('havingUmis').style.display = 'none';
        document.getElementById('Noumis').style.display = 'none';
        document.getElementById('toggleUmis').style.display = 'none';

    } else if (value == "1") {

        $("#lat_entry").val('');
        $("#umisSelect").val('');
        $("#umis_no").val('');
        $("#umis_std_name").val('');
        $("#umis_dob").val('');
        $("#umis_std_degree").val('');
        $("#umis_std_course").val('');
        $("#umis_yoa").val('');
        $("#umis_yos").val('');
        $("#umis_clg_name").val('');
        $("#umis_clg_add").val('');
        $("#caDistrictId").val('');
        $("#no_umis_name").val('');
        $("#no_umis_course").val('');
        $("#no_umis_stream").val('');
        $("#no_umis_college").val('');
        $("#no_umis_clg_district").val('');
        $("#no_umis_pincode").val('');
        $("#no_umis_yoa").val('');
        $("#no_umis_yos").val('');
        document.getElementById('dob').value = '';
        document.getElementById('bloodgroup').value = '';
        document.getElementById('mailid').value = '';
        document.getElementById('firstgraduate').value = '';
        document.getElementById('dadmobno').value = '';
        document.getElementById('dadOccupation').value = '';
        document.getElementById('momOccupation').value = '';
        document.getElementById('t_dob').value = '';
        document.getElementById('age').value = '';


        document.getElementById('havingUmis').style.display = 'none';
        document.getElementById('Noumis').style.display = 'none';

        document.getElementById('lat_div').style.display = 'none';
        document.getElementById('have_umis_div').style.display = 'flex';
        document.getElementById('toggleUmis').style.display = 'none';

    } else if (value == "3" || value == "4" || value == "5") {

        $("#lat_entry").val('');
        $("#umisSelect").val('');
        $("#umis_no").val('');
        $("#umis_std_name").val('');
        $("#umis_dob").val('');
        $("#umis_std_degree").val('');
        $("#umis_std_course").val('');
        $("#umis_yoa").val('');
        $("#umis_yos").val('');
        $("#umis_clg_name").val('');
        $("#umis_clg_add").val('');
        $("#caDistrictId").val('');
        $("#no_umis_name").val('');
        $("#no_umis_course").val('');
        $("#no_umis_stream").val('');
        $("#no_umis_college").val('');
        $("#no_umis_clg_district").val('');
        $("#no_umis_pincode").val('');
        $("#no_umis_yoa").val('');
        $("#no_umis_yos").val('');
        document.getElementById('dob').value = '';
        document.getElementById('bloodgroup').value = '';
        document.getElementById('mailid').value = '';
        document.getElementById('firstgraduate').value = '';
        document.getElementById('dadmobno').value = '';
        document.getElementById('dadOccupation').value = '';
        document.getElementById('momOccupation').value = '';
        document.getElementById('t_dob').value = '';
        document.getElementById('age').value = '';


        document.getElementById('lat_div').style.display = 'none';
        document.getElementById('have_umis_div').style.display = 'none';
        document.getElementById('havingUmis').style.display = 'block';
        document.getElementById('toggleUmis').style.display = 'none';
        document.getElementById('Noumis').style.display = 'none';
        document.getElementById('dob').style.display = 'block';
        document.getElementById('bloodgroup').style.display = 'block';
        document.getElementById('bloodgroup_opt').style.display = 'none';

    } else {
        $("#lat_entry").val('');
        $("#umisSelect").val('');
        document.getElementById('lat_div').style.display = 'none';
        document.getElementById('have_umis_div').style.display = 'none';
        document.getElementById('havingUmis').style.display = 'none';
        document.getElementById('toggleUmis').style.display = 'none';
        document.getElementById('Noumis').style.display = 'none';
    }
}

function get_umis_values() {

    var umisSelect = $("#umisSelect").val();

    if (umisSelect == 'No') {



        document.getElementById('havingUmis').style.display = 'none';
        document.getElementById("toggleUmis").style.display = 'block';
        document.getElementById('Noumis').style.display = 'inline-flex';
        document.getElementById('firstgraduate').style.display = 'none';
        document.getElementById('firstgraduate_opt').style.display = 'block';
        document.getElementById('bloodgroup').style.display = 'none';
        document.getElementById('bloodgroup_opt').style.display = 'block';
        document.getElementById('t_dob').style.display = 'block';
        document.getElementById('dob').style.display = 'none';
        document.getElementById('diffabled').style.display = 'block';
        document.getElementById('diff_abled').style.display = 'none';

    } else if (umisSelect == 'Yes') {


        document.getElementById('havingUmis').style.display = 'block';
        document.getElementById("toggleUmis").style.display = 'none';
        document.getElementById('Noumis').style.display = 'none';
        document.getElementById('firstgraduate').style.display = 'block';
        document.getElementById('firstgraduate_opt').style.display = 'none';
        document.getElementById('bloodgroup').style.display = 'block';
        document.getElementById('bloodgroup_opt').style.display = 'none';
        document.getElementById('t_dob').style.display = 'none';
        document.getElementById('dob').style.display = 'block';
        document.getElementById('diffabled').style.display = 'none';
        document.getElementById('diff_abled').style.display = 'block';
    } else {

        document.getElementById('havingUmis').style.display = 'none';
        document.getElementById("toggleUmis").style.display = 'none';
        document.getElementById('Noumis').style.display = 'none';
    }
}

function get_values() {



    var umisSelect = $("#umisSelect").val();

    if (umisSelect == 'No') {
        $("#umis_no").val('');
        $("#umis_std_name").val('');
        $("#umis_dob").val('');
        $("#umis_std_degree").val('');
        $("#umis_std_course").val('');
        $("#umis_yoa").val('');
        $("#umis_yos").val('');
        $("#umis_clg_name").val('');
        $("#umis_clg_add").val('');
        $("#caDistrict").val('');
        // $("#t_dob").val('');

        document.getElementById('dob').value = '';
        document.getElementById('bloodgroup').value = '';
        document.getElementById('mailid').value = '';
        document.getElementById('firstgraduate').value = '';
        document.getElementById('dadmobno').value = '';
        document.getElementById('dadOccupation').value = '';
        document.getElementById('momOccupation').value = '';
        document.getElementById('t_dob').value = '';
        document.getElementById('age').value = '';


        document.getElementById('havingUmis').style.display = 'none';
        document.getElementById("toggleUmis").style.display = 'block';
        document.getElementById('Noumis').style.display = 'inline-flex';
        document.getElementById('firstgraduate').style.display = 'none';
        document.getElementById('firstgraduate_opt').style.display = 'block';
        document.getElementById('bloodgroup').style.display = 'none';
        document.getElementById('bloodgroup_opt').style.display = 'block';
        document.getElementById('t_dob').style.display = 'block';
        document.getElementById('dob').style.display = 'none';
        document.getElementById('diffabled').style.display = 'block';
        document.getElementById('diff_abled').style.display = 'none';
    } else if (umisSelect == 'Yes') {

        $("#no_umis_name").val('');
        $("#no_umis_course").val('');
        $("#no_umis_stream").val('');
        $("#no_umis_college").val('');
        $("#no_umis_clg_district").val('');
        $("#no_umis_pincode").val('');
        $("#no_umis_yoa").val('');
        $("#no_umis_yos").val('');
        document.getElementById('t_dob').value = '';
        document.getElementById('dob').value = '';
        document.getElementById('age').value = '';
        document.getElementById('havingUmis').style.display = 'block';
        document.getElementById("toggleUmis").style.display = 'none';
        document.getElementById('Noumis').style.display = 'none';
        document.getElementById('firstgraduate').style.display = 'block';
        document.getElementById('firstgraduate_opt').style.display = 'none';
        document.getElementById('bloodgroup').style.display = 'block';
        document.getElementById('bloodgroup_opt').style.display = 'none';
        document.getElementById('t_dob').style.display = 'none';
        document.getElementById('dob').style.display = 'block';
        document.getElementById('diffabled').style.display = 'none';
        document.getElementById('diff_abled').style.display = 'block';
    } else {
        $("#umis_no").val('');
        $("#umis_std_name").val('');
        $("#umis_dob").val('');
        $("#umis_std_degree").val('');
        $("#umis_std_course").val('');
        $("#umis_yoa").val('');
        $("#umis_yos").val('');
        $("#umis_clg_name").val('');
        $("#umis_clg_add").val('');
        $("#caDistrictId").val('');
        $("#no_umis_name").val('');
        $("#no_umis_course").val('');
        $("#no_umis_stream").val('');
        $("#no_umis_college").val('');
        $("#no_umis_clg_district").val('');
        $("#no_umis_pincode").val('');
        $("#no_umis_yoa").val('');
        $("#no_umis_yos").val('');

        document.getElementById('havingUmis').style.display = 'none';
        document.getElementById("toggleUmis").style.display = 'none';
        document.getElementById('Noumis').style.display = 'none';
    }
}

function get_have_umis() {
    var value = $("#lat_entry").val();

    if (value == "Yes") {

        $("#umisSelect").val('');
        $("#umis_no").val('');
        $("#umis_std_name").val('');
        $("#umis_dob").val('');
        $("#umis_std_degree").val('');
        $("#umis_std_course").val('');
        $("#umis_yoa").val('');
        $("#umis_yos").val('');
        $("#umis_clg_name").val('');
        $("#umis_clg_add").val('');
        $("#caDistrictId").val('');
        $("#no_umis_name").val('');
        $("#no_umis_course").val('');
        $("#no_umis_stream").val('');
        $("#no_umis_college").val('');
        $("#no_umis_clg_district").val('');
        $("#no_umis_pincode").val('');
        $("#no_umis_yoa").val('');
        $("#no_umis_yos").val('');
        document.getElementById('dob').value = '';
        document.getElementById('bloodgroup').value = '';
        document.getElementById('mailid').value = '';
        document.getElementById('firstgraduate').value = '';
        document.getElementById('dadmobno').value = '';
        document.getElementById('dadOccupation').value = '';
        document.getElementById('momOccupation').value = '';
        document.getElementById('t_dob').value = '';
        document.getElementById('age').value = '';


        document.getElementById('have_umis_div').style.display = 'flex';
        document.getElementById('havingUmis').style.display = 'none';
        document.getElementById('Noumis').style.display = 'none';
        document.getElementById('diffabled').style.display = 'block';
        document.getElementById('diff_abled').style.display = 'none';

    } else if (value == 'No') {

        $("#umisSelect").val('');
        $("#umis_no").val('');
        $("#umis_std_name").val('');
        $("#umis_dob").val('');
        $("#umis_std_degree").val('');
        $("#umis_std_course").val('');
        $("#caDistrictId").val('');
        $("#umis_yoa").val('');
        $("#umis_yos").val('');
        $("#umis_clg_name").val('');
        $("#umis_clg_add").val('');
        $("#no_umis_name").val('');
        $("#no_umis_course").val('');
        $("#no_umis_stream").val('');
        $("#no_umis_college").val('');
        $("#no_umis_clg_district").val('');
        $("#no_umis_pincode").val('');
        $("#no_umis_yoa").val('');
        $("#no_umis_yos").val('');
        document.getElementById('dob').value = '';
        document.getElementById('bloodgroup').value = '';
        document.getElementById('mailid').value = '';
        document.getElementById('firstgraduate').value = '';
        document.getElementById('dadmobno').value = '';
        document.getElementById('dadOccupation').value = '';
        document.getElementById('momOccupation').value = '';
        document.getElementById('t_dob').value = '';
        document.getElementById('age').value = '';



        document.getElementById('havingUmis').style.display = 'block';
        document.getElementById('toggleUmis').style.display = 'none';
        document.getElementById('have_umis_div').style.display = 'none';
        document.getElementById('Noumis').style.display = 'none';
        document.getElementById('diffabled').style.display = 'none';
        document.getElementById('diff_abled').style.display = 'block';
        document.getElementById('dob').style.display = 'block';
        document.getElementById('t_dob').style.display = 'none';
    } else {
        $("#umisSelect").val('');
        document.getElementById('havingUmis').style.display = 'none';
        document.getElementById('have_umis_div').style.display = 'none';
        document.getElementById('Noumis').style.display = 'none';
    }
}

function hav_umis_div() {
    var value = $("#lat_entry").val();


    if (value == "Yes") {

        document.getElementById('have_umis_div').style.display = 'flex';
        document.getElementById('havingUmis').style.display = 'none';
        document.getElementById('Noumis').style.display = 'none';
        document.getElementById('diffabled').style.display = 'block';
        document.getElementById('diff_abled').style.display = 'none';

    } else if (value == 'No') {

        document.getElementById('havingUmis').style.display = 'block';
        document.getElementById('have_umis_div').style.display = 'none';
        document.getElementById('Noumis').style.display = 'none';
        document.getElementById('diffabled').style.display = 'none';
        document.getElementById('diff_abled').style.display = 'block';
        document.getElementById('bloodgroup').style.display = 'block';
        document.getElementById('bloodgroup_opt').style.display = 'none';
    } else {

        document.getElementById('havingUmis').style.display = 'none';
        document.getElementById('have_umis_div').style.display = 'none';
        document.getElementById('Noumis').style.display = 'none';
    }
}



function com_revert() {
    $("#communityno").val('');
    $("#fullname1").val('');
    $("#castename").val('');
    $("#subcastename").val('');
    $("#fathername3").val('');
    $("#mothername3").val('');
    $("#communitycer").val('');

    var hiddenDivUmis = document.getElementById("togglecom");
    hiddenDivUmis.style.display = "none";
}

function com_confirm() {
    var communityno = $("#communityno").val();
    var fullname1 = $("#fullname1").val();
    var castename = $("#castename").val();
    var subcastename = $("#subcastename").val();
    var fathername3 = $("#fathername3").val();
    var mothername3 = $("#mothername3").val();
    // var communitycer = $("#communitycer").val();
    if ((fullname1 && castename && subcastename && fathername3) != '') {
        // alert();
        log_sweetalert("cc_verify");

    } else {
        log_sweetalert("form_alert");
    }
}

function inc_revert() {
    $("#fullname4").val('');
    $("#incomecerno").val('');
    $("#incomelevel").val('');
    $("#fathername4").val('');
    $("#mothername4").val('');
    $("#fatherincomesource").val('');
    $("#motherincomesource").val('');
    $("#incomecer").val('');
    $("#mothername4").attr("disabled", "disabled");
    $("#fathername4").attr("disabled", "disabled");
    var hiddenDivUmis = document.getElementById("toggleinc");
    hiddenDivUmis.style.display = "none";

}

function inc_confirm() {
    var incomecerno = $("#incomecerno").val();
    var fullname4 = $("#fullname4").val();
    var incomelevel = $("#incomelevel").val();
    var fatherincomesource = $("#fatherincomesource").val();
    var fathername4 = $("#fathername4").val();
    var mothername4 = $("#mothername4").val();
    var motherincomesource = $("#motherincomesource").val();

    if (parseInt(incomelevel) <= '250000.0') {

        if ((incomecerno && fullname4 && incomelevel && fatherincomesource && fathername4 && mothername4) != '') {
            // alert();
            log_sweetalert("inc_verify");

        } else {
            log_sweetalert("form_alert");
        }
    } else {
        log_sweetalert("income_level_exceed");
    }
}

function empty_com() {

    var com_no = $("#communityno").val();
    if (com_no == '') {

        $("#communityno").val('');
        $("#fullname1").val('');
        $("#castename").val('');
        $("#subcastename").val('');
        $("#fathername3").val('');
        $("#mothername3").val('');
        $("#communitycer").val('');
        var hiddenDivUmis = document.getElementById("togglecom");
        hiddenDivUmis.style.display = "none";
    }
}

function empty_inc() {

    var inc_no = $("#incomecerno").val();
    if (inc_no == '') {

        $("#fullname4").val('');
        $("#incomecerno").val('');
        $("#incomelevel").val('');
        $("#fathername4").val('');
        $("#mothername4").val('');
        $("#fatherincomesource").val('');
        $("#motherincomesource").val('');
        $("#incomecer").val('');
        $("#mothername4").attr("disabled", "disabled");
        $("#fathername4").attr("disabled", "disabled");
        var hiddenDivUmis = document.getElementById("toggleinc");
        hiddenDivUmis.style.display = "none";
    }
}

function input_status() {
    $("#input_status").val('1');
}

function toggleincome() {
    var incomecerno = $("#incomecerno").val();

    if (incomecerno != '') {
        showLoader();

        var ajax_url = "crud.php";
        var data = {
            "incomecerno": incomecerno,
            "action": "insert_income"
        };

        $.ajax({
            type: "POST",
            url: ajax_url,
            data: data,
            dataType: 'json',
            success: function (response) {
                hideLoader();
                //console.log(response.data);
                var xmlData = response.data;

                var startIndex = xmlData.indexOf('<?xml');
                if (startIndex > 0) {
                    var xmlDeclaration = xmlData.substring(startIndex, xmlData.indexOf('?>', startIndex) + 2);
                    xmlData = xmlData.replace(xmlDeclaration, '');
                    xmlData = xmlDeclaration + xmlData;
                }

                // Parse the XML string into an XML document
                var parser = new DOMParser();
                var xmlDoc = parser.parseFromString(xmlData, "text/xml");

                var applicantMsgElement = xmlDoc.querySelector("MSG");
                //var applicantMsg = applicantMsgElement.textContent;

                if (applicantMsgElement == null) {

                    var applicantexpiryElement = xmlDoc.querySelector("DATEOFEXPIRY");
                    var applicantexpiry = applicantexpiryElement.textContent;

                    //Find the APPLICANTNAME element
                    var applicantNameElement = xmlDoc.querySelector("APPLICANTNAME");
                    var applicantMotherElement = xmlDoc.querySelector("MOTHER_NAME");
                    var applicantFatherElement = xmlDoc.querySelector("FATHERHUSNAME");
                    var applicantIncomeElement = xmlDoc.querySelector("ANNUALINCOME");
                    var applicantOccupationElement = xmlDoc.querySelector("OCCUPATION");
                    var applicantOutputElement = xmlDoc.querySelector("OUTPUTPDF");
                    var applicantAddressElement = xmlDoc.querySelector("ADDRESS");
                    var applicantVillageElement = xmlDoc.querySelector("VILLTOWN");
                    var applicantTalukElement = xmlDoc.querySelector("TALUK");
                    var applicantDistrictElement = xmlDoc.querySelector("DISTRICT");
                    var applicantPincodeElement = xmlDoc.querySelector("PINCODE");
                    var applicantAuthorityElement = xmlDoc.querySelector("ISSUINGAUTHORITY");
                    var applicantIssueDateElement = xmlDoc.querySelector("DATEOFISSUE");
                    var applicantAttachementElement = xmlDoc.querySelector("ATTACHEMENT");
                    var applicantCertificateNoElement = xmlDoc.querySelector("CERTIFICATENO");

                    var applicant_Mother = applicantMotherElement.textContent;
                    var applicant_Father = applicantFatherElement.textContent;
                    var applicantName = base64Encode(applicantNameElement.textContent);
                    var applicantMother = base64Encode(applicantMotherElement.textContent);
                    var applicantFather = base64Encode(applicantFatherElement.textContent);
                    var applicantIncome = base64Encode(applicantIncomeElement.textContent);
                    var applicantOccupation = base64Encode(applicantOccupationElement.textContent);
                    var applicantOutput = base64Encode(applicantOutputElement.textContent);
                    var applicantAddress = base64Encode(applicantAddressElement.textContent);
                    var applicantVillage = base64Encode(applicantVillageElement.textContent);
                    var applicantTaluk = base64Encode(applicantTalukElement.textContent);
                    var applicantDistrict = base64Encode(applicantDistrictElement.textContent);
                    var applicantPincode = base64Encode(applicantPincodeElement.textContent);
                    var applicantAuthority = base64Encode(applicantAuthorityElement.textContent);
                    var applicantIssueDate = base64Encode(applicantIssueDateElement.textContent);
                    var applicantAttachement = base64Encode(applicantAttachementElement.textContent);
                    var applicantCertificateNo = base64Encode(applicantCertificateNoElement.textContent);


                    var download_link_income = document.getElementById('download_link_income');
                    download_link_income.href = applicantOutputElement.textContent;

                    if (applicant_Father == '') {
                        $("#fathername4").removeAttr("disabled");
                    }

                    if (applicant_Mother == '') {
                        $("#mothername4").removeAttr("disabled");
                    }

                    document.getElementById('fullname4').value = applicantNameElement.textContent;
                    document.getElementById('mothername4').value = applicantMotherElement.textContent;
                    document.getElementById('fathername4').value = applicantFatherElement.textContent;
                    document.getElementById('incomelevel').value = applicantIncomeElement.textContent;
                    document.getElementById('fatherincomesource').value = applicantOccupationElement.textContent;
                    document.getElementById('income_pdf').value = applicantOutputElement.textContent;
                    document.getElementById('momname').value = applicantMotherElement.textContent;
                    document.getElementById('dadname').value = applicantFatherElement.textContent;

                    var hiddenDivUmis = document.getElementById("toggleinc");
                    hiddenDivUmis.style.display = "inline-flex";

                    var s1_unique_id = $("#s1_unique_id").val();

                    var ajax_url = "crud.php";

                    var i_data = {
                        "s1_unique_id": s1_unique_id,
                        "applicantName": applicantName,
                        "applicantMother": applicantMother,
                        "applicantFather": applicantFather,
                        "applicantIncome": applicantIncome,
                        "applicantOccupation": applicantOccupation,
                        "applicantOutput": applicantOutput,
                        "applicantAddress": applicantAddress,
                        "applicantVillage": applicantVillage,
                        "applicantTaluk": applicantTaluk,
                        "applicantDistrict": applicantDistrict,
                        "applicantPincode": applicantPincode,
                        "applicantAuthority": applicantAuthority,
                        "applicantIssueDate": applicantIssueDate,
                        "applicantAttachement": applicantAttachement,
                        "applicantexpiry": base64Encode(applicantexpiry),
                        "applicantCertificateNo": applicantCertificateNo,
                        "action": "income_create"
                    };


                    $.ajax({
                        type: "POST",
                        url: ajax_url,
                        data: i_data,
                        dataType: 'json', // Parse response as JSON
                        success: function (response) {

                            if (response.status) {
                                // Handle success

                            } else {
                                // Handle error
                                console.log(response.data);
                            }
                        },
                        error: function (xhr, status, error) {
                            hideLoader();
                            // Handle AJAX error
                            console.error(xhr.responseText);
                        }
                    });

                } else {
                    hideLoader();
                    log_sweetalert('no_income_record');
                }
            },
            error: function (xhr, status, error) {
                hideLoader();
                console.error("AJAX error:", xhr.responseText);
                // Handle AJAX error
            }
        });

    } else {
        hideLoader();
        var hiddenDivUmis = document.getElementById("toggleinc");
        hiddenDivUmis.style.display = "none";
        log_sweetalert('valid_inc');
    }
}

function ready_toggle_inc() {
    var hiddenDivUmis = document.getElementById("toggleinc");
    hiddenDivUmis.style.display = "inline-flex";
}

function hostelChoice() {

    var s1_unique_id = $("#s1_unique_id").val();

    var ajax_url = "crud.php";
    if (s1_unique_id) {
        var data = {
            "s1_unique_id": s1_unique_id,
            "action": "hostelChoice"
        }

        $.ajax({
            type: "POST",
            url: ajax_url,
            data: data,
            success: function (response) {
                if (response === 'success') {
                    $(".nav-link").removeAttr("disabled");
                    $('a[href="#tab35"]').tab('show');
                } else if (response === 'failure') {
                    log_sweetalert_1('no_entry');
                }
            }
        });
    }
}

function get_phy_div(value) {

    var value = $("#diffabled").val();
    var value_txt = $("#diff_abled").val();
    if (value == "Yes" || value_txt == 'Yes') {
        document.getElementById('phy_div').style.display = 'flex';
    } else {
        $("#category").val('');
        $("#idnumber").val('');
        $("#disabilitypercentage").val('');


        document.getElementById('phy_div').style.display = 'none';

    }
}



function get_emis_check(value) {

    var value = $("input[name='emis_check']:checked").val();
    if (value == "YES") {

        document.getElementById('emis_btn').disabled = false;
    } else {
        document.getElementById('emis_btn').disabled = true;

    }

}

function get_umis_check(value) {

    var value = $("input[name='umis_check']:checked").val();
    if (value == "YES") {

        document.getElementById('umis_btn').disabled = false;
    } else {
        document.getElementById('umis_btn').disabled = true;

    }
}

function get_no_umis_check(value) {

    var value = $("input[name='no_umis_check']:checked").val();

    if (value == "YES") {

        document.getElementById('umis_btn').disabled = false;
    } else {
        document.getElementById('umis_btn').disabled = true;

    }

}

function get_certificate_check(value) {

    var value = $("input[name='cert_check']:checked").val();

    if (value == "YES") {

        document.getElementById('cert_btn').disabled = false;
    } else {
        document.getElementById('cert_btn').disabled = true;

    }

}

function get_familyinfo_check(value) {

    var value = $("input[name='fam_check']:checked").val();

    if (value == "YES") {

        document.getElementById('fam_btn').disabled = false;
    } else {
        document.getElementById('fam_btn').disabled = true;

    }

}
// document.getElementById('first_check').addEventListener('change', function() {
//     var button = document.getElementById('aadhar_btn');
//     button.disabled = !this.checked;
// });
function get_orphan(value) {
    var refugee = $("#refugee").val();
    // $("#orphan_type").val(orphan);
    if (refugee == 'YES') {
        document.getElementById('orphan_label').style.display = 'none';
        document.getElementById('orphan_input').style.display = 'none';

    } else if (refugee == 'NO') {
        document.getElementById('orphan_label').style.display = 'block';
        document.getElementById('orphan_input').style.display = 'block';

    }
}

function get_host_type() {
    // alert();
    var host_type = $("#host_type").val();

    if (host_type == '65f00a259436412348') {
        document.getElementById('emis').style.display = 'block';
        document.getElementById('umis').style.display = 'none';
        document.getElementById('clg_div').style.display = 'none';
        document.getElementById('scl_div').style.display = 'block';
        document.getElementById('last_clg_div').style.display = 'none';
        document.getElementById('last_scl_div').style.display = 'block';


    } else if (host_type == "65f00a53eef3015995") {
        // alert("elseif");
        // last_scl_div
        document.getElementById('last_clg_div').style.display = 'block';
        document.getElementById('last_scl_div').style.display = 'none';
    }
    else {
        document.getElementById('emis').style.display = 'none';
        document.getElementById('umis').style.display = 'block';
        document.getElementById('clg_div').style.display = 'block';
        document.getElementById('scl_div').style.display = 'none';
        document.getElementById('last_clg_div').style.display = 'none';
        document.getElementById('last_scl_div').style.display = 'block';
    }

}

function get_pc_details(value) {

    // var value = $("#physically_challenge").val();
    var orphan = $("input[name='physically_challenge']:checked").val();
    if (orphan == "YES") {
        document.getElementById('phy_div').style.display = 'block';
    } else {
        document.getElementById('phy_div').style.display = 'none';

    }
}

function get_graduate_no(value) {

    if (value == "YES") {
        document.getElementById('grad_div').style.display = 'block';
    } else {
        document.getElementById('grad_div').style.display = 'none';

    }
}


function get_university(stream_type = "") {

    var stream_type = $("#std_stream").val();
    var last_std_stream = $("#last_std_stream").val();

    var ajax_url = "crud.php";
    if (stream_type) {
        var data = {
            "stream_type": stream_type,

            "action": "get_university"
        }

        $.ajax({
            type: "POST",
            url: ajax_url,
            data: data,
            success: function (data) {

                if (data) {
                    $("#std_university").html(data);
                }
            }
        });
    }
    if (last_std_stream) {
        var data = {
            "stream_type": last_std_stream,

            "action": "get_university"
        }

        $.ajax({
            type: "POST",
            url: ajax_url,
            data: data,
            success: function (data) {

                if (data) {
                    $("#last_std_university").html(data);
                }
            }
        });
    }
}



function get_college() {

    var std_university = $("#std_university").val();
    var stream_type = $("#std_stream").val();

    var last_std_stream = $("#last_std_stream").val();
    var last_std_university = $("#last_std_university").val();

    var ajax_url = "crud.php";
    if (std_university) {
        var data = {
            "std_university": std_university,
            "stream_type": stream_type,
            "action": "get_college"
        }

        $.ajax({
            type: "POST",
            url: ajax_url,
            data: data,
            success: function (data) {

                if (data) {
                    $("#std_college_name").html(data);
                }
            }
        });
    }
    if (last_std_university) {
        var data = {
            "std_university": last_std_stream,
            "stream_type": last_std_university,


            "action": "get_college"
        }

        $.ajax({
            type: "POST",
            url: ajax_url,
            data: data,
            success: function (data) {

                if (data) {
                    $("#last_std_college_name").html(data);
                }
            }
        });
    }

}


function get_school() {

    var last_scl_district = $("#last_scl_district").val();
    var std_scl_district = $("#std_scl_district").val();

    var ajax_url = "crud.php";
    if (last_scl_district) {
        var data = {
            "last_scl_district": last_scl_district,
            "action": "get_school"
        }

        $.ajax({
            type: "POST",
            url: ajax_url,
            data: data,
            success: function (data) {

                if (data) {
                    $("#last_std_scl_name").html(data);
                }
            }
        });
    }
    if (std_scl_district) {
        var data = {

            "last_scl_district": std_scl_district,
            "action": "get_school"
        }

        $.ajax({
            type: "POST",
            url: ajax_url,
            data: data,
            success: function (data) {

                if (data) {
                    $("#std_school_name").html(data);
                }
            }
        });
    }

}


function get_course() {

    var std_university = $("#std_university").val();
    var stream_type = $("#std_stream").val();
    var std_college_name = $("#std_college_name").val();

    var last_std_stream = $("#last_std_stream").val();
    var last_std_university = $("#last_std_university").val();
    var last_std_college_name = $("#last_std_college_name").val();

    var ajax_url = "crud.php";
    if (std_college_name) {
        var data = {
            "std_university": std_university,
            "stream_type": stream_type,
            "std_college_name": std_college_name,
            "action": "get_course"
        }

        $.ajax({
            type: "POST",
            url: ajax_url,
            data: data,
            success: function (data) {

                if (data) {
                    $("#std_degree").html(data);
                }
            }
        });
    }

    if (last_std_college_name) {
        var data = {
            "std_university": last_std_university,
            "stream_type": last_std_stream,
            "std_college_name": last_std_college_name,


            "action": "get_course"
        }

        $.ajax({
            type: "POST",
            url: ajax_url,
            data: data,
            success: function (data) {

                if (data) {
                    $("#last_std_degree").html(data);
                }
            }
        });

    }
}

function last_get_university(stream_type = "") {

    var ajax_url = "crud.php";
    if (stream_type) {
        var data = {
            "stream_type": stream_type,

            "action": "get_university"
        }

        $.ajax({
            type: "POST",
            url: ajax_url,
            data: data,
            success: function (data) {

                if (data) {
                    $("#last_std_university").html(data);
                }
            }
        });
    }
}



function get_gender_type() {

    var hostel_district = $("#hostel_district").val();


    var hostel_taluk = $("#hostel_taluk").val();

    $("#scl_district").val(hostel_district);
    $("#scl_taluk").val(hostel_taluk);

    var ajax_url = "crud.php";
    if (hostel_district) {
        var data = {
            "hostel_district": hostel_district,
            "hostel_taluk": hostel_taluk,
            "action": "get_gender_name"
        }

        $.ajax({
            type: "POST",
            url: ajax_url,
            data: data,
            success: function (data) {

                if (data) {
                    $("#hostel_gender_type").html(data);
                }
            }
        });
    }
}

function get_clg_school() {

    var hostel_district = $("#hostel_district").val();
    var hostel_taluk = $("#hostel_taluk").val();

    var ajax_url = "crud.php";
    var data = {
        "hostel_district": hostel_district,
        "hostel_taluk": hostel_taluk,
        "action": "get_school_name"
    }

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function (data) {

            if (data) {
                $("#std_school_name").html(data);
            }
        }
    });
}



function get_hostel_type() {

    var hostel_district = $("#hostel_district").val();
    var hostel_taluk = $("#hostel_taluk").val();
    var hostel_gender_type = $("#hostel_gender_type").val();


    var ajax_url = "crud.php";
    var data = {
        "hostel_district": hostel_district,
        "hostel_taluk": hostel_taluk,
        "hostel_gender_type": hostel_gender_type,
        "action": "get_hostel_type"
    }

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function (data) {

            if (data) {
                $("#hostel_type").html(data);
            }
        }
    });

}

function log_sweetalert_1(msg = '') {
    switch (msg) {
        case "no_entry":

            Swal.fire({
                icon: 'warning',
                title: 'Please fill Educational Details tab to Select Hostel.',

                imageAlt: 'Custom image',
                showConfirmButton: true,
                timer: 9000,
                timerProgressBar: true,
                willClose: () => {
                    if (url) {
                        window.location = url;
                    }
                }
            });
            break;

    }

}

function log_sweetalert(msg = '', url = '', callback = '', title = '') {


    switch (msg) {
        case "create":

            Swal.fire({
                icon: 'success',
                title: 'Successfully Saved',
                // text: 'Modal with a custom image.',  
                //imageUrl:'img/emoji/success.webp',
                // imageWidth: 250,
                // imageHeight: 200,
                imageAlt: 'Custom image',
                showConfirmButton: true,
                timer: 3000,
                timerProgressBar: true,
                willClose: () => {
                    if (url) {
                        window.location = url;
                    }
                }
            });
            break;

        case "number_alert":
            Swal.fire({
                icon: 'warning',
                title: 'Enter a valid Mobile No Starts With 6, 7, 8 or 9',
                imageAlt: 'Custom image',
                showConfirmButton: true,
                timer: 3000,
                timerProgressBar: true,
                willClose: () => {
                    if (url) {
                        window.location = url;
                    }
                }
            });
            break;

        case "umis_already":
            Swal.fire({
                icon: 'warning',
                title: 'UMIS Number Already Exist',
                //imageUrl:'img/emoji/already.webp',
                showConfirmButton: true,
                timer: 6000,
                timerProgressBar: true,
                willClose: () => {
                    // alert("Hi");
                }
            });
            break;

        case "emis_already":
            Swal.fire({
                icon: 'warning',
                title: 'EMIS Number Already Exist',
                //imageUrl:'img/emoji/already.webp',
                showConfirmButton: true,
                timer: 6000,
                timerProgressBar: true,
                willClose: () => {
                    // alert("Hi");
                }
            });
            break;

        case "cancel_app":

            Swal.fire({
                icon: 'success',
                title: 'Your Application Has cancelled',
                // text: 'Modal with a custom image.',  
                //imageUrl:'img/emoji/success.webp',
                // imageWidth: 250,
                // imageHeight: 200,
                imageAlt: 'Custom image',
                showConfirmButton: true,
                timer: 3000,
                timerProgressBar: true,
                willClose: () => {
                    if (url) {
                        window.location = url;
                    }
                }
            });
            break;


        case "submitted":
            Swal.fire({
                icon: 'success',
                title: 'Your Application Has Submitted Successfully',
                // text: 'Modal with a custom image.',  
                //imageUrl:'img/emoji/success.webp',
                // imageWidth: 250,
                // imageHeight: 200,
                imageAlt: 'Custom image',
                showConfirmButton: true,
                timer: 6000,
                timerProgressBar: true,
                willClose: () => {
                    if (url) {
                        window.location = url;
                    }
                }
            });
            break;

        case "verify":
            Swal.fire({
                icon: 'success',
                title: 'OTP Verified Successfully',

                imageAlt: 'Custom image',
                showConfirmButton: true,
                timer: 6000,
                timerProgressBar: true,
                willClose: () => {
                    if (url) {
                        window.location = url;
                    }
                }
            });
            break;

        case "cc_verify":
            Swal.fire({
                icon: 'success',
                title: 'Verified',
                imageAlt: 'Custom image',
                showConfirmButton: true,
                timer: 6000,
                timerProgressBar: true,
                willClose: () => {
                    if (url) {
                        window.location = url;
                    }
                }
            });
            break;

        case "invalid_ext":
            Swal.fire({
                icon: 'warning',
                title: 'Upload Valid Files',
                imageAlt: 'Custom image',
                showConfirmButton: true,
                timer: 3000,
                timerProgressBar: true,
                willClose: () => {
                    if (url) {
                        window.location = url;
                    }
                }
            });
            break;

        case "inc_verify":
            Swal.fire({
                icon: 'success',
                title: 'Verified',
                imageAlt: 'Custom image',
                showConfirmButton: true,
                timer: 6000,
                timerProgressBar: true,
                willClose: () => {
                    if (url) {
                        window.location = url;
                    }
                }
            });
            break;

        case "priority_added":

            Swal.fire({
                icon: 'success',
                title: 'Priority For This Hostel Has Created',
                imageAlt: 'Custom image',
                showConfirmButton: true,
                timer: 6000,
                timerProgressBar: true,
                willClose: () => {
                    if (url) {
                        window.location = url;
                    }
                }
            });
            break;

        case "wrong_otp":
            Swal.fire({
                icon: 'warning',
                title: 'Please Enter Valid OTP',
                // text: 'Modal with a custom image.',  
                //imageUrl:'img/emoji/success.webp',
                // imageWidth: 250,
                // imageHeight: 200,
                imageAlt: 'Custom image',
                showConfirmButton: true,
                timer: 6000,
                timerProgressBar: true,
                willClose: () => {
                    if (url) {
                        window.location = url;
                    }
                }
            });
            break;



        case "income_expiry":
            Swal.fire({
                icon: 'warning',
                title: 'Your Income Certificate is Expired',
                // text: 'Modal with a custom image.',  
                //imageUrl:'img/emoji/success.webp',
                // imageWidth: 250,
                // imageHeight: 200,
                imageAlt: 'Custom image',
                showConfirmButton: true,
                timer: 6000,
                timerProgressBar: true,
                willClose: () => {
                    if (url) {
                        window.location = url;
                    }
                }
            });
            break;

        case "income_level_exceed":
            Swal.fire({
                icon: 'warning',
                title: 'Your Income Level is above the Limit, You Cannot Proceed the Application',
                // text: 'Modal with a custom image.',  
                //imageUrl:'img/emoji/success.webp',
                // imageWidth: 250,
                // imageHeight: 200,
                imageAlt: 'Custom image',
                showConfirmButton: true,
                timer: 6000,
                timerProgressBar: true,
                willClose: () => {
                    if (url) {
                        window.location = url;
                    }
                }
            });
            break;

        case "invalid_aadhaar":
            Swal.fire({
                icon: 'warning',
                title: 'Please Enter Valid Aadhaar Number',
                // text: 'Modal with a custom image.',  
                //imageUrl:'img/emoji/success.webp',
                // imageWidth: 250,
                // imageHeight: 200,
                imageAlt: 'Custom image',
                showConfirmButton: true,
                timer: 6000,
                timerProgressBar: true,
                willClose: () => {
                    if (url) {
                        // window.location = url;
                    }
                }
            });
            break;

        case "no_income_record":
            Swal.fire({
                icon: 'warning',
                title: 'No Data found for given Certificate No.',
                imageAlt: 'Custom image',
                showConfirmButton: true,
                timer: 6000,
                timerProgressBar: true,
                willClose: () => {
                    if (url) {
                        // window.location = url;
                    }
                }
            });
            break;

        case "no_community_record":
            Swal.fire({
                icon: 'warning',
                title: 'No Data found for given Certificate No.',
                imageAlt: 'Custom image',
                showConfirmButton: true,
                timer: 6000,
                timerProgressBar: true,
                willClose: () => {
                    if (url) {
                        // window.location = url;
                    }
                }
            });
            break;

        case "mobile_already":
            Swal.fire({
                icon: 'warning',
                title: 'Mobile Number Already Registered',

                imageAlt: 'Custom image',
                showConfirmButton: true,
                timer: 6000,
                timerProgressBar: true,
                willClose: () => {
                    if (url) {
                        window.location = url;
                    }
                }
            });
            break;

        case "priority_exceed":

            Swal.fire({
                icon: 'warning',
                title: 'Priority For This Hostel Is Removed',

                imageAlt: 'Custom image',
                showConfirmButton: true,
                timer: 6000,
                timerProgressBar: true,
                willClose: () => {
                    if (url) {
                        window.location = url;
                    }
                }
            });
            break;

        // case "length_emis":
        //   Swal.fire({
        //       icon: 'warning',
        //       title: 'Please enter Valid EMIS ID',

        //       imageAlt: 'Custom image',
        //       showConfirmButton: true,
        //       timer: 1000,
        //       timerProgressBar: true,
        //       willClose: () => {
        //           if (url) {
        //               window.location = url;
        //           }
        //       }
        //   });
        // break;

        case "valid_emis":
            Swal.fire({
                icon: 'warning',
                title: 'Please enter Valid EMIS ID',

                imageAlt: 'Custom image',
                showConfirmButton: true,
                timer: 6000,
                timerProgressBar: true,
                willClose: () => {
                    if (url) {
                        window.location = url;
                    }
                }
            });
            break;

        case "valid_umis":
            Swal.fire({
                icon: 'warning',
                title: 'Please enter Valid UMIS ID',

                imageAlt: 'Custom image',
                showConfirmButton: true,
                timer: 6000,
                timerProgressBar: true,
                willClose: () => {
                    if (url) {
                        window.location = url;
                    }
                }
            });
            break;

        case "valid_com":
            Swal.fire({
                icon: 'warning',
                title: 'Please enter Valid Community Certificate Number',

                imageAlt: 'Custom image',
                showConfirmButton: true,
                timer: 6000,
                timerProgressBar: true,
                willClose: () => {
                    if (url) {
                        window.location = url;
                    }
                }
            });
            break;

        case "valid_inc":
            Swal.fire({
                icon: 'warning',
                title: 'Please enter Valid Income Certificate Number',

                imageAlt: 'Custom image',
                showConfirmButton: true,
                timer: 6000,
                timerProgressBar: true,
                willClose: () => {
                    if (url) {
                        window.location = url;
                    }
                }
            });
            break;

        case "aadhar_already":
            Swal.fire({
                icon: 'warning',
                title: 'Aadhar Number has Already Registered',

                imageAlt: 'Custom image',
                showConfirmButton: true,
                timer: 6000,
                timerProgressBar: true,
                willClose: () => {
                    if (url) {
                        window.location = url;
                    }
                }
            });
            break;

        case "not_reg_mob":
            Swal.fire({
                icon: 'warning',
                title: 'Please Enter Registered Mobile Number',
                // text: 'Modal with a custom image.',  
                //imageUrl:'img/emoji/success.webp',
                // imageWidth: 250,
                // imageHeight: 200,
                imageAlt: 'Custom image',
                showConfirmButton: true,
                timer: 6000,
                timerProgressBar: true,
                willClose: () => {
                    if (url) {
                        window.location = url;
                    }
                }
            });
            break;


        case "update":
            Swal.fire({
                icon: 'success',
                title: 'Successfully Updated',
                //imageUrl:'img/emoji/clapping.webp',
                showConfirmButton: true,
                timer: 2000,
                timerProgressBar: true,
                willClose: () => {
                    if (url) {
                        window.location = url;
                    }
                }
            });
            break;

        case "error":
            Swal.fire({
                icon: 'error',
                title: 'Error Occured',
                showConfirmButton: true,
                timer: 2000,
                timerProgressBar: true,
                willClose: () => {
                    // alert("Hi");
                }
            });
            break;

        case "network_err":
            Swal.fire({
                icon: 'error',
                title: 'Network Error Occured',
                showConfirmButton: true,
                timer: 2000,
                timerProgressBar: true,
                willClose: () => {
                    // alert("Hi");
                }
            });
            break;

        // mythili
        case "demo":
            Swal.fire({
                icon: 'warning',
                title: 'End-time must be bigger then Start-time!',
                showConfirmButton: true,
                timer: 6000,
                timerProgressBar: true,
                willClose: () => {
                    // alert("Hi");
                }
            });
            break;
        case "otp":
            Swal.fire({
                icon: 'success',
                title: 'OTP verified!',
                showConfirmButton: true,
                timer: 6000,
                timerProgressBar: true,
                willClose: () => {
                    // alert("Hi");
                }
            });
        case "otp_verify":
            Swal.fire({
                icon: 'warning',
                title: 'Please Enter valid OTP!',
                showConfirmButton: true,
                timer: 6000,
                timerProgressBar: true,
                willClose: () => {
                    // alert("Hi");
                }
            });
            break;
        // end
        case "not_exist":
            Swal.fire({
                icon: 'warning',
                title: 'Mobile Number Not Registered',
                //imageUrl:'img/emoji/already.webp',
                showConfirmButton: true,
                timer: 6000,
                timerProgressBar: true,
                willClose: () => {
                    // alert("Hi");
                }
            });
            break;

        case "mob_already_exist":
            Swal.fire({
                icon: 'warning',
                title: 'Mobile Number Already Registered',
                //imageUrl:'img/emoji/already.webp',
                showConfirmButton: true,
                timer: 6000,
                timerProgressBar: true,
                willClose: () => {
                    // alert("Hi");
                }
            });
            break;



        case "already":
            Swal.fire({
                icon: 'warning',
                title: 'Mobile Number Already',
                //imageUrl:'img/emoji/already.webp',
                showConfirmButton: true,
                timer: 6000,
                timerProgressBar: true,
                willClose: () => {
                    // alert("Hi");
                }
            });
            break;

        case "no_internet":
            Swal.fire({
                icon: 'warning',
                title: 'Please Check Your Internet Connection!',
                showConfirmButton: true,
                timer: 2000,
                timerProgressBar: true,

                willClose: () => {
                    // alert("Hi");
                }
            });
            break;

        case "no_location":
            Swal.fire({
                icon: 'warning',
                title: 'Please Check Your Geo Location!',
                showConfirmButton: true,
                timer: 2000,

                timerProgressBar: true,
                willClose: () => {
                    // alert("Hi");
                }
            });
            break;

        case "delete":
            return Swal.fire({
                title: 'Are you sure to Delete?',
                // text: "You won't be able to revert this!",
                icon: 'warning',
                //imageUrl:'img/emoji/delete.webp',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                preConfirm: () => {
                    return true;
                }
            });
            break;

        case "success_delete":
            Swal.fire({
                icon: 'success',
                title: 'Deleted!',
                //imageUrl:'img/emoji/success_delete.webp',
                showConfirmButton: true,
                timer: 1500,
                timerProgressBar: true
            });
            break;

        case "form_alert":
            Swal.fire({
                icon: 'info',
                title: 'Fill Out All Mandatory Fields',
                // imageUrl:'img/emoji/form_fill.webp',
                showConfirmButton: true,
                timer: 6000,
                timerProgressBar: true
            })
            break;

        case "no_host_sel":
            Swal.fire({
                icon: 'info',
                title: 'Give Atleast One Priority',
                // imageUrl:'img/emoji/form_fill.webp',
                showConfirmButton: true,
                timer: 6000,
                timerProgressBar: true
            })
            break;

        case "no_priority_1":

            Swal.fire({
                icon: 'warning',
                title: 'Must Need to Add Priority 1',

                imageAlt: 'Custom image',
                showConfirmButton: true,
                timer: 3000,
                timerProgressBar: true,
                willClose: () => {
                    if (url) {
                        window.location = url;
                    }
                }
            });
            break;

        case "no_entry":

            Swal.fire({
                icon: 'warning',
                title: 'Please fill Educational Details tab to Select Hostel.',

                imageAlt: 'Custom image',
                showConfirmButton: true,
                timer: 9000,
                timerProgressBar: true,
                willClose: () => {
                    if (url) {
                        window.location = url;
                    }
                }
            });
            break;


        case "no_entry_for_umis_or_emis":

            Swal.fire({
                icon: 'warning',
                title: 'Please fill Educational Details tab to Select Hostel.',
                imageAlt: 'Custom image',
                showConfirmButton: true,
                timer: 9000,
                timerProgressBar: true,
                willClose: () => {
                    if (url) {
                        // window.location = url;
                    }
                }
            });
            break;

        case "approve":
            Swal.fire({
                icon: 'success',
                title: 'Successfully Approved',
                showConfirmButton: true,
                timer: 2000,
                willClose: () => {
                    window.location = url;
                }
            });
            break;

        case "convert":
            Swal.fire({
                icon: 'success',
                title: 'Successfully Converted',
                showConfirmButton: true,
                timer: 2000,
                willClose: () => {
                    window.location = url;
                }
            });
            break;

        case "add":
            Swal.fire({
                icon: 'success',
                title: 'Successfully Added',
                //imageUrl:'img/emoji/success_delete.webp',
                showConfirmButton: true,
                timer: 2000,
                willClose: () => {
                    //   window.location = url;
                }
            });
            break;

        case "custom":
            Swal.fire({
                icon: 'info',
                title: title,
                willClose: () => {

                    if (url != "") {
                        window.location = url;
                    }
                }
            });
            break;

        case "password_alert":
            Swal.fire({
                icon: 'info',
                title: 'Please Update either Password Or Profile Image',
                //imageUrl:'img/emoji/form_fill.webp',
                showConfirmButton: true,
                timer: 2000,
                timerProgressBar: true
            })
            break;
    }
}





function certificateinfo() {

    // alert("jiiii");
    var communityno = document.getElementById('communityno').value;
    var fullname1 = document.getElementById('fullname1').value;
    var castename = document.getElementById('castename').value;
    var subcastename = document.getElementById('subcastename').value;
    var fathername3 = document.getElementById('fathername3').value;
    // var mothername3 = document.getElementById('mothername3').value;
    // var communitycer =document.getElementById('communitycer').value;
    var incomecerno = document.getElementById('incomecerno').value;
    var fullname4 = document.getElementById('fullname4').value;
    var incomelevel = document.getElementById('incomelevel').value;
    var fathername4 = document.getElementById('fathername4').value;
    var mothername4 = document.getElementById('mothername4').value;
    var fatherincomesource = document.getElementById('fatherincomesource').value;
    var motherincomesource = document.getElementById('motherincomesource').value;
    // var incomecer =document.getElementById('incomecer').value;
    var diffabled = document.getElementById('diffabled').value;
    var diff_abled = document.getElementById('diff_abled').value;
    var category = document.getElementById('category').value;
    var idnumber = document.getElementById('idnumber').value;
    var disabilitypercentage = document.getElementById('disabilitypercentage').value;
    var com_name = document.getElementById('com_name').value;
    var cert_detail = document.getElementById('cert_detail').value;
    // var disabilitycertificate =document.getElementById('disabilitycertificate').value;

    var image_c = document.getElementById('communitycer');
    // var image_i = document.getElementById('incomecer');
    var image_d = document.getElementById('disabilitycertificate');
    var community_pdf = document.getElementById('community_pdf').value;
    var income_pdf = document.getElementById('income_pdf').value;
    var image_d = document.getElementById('disabilitycertificate');
    var s1_unique_id = document.getElementById('s1_unique_id').value;

    var data = new FormData();

    if (diffabled != '') {
        var diffabled = diffabled;

    } else if (diff_abled != '') {
        var diffabled = diff_abled;
    }

    const fileInput = document.getElementById('communitycer');
    const file = fileInput.files[0];


    const allowedFileTypes = [
        'image/jpeg', 'image/png', 'image/gif', // Images
        'application/pdf',                     // PDF

    ];
    if (file) {
        if (!allowedFileTypes.includes(file.type)) {
            log_sweetalert('invalid_ext');
            return false;
        }
    }

    const phy_fileInput = document.getElementById('disabilitycertificate');
    const phy_file = phy_fileInput.files[0];


    const phy_allowedFileTypes = [
        'image/jpeg', 'image/png', 'image/gif', // Images
        'application/pdf'
    ];

    if (phy_file) {
        if (!phy_allowedFileTypes.includes(phy_file.type)) {
            log_sweetalert('invalid_ext');
            return false;
        }
    }

    if (image_c != '') {
        for (var i = 0; i < image_c.files.length; i++) {
            data.append("communitycer", document.getElementById('communitycer').files[i]);
        }
    } else {
        data.append("communitycer", '');
    }
    // if (image_i != '') {
    // 	for (var i = 0; i < image_i.files.length; i++) {
    // 		data.append("incomecer", document.getElementById('incomecer').files[i]);oteVe
    // 	}
    // } else {
    // 	data.append("incomecer", '');
    // }
    if (image_d != '') {
        for (var i = 0; i < image_d.files.length; i++) {
            data.append("disabilitycertificate", document.getElementById('disabilitycertificate').files[i]);
        }
    } else {
        data.append("disabilitycertificate", '');
    }

    if (incomelevel == '') {
        var income = incomelevel;
    } else {
        var income = parseInt(incomelevel);

    }

    if (income <= '250000.0') {



        if (((image_c || community_pdf) && fullname1 && castename && subcastename && fathername3 && incomecerno && fullname4 && incomelevel && fathername4 && mothername4 && diffabled) != '') {

            data.append("communityno", base64Encode(communityno));
            data.append("fullname1", base64Encode(fullname1));
            data.append("castename", base64Encode(castename));
            data.append("subcastename", base64Encode(subcastename));

            data.append("fathername3", base64Encode(fathername3));
            // data.append("mothername3", base64Encode(mothername3));
            data.append("incomecerno", base64Encode(incomecerno));
            data.append("fullname4", base64Encode(fullname4));
            data.append("incomelevel", base64Encode(incomelevel));
            data.append("fathername4", base64Encode(fathername4));
            data.append("mothername4", base64Encode(mothername4));
            data.append("fatherincomesource", base64Encode(fatherincomesource));
            data.append("motherincomesource", base64Encode(motherincomesource));
            data.append("diffabled", base64Encode(diffabled));
            data.append("category", base64Encode(category));
            data.append("idnumber", base64Encode(idnumber));
            data.append("income_pdf", base64Encode(income_pdf));
            data.append("community_pdf", base64Encode(community_pdf));
            data.append("disabilitypercentage", base64Encode(disabilitypercentage));
            data.append("com_name", base64Encode(com_name));
            data.append("cert_detail", base64Encode(cert_detail));
            data.append("s1_unique_id", base64Encode(s1_unique_id));
            data.append("action", "certificateinfocreat");


            var ajax_url = sessionStorage.getItem("folder_crud_link");
            var url = sessionStorage.getItem("list_link");

            $.ajax({
                type: "POST",
                url: "crud.php",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                // beforeSend 	: function() {
                // 	$(".createupdate_btn").attr("disabled","disabled");
                // 	$(".createupdate_btn").text("Loading...");
                // },
                success: function (data) {

                    var obj = JSON.parse(data);
                    var msg = obj.msg;
                    // var std_app_no = obj.std_app_no;
                    var status = obj.status;
                    var error = obj.error;

                    if (!status) {
                        url = '';
                        $(".createupdate_btn").text("Error");
                        console.log(error);
                    } else {
                        if (msg == "already") {
                            // Button Change Attribute
                            url = '';

                            // $(".createupdate_btn").removeAttr("disabled","disabled");
                            // if (unique_id) {
                            // 	$(".createupdate_btn").text("Update");
                            // } else {
                            // 	$(".createupdate_btn").text("Save");
                            // }
                            // 	}
                            // }

                            log_sweetalert(msg);
                        } else {
                            // $(".createupdate_btn").text("Add");
                            log_sweetalert("create");
                            $('a[href="#tab34"]').tab('show');

                            // $("#status_description").val("");
                        }
                    }



                },
                error: function (data) {
                    alert("Network Error");
                }
            });
        } else {
            log_sweetalert("form_alert");
        }
    } else {
        url = "form_otp.php";
        log_sweetalert("income_level_exceed", url);
    }
}

function base64Encode(str) {
    return btoa(unescape(encodeURIComponent(str)));
}

function familyinfo() {
    // alert("jii");
    var s1_unique_id = document.getElementById('s1_unique_id').value;
    var dob = document.getElementById('dob').value;
    var t_dob = document.getElementById('t_dob').value;
    var age = document.getElementById('age').value;
    var bloodgroup = document.getElementById('bloodgroup').value;
    var bloodgroup_opt = document.getElementById('bloodgroup_opt').value;
    var mailid = document.getElementById('mailid').value;
    var religion = document.getElementById('religion').value;
    var mothertongue = document.getElementById('mothertongue').value;
    // var aadharno = document.getElementById('aadharno').value;
    var refugee = document.getElementById('refugee').value;
    var orphan = document.getElementById('orphan').value;
    var singleparent = document.getElementById('singleparent').value;
    var firstgraduate = document.getElementById('firstgraduate').value;
    var firstgraduate_opt = document.getElementById('firstgraduate_opt').value;
    var dadname = document.getElementById('dadname').value;
    var momname = document.getElementById('momname').value;
    var dadqualification = document.getElementById('dadqualification').value;
    var momqualification = document.getElementById('momqualification').value;
    var dadOccupation = document.getElementById('dadOccupation').value;
    var momOccupation = document.getElementById('momOccupation').value;
    var dadmobno = document.getElementById('dadmobno').value;
    var guardianno = document.getElementById('guardianno').value;
    var door_no = document.getElementById('door_no').value;
    var taluk = document.getElementById('taluk').value;
    var District = document.getElementById('District').value;
    var Pincode = document.getElementById('Pincode').value;
    var street_name = document.getElementById('street_name').value;
    var area_name = document.getElementById('area_name').value;
    var hostel_type = $("#student_type").val();
    //alert(hostel_type);

    // if (!t_dob) {
    // 	focusOnEmptyField('t_dob', 'error_dob', "DOB is required");
    // 	return false;
    // } else {
    // 	$('#error_dob').text('');
    // }
    if (!bloodgroup_opt && !bloodgroup) {
        focusOnEmptyField('bloodgroup_opt', 'error_bloodgroup', "Blood Group is required");
        return false;
    } else {
        $('#error_bloodgroup').text('');
    }

    if (hostel_type != "65f00a259436412348") {
        if (!mailid) {
            focusOnEmptyField('mailid', 'error_mailid', "Email is required");
            return false;
        } else {
            $('#error_mailid').text('');
        }
    }

    if (!religion) {
        focusOnEmptyField('religion', 'error_religion', "Religion is required");
        return false;
    } else {
        $('#error_religion').text('');
    }

    if (!mothertongue) {
        focusOnEmptyField('mothertongue', 'error_mothertongue', "Mother Tongue is required");
        return false;
    } else {
        $('#error_mothertongue').text('');
    }

    if (!refugee) {
        focusOnEmptyField('refugee', 'error_refugee', "Refugee is required");
        return false;
    } else {
        $('#error_refugee').text('');
    }

    if (!orphan) {
        focusOnEmptyField('orphan', 'error_orphan', "Orphan is required");
        return false;
    } else {
        $('#error_orphan').text('');
    }

    if (!singleparent) {
        focusOnEmptyField('singleparent', 'error_singleparent', "Single Parent is required");
        return false;
    } else {
        $('#error_singleparent').text('');
    }

    if (!dadname) {
        focusOnEmptyField('dadname', 'error_dadname', "Dad Name is required");
        return false;
    } else {
        $('#error_dadname').text('');
    }

    if (!momname) {
        focusOnEmptyField('momname', 'error_momname', "Mother Name is required");
        return false;
    } else {
        $('#error_momname').text('');
    }

    if (!dadqualification) {
        focusOnEmptyField('dadqualification', 'error_dadqualification', "Dad Qualification is required");
        return false;
    } else {
        $('#error_dadqualification').text('');
    }

    if (dadqualification == "0") {
        focusOnEmptyField('dadqualification', 'error_dadqualification', "Enter a valid details");
        return false;
    } else {
        $('#error_dadqualification').text('');
    }

    if (!momqualification) {
        focusOnEmptyField('momqualification', 'error_momqualification', "Mom Qualification is required");
        return false;
    } else {
        $('#error_momqualification').text('');
    }

    if (momqualification == "0") {
        focusOnEmptyField('momqualification', 'error_momqualification', "Enter a valid details");
        return false;
    } else {
        $('#error_momqualification').text('');
    }

    if (!dadOccupation) {
        focusOnEmptyField('dadOccupation', 'error_dadOccupation', "Dad Occupation is required");
        return false;
    } else {
        $('#error_dadOccupation').text('');
    }

    if (!momOccupation) {
        focusOnEmptyField('momOccupation', 'error_momOccupation', "Mother Occupation is required");
        return false;
    } else {
        $('#error_momOccupation').text('');
    }

    if (!dadmobno) {
        focusOnEmptyField('dadmobno', 'error_dadmobno', "Mobile No. is required");
        return false;
    } else {
        $('#error_dadmobno').text('');
    }

    if (!guardianno) {
        focusOnEmptyField('guardianno', 'error_guardianno', "Guardian No. is required");
        return false;
    } else {
        $('#error_guardianno').text('');
    }

    if (!door_no) {
        focusOnEmptyField('door_no', 'error_door_no', "Door No. is required");
        return false;
    } else {
        $('#error_door_no').text('');
    }

    if (!street_name) {
        focusOnEmptyField('street_name', 'error_street_name', "Street is required");
        return false;
    } else {
        $('#error_street_name').text('');
    }

    if (!area_name) {
        focusOnEmptyField('area_name', 'error_area_name', "Area is required");
        return false;
    } else {
        $('#error_area_name').text('');
    }

    if (!taluk) {
        focusOnEmptyField('taluk', 'error_taluk', "Taluk is required");
        return false;
    } else {
        $('#error_taluk').text('');
    }

    if (!District) {
        focusOnEmptyField('District', 'error_District', "District is required");
        return false;
    } else {
        $('#error_District').text('');
    }

    if (!Pincode) {
        focusOnEmptyField('Pincode', 'error_Pincode', "Pincode is required");
        return false;
    } else {
        $('#error_Pincode').text('');
    }

    if (firstgraduate != '') {
        var first_graduate = firstgraduate;
    }
    if (firstgraduate_opt != '') {
        var first_graduate = firstgraduate_opt;
    }

    if (dob != '') {
        var dob = dob;
    }
    if (t_dob != '') {
        var dob = t_dob;
    }

    if (bloodgroup != '') {
        var blood_group = bloodgroup;
    }
    if (bloodgroup_opt != '') {
        var blood_group = bloodgroup_opt;
    }

    if (hostel_type == "65f00a259436412348") {
        if ((dob && age && blood_group && religion && mothertongue && refugee && orphan && singleparent && first_graduate && dadname && momname && dadqualification && momOccupation && dadmobno && Pincode && guardianno && District && door_no && street_name && area_name) != '') {
            var data = new FormData();
            data.append("dob", base64Encode(dob));
            data.append("age", base64Encode(age));
            data.append("bloodgroup", base64Encode(blood_group));
            data.append("religion", base64Encode(religion));
            data.append("mothertongue", base64Encode(mothertongue));
            // data.append("aadharno", base64Encode(aadharno));
            data.append("refugee", base64Encode(refugee));
            data.append("orphan", base64Encode(orphan));
            data.append("singleparent", base64Encode(singleparent));
            data.append("firstgraduate", base64Encode(first_graduate));
            data.append("dadname", base64Encode(dadname));
            data.append("momname", base64Encode(momname));
            data.append("dadqualification", base64Encode(dadqualification));
            data.append("momqualification", base64Encode(momqualification));
            data.append("dadOccupation", base64Encode(dadOccupation));
            data.append("momOccupation", base64Encode(momOccupation));
            data.append("dadmobno", base64Encode(dadmobno));
            data.append("guardianno", base64Encode(guardianno));
            data.append("door_no", base64Encode(door_no));
            data.append("taluk", base64Encode(taluk));
            data.append("District", base64Encode(District));
            data.append("Pincode", base64Encode(Pincode));
            data.append("street_name", base64Encode(street_name));
            data.append("area_name", base64Encode(area_name));
            data.append("s1_unique_id", base64Encode(s1_unique_id));
            data.append("action", "familyinfos");

            $.ajax({
                type: "POST",
                url: "crud.php",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                // beforeSend 	: function() {
                // 	$(".createupdate_btn").attr("disabled","disabled");
                // 	$(".createupdate_btn").text("Loading...");
                // },
                success: function (data) {
                    var obj = JSON.parse(data);
                    var msg = obj.msg;
                    // var std_app_no = obj.std_app_no;
                    var status = obj.status;
                    var error = obj.error;

                    if (!status) {
                        url = '';
                        $(".createupdate_btn").text("Error");
                        console.log(error);
                    } else {
                        if (msg == "already") {
                            // Button Change Attribute
                            url = '';
                            log_sweetalert(msg);
                        } else {
                            // $(".createupdate_btn").text("Add");
                            log_sweetalert("create");
                            hostelChoice();
                            // $('a[href="#tab35"]').tab('show');
                            // $("#status_description").val("");
                        }
                    }
                },
                error: function (data) {
                    alert("Network Error");
                }
            });
        } else {
            log_sweetalert("form_alert");
        }
    } else {
        if ((dob && age && blood_group && mailid && religion && mothertongue && refugee && orphan && singleparent && first_graduate && dadname && momname && dadqualification && momOccupation && dadmobno && Pincode && guardianno && District && door_no && street_name && area_name) != '') {
            var data = new FormData();
            data.append("dob", base64Encode(dob));
            data.append("age", base64Encode(age));
            data.append("bloodgroup", base64Encode(blood_group));
            data.append("mailid", base64Encode(mailid));
            data.append("religion", base64Encode(religion));
            data.append("mothertongue", base64Encode(mothertongue));
            // data.append("aadharno", base64Encode(aadharno));
            data.append("refugee", base64Encode(refugee));
            data.append("orphan", base64Encode(orphan));
            data.append("singleparent", base64Encode(singleparent));
            data.append("firstgraduate", base64Encode(first_graduate));
            data.append("dadname", base64Encode(dadname));
            data.append("momname", base64Encode(momname));
            data.append("dadqualification", base64Encode(dadqualification));
            data.append("momqualification", base64Encode(momqualification));
            data.append("dadOccupation", base64Encode(dadOccupation));
            data.append("momOccupation", base64Encode(momOccupation));
            data.append("dadmobno", base64Encode(dadmobno));
            data.append("guardianno", base64Encode(guardianno));
            data.append("door_no", base64Encode(door_no));
            data.append("taluk", base64Encode(taluk));
            data.append("District", base64Encode(District));
            data.append("Pincode", base64Encode(Pincode));
            data.append("street_name", base64Encode(street_name));
            data.append("area_name", base64Encode(area_name));
            data.append("s1_unique_id", base64Encode(s1_unique_id));
            data.append("action", "familyinfos");

            $.ajax({
                type: "POST",
                url: "crud.php",
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                // beforeSend 	: function() {
                // 	$(".createupdate_btn").attr("disabled","disabled");
                // 	$(".createupdate_btn").text("Loading...");
                // },
                success: function (data) {
                    var obj = JSON.parse(data);
                    var msg = obj.msg;
                    // var std_app_no = obj.std_app_no;
                    var status = obj.status;
                    var error = obj.error;

                    if (!status) {
                        url = '';
                        $(".createupdate_btn").text("Error");
                        console.log(error);
                    } else {
                        if (msg == "already") {
                            // Button Change Attribute
                            url = '';
                            log_sweetalert(msg);
                        } else {
                            // $(".createupdate_btn").text("Add");
                            log_sweetalert("create");
                            hostelChoice();
                            // $('a[href="#tab35"]').tab('show');
                            // $("#status_description").val("");
                        }
                    }
                },
                error: function (data) {
                    alert("Network Error");
                }
            });
        } else {
            log_sweetalert("form_alert");
        }
    }
}




// function toggleDiv_emis() {
// 	var emis_no = $("#emis_no").val();
// 	if (emis_no != '' && emis_no.length == '10') {
// 		var hiddenDiv = document.getElementById("hiddenDiv");
// 		if (hiddenDiv.style.display === "none") {
// 			hiddenDiv.style.display = "inline-flex";
// 		} else {
// 			hiddenDiv.style.display = "none";
// 		}
// 	} else {

// 		log_sweetalert('valid_emis');
// 	}
// }

function emis_div() {
    var emis_no = $("#emis_no").val();
    if (emis_no != '' && emis_no.length == '10') {

        var hiddenDiv = document.getElementById("hiddenDiv");

        hiddenDiv.style.display = "inline-flex";
    }
}

function umis_div() {
    var umis_no = $("#umis_no").val();
    if (umis_no != '' && umis_no.length == '10') {

        var hiddenDivUmis = document.getElementById("toggleUmis");

        hiddenDivUmis.style.display = "inline-flex";
    }
}

function get_father_occu(val) {




    var ajax_url = "crud.php";
    // if (hostel_district) {
    var data = {
        "father_occu": val,
        "action": "get_occu"
    }

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function (data) {

            if (data) {
                $("#dadOccupation").val(data);
            }
        }
    });
}

function get_umis_father_occu(val) {




    var ajax_url = "crud.php";
    // if (hostel_district) {
    var data = {
        "father_occu": val,
        "action": "get_umis_occu"
    }

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function (data) {

            if (data) {
                $("#dadOccupation").val(data);
            }
        }
    });
}


function get_mother_occu(val) {




    var ajax_url = "crud.php";
    // if (hostel_district) {
    var data = {
        "father_occu": val,
        "action": "get_occu"
    }

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function (data) {

            if (data) {
                $("#momOccupation").val(data);
            }
        }
    });
}

function get_umis_mother_occu(val) {




    var ajax_url = "crud.php";
    // if (hostel_district) {
    var data = {
        "father_occu": val,
        "action": "get_umis_occu"
    }

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function (data) {

            if (data) {
                $("#momOccupation").val(data);
            }
        }
    });
}


function umis_no_check() {

    var umis_no = $("#umis_no").val();

    if (umis_no != '' && (umis_no.length == '10' || umis_no.length == '12')) {

        var ajax_url = "crud.php";
        var data = {
            "umis_no": umis_no,
            "action": "umis_no_check"
        }

        $.ajax({
            type: "POST",
            url: ajax_url,
            data: data,
            success: function (data) {
                var obj = JSON.parse(data);
                var msg = obj.msg;

                if (msg == 'already') {
                    log_sweetalert("umis_already");
                } else {
                    toggleDiv_umis();
                }
            }
        });
    }
}



function umis_already() {

    var umis_no = $("#umis_no").val();

    if (umis_no != '' && (umis_no.length == '10' || umis_no.length == '12')) {


        var ajax_url = "crud.php";
        // if (hostel_district) {
        var data = {
            "umis_no": umis_no,
            "action": "umis_already"
        }

        $.ajax({
            type: "POST",
            url: ajax_url,
            data: data,
            success: function (data) {
                var obj = JSON.parse(data);
                var msg = obj.msg;

                if (msg == 'already') {
                    // log_sweetalert("umis_already");
                    umis_no_check();
                } else {
                    toggleDiv_umis();
                }
            }
        });
    }
}




function emis_already() {

    var emis_no = $("#emis_no").val();

    if (emis_no != '' && emis_no.length == '10') {


        var ajax_url = "crud.php";
        // if (hostel_district) {
        var data = {
            "emis_no": emis_no,
            "action": "emis_already"
        }

        $.ajax({
            type: "POST",
            url: ajax_url,
            data: data,
            success: function (data) {
                var obj = JSON.parse(data);
                var msg = obj.msg;

                if (msg == 'already') {
                    // log_sweetalert("emis_already");
                    emis_no_check();
                } else {
                    toggleDiv_emis();
                }
            }
        }); 
    }
}


function emis_no_check() {

    var emis_no = $("#emis_no").val();

    if (emis_no != '' && emis_no.length == '10') {


        var ajax_url = "crud.php";
        // if (hostel_district) {
        var data = {
            "emis_no": emis_no,
            "action": "emis_no_check"
        }

        $.ajax({
            type: "POST",
            url: ajax_url,
            data: data,
            success: function (data) {
                var obj = JSON.parse(data);
                var msg = obj.msg;

                if (msg == 'already') {
                    log_sweetalert("emis_already");
                } else {
                    toggleDiv_emis();
                }
            }
        });
    }
}



function toggleDiv_emis() {
    showLoader();
    var emis_no = $("#emis_no").val();

    if (emis_no != '' && emis_no.length == '10') {

        showLoader();
        var ajax_url = "crud.php";
        var data = {
            "emis_id": emis_no,
            "action": "insert_emis"
        };

        $.ajax({
            type: "POST",
            url: ajax_url,
            data: data,
            dataType: 'json', // Parse response as JSON
            success: function (response) {
                hideLoader();
                // var obj = JSON.parse(data);
                // var msg = obj.msg;
                if (response.data.message != "No Data Found") {


                    var result_data = response.data.result[0];

                    document.getElementById('emis_name').value = result_data.name;
                    document.getElementById('emis_dob').value = result_data.dob;

                    // document.getElementById('emis_dob').value = result_data.dob;

                    var dateStr = result_data.dob;
                    var [day, month, year] = dateStr.split('-');
                    var formattedDate = `${year}-${month}-${day}`;
                    document.getElementById('dob').value = formattedDate;


                    get_age(result_data.dob);


                    document.getElementById('emis_class').value = result_data.class_studying_id;
                    if (result_data.class_studying_id == '11' || result_data.class_studying_id == '12') {
                        document.getElementById('emis_group').style.display = 'block';
                        document.getElementById('group_lbl').style.display = 'block';
                    } else {
                        document.getElementById('emis_group').style.display = 'none';
                        document.getElementById('group_lbl').style.display = 'none';

                    }
                    document.getElementById('emis_group').value = result_data.group_name;
                    document.getElementById('emis_medium').value = result_data.MEDINSTR_DESC;
                    document.getElementById('emis_school_name').value = result_data.school_name;
                    document.getElementById('emis_school_block').value = result_data.block_name;
                    document.getElementById('emis_school_district').value = result_data.district_name;
                    document.getElementById('emis_hostel_district').value = result_data.district_name;

                    document.getElementById('udise_code').value = result_data.udise_code;
                    document.getElementById('class_section').value = result_data.class_section;
                    document.getElementById('community_name').value = result_data.community_name;
                    document.getElementById('group_code_id').value = result_data.group_code_id;
                    document.getElementById('emis_mother_occupation').value = result_data.mother_occupation;
                    document.getElementById('emis_father_occupation').value = result_data.father_occupation;
                    document.getElementById('emis_mother_name').value = result_data.mother_name;
                    document.getElementById('emis_father_name').value = result_data.father_name;

                    get_father_occu(result_data.father_occupation);
                    get_mother_occu(result_data.mother_occupation);

                    host_district();



                    var hiddenDiv = document.getElementById("hiddenDiv");
                    // console.log(hiddenDiv);

                    // if (hiddenDiv.style.display == "none") {
                    hiddenDiv.style.display = "inline-flex";
                    // } else {
                    // 	hiddenDiv.style.display = "none";
                    // } 
                    // Optionally, do something with the returned data
                } else {
                    // Handle error
                    hideLoader();
                    console.log(response.data.message);
                    log_sweetalert('valid_emis');
                    var hiddenDiv = document.getElementById("hiddenDiv");
                    hiddenDiv.style.display = "none";


                }

            },

            error: function (xhr, status, error) {
                // Handle AJAX error
                console.error(xhr.responseText);
            }
        });


    } else {
        hideLoader();
        var hiddenDiv = document.getElementById("hiddenDiv");
        hiddenDiv.style.display = "none";
        log_sweetalert('valid_emis');
    }
}

function empty_emis() {


    var emis_no = $("#emis_no").val();

    if (emis_no == '') {

        document.getElementById('emis_name').value = '';
        document.getElementById('emis_dob').value = '';
        document.getElementById('emis_class').value = '';
        document.getElementById('emis_group').value = '';
        document.getElementById('emis_medium').value = '';
        document.getElementById('emis_school_name').value = '';
        document.getElementById('emis_school_block').value = '';
        document.getElementById('emis_school_district').value = '';

        var hiddenDiv = document.getElementById("hiddenDiv");
        hiddenDiv.style.display = "none";
    }
}


function get_district_id(institude_id) {



    var ajax_url = "crud.php";
    // if (hostel_district) {
    var data = {
        "institude_id": institude_id,
        "action": "get_district_id"
    }

    $.ajax({
        type: "POST",
        url: ajax_url,
        data: data,
        success: function (data) {

            $("#caDistrictId").val(data);
            // $("#umis_dist").text(data);
            get_umis_district();

        }
    });

}

function toggleDiv_umis() {
    showLoader();
    var umis_no = $("#umis_no").val();

    if (umis_no != '' && (umis_no.length == '10' || umis_no.length == '12')) {
        showLoader();

        var ajax_url = "crud.php";
        var data = {
            "umis_no": umis_no,
            "action": "insert_umis"
        };

        $.ajax({
            type: "POST",
            url: ajax_url,
            data: data,
            dataType: 'json', // Parse response as JSON
            success: function (response) {
                hideLoader();
                // var obj = JSON.parse(data);
                // var msg = obj.msg;
                //console.log(response);
                if (response.status) {

                    // if (response.status) {
                    // Handle success
                    // alert(response.data.result);
                    // console.log(response.data.result[0]);
                    //var result_data = response.data.result[0];
                    // console.log(response);
                    // console.log(response.data);
                    //alert("inside the response");
                    // var name = response.data.name;
                    // alert(name);


                    document.getElementById('umis_std_name').value = response.data.name;

                    var courseId = response.data.courseId;

                    var dateOfAdmission = response.data.dateOfAdmission;
                    var year = new Date(dateOfAdmission).getFullYear().toString();
                    document.getElementById('umis_yoa').value = year;

                    var dob = new Date(response.data.dateOfBirth);
                    var formattedDOB = dob.getDate() + '-' + (dob.getMonth() + 1) + '-' + dob.getFullYear();
                    var formatDOB = dob.getFullYear() + '-' + (dob.getMonth() + 1) + '-' + dob.getDate();
                    document.getElementById('umis_dob').value = formattedDOB;

                    document.getElementById('umis_yos').value = response.data.yearOfStudy;
                    document.getElementById('umis_clg_name').value = response.data.instituteName;
                    document.getElementById('umis_clg_add').value = response.data.caAddress;
                    document.getElementById('umis_std_course').value = response.data.courseType;
                    document.getElementById('caDistrictId').value = response.data.caDistrictId;

                    //document.getElementById('umis_std_degree').value = response.data.courseId;
                    document.getElementById('dob').value = formattedDOB;
                    document.getElementById('bloodgroup').value = response.data.bloodGroupId;
                    document.getElementById('mailid').value = response.data.emailId;
                    document.getElementById('firstgraduate').value = response.data.isFirstGraduate;
                    document.getElementById('dadmobno').value = response.data.parentMobileNo;
                    document.getElementById('dadOccupation').value = response.data.fatherOccupationId;
                    document.getElementById('momOccupation').value = response.data.motherOccupationId;
                    var isDifferentlyAbled = response.data.isDifferentlyAbled;
                    var institute_id = response.data.instituteId;

                    if (isDifferentlyAbled == false) {
                        var diff_abled = 'No';
                        // document.getElementById('phy_div').style.display = 'inline-flex';
                    } else if (isDifferentlyAbled == true) {
                        var diff_abled = 'Yes';
                        document.getElementById('phy_div').style.display = 'inline-flex';


                    }
                    document.getElementById('diff_abled').value = diff_abled;
                    get_umis_father_occu(response.data.fatherOccupationId);
                    get_umis_mother_occu(response.data.motherOccupationId);
                    get_age(formatDOB);
                    get_blood_group();
                    get_district_id(institute_id);
                    // get_umis_district();

                    var ajax_url = "crud.php";
                    var data_course = {
                        "courseId": courseId,
                        "action": "get_courseName"
                    }

                    $.ajax({
                        type: "POST",
                        url: ajax_url,
                        data: data_course,
                        success: function (data) {
                            $("#umis_std_degree").val(data);
                        }
                    });


                    var hiddenDivUmis = document.getElementById("toggleUmis");

                    hiddenDivUmis.style.display = "inline-flex";

                    //for umis insert
                    var s1_unique_id = $("#s1_unique_id").val();
                    // alert(response.data.udid);
                    var ajax_url = "crud.php";
                    var data_umis = {
                        "s1_unique_id": base64Encode(s1_unique_id),
                        "umis_no": base64Encode(umis_no),
                        "name": base64Encode(response.data.name),
                        "emsid": base64Encode(response.data.emsid),
                        "dateOfBirth": base64Encode(response.data.dateOfBirth),
                        "nationalityId": base64Encode(response.data.nationalityId),
                        "religionId": base64Encode(response.data.religionId),
                        "communityId": base64Encode(response.data.communityId),
                        "casteId": base64Encode(response.data.casteId),
                        "isFirstGraduate": base64Encode(response.data.isFirstGraduate),
                        "isSpecialCategory": base64Encode(response.data.isSpecialCategory),
                        "isDifferentlyAbled": base64Encode(response.data.isDifferentlyAbled),
                        "udid": base64Encode(response.data.udid),
                        "disabilityId": base64Encode(response.data.disabilityId),
                        "extentOfDisability": base64Encode(response.data.extentOfDisability),
                        "bloodGroupId": base64Encode(response.data.bloodGroupId),
                        "genderId": base64Encode(response.data.genderId),
                        "salutationId": base64Encode(response.data.salutationId),
                        "instituteId": base64Encode(response.data.instituteId),
                        "umisId": base64Encode(response.data.umisId),
                        "nameAsOnCertificate": base64Encode(response.data.nameAsOnCertificate),
                        "isFirstGraduateVerifiedbyUniversity": base64Encode(response.data.isFirstGraduateVerifiedbyUniversity),
                        "isFirstGraduateVerifiedbyHod": base64Encode(response.data.isFirstGraduateVerifiedbyHod),
                        "mobileNumber": base64Encode(response.data.mobileNumber),
                        "emailId": base64Encode(response.data.emailId),
                        "permAddress": base64Encode(response.data.permAddress),
                        "countryId": base64Encode(response.data.countryId),
                        "stateId": base64Encode(response.data.stateId),
                        "districtId": base64Encode(response.data.districtId),
                        "zoneId": base64Encode(response.data.zoneId),
                        "blockId": base64Encode(response.data.blockId),
                        "caCountryId": base64Encode(response.data.caCountryId),
                        "caStateId": base64Encode(response.data.caStateId),
                        "caDistrictId": base64Encode(response.data.caDistrictId),
                        "caAddress": base64Encode(response.data.caAddress),
                        "caZoneId": base64Encode(response.data.caZoneId),
                        "caCorporationId": base64Encode(response.data.caCorporationId),
                        "caBlockId": base64Encode(response.data.caBlockId),
                        "caVillagePanchayatId": base64Encode(response.data.caVillagePanchayatId),
                        "caWardId": base64Encode(response.data.caWardId),
                        "caTalukId": base64Encode(response.data.caTalukId),
                        "caVillageId": base64Encode(response.data.caVillageId),
                        "talukId": base64Encode(response.data.talukId),
                        "villageId": base64Encode(response.data.villageId),
                        "wardId": base64Encode(response.data.wardId),
                        "corporationId": base64Encode(response.data.corporationId),
                        "villagePanchayatId": base64Encode(response.data.villagePanchayatId),
                        "courseId": base64Encode(response.data.courseId),
                        "courseSpecializationId": base64Encode(response.data.courseSpecializationId),
                        "dateOfAdmission": base64Encode(response.data.dateOfAdmission),
                        "academicYearId": base64Encode(response.data.academicYearId),
                        "streamInfoId": base64Encode(response.data.streamInfoId),
                        "courseType": base64Encode(response.data.courseType),
                        "mediumOfInstructionType": base64Encode(response.data.mediumOfInstructionType),
                        "academicStatusType": base64Encode(response.data.academicStatusType),
                        "yearOfStudy": base64Encode(response.data.yearOfStudy),
                        "isLateralEntry": base64Encode(response.data.isLateralEntry),
                        "isHosteler": base64Encode(response.data.isHosteler),
                        "hostelAdmissionDate": base64Encode(response.data.hostelAdmissionDate),
                        "leavingFromHostelDate": base64Encode(response.data.leavingFromHostelDate),
                        "studentId": base64Encode(response.data.studentId),
                        "parentMobileNo": base64Encode(response.data.parentMobileNo),
                        "fatherOccupationId": base64Encode(response.data.fatherOccupationId),
                        "motherOccupationId": base64Encode(response.data.motherOccupationId),
                        "guardianOccupationId": base64Encode(response.data.guardianOccupationId),
                        "aisheId": base64Encode(response.data.aisheId),
                        "instituteName": base64Encode(response.data.instituteName),
                        "instituteTypeId": base64Encode(response.data.instituteTypeId),
                        "instituteOwnershipId": base64Encode(response.data.instituteOwnershipId),
                        "instituteCategoryId": base64Encode(response.data.instituteCategoryId),
                        "instituteStatusType": base64Encode(response.data.instituteStatusType),
                        "universityName": base64Encode(response.data.universityName),
                        "universityTypeId": base64Encode(response.data.universityTypeId),
                        "hodName": base64Encode(response.data.hodName),
                        "departmentName": base64Encode(response.data.departmentName),


                        "action": "umis_insert"
                    }


                    $.ajax({
                        type: "POST",
                        url: ajax_url,
                        data: data_umis,
                        success: function (data) {

                        }
                    });


                    //end umis insert

                } else {
                    // Handle erro
                    hideLoader();
                    console.log(response.data.message);
                    log_sweetalert('valid_umis');
                    var hiddenDivUmis = document.getElementById("toggleUmis");
                    hiddenDivUmis.style.display = "none";
                }

            },

            error: function (xhr, status, error) {
                // Handle AJAX error
                console.error(xhr.responseText);
            }
        });


    } else {
        hideLoader();
        var hiddenDivUmis = document.getElementById("toggleUmis");
        hiddenDivUmis.style.display = "none";
        log_sweetalert('valid_umis');
    }
}

function showLoader() {

    $("#loader").css("display", "inline-block"); // or "block" depending on your preference
}

function hideLoader() {
    $("#loader").css("display", "none");
}

function empty_umis() {

    var umis_no = $("#umis_no").val();

    if (umis_no == '') {

        $("#umis_no").val('');
        $("#umis_std_name").val('');
        $("#umis_dob").val('');
        $("#umis_std_degree").val('');
        $("#umis_std_course").val('');
        $("#umis_yoa").val('');
        $("#umis_yos").val('');
        $("#umis_clg_name").val('');
        $("#umis_clg_add").val('');
        document.getElementById('havingUmis').style.display = 'none';
    }
}

document.getElementById('umisSelect').addEventListener('change', function () {
    var selectedValue = this.value;
    if (selectedValue === 'Yes') {
        document.getElementById('havingUmis').style.display = 'block';
        document.getElementById('Noumis').style.display = 'none';
    } else if (selectedValue === 'No') {
        document.getElementById('havingUmis').style.display = 'none';
        document.getElementById('Noumis').style.display = 'inline-flex';
    } else {
        document.getElementById('havingUmis').style.display = 'none';
        document.getElementById('Noumis').style.display = 'none';
    }
});

function validateNumber() {
    // alert();
    var inputField = document.getElementById("no_umis_pincode");
    var inputValue = inputField.value.trim();
    var lblError = document.getElementById("invalid_no");
    lblError.innerHTML = "";

    // Check if the input starts with  6
    var firstDigit = inputValue.charAt(0);
    if (['6'].includes(firstDigit)) {

        inputField.style = "";
    } else {
        if (inputValue != '') {
            // alert("Invalid Mobile Number");
            lblError.innerHTML = "Must Start With 6.";
            $("#no_umis_pincode").val('');


            inputField.style = "2px solid red";
        }
    }
}

//family-personal--pincode//

function validatepincode() {
    // alert();
    var inputField = document.getElementById("Pincode");
    var inputValue = inputField.value.trim();
    var lblError = document.getElementById("invalid_pincode");
    lblError.innerHTML = "";

    // Check if the input starts with  6
    var firstDigit = inputValue.charAt(0);
    if (['6'].includes(firstDigit)) {

        inputField.style = "";
    } else {
        if (inputValue != '') {
            // alert("Invalid Mobile Number");
            lblError.innerHTML = "Enter valid pincode.";
            $("#Pincode").val('');

            inputField.style = "2px solid red";
        }
    }
}

function validateMobileNum() {
    // alert();
    //aadharno
    var inputField = document.getElementById("dadmobno");
    var inputField_2 = document.getElementById("guardianno");
    var inputField_3 = document.getElementById("aadharno");
    var inputValue = inputField.value.trim();
    var inputValue_2 = inputField_2.value.trim();
    var inputValue_3 = inputField_3.value.trim();

    var lblError = document.getElementById("invalid_nos");
    var lblErrorGuardian = document.getElementById("guardianno_error");
    var lblErroraadharno = document.getElementById("aadharno_error");

    lblError.innerHTML = "";
    lblErrorGuardian.innerHTML = "";
    lblErroraadharno.innerHTML = "";

    // var lblError_1 = document.getElementById("invalid_nos_1");
    //     lblError_1.innerHTML = "";
    // Check if the input starts with  6
    if (inputField) {
        var firstDigit = inputValue.charAt(0);
        if (['9', '8', '7', '6'].includes(firstDigit)) {

            inputField.style = "";
        } else {
            if (inputValue != '') {
                // alert("Invalid Mobile Number");
                lblError.innerHTML = "Enter valid mobile number.";
                inputField.value = '';

                // $("#dadmobno").val('');


                inputField.style = "2px solid red";
            }
        }
    }

    if (inputField_2) {

        var firstDigit_2 = inputValue_2.charAt(0);
        if (['9', '8', '7', '6'].includes(firstDigit_2)) {

            inputField_2.style = "";

        } else {
            if (inputValue_2 != '') {
                // alert("Invalid Mobile Number");
                lblErrorGuardian.innerHTML = "Enter valid mobile number.";
                inputField_2.value = '';
                // $("#guardianno").val('');

                inputField_2.style = "2px solid red";
            }
        }
    }

    if (inputField_3) {

        var firstDigit_3 = inputValue_3.charAt(0);
        if (['9', '8', '7', '6'].includes(firstDigit_3)) {

            inputField_3.style = "";

        } else {
            if (inputValue_3 != '') {
                // alert("Invalid Mobile Number");
                lblErroraadharno.innerHTML = "Enter valid mobile number.";
                inputField_3.value = '';
                // $("#guardianno").val('');

                inputField_3.style = "2px solid red";
            }
        }
    }

}


/////////////////////////////

function valid_mobile_number(input) {
    const allowedChars = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    const validStartingChars = ['6', '7', '8', '9'];

    // Check if the first character is valid
    if (input.value.length > 0 && !validStartingChars.includes(input.value.charAt(0))) {
        var msg = "number_alert"
        log_sweetalert(msg);
        // Clear the input if the first character is invalid
        input.value = '';
        return;
    }

    // Filter out characters that are not in the allowedChars array
    input.value = input.value.split('').filter(char => allowedChars.includes(char)).join('');
}

function valid_user_name(input) {

    const allowedChars = [' ', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', ' '
    ];

    // Filter out characters that are not in the allowedChars array
    input.value = input.value.split('').filter(char => allowedChars.includes(char)).join('');
}

function off_id(input) {

    const allowedChars = [' ', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '-', '_',
        '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '-', '_'
    ];

    // Filter out characters that are not in the allowedChars array
    input.value = input.value.split('').filter(char => allowedChars.includes(char)).join('');
}

function validateCharInput(input) {

    const allowedChars = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
        'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
        ' ', ',', '.', '!', '-', '/', '_', '@'];

    // Filter out characters that are not in the allowedChars array
    input.value = input.value.split('').filter(char => allowedChars.includes(char)).join('');
}

function description_val(input) {

    const allowedChars = [
        'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
        ' ', ',', '.'];

    // Filter out characters that are not in the allowedChars array
    input.value = input.value.split('').filter(char => allowedChars.includes(char)).join('');
}

function mail_valid(input) {

    const allowedChars = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
        'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
        ' ', '_', '.', '@'];

    // Filter out characters that are not in the allowedChars array
    input.value = input.value.split('').filter(char => allowedChars.includes(char)).join('');
}

function valid_address(input) {

    const allowedChars = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
        'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
        ' ', ',', '.', '-', '/'];

    // Filter out characters that are not in the allowedChars array
    input.value = input.value.split('').filter(char => allowedChars.includes(char)).join('');
}


function number_only(input) {

    const allowedChars = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '.', ','
    ];
    // Filter out characters that are not in the allowedChars array
    input.value = input.value.split('').filter(char => allowedChars.includes(char)).join('');
}

function pincode(input) {

    const allowedChars = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    // Filter out characters that are not in the allowedChars array
    input.value = input.value.split('').filter(char => allowedChars.includes(char)).join('');
}

function dec_number(input) {

    const allowedChars = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '.'];

    // Filter out characters that are not in the allowedChars array
    input.value = input.value.split('').filter(char => allowedChars.includes(char)).join('');
}

function mail_valid(input) {

    const allowedChars = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
        'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
        ' ', '_', '.', '@'];

    // Filter out characters that are not in the allowedChars array
    input.value = input.value.split('').filter(char => allowedChars.includes(char)).join('');
}

function valid_address(input) {

    const allowedChars = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
        'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
        ' ', ',', '.', '-', '/'];

    // Filter out characters that are not in the allowedChars array
    input.value = input.value.split('').filter(char => allowedChars.includes(char)).join('');
}

$(function () {
    var batch_no = $('#batch_no').val()

    if (batch_no === '' || batch_no === null || batch_no === undefined) {

        document.getElementById('emis_cancel').style.display = 'block';
        document.getElementById('umis_cancel').style.display = 'block';
    } else {

        document.getElementById('emis_cancel').style.display = 'none';
        document.getElementById('umis_cancel').style.display = 'none';
    }
});