<style>

table tr th{ vertical-align:middle;}
table tr td{ vertical-align:middle;padding: 8px 6px!important;}
table.table tr th, td {
    padding: 12px;
}
thead.table-info2 tr th {
    color: #000;
    font-weight: 600;
}
thead.table-info2 {
    background: #f9f9f9;
    border-top: 1px solid #000;
    border-bottom: 1px solid #000;
}
.p-gnd{
	margin:30px;
}
h2.print-hed {
    text-align: center;
    font-size: 22px;
    font-weight: 600;
    margin-bottom: 21px;
    color: #00aff0;
}
table.table tr td {
    font-size: 16px;
}
td.test h3 {
    font-size: 15px;
}
td.test h3 span {
    font-size: 15px;
    font-weight: 700;
}
</style>

<link rel="stylesheet" href="assets/css/app-saas.min.css">
<div class="card p-gnd">
<div class="card-body">
    <h2 class="print-hed">Online Document Download </h2>
                    
<div class="">

<table class="table table-centered mb-0">
    <thead class="table-info2">
        <tr>
           <th >S.No</th>
            <th >Date</th>
            <th >Document Details</th>
            <th >Download Document</th>
        </tr>
    </thead>
    <tbody>

<?php
        include 'config/dbconfig.php';
        // include 'function.php';
  

			$table = "application_download";

            $json_array     = "";

            $columns        = [
                "@a:=@a+1 s_no",
                "validate_date",
                "application_name",
                "description",
                "file_name",
                // "file_org_name",
                "is_active",
                "unique_id"
            ];

            $table_details  = [
                $table ,
                $columns
            ];
            $where          = "is_delete = 0";
            $order_by       = "";
        
        
            
        
            $sql_function   = "SQL_CALC_FOUND_ROWS";
        
            $result         = $pdo->select($table_details,$where);
            // print_r($result);die();
            $total_records  = total_records();
        
            if ($result->status) {
        
                $res_array      = $result->data;
                $sno= 1;

                foreach ($res_array as $key => $value) {
                    // $value['district_name'] = district_list($value['district_name']);
                    $app_file_name = $value['file_name'];
                    $value['s_no']= $sno;
                    $value['file_name'] = image_views( $value['unique_id'], $value['file_name']);
                    // $file_name          = $result_values[0]["file_name"];
                    // $unique_id = $result_values[0]["unique_id"];
                    $value['is_active']     = is_active_show($value['is_active']);



                    $html = '<tr>
                    <td>' . $value['s_no'] . '</td>
          
            <td>' . $value['validate_date'] . '</td>
            <td>
                <div style="font-size:13px;">
                    Application Name: ' . $value['application_name'] . '<br><br><br>
                    <div style="font-size:12px;">
                        [Description:] ' . $value['description'] . '
                        <td>' . $value['file_name'] . '</td>
                    </div>
                </div>
            </td>
        </tr>';

echo $html;
                   
        
                    $btn_update         = btn_update($folder_name,$value['unique_id']);
                    $btn_delete         = btn_delete($folder_name,$value['unique_id']);
        
                    $value['unique_id'] = $btn_update.$btn_delete;
                    $data[]             = array_values($value);
                    $sno++;

                }
                
                $json_array = [
                    "draw"              => intval($draw),
                    "recordsTotal"      => intval($total_records),
                    "recordsFiltered"   => intval($total_records),
                    "data"              => $data,
                    "testing"           => $result->sql
                ];
            } else {
                print_r($result);
            }
              
         
    
    
        
        ?>


<!-- 
    <tr>
            <td><?= $value['s_no']; ?></td>
            <td><?= $value['date_time']; ?></td>
            <td><?= $value['validate_date']; ?></td>
            <td>
            <div style="font-size:13px;">
    <?='Application Name:' .$value['application_name']; ?><br><br><br>
 
    <div style="font-size:12px;">

    
        <?= '[Description:]'.' '.$value['description']; ?>

        <td><?= $value['file_name']; ?></td>


    </div>
</td>
        </tr> -->
      
    </tbody>
</table>

</div>
</div>
</div>
</div>


