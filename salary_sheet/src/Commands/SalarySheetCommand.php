<?php

namespace Drupal\salary_sheet\Commands;

use Drush\Commands\DrushCommands;
use Drupal\salary_sheet\Services\SalarySheetCSVService;



class SalarySheetCommand extends DrushCommands {

  /**
   * The salary sheet service.
   *
   * @var \Drupal\salary_sheet\Services\SalarySheetCSVService
   */
  protected $salarySheetCSVService;

  /**
   * Constructor for SalarySheetCommand.
   *
   * @param \Drupal\salary_sheet\Services\SalarySheetCSVService $salarySheetCSVService
   *   The salary sheet service.
   */
  public function __construct(SalarySheetCSVService $salarySheetCSVService) {
    $this->salarySheetCSVService = $salarySheetCSVService;
    parent::__construct();
  }

  /**
   * Generate a salary sheet.
   *
   * @command create:salarysheet
   * @aliases css
   * @option start_date The starting date. Defaults to current date.
   * @usage create:salarysheet --start_date=2023-09-22
   *   Generate a salary sheet starting from September 22, 2023.
   *
   * @validate-module-enabled salary_sheet
   */

  public function createSalarySheet($options = ['start_date' => '']) {
    $output = $this->output();
    $start_date = empty($options['start_date']) ? time() : strtotime($options['start_date']);
    $output->writeln('Generating salary sheet...');

    // Generate CSV content.
    $csv_content = $this->salarySheetCSVService->generateCSV($start_date);

    // Save CSV content to a file and path.
    $file_path = 'public://salary_sheet.csv';
    $this->saveFile($file_path, $csv_content);

    $output->writeln("Salary sheet generated at $file_path");
  }

  /**
   * Save content to a file.
   */
  private function saveFile($file_path, $content) {
    // Create the file with the generated content.
    file_put_contents($file_path, $content);
  }

}