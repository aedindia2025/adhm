

<?php 

// Database connection
$mysqli = new mysqli("localhost", "root", "4/rb5sO2s3TpL4gu", "adi_dravidar");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

       
    

        $stmt = $mysqli->prepare("SELECT * FROM std_app_s AS test WHERE test.status = 1 and status_upd_date = '2024-11-11' AND NOT EXISTS ( SELECT 1 FROM std_reg_s AS reg WHERE reg.unique_id = test.unique_id )");
        $stmt->execute();
        $result = $stmt->get_result();
       
        if ($result) {
            $table_update = "std_reg_s";
            $res_array = $result->fetch_all(MYSQLI_ASSOC);
$sn = '0';
            foreach ($res_array as $key => $value) {
               
                // $tot_reg_cnt = total_registered($value['hostel_name']); 

                // if($tot_reg_cnt < $sanc_cnt){

                $select_where = 'unique_id = ? AND is_delete = 0';
                $stmt = $mysqli->prepare("SELECT COUNT(unique_id) AS count FROM $table_update WHERE $select_where");
                $stmt->bind_param("s", $value['unique_id']);
                $stmt->execute();
                $action_obj = $stmt->get_result();
                $stmt->close();

                if ($action_obj) {
                    $data = $action_obj->fetch_assoc();
                    $error = "";
                } else {
                    $data = [];
                    $error = $mysqli->error;
                }

                if ($data["count"]) {
                    $msg = "already";
                } else if ($data["count"] == 0) {

                    // Fetch data from std_app_p2 based on unique_id
                    $select_where_p1 = 'unique_id = ?';
                    $stmt = $mysqli->prepare("SELECT * FROM std_app_s WHERE $select_where_p1");
                    $stmt->bind_param("s", $value['unique_id']);
                    $stmt->execute();
                    $action_obj_p1 = $stmt->get_result();
                    $stmt->close();

                    if ($action_obj_p1) {
                        $data_p1 = $action_obj_p1->fetch_all(MYSQLI_ASSOC);
                        $error_p1 = "";

                        // Insert fetched data into std_reg_p1
                        if (!empty($data_p1)) {
                            foreach ($data_p1 as $row_p1) {
                                unset($row_p1['id']);

                                $std_reg_no = reg_no($academic_year, $value['unique_id']);
                                $row_p1['user_name'] = $std_reg_no;
                                $row_p1['std_reg_no'] = $std_reg_no;

                                $select_where_fetch = 's1_unique_id = ?';
                                $stmt = $mysqli->prepare("SELECT dob FROM std_app_s6 WHERE $select_where_fetch");
                                $stmt->bind_param("s", $value['unique_id']);
                                $stmt->execute();
                                $action_obj_fetch = $stmt->get_result();
                                $stmt->close();


                                if ($action_obj_fetch && $row = $action_obj_fetch->fetch_assoc()) {
                                    $std_dob = $row['dob'];
                                    $formatted_dob = date('d/m/Y', strtotime($std_dob));
                                    $row_p1['password'] .= $formatted_dob;
                                    $row_p1['confirm_password'] .= $formatted_dob;
                                    $row_p1['enc_password'] = hash('sha256', $row_p1['password']);
                                } else {
                                    $error_p1 = $mysqli->error;
                                    $msg_p1 = "error";
                                    // Handle the error accordingly, maybe log it or set an error response
                                }

                                $stmt = $mysqli->prepare("INSERT INTO std_reg_s (" . implode(", ", array_keys($row_p1)) . ") VALUES (" . implode(", ", array_fill(0, count($row_p1), "?")) . ")");
                                $stmt->bind_param(str_repeat("s", count($row_p1)), ...array_values($row_p1));
                                if (!$stmt->execute()) {
                                    $error_p1 = $mysqli->error;
                                    $msg_p1 = "error";
                                    break;
                                }
                                $stmt->close();
                            }
                            $msg_p1 = "inserted";
                        } else {
                            $msg_p1 = "no_data_found";
                        }
                    } else {
                        $error_p1 = $mysqli->error;
                        $msg_p1 = "error";
                    }

                    $select_where = 's1_unique_id = ?';
                    $stmt = $mysqli->prepare("SELECT * FROM std_app_s2 WHERE $select_where");
                    $stmt->bind_param("s", $value['unique_id']);
                    $stmt->execute();
                    $action_obj = $stmt->get_result();
                    $stmt->close();

                    if ($action_obj) {
                        $data = $action_obj->fetch_all(MYSQLI_ASSOC);
                        $error = "";

                        if (!empty($data)) {
                            foreach ($data as $row) {
                                unset($row['id']);
                                $stmt = $mysqli->prepare("INSERT INTO std_reg_s2 (" . implode(", ", array_keys($row)) . ") VALUES (" . implode(", ", array_fill(0, count($row), "?")) . ")");
                                $stmt->bind_param(str_repeat("s", count($row)), ...array_values($row));
                              if (!$stmt->execute()) {
                                    $error = $mysqli->error;
                                    $msg = "error";
                                    break;
                                }
                                $stmt->close();
                            }
                            $msg = "inserted";
                        } else {
                            $msg = "no_data_found";
                        }
                    } else {
                        $error = $mysqli->error;
                        $msg = "error";
                    }

                    // Fetch data from std_app_p3 based on unique_id
                    $select_where_p3 = 's1_unique_id = ?';
                    $stmt = $mysqli->prepare("SELECT * FROM std_app_emis_s3 WHERE $select_where_p3");
                    $stmt->bind_param("s", $value['unique_id']);
                    $stmt->execute();
                    $action_obj_p3 = $stmt->get_result();
                    $stmt->close();

                    if ($action_obj_p3) {
                        $data_p3 = $action_obj_p3->fetch_all(MYSQLI_ASSOC);
                        $error_p3 = "";

                        if (!empty($data_p3)) {
                            foreach ($data_p3 as $row_p3) {
                                unset($row_p3['id']);

                                $stmt = $mysqli->prepare("INSERT INTO std_reg_emis_s3 (" . implode(", ", array_keys($row_p3)) . ") VALUES (" . implode(", ", array_fill(0, count($row_p3), "?")) . ")");
                                $stmt->bind_param(str_repeat("s", count($row_p3)), ...array_values($row_p3));
                                if (!$stmt->execute()) {
                                    $error_p3 = $mysqli->error;
                                    $msg_p3 = "error";
                                    break;
                                }
                                $stmt->close();
                            }
                            $msg_p3 = "inserted";
                        } else {
                            $msg_p3 = "no_data_found";
                        }
                    } else {
                        $error_p3 = $mysqli->error;
                        $msg_p3 = "error";
                    }

                    $select_where_p4 = 's1_unique_id = ?';
                    $stmt = $mysqli->prepare("SELECT * FROM std_app_umis_s4 WHERE $select_where_p4");
                    $stmt->bind_param("s", $value['unique_id']);
                    $stmt->execute();
                    $action_obj_p4 = $stmt->get_result();
                    $stmt->close();

                    if ($action_obj_p4) {
                        $data_p4 = $action_obj_p4->fetch_all(MYSQLI_ASSOC);
                        $error_p4 = "";

                        if (!empty($data_p4)) {
                            foreach ($data_p4 as $row_p4) {
                                unset($row_p4['id']);

                                $stmt = $mysqli->prepare("INSERT INTO std_reg_umis_s4 (" . implode(", ", array_keys($row_p4)) . ") VALUES (" . implode(", ", array_fill(0, count($row_p4), "?")) . ")");
                                $stmt->bind_param(str_repeat("s", count($row_p4)), ...array_values($row_p4));
                                if (!$stmt->execute()) {
                                    $error_p4 = $mysqli->error;
                                    $msg_p4 = "error";
                                    break;
                                }
                                $stmt->close();
                            }
                            $msg_p4 = "inserted";
                        } else {
                            $msg_p4 = "no_data_found";
                        }
                    } else {
                        $error_p4 = $mysqli->error;
                        $msg_p4 = "error";
                    }

                    $select_where_p5 = 's1_unique_id = ?';
                    $stmt = $mysqli->prepare("SELECT * FROM std_app_s5 WHERE $select_where_p5");
                    $stmt->bind_param("s", $value['unique_id']);
                    $stmt->execute();
                    $action_obj_p5 = $stmt->get_result();
                    $stmt->close();

                    if ($action_obj_p5) {
                        $data_p5 = $action_obj_p5->fetch_all(MYSQLI_ASSOC);
                        $error_p5 = "";

                        if (!empty($data_p5)) {
                            foreach ($data_p5 as $row_p5) {
                                unset($row_p5['id']);

                                $stmt = $mysqli->prepare("INSERT INTO std_reg_s5 (" . implode(", ", array_keys($row_p5)) . ") VALUES (" . implode(", ", array_fill(0, count($row_p5), "?")) . ")");
                                $stmt->bind_param(str_repeat("s", count($row_p5)), ...array_values($row_p5));
                                if (!$stmt->execute()) {
                                    $error_p5 = $mysqli->error;
                                    $msg_p5 = "error";
                                    break;
                                }
                                $stmt->close();
                            }
                            $msg_p5 = "inserted";
                        } else {
                            $msg_p5 = "no_data_found";
                        }
                    } else {
                        $error_p5 = $mysqli->error;
                        $msg_p5 = "error";
                    }



                    $select_where_p6 = 's1_unique_id = ?';
                    $stmt = $mysqli->prepare("SELECT * FROM std_app_s6 WHERE $select_where_p6");
                    $stmt->bind_param("s", $value['unique_id']);
                    $stmt->execute();
                    $action_obj_p6 = $stmt->get_result();
                    $stmt->close();

                    if ($action_obj_p6) {
                        $data_p6 = $action_obj_p6->fetch_all(MYSQLI_ASSOC);
                        $error_p6 = "";

                        if (!empty($data_p6)) {
                            foreach ($data_p6 as $row_p6) {
                                unset($row_p6['id']);

                                $stmt = $mysqli->prepare("INSERT INTO std_reg_s6 (" . implode(", ", array_keys($row_p6)) . ") VALUES (" . implode(", ", array_fill(0, count($row_p6), "?")) . ")");
                                $stmt->bind_param(str_repeat("s", count($row_p6)), ...array_values($row_p6));
                                if (!$stmt->execute()) {
                                    $error_p6 = $mysqli->error;
                                    $msg_p6 = "error";
                                    break;
                                }
                                $stmt->close();
                            }
                            $msg_p6 = "inserted";
                        } else {
                            $msg_p6 = "no_data_found";
                        }
                    } else {
                        $error_p6 = $mysqli->error;
                        $msg_p6 = "error";
                    }

                    $select_where_p7 = 's1_unique_id = ?';
                    $stmt = $mysqli->prepare("SELECT * FROM std_app_s7 WHERE $select_where_p7");
                    $stmt->bind_param("s", $value['unique_id']);
                    $stmt->execute();
                    $action_obj_p7 = $stmt->get_result();
                    $stmt->close();

                    if ($action_obj_p7) {
                        $data_p7 = $action_obj_p7->fetch_all(MYSQLI_ASSOC);
                        $error_p7 = "";

                        if (!empty($data_p7)) {
                            foreach ($data_p7 as $row_p7) {
                                unset($row_p7['id']);

                                $stmt = $mysqli->prepare("INSERT INTO std_reg_s7 (" . implode(", ", array_keys($row_p7)) . ") VALUES (" . implode(", ", array_fill(0, count($row_p7), "?")) . ")");
                                $stmt->bind_param(str_repeat("s", count($row_p7)), ...array_values($row_p7));
                                if (!$stmt->execute()) {
                                    $error_p7 = $mysqli->error;
                                    $msg_p7 = "error";
                                    break;
                                }
                                $stmt->close();
                            }
                            $msg_p7 = "inserted";
                        } else {
                            $msg_p7 = "no_data_found";
                        }
                    } else {
                        $error_p7 = $mysqli->error;
                        $msg_p7 = "error";
                    }

                }
            // }else{
            //     $sanc_cnt_exceed = 'sanc_cnt_exceed';
            //     break;
            // }
            }
        }

    
        function reg_no($academic_year, $s1_unique_id)
        {
            // $date = date("Y");
            // $st_date = substr($date, 4);
        
            $servername = "localhost";
            $username = "root";
            $password = "4/rb5sO2s3TpL4gu";
            $database_name = "adi_dravidar";
        
            try {
                $conn = new PDO("mysql:host=$servername;dbname=adi_dravidar", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
            }
        
            $sql = $conn->query("SELECT * FROM academic_year_creation where is_delete = '0'  ORDER BY s_no DESC LIMIT 1");
            $row = $sql->fetch();
        
            $acc_year = $row['amc_year'];
            $a = str_split($acc_year);
            $splt_acc_yr = $a[0] . $a[1] . $a[2] . $a[3];
        
            $stmt = $conn->query("SELECT std_reg_no FROM std_reg_s WHERE is_delete = '0' ORDER BY CAST(RIGHT(std_reg_no, 6) AS UNSIGNED) DESC LIMIT 1;");
            $last_reg_no = $stmt->fetchColumn();
        
            $hosteltype = $conn->query("SELECT student_type FROM std_app_s where unique_id='" . $s1_unique_id . "' ");
            $row = $hosteltype->fetch();
            $hosteltype = $row['student_type'];
        
            if ($hosteltype == '65f00a259436412348') {
                $hosteltype = 'S';
            } elseif ($hosteltype == '65f00a327c08582160') {
                $hosteltype = 'I';
            } elseif ($hosteltype == '65f00a3e3c9a337012') {
                $hosteltype = 'D';
            } elseif ($hosteltype == '65f00a495599589293' || $hosteltype == '65f00a53eef3015995') {
                $hosteltype = 'C';
            }
        
        
            if ($last_reg_no == '') {
                $new_seq_no = 1;
            } else {
                // Extract year and sequence number from the last registration number
                $last_seq_no = intval(substr($last_reg_no, -6)); // Extract last 4 digits
        
                // Increment the sequence number
                $new_seq_no = $last_seq_no + 1;
            }
        
            // Format the new registration number
            $registration_no = $splt_acc_yr . 'ADTW' . $hosteltype . str_pad($new_seq_no, 6, '0', STR_PAD_LEFT);
        
            return $registration_no;
        }
        