<script>

    
function print_view(file_name) {
	var iframeContent = '<html><head><style>body, html {width: 100%; height: 100%; margin: 0; padding: 0;}</style></head><body>' +
		'<iframe id="myIframe" src="adhmAdmin/uploads/application_download' + file_name + '"' + ' style="height:100%; width:100%; border:none;"></iframe>' +
		'</body></html>';


	var win = window.open("", "", "width=600,height=480,toolbar=no,menubar=no,resizable=yes");

	if (win) {

		win.document.open();

		win.document.write(iframeContent);

		win.document.close();

		var iframe = win.document.getElementById('myIframe');
		iframe.onload = function () {
			var iframeDoc = iframe.contentDocument || iframe.contentWindow.document;

			// Prevent right-click context menu inside the iframe
			iframeDoc.addEventListener('contextmenu', function (e) {
				e.preventDefault();
			});

			iframeDoc.addEventListener('keydown', function (e) {
				// Check for specific key combinations
				if ((e.ctrlKey || e.metaKey) && (e.keyCode == 83 || e.keyCode == 67 || e.keyCode == 74 || e.keyCode == 73)) {
					// Prevent default action (e.g., save, copy, downloads, inspect)
					e.preventDefault();
					e.stopPropagation();
					return false;
				}
				// Check for F12 key
				if (e.keyCode == 123) {
					e.preventDefault();
					e.stopPropagation();
					return false;
				}
			});

		};


	} else {
		alert('Please allow popups for this website');
	}
}

function print_pdf(file_name) {
	var pdfUrl = "adhmAdmin/uploads/application_download" + file_name;
	var link = document.createElement("a");
	link.href = pdfUrl;
	link.download = file_name;
	document.body.appendChild(link);
	link.click();
	document.body.removeChild(link);
}

function print(file_name) {
	// Construct the full URL to your Excel file
	var excelUrl = "adhmAdmin/uploads/application_download" + file_name;
	var link = document.createElement("a");
	link.href = excelUrl;
	link.download = file_name;
	document.body.appendChild(link);
	link.click();
	document.body.removeChild(link);
}

// function print_pdf(file_names) {
    
//         onmouseover = window.open('../adhm/adhmAdmin/uploads/application_download' + file_names, 'onmouseover', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
//     }

//     function print_excel(file_names) {
//         // alert('hi');
//         window.location = '../adhm/adhmAdmin/uploads/application_download' + file_names, 'onmouseover', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no';
//     }
//     // function print(file_names) {
//     //     onmouseover = window.open('uploads/kra_kpi_form/' + file_names, 'onmouseover', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
//     // }
//     function print_view(file_names) {
//         onmouseover = window.open('../adhm/adhmAdmin/uploads/application_download/' + file_names, 'onmouseover', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
//     }

//     function print_1(file_names) {
//         window.location = '../adhm/adhmAdmin/uploads/application_download/' + file_names, 'onmouseover', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no';
//     }

//     function print_pdf_1(file_names) {
//         onmouseover = window.open('../adhm/adhmAdmin/uploads/application_download' + file_names, 'onmouseover', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
//     }
//     // function print(file_names) {
//     //     onmouseover = window.open('uploads/kra_kpi_form/' + file_names, 'onmouseover', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
//     // }
//     function print_view_1(file_names) {
//         window.open('../adhm/adhmAdmin/uploads/application_download' + file_names, 'onmouseover', 'height=600,width=900,scrollbars=yes,resizable=no,left=200,top=150,toolbar=no,location=no,directories=no,status=no,menubar=no');
//     }
        </script>

        <?php
function image_views($file_names = "", $app_file_name="")
{
  
    $file_names = explode(',', $app_file_name);
   
    $image_view = '';
    if ($file_names) {
        foreach ($file_names as $file_key => $file_names) {
        
            if ($file_key != 0) {
                if ($file_key % 4 != 0) {
                    $image_view .= "&nbsp";
                } else {
                    $image_view .= "<br><br>";
                }
            }
            $cfile_name = explode('.',$file_names);
            if ($file_names) {
                if (($cfile_name[1] == 'jpg') || ($cfile_name[1] == 'png') || ($cfile_name[1] == 'jpeg')) {
                    $image_view .= '<a href="javascript:print_view(\'/' .  $file_names . '\')"><img src="assets/images/images.png"  height="30px" width="30px" ></a>';
                    // $image_view .= '<img src="uploads/'.$folder_name.'/'.$doc_name.'"  height="50px" width="50px" >';
                } else if ($cfile_name[1] == 'pdf') {
                    $image_view .= '<a href="javascript:print_pdf(\'/' .$file_names. '\')"><img src="pdf.png"  height="30px" width="30px" ></a>';
                } else if (($cfile_name[1] == 'pdf') || ($cfile_name[1] == 'xls') || ($cfile_name[1] == 'xlsx')) {
                    $image_view .= '<a href="javascript:print_excel(\'/' .  $file_names . '\')"><img src="assets/images/excel.png"  height="30px" width="30px" ></a>';
                } else if (($cfile_name[1] == 'txt') || ($cfile_name[1] == 'docx') || ($cfile_name[1] == 'doc')) {
                    $image_view .= '<a href="javascript:print(\'/' .  $file_names . '\')"><img src="assets/images/word.png"  height="30px" width="30px" ></a>';
                }
            }
        }
    }
    // print_r($image_view);
    return $image_view;
}
        ?>



