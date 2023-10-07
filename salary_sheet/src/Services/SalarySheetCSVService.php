<?php

namespace Drupal\salary_sheet\Services;

/**
 * Class SalarySheetCSVService.
 */
class SalarySheetCSVService {

  /**
   * Generate CSV content.
   */
  public function generateCSV($start_date) {
    $csv_content = "Month Name,Salary Payment Date,Bonus Payment Date\n";
    for ($i = 0; $i < 12; $i++) {
      $month = strtotime("+$i months", $start_date);
      $month_name = date('F', $month);

      // Determine salary payment date (last day of the month, or the last Friday if it falls on a weekend).
      $last_day_of_month = strtotime('last day of', $month);
      $salary_date = date('Y-m-d', $last_day_of_month);

      if (date('N', $last_day_of_month) >= 6) {
        $salary_date = date('Y-m-d', strtotime('last friday', $last_day_of_month));
      }

      // Bonus payment is on the 15th for the previous month.
      $bonus_date = date('Y-m-15', $month);

      // If the 15th is a weekend, the bonus payment date is moved to the first Wednesday after the 15th.
      $bonus_day = date('N', strtotime($bonus_date));
      if ($bonus_day >= 6) {
        $bonus_date = date('Y-m-d', strtotime('first wednesday', strtotime($bonus_date)));
      }

      $csv_content .= "$month_name,$salary_date,$bonus_date\n";
    }
    return $csv_content;
  }
}