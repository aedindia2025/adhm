<?php
session_start();
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
?>
<!DOCTYPE html>
<html>

<head>
    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet" />

    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <style>
  /* Overlay background */
  #holidayOverlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.4);
    z-index: 999;
  }

  /* Modal container */
  #holidayModal {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: #ffffff;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    width: 40%;
    padding: 42px;
    z-index: 1002;
    animation: fadeIn 0.3s ease;
  }

  #holidayModal h4 {
    margin-bottom: 16px;
    font-size: 20px;
    color: #00729d;
    text-align: center;
    margin-top: 0px;
    background: #bdedff;
    padding: 10px;
    text-transform: uppercase;
    font-weight: 700;
}

  #holidayModal input[type="text"] {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 14px;
    margin-bottom: 12px;
    transition: border-color 0.2s;
  }

  #holidayModal input[type="text"]:focus {
    outline: none;
    border-color: #00aff0;
  }

  #holidayModal button {
    padding: 8px 14px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    transition: background 0.2s;
    margin-right: 6px;
    margin-top: 22px;
  }

  #saveHoliday {
    background: #00aff0;
    color: #fff;
  }

  #saveHoliday:hover {
    background: #0056b3;
  }

  #deleteHoliday {
    background: #dc3545;
    color: #fff;
  }

  #deleteHoliday:hover {
    background: #a71d2a;
  }

  #holidayModal button:last-child {
    background: #f1f1f1;
    color: #333;
  }

  #holidayModal button:last-child:hover {
    background: #e2e2e2;
  }

  @keyframes fadeIn {
    from {
      opacity: 0;
      transform: translate(-50%, -45%);
    }
    to {
      opacity: 1;
      transform: translate(-50%, -50%);
    }
  }
  button.fc-today-button.fc-button.fc-button-primary {
    background: #ff0034;
    border: 0px;
    padding: 7px 15px;
}
/* Reduce day cell height */
.fc-daygrid-day-frame {
  min-height: 60px !important; /* default is ~100px */
}

/* Reduce inner padding for cleaner look */
.fc-daygrid-day-top {
  padding: 2px 4px !important;
}

.fc-daygrid-day-number {
    font-size: 12px;
    color: #000;
    font-weight: 500;
}

/* Optional: reduce event text size */
.fc-event {
    font-size: 13px !important;
    padding: 7px 7px !important;
    line-height: 1.2;
    border-radius: 3px;
}
.fc-theme-standard td, .fc-theme-standard th {
    border: 1px solid #e0e0e0ab;
}
.fc-theme-standard td {
    padding: 0px !important;
}
.fc-theme-standard th {
    padding: 0px !important;
}
.fc .fc-col-header-cell-cushion {
    display: inline-block;
    padding: 9px 4px;
    color: #646464;
}
.fc .fc-toolbar-title {
    font-size: 1.75em;
    margin: 0px;
    color: #000;
}
.swal-modal-on-top {
    z-index: 20000 !important; /* higher than your modal */
}
</style>
</head>

