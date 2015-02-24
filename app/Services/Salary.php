<?php namespace HRis\Services;

use Config;
use HRis\Eloquent\Dependent;
use HRis\Eloquent\SalaryComponents;
use HRis\Eloquent\SSSContributions;
use HRis\Eloquent\TaxComputations;

/**
 * Class Salary
 * @package HRis\Services
 */
class Salary {

    /**
     * @param TaxComputations $tax_computations
     * @param Dependent $dependent
     * @param SSSContributions $sss_contribution
     * @param SalaryComponents $salary_components
     */
    public function __construct(TaxComputations $tax_computations, Dependent $dependent, SSSContributions $sss_contribution, SalaryComponents $salary_components)
    {
        $this->tax_computations = $tax_computations;
        $this->dependent = $dependent;
        $this->sss_contribution = $sss_contribution;
        $this->salary_components = $salary_components;
    }

    /**
     * @param $employee
     * @return array
     */
    function getSalaryDetails($employee)
    {
        $mode = Config::get('salary.semi_monthly');
        $employee_salary_components = $employee->employeeSalaryComponents;
        $component_ids = $this->salary_components->getSalaryAndSSS();
        $deductions = 0;

        $salary = 0;
        foreach ($employee_salary_components as $employee_salary_component)
        {
            if ($employee_salary_component->component_id == $component_ids['monthlyBasic'])
            {
                $salary = $employee_salary_component->value / $mode;
            }
            else if ($employee_salary_component->component_id == $component_ids['SSS'] && $employee_salary_component->value == 0 && $salary != 0)
            {
                $getSSS = $this->sss_contribution->where('range_compensation_from', '<=', $salary)
                    ->orderBy('range_compensation_from', 'desc')
                    ->first();
                $employee_salary_component->value = $getSSS->sss_ee;
            }
        }

        $employee_status = 'ME_S';
        $dependents = count($employee->dependents);
        if ($dependents)
        {
            $employee_status = 'ME' . $dependents . '_S' . $dependents;
        }

        $taxableSalary = $salary - $deductions;
        $taxes = $this->tax_computations->getTaxRate($employee_status, $taxableSalary);

        $over = 0;
        $totalTax = 0;
        if ($taxes)
        {
            if ($taxableSalary > $taxes->$employee_status)
            {
                $over = $taxableSalary - $taxes->$employee_status;
            }

            $totalTax = $taxes->exemption + ($over * $taxes->percentage_over);
        }
        $totalTax = $taxes->exemption + ($over * $taxes->percentage_over);

        return ['total_tax' => round($totalTax, 2), 'employee_status' => $employee_status, 'salary' => $salary];

    }

}
