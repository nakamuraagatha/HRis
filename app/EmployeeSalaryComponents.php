<?php namespace HRis;

use Illuminate\Database\Eloquent\Model;

class EmployeeSalaryComponents extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'employee_salary_components';

    protected $fillable = [
        'component_id',
        'value',
        'employee_id',
        'effective_date'
    ];

    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function salaryComponent()
    {
        return $this->hasOne('HRis\SalaryComponents', 'id', 'component_id');
    }

}
