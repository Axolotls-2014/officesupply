<?php
// Check if payroll exists for that month-year
$data['payroll_exists'] = $this->payroll_history_model->get_employee_payroll_status($employee_id, $payroll_month);
?>

<style>
  .day-number {
      position: absolute;
      left: 5px;
      bottom: 5px;
      font-size: smaller;
  }
  .present {
      background-color: green;
  }
  .absent {
      background-color: red;
  }
  .half-leave {
      background-color: pink;
  }
  .one-forth-leave {
      background-color: yellow;
  }
  #calendar-container tbody td {
      padding-top: 0 !important;
      padding-bottom: 0 !important;
  }
</style>

<div id="calendar-container">
  <table id="calendar" class="table table-bordered">
      <thead>
          <tr>
              <th>Sun</th>
              <th>Mon</th>
              <th>Tue</th>
              <th>Wed</th>
              <th>Thu</th>
              <th>Fri</th>
              <th>Sat</th>
          </tr>
      </thead>
      <tbody>
          <?php
          $numDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
          $firstDayOfMonth = date('w', strtotime("$year-$month-01"));
          $currentDay = 1;

          echo '<tr>';
          for ($i = 0; $i < $firstDayOfMonth; $i++) {
              echo '<td></td>';
          }

          while ($currentDay <= $numDays) {
              for ($i = $firstDayOfMonth; $i < 7; $i++) {
                  if ($currentDay > $numDays) {
                      echo '<td></td>';
                  } else {
                      $class = '';
                      $selectHtml = '<select class="attendance-select" style="width: 100%;" data-day="' . $currentDay . '" data-month="' . $month . '" data-year="' . $year . '" data-employee-id="' . $employee_id . '" ' . ($data['payroll_exists'] ? 'data-payroll-exists="true"' : '') . '>
                          <option value="">Select Status</option>
                          <option value="1">Present</option>
                          <option value="0">Absent</option>
                          <option value="0.5">Half Leave</option>
                          <option value="0.25">One Forth Leave</option>
                      </select>';

                      if (!empty($attendance) && is_array($attendance)) {
                          foreach ($attendance as $row) {
                              if ((int)$row['day'] === $currentDay) {
                                  switch ($row['attendance_status']) {
                                      case '1':
                                          $class = 'present';
                                          break;
                                      case '0':
                                          $class = 'absent';
                                          break;
                                      case '0.5':
                                          $class = 'half-leave';
                                          break;
                                      case '0.25':
                                          $class = 'one-forth-leave';
                                          break;
                                      default:
                                          $class = '';
                                          break;
                                  }

                                  $selectHtml = '<select class="attendance-select" style="width: 100%;" data-day="' . $currentDay . '" data-month="' . $month . '" data-year="' . $year . '" data-employee-id="' . $row['employee_id'] . '" ' . ($data['payroll_exists'] ? 'data-payroll-exists="true"' : '') . '>
                                      <option value="">Select Status</option>
                                      <option value="1" ' . ($row['attendance_status'] == '1' ? 'selected' : '') . '>Present</option>
                                      <option value="0" ' . ($row['attendance_status'] == '0' ? 'selected' : '') . '>Absent</option>
                                      <option value="0.5" ' . ($row['attendance_status'] == '0.5' ? 'selected' : '') . '>Half Leave</option>
                                      <option value="0.25" ' . ($row['attendance_status'] == '0.25' ? 'selected' : '') . '>One Forth Leave</option>
                                  </select>';
                                  break;
                              }
                          }
                      }

                      echo '<td class="' . $class . '">' . '<span class="day-number">' . $currentDay . '</span>' . '<br>' . $selectHtml . '</td>';
                      $currentDay++;
                  }
              }
              echo '</tr>';

              if ($currentDay <= $numDays) {
                  echo '<tr>';
                  $firstDayOfMonth = 0;
              }
          }
          ?>
      </tbody>
  </table>
</div>

<script>
    $(document).ready(function() {
        $('.attendance-select').select2({
            minimumResultsForSearch: Infinity,
            dropdownAutoWidth: true,
            width: '100%'
        }).each(function() {
            const value = $(this).val();
            const td = $(this).closest('td');
            td.removeClass('present absent half-leave one-forth-leave').addClass(getStatusClass(value));
            updateCellBackground(td, value);

            if ($(this).data('payroll-exists')) {
                $(this).find('option').prop('disabled', true);
                $(this).find('option:selected').prop('disabled', false);
            }
        }).on('change', function() {
            const value = $(this).val();
            const td = $(this).closest('td');
            const day = $(this).data('day');
            const month = $(this).data('month');
            const year = $(this).data('year');
            const employeeId = $(this).data('employee-id');

            td.removeClass('present absent half-leave one-forth-leave').addClass(getStatusClass(value));
            updateCellBackground(td, value);

            saveAttendanceStatus(employeeId, `${year}-${month}-${day}`, value);
        });

        function getStatusClass(status) {
            switch (status) {
                case '1':
                    return 'present';
                case '0':
                    return 'absent';
                case '0.5':
                    return 'half-leave';
                case '0.25':
                    return 'one-forth-leave';
                default:
                    return '';
            }
        }

        function updateCellBackground(cell, status) {
            const colorMap = {
                '1': 'green',
                '0': 'red',
                '0.5': 'pink',
                '0.25': 'yellow'
            };
            cell.css('background-color', colorMap[status] || 'transparent');
        }

        function saveAttendanceStatus(employeeId, date, status) {
            $.ajax({
                url: '<?= base_url('attendance/add') ?>',
                type: 'POST',
                data: {
                    employee_id: employeeId,
                    attendance_date: date,
                    attendance_status: status,
                    '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                },
                dataType: 'json',
                success: function(response) {
                    if(response.code === 1) {
                        Swal.fire({
                            title: 'SUCCESS !!',
                            text: response.message,
                            icon: "success",
                            buttonsStyling: !1,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            },
                            timer: 1000
                        });
                    } else {
                        Swal.fire({
                            title: 'FAILURE !!',
                            text: response.message,
                            icon: "error",
                            buttonsStyling: !1,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            },
                            timer: 1000
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error: ' + status + ' - ' + error);
                }
            });
        }
    });
</script>