<body>
    <div id="holidayOverlay"></div>

    <!-- Modal for Adding/Editing Holiday -->
    <div id="holidayModal">
        <h4 id="modalTitle">Add Holiday</h4>
        <input type="hidden" id="holidayId">
        <input type="text" id="holidayDate" readonly style="margin-bottom:10px; width:100%;"><br>
        <input type="text" id="holidayDesc" placeholder="Enter description" style="width:100%; margin-bottom:10px;"><br>
        <div class="row">
            <div class="col-md-6">
                  <button onclick="closeModal()">Cancel</button>
        </div>
         <div class="col-md-6 text-end">
             <button id="saveHoliday">Save</button>
        <button id="deleteHoliday" style="display: none;">Delete</button>
        </div>
        </div>
    </div>

    <!-- Background overlay -->
    <div id="modalBackdrop"
        style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:#00000055; z-index:1001;">
    </div>

    <div class="content-page">
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box">
                            <h4 class="page-title">Holiday Master</h4>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div id='calendar'></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery (required for $.post) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        sessionStorage.setItem('csrf_token', '<?php echo $_SESSION['csrf_token']; ?>');

        function openModal(date = '', desc = '', id = '', endDate = '', rangeId = '') {
            $('#holidayId').val(id);
            $('#holidayDesc').val(desc);

            if (endDate && date !== endDate) {
                // Range selection
                $('#holidayDate').val(date + ' to ' + endDate);
                $('#holidayDate').data('range', { start: date, end: endDate });
            } else {
                $('#holidayDate').val(date);
                $('#holidayDate').data('range', null);
            }

            $('#holidayId').data('range_id', rangeId || '');

            $('#modalTitle').text(id ? 'Edit Holiday' : 'Add Holiday');

            if (id) {
                $('#deleteHoliday').show();
            } else {
                $('#deleteHoliday').hide();
            }

            $('#holidayModal').show();
            $('#modalBackdrop').show();
        }

        function closeModal() {
            $('#holidayModal').hide();
            $('#modalBackdrop').hide();

            // Clear modal data attributes
            $('#holidayId').val('');
            $('#holidayId').data('range_id', '');
            $('#holidayDesc').val('');
            $('#holidayDate').val('');
            $('#holidayDate').data('range', null);
        }

        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                editable: true,
                selectable: true,
                events: 'folders/calender_holiday/crud.php?action=fetch',
                eventDisplay: 'block',
                eventColor: '#ff6666',
                allDayDefault: true,
                 contentHeight: 'auto', // auto-shrink height
  aspectRatio: 1.3,      // controls cell height/width ratio

                select: function (info) {
                    const startDate = info.startStr;
                    const endDateObj = new Date(info.end); // end is exclusive
                    endDateObj.setDate(endDateObj.getDate()); // make it inclusive

                    const formattedEnd = endDateObj.toISOString().split('T')[0];
                    openModal(startDate, '', '', formattedEnd, '');
                },

                eventClick: function (info) {
                    const event = info.event;
                    const id = event.id; // can be unique_id or range_id

                    // Calculate end date for display (FullCalendar end is exclusive)
                    let endDate = null;
                    if (event.end) {
                        let endDateObj = new Date(event.end);
                        endDateObj.setDate(endDateObj.getDate());
                        endDate = endDateObj.toISOString().split('T')[0];
                    }

                    openModal(event.startStr, event.title, id, endDate, id);
                }
            });

            calendar.render();

            // Generate a random string for range_id on frontend (simplified)
            function generateRangeId() {
                return Math.random().toString(36).substring(2, 14);
            }

            $('#saveHoliday').on('click', function () {
                const id = $('#holidayId').val();
                const desc = $('#holidayDesc').val();
                const token = sessionStorage.getItem('csrf_token');

                let dateRange = $('#holidayDate').data('range');
                let datesToInsert = [];
                let rangeId = $('#holidayId').data('range_id') || '';

                if (dateRange) {
                    let start = new Date(dateRange.start);
                    let end = new Date(dateRange.end);

                    while (start <= end) {
                        let dateStr = start.toISOString().split('T')[0];
                        datesToInsert.push(dateStr);
                        start.setDate(start.getDate() + 1);
                    }
                } else {
                    const date = $('#holidayDate').val();
                    if (!date) {
                        Swal.fire({
                            icon: 'info',
                            title: 'Missing Date',
                            text: 'Please enter a valid date.',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK'
                        });
                        return;
                    }
                    datesToInsert.push(date);
                }

                if (!desc) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Missing Description',
                        text: 'Please enter a description.',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK',
                        customClass: {
                            popup: 'swal2-popup',
                        },
                        didOpen: (popup) => {
                            popup.style.zIndex = 15000; // set z-index directly
                        }
                    });
                    return;
                }

                // If it's a range and no rangeId assigned, generate one
                if (!rangeId) {
                    rangeId = generateRangeId();
                    $('#holidayId').data('range_id', rangeId);
                }

                let promises = datesToInsert.map(d =>
                    $.post('folders/calender_holiday/crud.php', {
                        action: 'add_update',
                        unique_id: (id && datesToInsert.length === 1) ? id : '', // reuse id only for single date
                        holiday_date: d,
                        description: desc,
                        range_id: rangeId || '',
                        csrf_token: token
                    })
                );

                Promise.all(promises).then(() => {
                    sweetalert('add');
                    calendar.refetchEvents();
                    closeModal();
                });
            });

            $('#deleteHoliday').on('click', function () {
                const id = $('#holidayId').val();
                const token = sessionStorage.getItem('csrf_token');
                const rangeId = $('#holidayId').data('range_id');
                const holidayDate = $('#holidayDate').val();
                $('#holidayModal').hide();
                $('#modalBackdrop').hide();
                // SweetAlert confirmation
                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you really want to delete this holiday?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    customClass: {
                        popup: 'swal-modal-on-top'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Proceed with deletion
                        $.post('folders/calender_holiday/crud.php', {
                            action: 'delete',
                            csrf_token: token,
                            range_id: rangeId,
                            holiday_date: holidayDate
                        }, function (data) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: data.msg,
                                confirmButtonColor: '#3085d6'
                            });
                            calendar.refetchEvents();
                            closeModal();
                        });
                    }
                });
            });


        });

    </script>
</body>


</html>